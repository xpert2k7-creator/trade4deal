@extends('layouts.marketplace')

@section('title', 'Buyer Leads - Trade4Deal')

@push('styles')
<style>
    .products-page {
        background: #f3f6f9;
        color: #1f2937;
    }

    .products-shell {
        max-width: 1560px;
        margin: 0 auto;
        padding: 0.75rem 0.75rem 2rem;
    }

    .products-topbar {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
        padding: 0.6rem 0.25rem 0.5rem;
    }

    .products-breadcrumb {
        display: flex;
        flex-wrap: wrap;
        gap: 0.35rem;
        margin-bottom: 0.35rem;
        color: #64748b;
        font-size: 0.78rem;
    }

    .products-breadcrumb a {
        color: #0f766e;
        text-decoration: none;
        font-weight: 700;
    }

    .products-title {
        margin: 0;
        color: #0f172a;
        font-size: 1.28rem;
        font-weight: 850;
        line-height: 1.2;
    }

    .products-count {
        margin-top: 0.22rem;
        color: #64748b;
        font-size: 0.84rem;
    }

    .products-view-actions {
        display: flex;
        align-items: center;
        gap: 0.45rem;
        color: #64748b;
        font-size: 0.78rem;
        white-space: nowrap;
    }

    .view-icon {
        width: 30px;
        height: 30px;
        border: 1px solid #d7dee8;
        border-radius: 5px;
        display: inline-grid;
        place-items: center;
        background: #fff;
        color: #0f766e;
    }

    .products-filter-card {
        border: 1px solid #d8e0ea;
        border-radius: 6px;
        background: #fff;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.05);
        overflow: hidden;
    }

    .category-strip {
        display: flex;
        gap: 0.45rem;
        overflow-x: auto;
        padding: 0.6rem 0.7rem;
        border-bottom: 1px solid #e2e8f0;
        scrollbar-width: thin;
    }

    .category-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        flex: 0 0 auto;
        min-height: 30px;
        padding: 0.35rem 0.68rem;
        border: 1px solid #ccd7e3;
        border-radius: 999px;
        background: #fff;
        color: #334155;
        text-decoration: none;
        font-size: 0.76rem;
        font-weight: 800;
    }

    .category-pill:hover,
    .category-pill.is-active {
        border-color: #0f766e;
        background: #e8f6f4;
        color: #0f766e;
    }

    .products-filter-form {
        display: grid;
        grid-template-columns: minmax(220px, 1.25fr) minmax(160px, 0.7fr) minmax(160px, 0.7fr) auto auto;
        gap: 0.55rem;
        align-items: end;
        padding: 0.7rem;
    }

    .field-label {
        display: block;
        margin-bottom: 0.28rem;
        color: #475569;
        font-size: 0.7rem;
        font-weight: 850;
        text-transform: uppercase;
    }

    .products-filter-form .form-control,
    .products-filter-form .form-select {
        min-height: 38px;
        border-radius: 5px;
        font-size: 0.84rem;
    }

    .filter-submit,
    .filter-reset {
        min-height: 38px;
        border-radius: 5px;
        font-size: 0.82rem;
        font-weight: 850;
    }

    .filter-submit {
        border: 0;
        background: #0f766e;
        color: #fff;
    }

    .filter-reset {
        border: 1px solid #d7dee8;
        background: #fff;
        color: #334155;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        padding: 0 0.9rem;
    }

    .active-filter-note {
        display: flex;
        justify-content: space-between;
        gap: 0.75rem;
        padding: 0.6rem 0.7rem;
        border-top: 1px solid #e2e8f0;
        color: #64748b;
        font-size: 0.82rem;
    }

    .active-filter-note strong {
        color: #0f172a;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 0.72rem;
        margin-top: 0.8rem;
    }

    .product-card {
        position: relative;
        min-width: 0;
        border: 1px solid #cfdbe8;
        border-radius: 7px;
        background: #fff;
        overflow: hidden;
        box-shadow: 0 3px 10px rgba(15, 23, 42, 0.08);
        transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
    }

    .product-card:hover {
        transform: translateY(-3px);
        border-color: #0f766e;
        box-shadow: 0 10px 22px rgba(15, 23, 42, 0.14);
    }

    .product-badge {
        position: absolute;
        top: 0.45rem;
        left: 0.45rem;
        z-index: 2;
        border-radius: 4px;
        background: #3b82f6;
        color: #fff;
        padding: 0.2rem 0.42rem;
        font-size: 0.62rem;
        font-weight: 850;
    }

    .product-media {
        position: relative;
        display: block;
        height: 190px;
        background: #f8fafc;
        overflow: hidden;
    }

    .product-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.28s ease;
    }

    .product-card:hover .product-media img {
        transform: scale(1.04);
    }

    .product-img-fallback {
        width: 64px;
        height: 64px;
        border-radius: 8px;
        display: grid;
        place-items: center;
        margin: 62px auto 0;
        background: #e8f6f4;
        color: #0f766e;
        font-size: 1.8rem;
    }

    .product-photo-count {
        position: absolute;
        right: 0.42rem;
        bottom: 0.42rem;
        display: inline-flex;
        align-items: center;
        gap: 0.18rem;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.92);
        color: #0f766e;
        padding: 0.16rem 0.38rem;
        font-size: 0.68rem;
        font-weight: 850;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.12);
    }

    .product-body {
        padding: 0.62rem;
    }

    .product-name {
        display: -webkit-box;
        min-height: 2.35rem;
        margin: 0 0 0.3rem;
        overflow: hidden;
        color: #1e40af;
        font-size: 0.82rem;
        font-weight: 850;
        line-height: 1.38;
        text-decoration: none;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .product-name:hover {
        color: #0f766e;
    }

    .product-price {
        margin-bottom: 0.45rem;
        color: #111827;
        font-size: 0.92rem;
        font-weight: 850;
    }

    .contact-supplier {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        width: 100%;
        min-height: 31px;
        border: 0;
        border-radius: 4px;
        background: #0f766e;
        color: #fff;
        font-size: 0.75rem;
        font-weight: 850;
    }

    .spec-table {
        display: grid;
        gap: 0.16rem;
        margin-top: 0.55rem;
        padding-top: 0.5rem;
        border-top: 1px solid #e2e8f0;
    }

    .spec-row {
        display: grid;
        grid-template-columns: minmax(58px, 0.7fr) minmax(0, 1fr);
        gap: 0.35rem;
        min-height: 1.05rem;
        color: #475569;
        font-size: 0.68rem;
        line-height: 1.25;
    }

    .spec-row span:first-child {
        color: #64748b;
    }

    .spec-row span:last-child {
        min-width: 0;
        overflow: hidden;
        color: #1f2937;
        text-align: right;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-weight: 700;
    }

    .supplier-line {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.35rem;
        margin-top: 0.42rem;
        color: #475569;
        font-size: 0.68rem;
    }

    .supplier-line strong {
        min-width: 0;
        overflow: hidden;
        color: #0f172a;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .rating-line {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.35rem;
        margin-top: 0.35rem;
        color: #f59e0b;
        font-size: 0.68rem;
        font-weight: 850;
    }

    .call-line {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.25rem;
        margin-top: 0.45rem;
        color: #0f766e;
        font-size: 0.7rem;
        font-weight: 850;
        text-decoration: none;
    }

    .quote-band {
        grid-column: 1 / -1;
        margin: 0.35rem auto 0.2rem;
        width: min(100%, 1250px);
        border-radius: 6px;
        background: #34309a;
        box-shadow: 0 8px 20px rgba(31, 41, 55, 0.18);
        color: #fff;
        padding: 1rem 1.4rem;
    }

    .quote-band h2 {
        margin: 0 0 0.75rem;
        color: #fff;
        text-align: center;
        font-size: 0.98rem;
        font-weight: 850;
    }

    .quote-form {
        display: grid;
        grid-template-columns: minmax(170px, 270px) minmax(140px, 210px) auto;
        justify-content: center;
        gap: 0.5rem;
    }

    .quote-form input {
        min-height: 37px;
        border: 0;
        border-radius: 5px;
        padding: 0 0.8rem;
        font-size: 0.82rem;
    }

    .quote-form button {
        min-height: 37px;
        border: 0;
        border-radius: 5px;
        background: #fff;
        color: #1f2474;
        padding: 0 1rem;
        font-size: 0.78rem;
        font-weight: 850;
    }

    .empty-products {
        margin-top: 0.8rem;
        border: 1px dashed #b8c5d4;
        border-radius: 7px;
        background: #fff;
        padding: 2.5rem 1rem;
        text-align: center;
    }

    .empty-products h2 {
        margin: 0 0 0.35rem;
        color: #0f172a;
        font-size: 1.15rem;
        font-weight: 850;
    }

    .empty-products p {
        margin: 0 0 1rem;
        color: #64748b;
    }

    .load-more-panel {
        display: grid;
        place-items: center;
        gap: 0.8rem;
        margin: 1.4rem 0 0;
    }

    .verified-seller-box {
        width: min(100%, 620px);
        border-top: 2px solid #34309a;
        border-bottom: 2px solid #34309a;
        padding: 1rem;
        text-align: center;
    }

    .verified-seller-box h2 {
        margin: 0 0 0.65rem;
        color: #0f172a;
        font-size: 0.95rem;
        font-weight: 850;
    }

    .verified-seller-box input {
        width: min(100%, 420px);
        min-height: 34px;
        border: 1px solid #9ca3af;
        border-radius: 6px;
        padding: 0 0.75rem;
        font-size: 0.82rem;
    }

    .verified-seller-box button {
        display: block;
        width: min(100%, 300px);
        min-height: 34px;
        margin: 0.75rem auto 0;
        border: 0;
        border-radius: 6px;
        background: #9fc7c8;
        color: #fff;
        font-size: 0.78rem;
        font-weight: 850;
    }

    @media (max-width: 1399px) {
        .product-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }
    }

    @media (max-width: 1099px) {
        .product-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .products-filter-form {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 767px) {
        .products-topbar,
        .active-filter-note {
            flex-direction: column;
        }

        .product-grid,
        .products-filter-form,
        .quote-form {
            grid-template-columns: 1fr;
        }

        .product-media {
            height: 220px;
        }
    }

    @media (max-width: 520px) {
        .product-grid {
            gap: 0.6rem;
        }

        .product-media {
            height: 190px;
        }
    }
</style>
@endpush

@section('content')
@php
    $selectedCategory = $filters['category'] !== '' ? $categories->firstWhere('value', $filters['category']) : null;
    $heading = $selectedCategory?->label() ?? ($filters['search'] !== '' ? $filters['search'] : 'Buyer Leads');
@endphp

<main class="products-page">
    <div class="products-shell">
        <div class="products-topbar">
            <div>
                <nav class="products-breadcrumb" aria-label="Breadcrumb">
                    <a href="{{ route('home') }}">Home</a>
                    <span>/</span>
                    <a href="{{ route('marketplace.page', ['page' => 'lead-directory']) }}">Buyer Leads</a>
                    @if($selectedCategory)
                        <span>/</span>
                        <span>{{ $selectedCategory->label() }}</span>
                    @endif
                </nav>
                <h1 class="products-title">{{ $heading }}</h1>
                <div class="products-count">
                    {{ number_format($leads->total()) }} buyer lead{{ $leads->total() === 1 ? '' : 's' }}
                    @if($filters['country'] !== '')
                        in {{ $filters['country'] }}
                    @endif
                </div>
            </div>
            <div class="products-view-actions" aria-label="Lead view options">
                <span>Sort by</span>
                <strong>Latest</strong>
                <span class="view-icon"><i class="bi bi-grid-3x3-gap"></i></span>
                <span class="view-icon"><i class="bi bi-list-ul"></i></span>
            </div>
        </div>

        <section class="products-filter-card" aria-label="Buyer lead filters">
            <div class="category-strip">
                <a class="category-pill {{ $filters['category'] === '' ? 'is-active' : '' }}" href="{{ route('marketplace.page', ['page' => 'lead-directory', 'search' => $filters['search'] ?: null, 'country' => $filters['country'] ?: null]) }}">
                    <i class="bi bi-grid"></i> All Leads
                </a>
                @foreach($categories as $category)
                    <a
                        class="category-pill {{ $filters['category'] === $category->value ? 'is-active' : '' }}"
                        href="{{ route('marketplace.page', ['page' => 'lead-directory', 'category' => $category->value, 'search' => $filters['search'] ?: null, 'country' => $filters['country'] ?: null]) }}"
                    >
                        <i class="bi bi-tag"></i>{{ $category->label() }}
                    </a>
                @endforeach
            </div>

            <form class="products-filter-form" method="GET" action="{{ route('marketplace.page', ['page' => 'lead-directory']) }}">
                <div>
                    <label class="field-label" for="leadSearch">Search Buyer Leads</label>
                    <input id="leadSearch" class="form-control" type="search" name="search" value="{{ $filters['search'] }}" placeholder="Search product need, company, or country" autocomplete="off">
                </div>
                <div>
                    <label class="field-label" for="leadCategory">Category</label>
                    <select id="leadCategory" class="form-select" name="category">
                        <option value="">All categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->value }}" @selected($filters['category'] === $category->value)>{{ $category->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="field-label" for="leadCountry">Country</label>
                    <select id="leadCountry" class="form-select" name="country">
                        <option value="">All countries</option>
                        @foreach($countries as $country)
                            <option value="{{ $country }}" @selected($filters['country'] === $country)>{{ $country }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="filter-submit" type="submit"><i class="bi bi-search me-1"></i>Search</button>
                <a class="filter-reset" href="{{ route('marketplace.page', ['page' => 'lead-directory']) }}">Reset</a>
            </form>

            <div class="active-filter-note">
                <span>
                    Showing <strong>active published buyer leads</strong>
                    @if($filters['country'] !== '')
                        from <strong>{{ $filters['country'] }}</strong>
                    @else
                        across <strong>all countries</strong>
                    @endif
                </span>
                <span><i class="bi bi-shield-check"></i> Lead detail pages include contact actions</span>
            </div>
        </section>

        @if($leads->isEmpty())
            <section class="empty-products">
                <h2>{{ $filters['search'] !== '' || $filters['category'] !== '' || $filters['country'] !== '' ? 'No matching buyer leads' : 'No buyer leads found' }}</h2>
                <p>Try another product need, category, or country.</p>
                <a href="{{ route('marketplace.page', ['page' => 'lead-directory']) }}" class="filter-submit d-inline-flex align-items-center text-decoration-none px-4">View all buyer leads</a>
            </section>
        @else
            <section class="product-grid" aria-label="Buyer lead listings">
                @foreach($leads as $lead)
                    @php
                        $leadUrl = route('leads.show', $lead);
                        $unitLabel = $lead->units?->label() ?? 'Unit';
                        $roleLabel = $lead->business_type?->label() ?? 'Lead';
                    @endphp

                    <article class="product-card marketplace-search-item" data-search-text="{{ strtolower($lead->product_interest.' '.$lead->company_name.' '.$lead->country.' '.$lead->product_type?->label().' '.$roleLabel) }}">
                        <span class="product-badge">{{ $roleLabel }}</span>
                        <a class="product-media" href="{{ $leadUrl }}" aria-label="View {{ $lead->product_interest }}">
                            @if($lead->productImageUrl())
                                <img src="{{ $lead->productImageUrl() }}" alt="{{ $lead->product_interest }}" loading="lazy" onerror="this.nextElementSibling.classList.remove('d-none'); this.remove();">
                                <span class="product-img-fallback d-none" aria-hidden="true"><i class="bi bi-clipboard-check"></i></span>
                            @else
                                <span class="product-img-fallback" aria-hidden="true"><i class="bi bi-clipboard-check"></i></span>
                            @endif
                            <span class="product-photo-count"><i class="bi bi-clock"></i> {{ $lead->published_at?->diffForHumans() ?? 'New' }}</span>
                        </a>

                        <div class="product-body">
                            <a class="product-name" href="{{ $leadUrl }}">{{ $lead->product_interest }}</a>
                            <div class="product-price">{{ $lead->currency?->value ?? 'USD' }} · {{ $unitLabel }}</div>
                            <a class="contact-supplier text-decoration-none" href="{{ $leadUrl }}">
                                <i class="bi bi-send-fill"></i> View &amp; Contact
                            </a>

                            <div class="spec-table" aria-label="{{ $lead->product_interest }} details">
                                <div class="spec-row"><span>Category</span><span>{{ $lead->product_type?->label() ?? 'Other' }}</span></div>
                                <div class="spec-row"><span>Trade</span><span>{{ $roleLabel }}</span></div>
                                <div class="spec-row"><span>Unit</span><span>{{ $unitLabel }}</span></div>
                                <div class="spec-row"><span>Country</span><span>{{ $lead->country ?: 'Not set' }}</span></div>
                            </div>

                            <div class="supplier-line">
                                <strong>{{ $lead->company_name }}</strong>
                                <span><i class="bi bi-patch-check-fill text-success"></i></span>
                            </div>
                            <div class="rating-line">
                                <span><i class="bi bi-geo-alt-fill"></i> Country</span>
                                <span>{{ $lead->country ?: 'Global' }}</span>
                            </div>
                            <a class="call-line" href="{{ $leadUrl }}"><i class="bi bi-eye"></i> Open Lead</a>
                        </div>
                    </article>

                    @if($loop->iteration === 10 || $loop->iteration === 25)
                        <aside class="quote-band" aria-label="Submit your buyer requirement">
                            <h2>Looking for suppliers? Submit your requirement</h2>
                            <form class="quote-form" action="{{ route('marketplace.page', ['page' => 'submit-requirement']) }}" method="GET">
                                <input type="text" name="product" value="{{ $filters['search'] ?: $heading }}" aria-label="Product requirement">
                                <input type="tel" name="mobile" placeholder="+91  Enter your mobile" aria-label="Mobile number">
                                <button type="button" data-bs-toggle="modal" data-bs-target="#leadModal">
                                    <i class="bi bi-send me-1"></i>Submit Requirement
                                </button>
                            </form>
                        </aside>
                    @endif
                @endforeach
            </section>

            <div class="load-more-panel">
                {{ $leads->links() }}
                <div class="verified-seller-box">
                    <h2><i class="bi bi-send-fill me-1"></i>Post Your Buyer Requirement</h2>
                    <input type="tel" placeholder="Enter your mobile number" aria-label="Mobile number">
                    <button type="button" data-bs-toggle="modal" data-bs-target="#leadModal">CONTINUE</button>
                </div>
            </div>
        @endif
    </div>
</main>
@endsection
