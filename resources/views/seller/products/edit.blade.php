@php
    $isEdit = isset($product);
    $action = $isEdit ? route('seller.products.update', $product) : route('seller.products.store');
@endphp
@extends('layouts.seller')

@section('title', $isEdit ? 'Edit Product' : 'Add Product')
@section('page-title', $isEdit ? 'Edit Product' : 'Add Product')

@section('topbar-actions')
    <a href="{{ route('seller.products.index') }}" class="btn btn-sm btn-outline-t4d">Back to products</a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="panel">
            <div class="panel-header">
                <h2>{{ $isEdit ? $product->name : 'New product listing' }}</h2>
            </div>
            <div class="panel-body">
                <form method="POST" action="{{ $action }}" enctype="multipart/form-data">
                    @csrf
                    @if ($isEdit) @method('PUT') @endif

                    <div class="row g-3">
                        <div class="col-12">
                            <label for="name" class="form-label">Product name</label>
                            <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $product->name ?? '') }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="product_type" class="form-label">Category</label>
                            <select id="product_type" name="product_type" class="form-select @error('product_type') is-invalid @enderror" required>
                                @foreach (\App\Support\Enums\ProductType::cases() as $type)
                                    <option value="{{ $type->value }}" @selected(old('product_type', $product->product_type->value ?? '') === $type->value)>{{ $type->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" name="status" class="form-select" required>
                                <option value="{{ \App\Support\Enums\RecordStatus::Active->value }}" @selected((int) old('status', $product->status->value ?? 1) === 1)>Live</option>
                                <option value="{{ \App\Support\Enums\RecordStatus::Inactive->value }}" @selected((int) old('status', $product->status->value ?? 1) === 0)>Draft</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description" name="description" rows="5" class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description ?? '') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="currency" class="form-label">Currency</label>
                            <select id="currency" name="currency" class="form-select" required>
                                @foreach (\App\Support\Enums\Currency::cases() as $currency)
                                    <option value="{{ $currency->value }}" @selected(old('currency', $product->currency->value ?? 'USD') === $currency->value)>{{ $currency->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="units" class="form-label">Units</label>
                            <select id="units" name="units" class="form-select" required>
                                @foreach (\App\Support\Enums\LeadUnit::cases() as $unit)
                                    <option value="{{ $unit->value }}" @selected(old('units', $product->units->value ?? 'pieces') === $unit->value)>{{ $unit->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="min_order_qty" class="form-label">Minimum qty</label>
                            <input id="min_order_qty" name="min_order_qty" type="text" class="form-control @error('min_order_qty') is-invalid @enderror"
                                   value="{{ old('min_order_qty', $product->min_order_qty ?? '') }}" placeholder="e.g. 100">
                            @error('min_order_qty')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="price_from" class="form-label">Price from</label>
                            <input id="price_from" name="price_from" type="number" step="0.01" min="0" class="form-control @error('price_from') is-invalid @enderror"
                                   value="{{ old('price_from', $product->price_from ?? '') }}">
                            @error('price_from')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="price_to" class="form-label">Price to</label>
                            <input id="price_to" name="price_to" type="number" step="0.01" min="0" class="form-control @error('price_to') is-invalid @enderror"
                                   value="{{ old('price_to', $product->price_to ?? '') }}">
                            @error('price_to')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label for="image" class="form-label">Product image</label>
                            @if ($isEdit && $product->imageUrl())
                                <div class="mb-2"><img src="{{ $product->imageUrl() }}" alt="" class="rounded" style="height:72px;width:72px;object-fit:cover;"></div>
                            @endif
                            <input id="image" name="image" type="file" class="form-control @error('image') is-invalid @enderror" accept="image/jpeg,image/png,image/webp">
                            @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary-t4d">{{ $isEdit ? 'Save changes' : 'Create product' }}</button>
                        <a href="{{ route('seller.products.index') }}" class="btn btn-outline-t4d">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
