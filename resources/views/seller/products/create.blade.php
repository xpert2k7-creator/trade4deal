@extends('layouts.seller')

@section('title', 'Add Product')
@section('page-title', 'Add Product')

@section('topbar-actions')
    <a href="{{ route('seller.products.index') }}" class="btn btn-sm btn-outline-t4d">Back to products</a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="panel">
            <div class="panel-header">
                <h2>New product listing</h2>
            </div>
            <div class="panel-body">
                @include('seller.products.partials.product-form', [
                    'dynamicFieldValues' => old('dynamic_fields', []),
                ])
            </div>
        </div>
    </div>
</div>
@endsection
