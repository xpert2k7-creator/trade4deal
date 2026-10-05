@extends('layouts.marketplace')

@section('title', ($seller->company_name ?? 'Seller').' - Trade4Deal')

@push('styles')
<style>
    .seller-store {
        background:
            radial-gradient(circle at 8% 12%, rgba(245, 130, 32, 0.09), transparent 28%),
            radial-gradient(circle at 92% 6%, rgba(6, 68, 117, 0.12), transparent 30%),
            linear-gradient(180deg, #f8fbff 0%, #eef4fb 48%, #f8fafc 100%);
        color: #0f172a;
    }

    .seller-hero {
        position: relative;
        min-height: 330px;
        overflow: hidden;
        background:
            linear-gradient(115deg, rgba(3, 21, 43, 0.94), rgba(6, 68, 117, 0.88) 52%, rgba(13, 148, 136, 0.68)),
            {{ $seller->coverImageUrl() ? "url('".$seller->coverImageUrl()."') center/cover" : "url('https://images.unsplash.com/photo-1494412685616-a5d310fbb07d?auto=format&fit=crop&w=1900&q=82') center/cover" }};
        color: #fff;
    }

    .seller-hero::before {
        content: "";
        position: absolute;
        inset: 0;
        background:
            radial-gradient(circle at 74% 20%, rgba(255, 255, 255, 0.22), transparent 15%),
            linear-gradient(22deg, rgba(255, 255, 255, 0.09), transparent 32%);
        pointer-events: none;
    }

    .seller-hero-inner {
        position: relative;
        display: grid;
        align-items: end;
        min-height: 330px;
        padding: 3.2rem 0 4.6rem;
    }

    .seller-breadcrumb {
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
        margin-bottom: 1.2rem;
        color: rgba(255, 255, 255, 0.76);
        font-size: 0.82rem;
        font-weight: 700;
    }

    .seller-breadcrumb a {
        color: #fff;
        text-decoration: none;
    }

    .seller-hero-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        gap: 2rem;
        align-items: end;
    }

    .seller-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        width: fit-content;
        margin-bottom: 0.85rem;
        border: 1px solid rgba(255, 255, 255, 0.25);
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.14);
        padding: 0.42rem 0.78rem;
        color: #fff;
        font-size: 0.76rem;
        font-weight: 850;
        text-transform: uppercase;
        backdrop-filter: blur(10px);
    }

    .seller-hero-title {
        max-width: 880px;
        margin: 0;
        color: #fff;
        font-size: clamp(2rem, 4.3vw, 4rem);
        font-weight: 900;
        line-height: 1.02;
        letter-spacing: 0;
    }

    .seller-hero-subtitle {
        max-width: 720px;
        margin: 0.8rem 0 0;
        color: rgba(255, 255, 255, 0.82);
        font-size: 1.02rem;
        line-height: 1.7;
    }

    .hero-action-card {
        min-width: 250px;
        border: 1px solid rgba(255, 255, 255, 0.22);
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.13);
        padding: 1rem;
        backdrop-filter: blur(16px);
        box-shadow: 0 24px 60px rgba(0, 0, 0, 0.18);
    }

    .hero-action-card strong {
        display: block;
        color: #fff;
        font-size: 1.05rem;
        margin-bottom: 0.3rem;
    }

    .hero-action-card span {
        display: block;
        color: rgba(255, 255, 255, 0.75);
        font-size: 0.82rem;
        line-height: 1.5;
    }

    .hero-action-card a {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
        margin-top: 0.9rem;
        border-radius: 10px;
        background: #fff;
        color: var(--t4d-primary);
        padding: 0.72rem 1rem;
        text-decoration: none;
        font-weight: 900;
    }

    .seller-profile-wrap {
        position: relative;
        margin-top: -3.4rem;
        padding-bottom: 3rem;
    }

    .seller-identity-card {
        display: grid;
        grid-template-columns: auto minmax(0, 1fr) auto;
        gap: 1rem;
        align-items: center;
        border: 1px solid rgba(203, 213, 225, 0.75);
        border-radius: 18px;
        background: rgba(255, 255, 255, 0.94);
        padding: 1.1rem;
        box-shadow: 0 24px 65px rgba(15, 23, 42, 0.14);
        backdrop-filter: blur(14px);
    }

    .seller-logo,
    .seller-logo-ph {
        width: 104px;
        height: 104px;
        border: 4px solid #fff;
        border-radius: 22px;
        box-shadow: 0 18px 42px rgba(15, 23, 42, 0.18);
    }

    .seller-logo {
        object-fit: cover;
        background: #fff;
    }

    .seller-logo-ph {
        display: grid;
        place-items: center;
        background: linear-gradient(135deg, var(--t4d-primary), #0e7490);
        color: #fff;
        font-size: 2.3rem;
        font-weight: 900;
    }

    .seller-title {
        margin: 0 0 0.3rem;
        color: #061224;
        font-size: clamp(1.45rem, 2.5vw, 2.15rem);
        font-weight: 900;
        line-height: 1.12;
        letter-spacing: 0;
    }

    .seller-tagline {
        margin: 0;
        color: #64748b;
        line-height: 1.55;
    }

    .seller-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 0.45rem;
        margin-top: 0.75rem;
    }

    .store-chip,
    .verified-supplier-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.34rem;
        min-height: 31px;
        border-radius: 999px;
        padding: 0.35rem 0.7rem;
        font-size: 0.75rem;
        font-weight: 850;
        line-height: 1;
    }

    .store-chip {
        border: 1px solid #dbe3ef;
        background: #f8fafc;
        color: #334155;
    }

    .verified-supplier-badge {
        border: 1px solid rgba(245, 130, 32, 0.35);
        background: rgba(245, 130, 32, 0.14);
        color: #9a4b09;
    }

    .identity-stats {
        display: grid;
        grid-template-columns: repeat(3, minmax(82px, 1fr));
        gap: 0.6rem;
        min-width: 310px;
    }

    .identity-stat {
        border: 1px solid #e2e8f0;
        border-radius: 13px;
        background: linear-gradient(180deg, #fff, #f8fafc);
        padding: 0.78rem;
        text-align: center;
    }

    .identity-stat strong {
        display: block;
        color: var(--t4d-primary);
        font-size: 1.1rem;
        font-weight: 900;
    }

    .identity-stat span {
        color: #64748b;
        font-size: 0.72rem;
        font-weight: 800;
    }

    .seller-content-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(330px, 0.43fr);
        gap: 1.25rem;
        align-items: start;
        margin-top: 1.25rem;
    }

    .store-panel,
    .enquire-card {
        border: 1px solid rgba(203, 213, 225, 0.82);
        border-radius: 18px;
        background: rgba(255, 255, 255, 0.96);
        box-shadow: 0 18px 45px rgba(15, 23, 42, 0.08);
    }

    .store-panel {
        padding: 1.25rem;
        margin-bottom: 1rem;
    }

    .panel-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 0.9rem;
    }

    .panel-heading h2,
    .enquire-card h2 {
        margin: 0;
        color: #061224;
        font-size: 1.08rem;
        font-weight: 900;
        letter-spacing: 0;
    }

    .panel-icon {
        width: 38px;
        height: 38px;
        border-radius: 11px;
        display: grid;
        place-items: center;
        background: #e8f1fb;
        color: var(--t4d-primary);
        font-size: 1.08rem;
    }

    .about-text,
    .store-muted {
        margin: 0;
        color: #64748b;
        font-size: 0.95rem;
        line-height: 1.72;
    }

    .about-text {
        white-space: pre-wrap;
    }

    .industry-strip {
        display: flex;
        flex-wrap: wrap;
        gap: 0.55rem;
    }

    .industry-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.42rem;
        border: 1px solid #dbe3ef;
        border-radius: 12px;
        background: linear-gradient(180deg, #fff, #f8fafc);
        color: #0f172a;
        padding: 0.62rem 0.75rem;
        font-size: 0.83rem;
        font-weight: 850;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.9rem;
    }

    .product-card {
        display: grid;
        grid-template-rows: 190px minmax(156px, auto);
        min-height: 100%;
        overflow: hidden;
        border: 1px solid #dbe3ef;
        border-radius: 16px;
        background: #fff;
        color: inherit;
        text-decoration: none;
        box-shadow: 0 10px 26px rgba(15, 23, 42, 0.07);
        transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
    }

    .product-card:hover {
        transform: translateY(-4px);
        border-color: rgba(6, 68, 117, 0.32);
        box-shadow: 0 20px 45px rgba(15, 23, 42, 0.13);
        color: inherit;
    }

    .product-media {
        position: relative;
        display: grid;
        place-items: center;
        overflow: hidden;
        background: linear-gradient(135deg, #f8fafc, #e8f1fb);
    }

    .product-media img,
    .product-media .ph {
        width: 100%;
        height: 100%;
        display: block;
    }

    .product-media img {
        object-fit: cover;
        transition: transform 0.28s ease;
    }

    .product-card:hover .product-media img {
        transform: scale(1.04);
    }

    .product-media .ph {
        display: grid;
        place-items: center;
        color: var(--t4d-primary);
        font-size: 2rem;
    }

    .product-type-badge {
        position: absolute;
        left: 0.75rem;
        top: 0.75rem;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.92);
        color: var(--t4d-primary);
        padding: 0.32rem 0.6rem;
        font-size: 0.7rem;
        font-weight: 900;
        box-shadow: 0 8px 18px rgba(15, 23, 42, 0.1);
    }

    .product-card .body {
        display: grid;
        gap: 0.48rem;
        padding: 0.95rem;
    }

    .product-title {
        display: -webkit-box;
        min-height: 2.55rem;
        margin: 0;
        overflow: hidden;
        color: #0f172a;
        font-size: 1rem;
        font-weight: 900;
        line-height: 1.28;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .product-description {
        display: -webkit-box;
        min-height: 2.45rem;
        margin: 0;
        overflow: hidden;
        color: #64748b;
        font-size: 0.82rem;
        line-height: 1.5;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .product-meta-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.75rem;
        border-top: 1px solid #edf2f7;
        padding-top: 0.62rem;
    }

    .product-price {
        color: var(--t4d-accent-dark);
        font-weight: 900;
        font-size: 0.9rem;
    }

    .meta-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.7rem;
    }

    .meta-item {
        border: 1px solid #e2e8f0;
        border-radius: 13px;
        background: #f8fafc;
        padding: 0.78rem;
    }

    .meta-item .label {
        color: #64748b;
        font-size: 0.64rem;
        font-weight: 900;
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }

    .meta-item .value {
        margin-top: 0.2rem;
        color: #0f172a;
        font-size: 0.9rem;
        font-weight: 850;
        overflow-wrap: anywhere;
    }

    .privacy-note {
        display: flex;
        gap: 0.45rem;
        margin-top: 0.9rem;
        border: 1px solid #dbe3ef;
        border-radius: 12px;
        background: #f8fafc;
        color: #64748b;
        padding: 0.72rem;
        font-size: 0.82rem;
        line-height: 1.45;
    }

    .enquire-card {
        position: sticky;
        top: 5.25rem;
        padding: 1.15rem;
    }

    .enquire-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        margin-bottom: 0.45rem;
        color: var(--t4d-accent-dark);
        font-size: 0.7rem;
        font-weight: 900;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .enquire-card .form-label {
        color: #0f172a;
        font-size: 0.84rem;
        font-weight: 850;
    }

    .enquire-card .form-control {
        min-height: 44px;
        border-radius: 10px;
    }

    .enquire-card textarea.form-control {
        min-height: 112px;
    }

    .enquire-submit {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
        min-height: 48px;
        border: 0;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--t4d-primary), #0e7490);
        color: #fff;
        font-weight: 900;
        box-shadow: 0 12px 26px rgba(6, 68, 117, 0.22);
    }

    .seller-side-stack {
        display: grid;
        gap: 1rem;
    }

    @media (max-width: 1199px) {
        .seller-content-grid {
            grid-template-columns: minmax(0, 1fr) minmax(310px, 0.45fr);
        }

        .product-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 991px) {
        .seller-hero-grid,
        .seller-identity-card,
        .seller-content-grid {
            grid-template-columns: 1fr;
        }

        .hero-action-card,
        .identity-stats {
            min-width: 0;
        }

        .enquire-card {
            position: static;
        }

        .product-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 575px) {
        .seller-hero-inner {
            min-height: 300px;
            padding: 2rem 0 4rem;
        }

        .seller-profile-wrap {
            margin-top: -2.2rem;
        }

        .seller-identity-card {
            padding: 0.9rem;
        }

        .seller-logo,
        .seller-logo-ph {
            width: 84px;
            height: 84px;
            border-radius: 18px;
        }

        .identity-stats,
        .meta-grid,
        .product-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
@php
    $sellerLocation = collect([$seller->city, $seller->state, $seller->country])->filter()->implode(', ');
    $productTotal = $products->total();
    $industryTotal = count($seller->industryEnums());
@endphp

<div class="seller-store">
    <section class="seller-hero">
        <div class="container seller-hero-inner">
            <div>
                <nav class="seller-breadcrumb" aria-label="Breadcrumb">
                    <a href="{{ route('home') }}">Home</a>
                    <span>/</span>
                    <a href="{{ route('marketplace.page', ['page' => 'product-directory']) }}">Products</a>
                    <span>/</span>
                    <span>{{ $seller->company_name }}</span>
                </nav>

                <div class="seller-hero-grid">
                    <div>
                        <span class="seller-kicker"><i class="bi bi-shop-window"></i> Trade4Deal Supplier Storefront</span>
                        <h1 class="seller-hero-title">{{ $seller->company_name }}</h1>
                        <p class="seller-hero-subtitle">
                            {{ $seller->tagline ?: 'Explore products, company information, and enquiry options from this Trade4Deal seller profile.' }}
                        </p>
                    </div>

                    <aside class="hero-action-card">
                        <strong>Ready to source from this supplier?</strong>
                        <span>Send your requirement with quantity, delivery, and specification details.</span>
                        <a href="#enquire"><i class="bi bi-send"></i> Enquire now</a>
                    </aside>
                </div>
            </div>
        </div>
    </section>

    <section class="seller-profile-wrap">
        <div class="container">
            <div class="seller-identity-card">
                @if ($seller->logoUrl())
                    <img src="{{ $seller->logoUrl() }}" alt="{{ $seller->company_name }}" class="seller-logo">
                @else
                    <div class="seller-logo-ph">{{ strtoupper(substr($seller->company_name ?? 'S', 0, 1)) }}</div>
                @endif

                <div>
                    @if ($seller->userPlan()->isGold())
                        <span class="verified-supplier-badge">
                            <i class="bi bi-patch-check-fill"></i>
                            Verified supplier by Trade4Deal
                        </span>
                    @endif
                    <h2 class="seller-title">{{ $seller->company_name }}</h2>
                    <p class="seller-tagline">{{ $seller->tagline ?: 'Verified Trade4Deal seller' }}</p>
                    <div class="seller-chips">
                        @if ($sellerLocation)
                            <span class="store-chip"><i class="bi bi-geo-alt"></i>{{ $sellerLocation }}</span>
                        @endif
                        @if ($seller->year_established)
                            <span class="store-chip"><i class="bi bi-calendar3"></i>Est. {{ $seller->year_established }}</span>
                        @endif
                        @if ($seller->employees_range)
                            <span class="store-chip"><i class="bi bi-people"></i>{{ $seller->employees_range->label() }}</span>
                        @endif
                    </div>
                </div>

                <div class="identity-stats" aria-label="Seller summary">
                    <div class="identity-stat">
                        <strong>{{ $productTotal }}</strong>
                        <span>Products</span>
                    </div>
                    <div class="identity-stat">
                        <strong>{{ $industryTotal }}</strong>
                        <span>Industries</span>
                    </div>
                    <div class="identity-stat">
                        <strong>{{ $seller->userPlan()->isGold() ? 'Gold' : 'Free' }}</strong>
                        <span>Plan</span>
                    </div>
                </div>
            </div>

            <div class="seller-content-grid">
                <main>
                    <section class="store-panel">
                        <div class="panel-heading">
                            <h2>About us</h2>
                            <span class="panel-icon"><i class="bi bi-building"></i></span>
                        </div>
                        @if ($seller->about)
                            <p class="about-text">{{ $seller->about }}</p>
                        @else
                            <p class="store-muted">{{ $seller->company_name }} is a Trade4Deal seller based in {{ $seller->country }}. Use the enquiry form to connect.</p>
                        @endif
                    </section>

                    @if ($industryTotal > 0)
                        <section class="store-panel">
                            <div class="panel-heading">
                                <h2>Industries</h2>
                                <span class="panel-icon"><i class="bi bi-tags"></i></span>
                            </div>
                            <div class="industry-strip">
                                @foreach ($seller->industryEnums() as $industry)
                                    <span class="industry-pill"><i class="bi bi-check2-circle"></i>{{ $industry->label() }}</span>
                                @endforeach
                            </div>
                        </section>
                    @endif

                    <section class="store-panel" id="products">
                        <div class="panel-heading">
                            <div>
                                <h2>Products</h2>
                                <p class="store-muted">{{ $productTotal }} listed product{{ $productTotal === 1 ? '' : 's' }}</p>
                            </div>
                            <span class="panel-icon"><i class="bi bi-box-seam"></i></span>
                        </div>

                        @if ($products->isEmpty())
                            <p class="store-muted">No live products yet.</p>
                        @else
                            <div class="product-grid">
                                @foreach ($products as $product)
                                    <article class="product-card">
                                        @php($productImageUrls = $product->imageUrls())
                                        <div class="product-media">
                                            @if ($productImageUrls !== [])
                                                <img src="{{ $productImageUrls[0] }}" alt="{{ $product->name }}" loading="lazy">
                                            @else
                                                <div class="ph"><i class="bi bi-box-seam"></i></div>
                                            @endif
                                            @if (count($productImageUrls) > 1)
                                                <span class="product-type-badge" style="right:auto;left:0.65rem;">
                                                    <i class="bi bi-images"></i> {{ count($productImageUrls) }}
                                                </span>
                                            @endif
                                            <span class="product-type-badge">{{ $product->product_type?->label() }}</span>
                                        </div>
                                        <div class="body">
                                            <h3 class="product-title">{{ $product->name }}</h3>
                                            <p class="product-description">{{ $product->description ?: 'Contact this supplier for specifications, quantity, and delivery details.' }}</p>
                                            <div class="product-meta-row">
                                                <span class="product-price">{{ $product->priceLabel() }}</span>
                                                <span class="small text-muted">{{ $product->units?->label() }}</span>
                                            </div>
                                            @if ($product->min_order_qty)
                                                <div class="small text-muted">MOQ: {{ $product->min_order_qty }}</div>
                                            @endif
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                            <div class="mt-3">
                                {{ $products->links() }}
                            </div>
                        @endif
                    </section>
                </main>

                <aside class="seller-side-stack">
                    <section class="store-panel">
                        <div class="panel-heading">
                            <h2>Company details</h2>
                            <span class="panel-icon"><i class="bi bi-person-vcard"></i></span>
                        </div>
                        <div class="meta-grid">
                            <div class="meta-item">
                                <div class="label">Contact</div>
                                <div class="value">
                                    {{ $seller->name }}
                                    @if ($seller->designation)
                                        <div class="small text-muted fw-normal">{{ $seller->designation }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="meta-item">
                                <div class="label">Country</div>
                                <div class="value">{{ $seller->country }}</div>
                            </div>
                            @if ($seller->state)
                                <div class="meta-item">
                                    <div class="label">State</div>
                                    <div class="value">{{ $seller->state }}</div>
                                </div>
                            @endif
                            @if ($seller->district)
                                <div class="meta-item">
                                    <div class="label">District</div>
                                    <div class="value">{{ $seller->district }}</div>
                                </div>
                            @endif
                            @if ($seller->city)
                                <div class="meta-item">
                                    <div class="label">City</div>
                                    <div class="value">{{ $seller->city }}</div>
                                </div>
                            @endif
                            @if ($seller->gstin)
                                <div class="meta-item">
                                    <div class="label">GSTIN</div>
                                    <div class="value">{{ $seller->gstin }}</div>
                                </div>
                            @endif
                            @if ($seller->cin)
                                <div class="meta-item">
                                    <div class="label">CIN</div>
                                    <div class="value">{{ $seller->cin }}</div>
                                </div>
                            @endif
                            @if ($seller->website)
                                <div class="meta-item">
                                    <div class="label">Website</div>
                                    <div class="value"><a href="{{ $seller->website }}" target="_blank" rel="noopener" class="text-decoration-none" style="color:var(--t4d-primary);">Visit site</a></div>
                                </div>
                            @endif
                        </div>
                        <div class="privacy-note">
                            <i class="bi bi-shield-lock"></i>
                            <span>Phone number is private. Reach this seller with the enquiry form.</span>
                        </div>
                    </section>

                    <section class="enquire-card" id="enquire">
                        <span class="enquire-eyebrow"><i class="bi bi-lightning-charge-fill"></i> Connect</span>
                        <h2>Enquire with seller</h2>
                        <p class="store-muted">Your message is emailed directly to {{ $seller->company_name }}.</p>
                        <div class="privacy-note">
                            <i class="bi bi-eye-slash"></i>
                            <span>Seller phone is never shown publicly.</span>
                        </div>

                        <form method="POST" action="{{ route('sellers.contact', $seller->slug) }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="name">Your name</label>
                                <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', auth()->user()?->name) }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="email">Your email</label>
                                <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email', auth()->user()?->email) }}" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="company_name">Your company</label>
                                <input id="company_name" name="company_name" type="text" class="form-control @error('company_name') is-invalid @enderror"
                                       value="{{ old('company_name', auth()->user()?->company_name) }}" required>
                                @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="row g-2 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="country">Country</label>
                                    <input id="country" name="country" type="text" class="form-control" value="{{ old('country', auth()->user()?->country) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="phone">Phone <span class="text-muted fw-normal">(optional)</span></label>
                                    <input id="phone" name="phone" type="text" class="form-control" value="{{ old('phone', auth()->user()?->phone) }}">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="message">Message</label>
                                <textarea id="message" name="message" rows="4" class="form-control @error('message') is-invalid @enderror"
                                          placeholder="Tell them what you need..." required>{{ old('message') }}</textarea>
                                @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <button type="submit" class="enquire-submit w-100">
                                <i class="bi bi-send"></i> Send enquiry
                            </button>
                        </form>
                    </section>
                </aside>
            </div>
        </div>
    </section>
</div>
@endsection
