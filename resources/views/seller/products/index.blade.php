@extends('layouts.seller')

@section('title', 'Products')
@section('page-title', 'Products')

@section('topbar-actions')
    <a href="{{ route('seller.products.create') }}" class="btn btn-sm btn-primary-t4d">
        <i class="bi bi-plus-lg"></i> Add product
    </a>
@endsection

@section('content')
<div class="panel">
    <div class="panel-header">
        <h2>Your catalogue</h2>
        <span class="small text-muted">{{ $products->total() }} product{{ $products->total() === 1 ? '' : 's' }}</span>
    </div>

    @if ($products->isEmpty())
        <div class="panel-body text-center text-muted py-5">
            <i class="bi bi-box-seam fs-2 d-block mb-2"></i>
            No products yet.
            <div class="mt-3">
                <a href="{{ route('seller.products.create') }}" class="btn btn-sm btn-primary-t4d">Add your first product</a>
            </div>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-seller align-middle mb-0">
                <thead>
                <tr>
                    <th class="ps-3">Product</th>
                    <th>Category</th>
                    <th>Trade</th>
                    <th>Status</th>
                    <th class="pe-3 text-end">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td class="ps-3">
                            <div class="d-flex align-items-center gap-2">
                                @if ($product->imageUrl())
                                    <img src="{{ $product->imageUrl() }}" alt="" class="rounded" style="width:42px;height:42px;object-fit:cover;">
                                @else
                                    <div class="rounded bg-light d-flex align-items-center justify-content-center text-muted" style="width:42px;height:42px;"><i class="bi bi-image"></i></div>
                                @endif
                                <div>
                                    <div class="fw-semibold">{{ $product->name }}</div>
                                    <div class="small text-muted">{{ $product->priceLabel() }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="small">{{ $product->product_type?->label() }}</td>
                        <td class="small">{{ $product->currency?->value }} / {{ $product->units?->label() }}</td>
                        <td><span class="badge {{ $product->status->badgeClass() }}">{{ $product->isLive() ? 'Live' : 'Draft' }}</span></td>
                        <td class="pe-3 text-end text-nowrap">
                            <a href="{{ route('seller.products.edit', $product) }}" class="btn btn-sm btn-outline-t4d me-1">Edit</a>
                            <form action="{{ route('seller.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this product?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="panel-body border-top">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection
