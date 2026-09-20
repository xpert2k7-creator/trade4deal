@extends('layouts.seller')

@section('title', 'Overview')
@section('page-title', 'Seller Overview')

@section('topbar-actions')
    <a href="{{ route('seller.products.create') }}" class="btn btn-sm btn-primary-t4d">
        <i class="bi bi-plus-lg"></i> Add product
    </a>
@endsection

@section('content')
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="kpi">
            <div class="label">Products</div>
            <div class="value">{{ $productCount }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="kpi">
            <div class="label">Live</div>
            <div class="value" style="color:#059669;">{{ $liveCount }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="kpi">
            <div class="label">Drafts</div>
            <div class="value" style="color:#B45309;">{{ $draftCount }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="kpi">
            <div class="label">Profile</div>
            <div class="value">{{ $completeness }}%</div>
            <div class="progress-t4d mt-2">
                <div class="bar" style="width: {{ $completeness }}%;"></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="panel">
            <div class="panel-header">
                <h2>Recent products</h2>
                <a href="{{ route('seller.products.index') }}" class="small fw-semibold text-decoration-none" style="color:var(--t4d-primary);">View all</a>
            </div>
            @if ($recentProducts->isEmpty())
                <div class="panel-body text-center text-muted py-5">
                    <i class="bi bi-box-seam fs-2 d-block mb-2"></i>
                    No products yet. Add your first listing.
                    <div class="mt-3">
                        <a href="{{ route('seller.products.create') }}" class="btn btn-sm btn-primary-t4d">Add product</a>
                    </div>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-seller align-middle mb-0">
                        <thead>
                        <tr>
                            <th class="ps-3">Product</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Minimum qty</th>
                            <th>Status</th>
                            <th class="pe-3 text-end">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($recentProducts as $product)
                            <tr>
                                <td class="ps-3 fw-semibold">{{ $product->name }}</td>
                                <td class="small">{{ $product->product_type?->label() }}</td>
                                <td class="small">{{ $product->priceLabel() }}</td>
                                <td class="small">{{ $product->min_order_qty ?: '—' }}</td>
                                <td><span class="badge {{ $product->status->badgeClass() }}">{{ $product->isLive() ? 'Live' : 'Draft' }}</span></td>
                                <td class="pe-3 text-end">
                                    <a href="{{ route('seller.products.edit', $product) }}" class="btn btn-sm btn-outline-t4d">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
    <div class="col-lg-4">
        <div class="panel mb-3">
            <div class="panel-header">
                <h2>Matching leads</h2>
                <a href="{{ route('home') }}#leads" class="small fw-semibold text-decoration-none" style="color:var(--t4d-primary);">View all</a>
            </div>
            <div class="panel-body">
                @if ($matchedCategories->isEmpty())
                    <div class="text-muted small">
                        Add industries in your profile or create products with categories to see matched leads here.
                    </div>
                    <a href="{{ route('seller.profile.edit') }}" class="btn btn-sm btn-outline-t4d mt-3">Set categories</a>
                @else
                    <div class="d-flex flex-wrap gap-1 mb-3">
                        @foreach ($matchedCategories as $category)
                            @php($type = \App\Support\Enums\ProductType::tryFrom($category))
                            <span class="badge text-bg-light border text-dark">{{ $type?->label() ?? $category }}</span>
                        @endforeach
                    </div>

                    @if ($matchingLeads->isEmpty())
                        <div class="text-muted small">
                            No visible leads match these categories yet. New approved leads will appear here automatically.
                        </div>
                    @else
                        <div class="d-grid gap-2">
                            @foreach ($matchingLeads as $lead)
                                <a href="{{ route('leads.show', $lead) }}" class="text-decoration-none border rounded p-2">
                                    <div class="fw-semibold text-dark">{{ $lead->product_interest }}</div>
                                    <div class="small text-muted">{{ $lead->company_name }} · {{ $lead->country }}</div>
                                    <div class="small text-muted">{{ $lead->product_type?->label() }} · {{ $lead->published_at?->diffForHumans() }}</div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                @endif
            </div>
        </div>
        <div class="panel mb-3">
            <div class="panel-header"><h2>Company page</h2></div>
            <div class="panel-body">
                <p class="small text-muted mb-3">
                    Buyers can view your public storefront, products, and send enquiries.
                </p>
                <div class="d-grid gap-2">
                    <a href="{{ route('seller.profile.edit') }}" class="btn btn-outline-t4d btn-sm">Edit company profile</a>
                    @if ($seller->slug && $seller->is_public)
                        <a href="{{ route('sellers.show', $seller->slug) }}" target="_blank" class="btn btn-primary-t4d btn-sm">
                            Open public page
                        </a>
                    @else
                        <div class="alert alert-warning small mb-0 py-2">
                            Publish your profile to make the storefront visible.
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="panel">
            <div class="panel-header"><h2>Quick tips</h2></div>
            <div class="panel-body small text-muted">
                <ul class="mb-0 ps-3">
                    <li class="mb-2">Add a logo and About Us to build trust.</li>
                    <li class="mb-2">Keep product photos clear and up to date.</li>
                    <li>Set products to <strong>Live</strong> so buyers can see them.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
