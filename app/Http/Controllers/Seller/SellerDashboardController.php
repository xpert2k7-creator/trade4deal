<?php

declare(strict_types=1);

namespace App\Http\Controllers\Seller;

use App\Domains\Lead\Services\LeadService;
use App\Domains\Product\Models\Product;
use App\Domains\Product\Requests\StoreProductRequest;
use App\Domains\Product\Requests\UpdateProductRequest;
use App\Domains\Product\Services\ProductDynamicFieldService;
use App\Domains\Product\Services\ProductImageStorage;
use App\Support\Enums\ProductType;
use App\Domains\Seller\Requests\UpdateSellerProfileRequest;
use App\Http\Controllers\Controller;
use App\Support\Enums\RecordStatus;
use App\Support\Location;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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

        $products = $seller->products()->latest()->get();

        return view('seller.dashboard', [
            'seller' => $seller,
            'productCount' => $products->count(),
            'liveCount' => $products->where('status', RecordStatus::Active)->count(),
            'draftCount' => $products->where('status', RecordStatus::Inactive)->count(),
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
        $data = $request->safe()->except(['logo', 'cover_image', 'remove_logo', 'remove_cover']);

        if ($request->boolean('remove_logo') && $seller->logo_path) {
            Storage::disk('public')->delete($seller->logo_path);
            $data['logo_path'] = null;
        }

        if ($request->boolean('remove_cover') && $seller->cover_image_path) {
            Storage::disk('public')->delete($seller->cover_image_path);
            $data['cover_image_path'] = null;
        }

        if ($request->hasFile('logo')) {
            if ($seller->logo_path) {
                Storage::disk('public')->delete($seller->logo_path);
            }

            $path = $request->file('logo')->store(self::SELLER_LOGO_DIRECTORY, 'public');
            if ($path === false) {
                return back()
                    ->withInput()
                    ->withErrors(['logo' => 'Company logo could not be uploaded. Please try again.']);
            }

            $data['logo_path'] = $path;
        }

        if ($request->hasFile('cover_image')) {
            if ($seller->cover_image_path) {
                Storage::disk('public')->delete($seller->cover_image_path);
            }

            $path = $request->file('cover_image')->store(self::SELLER_COVER_DIRECTORY, 'public');
            if ($path === false) {
                return back()
                    ->withInput()
                    ->withErrors(['cover_image' => 'Cover image could not be uploaded. Please try again.']);
            }

            $data['cover_image_path'] = $path;
        }

        $seller->fill($data);
        if ($seller->isDirty('email')) {
            $seller->email_verified_at = null;
        }
        $seller->save();
        $seller->ensureSellerSlug();

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

        if ($this->uploadedProductImagesFailed($request, $data)) {
            Log::warning('product_image.product_save.blocked', [
                'member_id' => $memberId,
                'action' => 'create',
                'product_name' => $data['name'] ?? null,
            ]);

            return back()
                ->withInput()
                ->withErrors([
                    'product_images' => 'Images could not be uploaded. Use JPG, PNG, or WebP (max 2 MB each).',
                ]);
        }

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

        if ($this->uploadedProductImagesFailed($request, $data, $product)) {
            Log::warning('product_image.product_save.blocked', [
                'member_id' => (string) $product->user_id,
                'action' => 'update',
                'product_id' => $product->id,
                'product_name' => $product->name,
            ]);

            return back()
                ->withInput()
                ->withErrors([
                    'product_images' => 'Images could not be uploaded. Use JPG, PNG, or WebP (max 2 MB each).',
                ]);
        }

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

        return redirect()
            ->route('seller.products.edit', $product)
            ->with('success', 'Product updated successfully.');
    }

    public function destroyProduct(Product $product, ProductImageStorage $images): RedirectResponse
    {
        $this->authorize('delete', $product);

        $images->deleteAllImages($product);

        $product->delete();

        return redirect()
            ->route('seller.products.index')
            ->with('success', 'Product deleted.');
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function uploadedProductImagesFailed(
        StoreProductRequest|UpdateProductRequest $request,
        array $data,
        ?Product $product = null,
    ): bool {
        if (! $request->hasFile('product_images') && ! $request->hasFile('image')) {
            return false;
        }

        $paths = $data['image_paths'] ?? null;
        if (is_array($paths) && $paths !== []) {
            return false;
        }

        if ($product !== null && $product->imagePathsList() !== []) {
            return false;
        }

        return true;
    }
}
