<?php

declare(strict_types=1);

namespace App\Http\Controllers\Seller;

use App\Domains\Lead\Services\LeadService;
use App\Domains\Product\Models\Product;
use App\Domains\Product\Requests\StoreProductRequest;
use App\Domains\Product\Requests\UpdateProductRequest;
use App\Domains\Product\Services\ProductDynamicFieldService;
use App\Domains\Product\Services\ProductImageStorage;
use App\Domains\Seller\Requests\UpdateSellerProfileRequest;
use App\Domains\Seller\Services\UpdateSellerProfile;
use App\Http\Controllers\Controller;
use App\Support\Enums\ProductFieldType;
use App\Support\Enums\ProductType;
use App\Support\Enums\RecordStatus;
use App\Support\Location;
use App\Support\Storage\PublicFileTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class SellerDashboardController extends Controller
{
    private const SELLER_LOGO_DIRECTORY = 'sellers/logos';

    private const SELLER_COVER_DIRECTORY = 'sellers/covers';

    public function index(Request $request, LeadService $leadService): View
    {
        $this->authorize('manageProfile', Product::class);

        $seller = $request->user();
        $seller->ensureSellerSlug();

        $counts = $seller->products()->selectRaw('COUNT(*) as total, SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as live, SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as drafts')->first();
        $products = $seller->products()->latest()->limit(5)->get();

        return view('seller.dashboard', [
            'seller' => $seller,
            'productCount' => (int) $counts->total,
            'liveCount' => (int) $counts->live,
            'draftCount' => (int) $counts->drafts,
            'recentProducts' => $products->take(5),
            'completeness' => $seller->profileCompleteness(),
            'matchingLeads' => $leadService->getMatchingLeadsForSeller($seller),
            'matchedCategories' => $leadService->matchingCategoriesForSeller($seller),
        ]);
    }

    public function editProfile(Request $request): View
    {
        $this->authorize('manageProfile', Product::class);

        $seller = $request->user();
        $seller->ensureSellerSlug();

        return view('seller.profile.edit', compact('seller'));
    }

    public function updateProfile(
        UpdateSellerProfileRequest $request,
    ): RedirectResponse {
        $this->authorize('manageProfile', Product::class);

        $seller = $request->user();
        app(UpdateSellerProfile::class)->execute($seller, $request);

        return redirect()
            ->route('seller.profile.edit')
            ->with('success', 'Company profile updated successfully.');
    }

    public function products(Request $request): View
    {
        $this->authorize('viewAny', Product::class);

        $products = $request->user()
            ->products()
            ->latest()
            ->paginate(10);

        return view('seller.products.index', compact('products'));
    }

    public function createProduct(): View
    {
        $this->authorize('create', Product::class);

        return view('seller.products.create');
    }

    public function categoryFields(Request $request, ProductDynamicFieldService $fields): JsonResponse
    {
        $this->authorize('viewAny', Product::class);

        $productType = ProductType::tryFrom((string) $request->query('product_type', ''));
        if ($productType === null) {
            return response()->json(['fields' => []]);
        }

        return response()->json([
            'fields' => $fields->serializeFieldsForProductType($productType),
        ]);
    }

    public function storeProduct(
        StoreProductRequest $request,
        ProductDynamicFieldService $fields,
        ProductImageStorage $images,
    ): RedirectResponse {
        $this->authorize('create', Product::class);

        app(PublicFileTransaction::class)->run(function () use ($request, $fields, $images): void {
            $productType = ProductType::from((string) $request->validated('product_type'));

            $data = $request->safe()->except(['image', 'product_images', 'remove_product_images', 'dynamic_fields']);
            $data['user_id'] = $request->user()->id;
            $data['status'] = RecordStatus::from((int) $data['status']);
            $data['slug'] = Product::uniqueSlugForUser($request->user()->id, $data['name']);
            $data['location_id'] = Location::id(
                $data['location_city'] ?? null,
                $data['location_state'] ?? null,
                $data['location_country'] ?? null,
            );
            $memberId = (string) $request->user()->id;
            $data = $images->mergeUploadedImagesIntoProductData($request, $data, $memberId);

            DB::transaction(function () use ($request, $fields, $productType, $data, $memberId): void {
                $product = Product::query()->create($data);

                Log::info('product_image.product_saved', [
                    'member_id' => $memberId,
                    'action' => 'create',
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'image_count' => count($product->imagePathsList()),
                ]);

                $fields->syncValues(
                    $product,
                    $productType,
                    $request,
                    $request->input('dynamic_fields', []) ?? [],
                );
            });
        });

        return redirect()
            ->route('seller.products.index')
            ->with('success', 'Product added successfully.');
    }

    public function editProduct(Product $product, ProductDynamicFieldService $fields): View
    {
        $this->authorize('update', $product);

        $dynamicFieldValues = old(
            'dynamic_fields',
            $fields->valuesMapForProduct($product, $product->product_type),
        );

        return view('seller.products.edit', compact('product', 'dynamicFieldValues'));
    }

    public function updateProduct(
        UpdateProductRequest $request,
        Product $product,
        ProductDynamicFieldService $fields,
        ProductImageStorage $images,
    ): RedirectResponse {
        $this->authorize('update', $product);

        app(PublicFileTransaction::class)->run(function () use ($request, $fields, $images, $product): void {
            $productType = ProductType::from((string) $request->validated('product_type'));

            $data = $request->safe()->except(['image', 'product_images', 'remove_product_images', 'dynamic_fields']);
            $data['status'] = RecordStatus::from((int) $data['status']);

            if ($product->name !== $data['name']) {
                $data['slug'] = Product::uniqueSlugForUser($product->user_id, $data['name'], $product->id);
            }

            $data['location_id'] = Location::id(
                $data['location_city'] ?? null,
                $data['location_state'] ?? null,
                $data['location_country'] ?? null,
            );

            $data = $images->mergeUploadedImagesIntoProductData(
                $request,
                $data,
                (string) $product->user_id,
                $product,
            );

            DB::transaction(function () use ($request, $fields, $product, $productType, $data): void {
                $product->update($data);

                Log::info('product_image.product_saved', [
                    'member_id' => (string) $product->user_id,
                    'action' => 'update',
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'image_count' => count($product->imagePathsList()),
                ]);

                $fields->syncValues(
                    $product,
                    $productType,
                    $request,
                    $request->input('dynamic_fields', []) ?? [],
                );
            });
        });

        return redirect()
            ->route('seller.products.edit', $product)
            ->with('success', 'Product updated successfully.');
    }

    public function destroyProduct(Product $product, ProductImageStorage $images): RedirectResponse
    {
        $this->authorize('delete', $product);

        app(PublicFileTransaction::class)->run(function () use ($product, $images): void {
            $images->deleteAllImages($product);
            foreach ($product->fieldValues()->with('productField')->get() as $value) {
                if ($value->productField?->field_type === ProductFieldType::File) {
                    app(PublicFileTransaction::class)->removeAfterCommit($value->value);
                }
            }
            $product->delete();
        });

        return redirect()
            ->route('seller.products.index')
            ->with('success', 'Product deleted.');
    }
}
