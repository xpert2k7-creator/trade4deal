<?php

declare(strict_types=1);

namespace App\Http\Controllers\Seller;

use App\Domains\Product\Models\Product;
use App\Domains\Product\Requests\StoreProductRequest;
use App\Domains\Product\Requests\UpdateProductRequest;
use App\Domains\Seller\Requests\UpdateSellerProfileRequest;
use App\Http\Controllers\Controller;
use App\Support\Enums\RecordStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SellerDashboardController extends Controller
{
    public function index(Request $request): View
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
            $data['logo_path'] = $request->file('logo')->store('sellers/logos', 'public');
        }

        if ($request->hasFile('cover_image')) {
            if ($seller->cover_image_path) {
                Storage::disk('public')->delete($seller->cover_image_path);
            }
            $data['cover_image_path'] = $request->file('cover_image')->store('sellers/covers', 'public');
        }

        $seller->fill($data);
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

    public function storeProduct(StoreProductRequest $request): RedirectResponse
    {
        $this->authorize('create', Product::class);

        $data = $request->safe()->except(['image']);
        $data['user_id'] = $request->user()->id;
        $data['status'] = RecordStatus::from((int) $data['status']);
        $data['slug'] = Product::uniqueSlugForUser($request->user()->id, $data['name']);

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        Product::query()->create($data);

        return redirect()
            ->route('seller.products.index')
            ->with('success', 'Product added successfully.');
    }

    public function editProduct(Product $product): View
    {
        $this->authorize('update', $product);

        return view('seller.products.edit', compact('product'));
    }

    public function updateProduct(
        UpdateProductRequest $request,
        Product $product,
    ): RedirectResponse {
        $this->authorize('update', $product);

        $data = $request->safe()->except(['image']);
        $data['status'] = RecordStatus::from((int) $data['status']);

        if ($product->name !== $data['name']) {
            $data['slug'] = Product::uniqueSlugForUser($product->user_id, $data['name'], $product->id);
        }

        if ($request->hasFile('image')) {
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()
            ->route('seller.products.edit', $product)
            ->with('success', 'Product updated successfully.');
    }

    public function destroyProduct(Product $product): RedirectResponse
    {
        $this->authorize('delete', $product);

        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        return redirect()
            ->route('seller.products.index')
            ->with('success', 'Product deleted.');
    }
}
