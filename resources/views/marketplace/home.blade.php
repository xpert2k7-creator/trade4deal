@extends('layouts.marketplace')

@section('title', 'Trade4Deal — Global B2B Marketplace')

@section('content')
{{-- HERO --}}
<section class="hero-section">
    <div class="hero-bg" aria-hidden="true"></div>
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="hero-badge d-inline-flex align-items-center gap-2 mb-3 animate-in">
                    <i class="bi bi-globe2"></i> Trusted by businesses in 120+ countries
                </div>
                <h1 class="hero-title mb-4 animate-in animate-in-delay-1">
                    Connect with verified<br>
                    <em>global B2B partners</em>
                </h1>
                <p class="hero-lead mb-4 animate-in animate-in-delay-2">
                    Trade4Deal is your enterprise marketplace to discover buyers, sellers,
                    and trade opportunities worldwide — built for scale, security, and serious business.
                </p>
                <div class="d-flex flex-wrap gap-3 animate-in animate-in-delay-3">
                    <a href="{{ route('register') }}" class="btn btn-primary-t4d btn-lg text-white px-4">
                        <i class="bi bi-person-plus me-2"></i>Register Your Business
                    </a>
                    <button type="button" class="btn btn-ghost-light btn-lg px-4" data-bs-toggle="modal" data-bs-target="#leadModal">
                        <i class="bi bi-send me-2"></i>Submit a Lead
                    </button>
                </div>

                <div class="hero-stats animate-in animate-in-delay-3">
                    <div class="hero-stat">
                        <div class="value">{{ $leads->total() }}+</div>
                        <div class="label">Active Leads</div>
                    </div>
                    <div class="hero-stat">
                        <div class="value">120+</div>
                        <div class="label">Countries</div>
                    </div>
                    <div class="hero-stat">
                        <div class="value">50K+</div>
                        <div class="label">Products</div>
                    </div>
                    <div class="hero-stat">
                        <div class="value">99.9%</div>
                        <div class="label">Uptime SLA</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- HOW IT WORKS INFOGRAPHIC --}}
<section class="py-5" id="how-it-works">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="badge rounded-pill text-bg-primary bg-opacity-10 text-primary px-3 py-2 mb-3">
                How Trade4Deal works
            </span>
            <h2 class="section-title mb-2">From inquiry to partnership in 4 steps</h2>
            <p class="text-muted mx-auto" style="max-width: 520px;">
                A simple, secure flow designed for global buyers and sellers.
            </p>
        </div>

        <div class="row g-4 align-items-stretch">
            <div class="col-lg-3 col-md-6">
                <div class="infographic-step">
                    <div class="infographic-connector d-none d-lg-block"></div>
                    <div class="step-visual">
                        <div class="step-icon"><i class="bi bi-person-badge"></i></div>
                        <span class="step-num">1</span>
                    </div>
                    <h5 class="fw-bold">Register</h5>
                    <p class="text-muted small mb-0">Create your free buyer or seller profile in under 2 minutes.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="infographic-step">
                    <div class="infographic-connector d-none d-lg-block"></div>
                    <div class="step-visual">
                        <div class="step-icon"><i class="bi bi-send"></i></div>
                        <span class="step-num">2</span>
                    </div>
                    <h5 class="fw-bold">Submit Lead</h5>
                    <p class="text-muted small mb-0">Post your product interest or supply offer via our lead popup.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="infographic-step">
                    <div class="infographic-connector d-none d-lg-block"></div>
                    <div class="step-visual">
                        <div class="step-icon"><i class="bi bi-search"></i></div>
                        <span class="step-num">3</span>
                    </div>
                    <h5 class="fw-bold">Get Discovered</h5>
                    <p class="text-muted small mb-0">Verified partners worldwide browse live leads and reach out.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="infographic-step">
                    <div class="step-visual">
                        <div class="step-icon"><i class="bi bi-trophy"></i></div>
                        <span class="step-num">4</span>
                    </div>
                    <h5 class="fw-bold">Close Deals</h5>
                    <p class="text-muted small mb-0">Negotiate, share RFQs, and grow your international trade.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- FEATURE + IMAGE --}}
<section class="py-5 border-top border-bottom" style="border-color: var(--t4d-border) !important; background: var(--t4d-surface);">
    <div class="container py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="feature-visual">
                    <img
                        src="https://images.unsplash.com/photo-1566576721346-d4a3b4eaeb55?auto=format&fit=crop&w=1200&q=80"
                        alt="Global logistics and shipping containers"
                        loading="lazy"
                    >
                    <div class="feature-float" style="bottom: 1.25rem; left: 1.25rem;">
                        <div class="icon-box" style="background: #0B3A6E;"><i class="bi bi-truck"></i></div>
                        <div>
                            <div class="fw-bold small">Logistics ready</div>
                            <div class="text-muted" style="font-size: 0.75rem;">Ship across continents</div>
                        </div>
                    </div>
                    <div class="feature-float" style="top: 1.25rem; right: 1.25rem;">
                        <div class="icon-box" style="background: #0E7490;"><i class="bi bi-shield-lock"></i></div>
                        <div>
                            <div class="fw-bold small">Verified partners</div>
                            <div class="text-muted" style="font-size: 0.75rem;">Trust &amp; compliance</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <span class="badge rounded-pill text-bg-success bg-opacity-10 text-success px-3 py-2 mb-3">
                    Built for enterprise trade
                </span>
                <h2 class="section-title mb-3">A marketplace that looks as serious as your business</h2>
                <p class="text-muted mb-4">
                    From industrial machinery to textiles and electronics — Trade4Deal connects
                    decision-makers with the right counterparts, faster.
                </p>

                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <div class="d-flex gap-3">
                            <div class="step-icon flex-shrink-0" style="width: 48px; height: 48px; border-radius: 12px; font-size: 1.2rem;">
                                <i class="bi bi-bar-chart-line"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Live lead board</h6>
                                <p class="text-muted small mb-0">See fresh buyer &amp; seller interest in real time.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex gap-3">
                            <div class="step-icon flex-shrink-0" style="width: 48px; height: 48px; border-radius: 12px; font-size: 1.2rem;">
                                <i class="bi bi-lock"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Secure by design</h6>
                                <p class="text-muted small mb-0">RBAC, audit logs, and rate-limited submissions.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-primary-t4d text-white" data-bs-toggle="modal" data-bs-target="#leadModal">
                    <i class="bi bi-plus-circle me-2"></i>Post your first lead
                </button>
            </div>
        </div>
    </div>
</section>

{{-- INDUSTRIES / INFOGRAPHIC CARDS --}}
<section class="py-5" id="industries">
    <div class="container py-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4">
            <div>
                <span class="badge rounded-pill text-bg-primary bg-opacity-10 text-primary px-3 py-2 mb-2">
                    Industries
                </span>
                <h2 class="section-title mb-0">Trade across high-demand categories</h2>
            </div>
            <button type="button" class="btn btn-outline-t4d" data-bs-toggle="modal" data-bs-target="#leadModal">
                Submit interest <i class="bi bi-arrow-right ms-1"></i>
            </button>
        </div>

        <div class="row g-3">
            <div class="col-md-4 col-6">
                <button type="button" class="category-card border-0 w-100 p-0 text-start" data-bs-toggle="modal" data-bs-target="#leadModal">
                    <img src="https://images.unsplash.com/photo-1565043589221-1a6fd9ae45c7?auto=format&fit=crop&w=800&q=80" alt="Industrial Machinery" loading="lazy">
                    <div class="overlay">
                        <h5>Industrial Machinery</h5>
                        <small class="opacity-75">Equipment &amp; spare parts</small>
                    </div>
                </button>
            </div>
            <div class="col-md-4 col-6">
                <button type="button" class="category-card border-0 w-100 p-0 text-start" data-bs-toggle="modal" data-bs-target="#leadModal">
                    <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?auto=format&fit=crop&w=800&q=80" alt="Textiles" loading="lazy">
                    <div class="overlay">
                        <h5>Textiles &amp; Apparel</h5>
                        <small class="opacity-75">Fabrics, garments, yarn</small>
                    </div>
                </button>
            </div>
            <div class="col-md-4 col-6">
                <button type="button" class="category-card border-0 w-100 p-0 text-start" data-bs-toggle="modal" data-bs-target="#leadModal">
                    <img src="https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=800&q=80" alt="Electronics" loading="lazy">
                    <div class="overlay">
                        <h5>Electronics</h5>
                        <small class="opacity-75">Components &amp; devices</small>
                    </div>
                </button>
            </div>
            <div class="col-md-4 col-6">
                <button type="button" class="category-card border-0 w-100 p-0 text-start" data-bs-toggle="modal" data-bs-target="#leadModal">
                    <img src="https://images.unsplash.com/photo-1625246333195-78d9c38ad449?auto=format&fit=crop&w=800&q=80" alt="Agriculture" loading="lazy">
                    <div class="overlay">
                        <h5>Agriculture</h5>
                        <small class="opacity-75">Commodities &amp; produce</small>
                    </div>
                </button>
            </div>
            <div class="col-md-4 col-6">
                <button type="button" class="category-card border-0 w-100 p-0 text-start" data-bs-toggle="modal" data-bs-target="#leadModal">
                    <img src="https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=800&q=80" alt="Construction" loading="lazy">
                    <div class="overlay">
                        <h5>Construction</h5>
                        <small class="opacity-75">Materials &amp; tools</small>
                    </div>
                </button>
            </div>
            <div class="col-md-4 col-6">
                <button type="button" class="category-card border-0 w-100 p-0 text-start" data-bs-toggle="modal" data-bs-target="#leadModal">
                    <img src="https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&w=800&q=80" alt="Medical" loading="lazy">
                    <div class="overlay">
                        <h5>Medical Equipment</h5>
                        <small class="opacity-75">Devices &amp; supplies</small>
                    </div>
                </button>
            </div>
        </div>

        {{-- Global reach strip --}}
        <div class="world-map-strip mt-4">
            <div class="row align-items-center g-3">
                <div class="col-lg-5">
                    <h5 class="fw-bold mb-1">Global trade coverage</h5>
                    <p class="text-muted small mb-0">Active interest from buyers and sellers across major trade corridors.</p>
                </div>
                <div class="col-lg-7">
                    <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                        <span class="region-pill"><span class="dot" style="background:#0B3A6E;"></span> North America</span>
                        <span class="region-pill"><span class="dot" style="background:#0E7490;"></span> Europe</span>
                        <span class="region-pill"><span class="dot" style="background:#0891B2;"></span> Middle East</span>
                        <span class="region-pill"><span class="dot" style="background:#082F58;"></span> Asia Pacific</span>
                        <span class="region-pill"><span class="dot" style="background:#059669;"></span> Africa</span>
                        <span class="region-pill"><span class="dot" style="background:#64748B;"></span> LatAm</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- LIVE LEADS --}}
<section class="py-5" id="leads">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h2 class="section-title mb-1">Live Business Leads</h2>
                <p class="text-muted mb-0">Fresh inquiries from buyers and sellers on Trade4Deal.</p>
            </div>
            <button type="button" class="btn btn-primary-t4d text-white" data-bs-toggle="modal" data-bs-target="#leadModal">
                <i class="bi bi-plus-lg me-2"></i>Submit Business Lead
            </button>
        </div>

        @if($isStaffViewer ?? false)
            <div class="alert alert-success border-0 shadow-sm mb-3 d-flex align-items-center gap-2">
                <i class="bi bi-shield-check"></i>
                <span><strong>Staff view</strong> — all approved leads are visible with no plan delay.</span>
            </div>
        @elseif($viewerPlan->isGold())
            <div class="alert alert-warning border-0 shadow-sm mb-3 d-flex align-items-center gap-2">
                <i class="bi bi-star-fill"></i>
                <span><strong>Gold member</strong> — you see new leads instantly as they are published.</span>
            </div>
        @else
            <div class="alert alert-info border-0 shadow-sm mb-3 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2">
                <span><i class="bi bi-clock me-1"></i> Free accounts see leads <strong>24 hours</strong> after they go live.</span>
                <a href="{{ route('plans.index') }}" class="btn btn-sm btn-primary-t4d text-white">Upgrade to Gold</a>
            </div>
        @endif

        <div class="card card-t4d">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-broadcast text-primary"></i>
                    Available to your plan
                </div>
                <span class="badge bg-primary rounded-pill">{{ $leads->total() }} listed</span>
            </div>
            <div class="card-body p-0">
                @if($leads->isEmpty())
                    <div class="empty-state">
                        <h5 class="fw-semibold">No leads visible yet</h5>
                        <p class="mb-3">New leads appear here after review. @unless(($isStaffViewer ?? false) || $viewerPlan->isGold()) Gold members see them 24 hours sooner. @endunless</p>
                        <button type="button" class="btn btn-primary-t4d text-white" data-bs-toggle="modal" data-bs-target="#leadModal">
                            Submit a Lead
                        </button>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table lead-table mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Product</th>
                                    <th>Company</th>
                                    <th>Product Type</th>
                                    <th>Trade</th>
                                    <th>Payment</th>
                                    <th>Published</th>
                                    <th class="pe-4 text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($leads as $lead)
                                    <tr>
                                        <td class="ps-4">
                                            <a href="{{ route('leads.show', $lead) }}" class="text-decoration-none">
                                                @if($lead->productImageUrl())
                                                    <img src="{{ $lead->productImageUrl() }}" alt="Product" class="rounded" style="width:48px;height:48px;object-fit:cover;">
                                                @else
                                                    <div class="rounded bg-light d-flex align-items-center justify-content-center text-muted" style="width:48px;height:48px;">
                                                        <i class="bi bi-image"></i>
                                                    </div>
                                                @endif
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('leads.show', $lead) }}" class="text-decoration-none text-dark">
                                                <div class="fw-semibold">{{ $lead->company_name }}</div>
                                                <div class="small text-muted">{{ $lead->contact_name }} · {{ $lead->country }}</div>
                                                <div class="small text-muted">{{ $lead->product_interest }}</div>
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill bg-light text-dark border">{{ $lead->product_type?->label() }}</span>
                                            <div class="small text-muted mt-1">{{ $lead->business_type?->label() }}</div>
                                        </td>
                                        <td>
                                            <div class="small fw-semibold">{{ $lead->currency?->value }} · {{ $lead->units?->label() }}</div>
                                        </td>
                                        <td>
                                            @foreach($lead->paymentMethodEnums() as $method)
                                                <span class="badge text-bg-light text-dark border me-1 mb-1">{{ $method->label() }}</span>
                                            @endforeach
                                        </td>
                                        <td class="text-muted small">
                                            {{ $lead->published_at?->diffForHumans() }}
                                        </td>
                                        <td class="pe-4 text-end">
                                            <a href="{{ route('leads.show', $lead) }}" class="btn btn-sm btn-outline-t4d">
                                                View &amp; contact
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 px-4 py-3 border-top">
                        <div class="small text-muted">
                            Showing {{ $leads->firstItem() }}–{{ $leads->lastItem() }} of {{ $leads->total() }} leads
                        </div>
                        {{ $leads->onEachSide(1)->links() }}
                    </div>
                @endif
            </div>
        </div>

        <div class="cta-banner mt-5">
            <div class="row align-items-center g-3">
                <div class="col-lg-8">
                    <h3 class="fw-bold mb-2">
                        Ready to grow your global trade network?
                    </h3>
                    <p class="mb-0 opacity-75">
                        Create a free Trade4Deal account to manage leads, connect with partners, and access RFQs.
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('register') }}" class="btn btn-light fw-semibold me-2">Register Free</a>
                    <button type="button" class="btn btn-outline-light fw-semibold" data-bs-toggle="modal" data-bs-target="#leadModal">
                        Submit Lead
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
