@php
    /** @var \App\Domains\Product\Models\Product|null $product */
    $product = $product ?? null;
    $existingPaths = $product?->imagePathsList() ?? [];
@endphp

<div class="col-12">
    <label for="product_images" class="form-label">Product images</label>
    <p class="small text-muted mb-2">Upload up to 10 images (JPG, PNG, WebP). Max 2 MB each.</p>
    @if ($existingPaths !== [])
        <div class="d-flex flex-wrap gap-2 mb-3">
            @foreach ($existingPaths as $path)
                @php($url = \App\Support\Storage\PublicUploads::url($path))
                <div class="border rounded p-2 text-center" style="width:96px;">
                    <img src="{{ $url }}" alt="Product" class="rounded mb-1" style="width:72px;height:72px;object-fit:cover;">
                    <div class="form-check small">
                        <input class="form-check-input" type="checkbox" name="remove_product_images[]" value="{{ $path }}" id="remove_product_img_{{ md5($path) }}">
                        <label class="form-check-label" for="remove_product_img_{{ md5($path) }}">Remove</label>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    <input id="product_images" name="product_images[]" type="file" class="form-control @error('product_images') is-invalid @enderror @error('product_images.*') is-invalid @enderror" accept="image/jpeg,image/png,image/webp" multiple>
    @error('product_images')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    @error('product_images.*')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>
