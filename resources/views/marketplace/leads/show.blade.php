@extends('layouts.marketplace')

@section('title', $lead->product_interest.' — '.$lead->company_name.' | Trade4Deal')

@push('styles')
<style>
    .lead-page {
        background:
            radial-gradient(ellipse 70% 40% at 0% 0%, rgba(14, 116, 144, 0.08), transparent 55%),
            var(--t4d-bg);
    }

    .lead-hero {
        background: linear-gradient(165deg, #0B3A6E 0%, #082F58 100%);
        color: #fff;
        padding: 1.35rem 0 1.5rem;
    }

    .lead-crumb {
        font-size: 0.8rem;
        margin-bottom: 0.75rem;
    }

    .lead-crumb a {
        color: rgba(255, 255, 255, 0.72);
        text-decoration: none;
        font-weight: 600;
    }

    .lead-crumb a:hover { color: #fff; }
    .lead-crumb .sep { color: rgba(255, 255, 255, 0.4); margin: 0 0.4rem; }
    .lead-crumb .current { color: #fff; font-weight: 600; }

    .lead-hero-title {
        font-size: clamp(1.35rem, 2.4vw, 1.85rem);
        font-weight: 800;
        letter-spacing: -0.03em;
        line-height: 1.25;
        margin: 0 0 0.4rem;
    }

    .lead-hero-sub {
        margin: 0;
        font-size: 0.95rem;
        color: rgba(255, 255, 255, 0.78);
    }

    .lead-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.25rem 0.65rem;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.16);
        font-size: 0.75rem;
        font-weight: 600;
        margin: 0 0.35rem 0.35rem 0;
    }

    .lead-body {
        padding: 1.5rem 0 2.5rem;
    }

    .panel {
        background: var(--t4d-surface);
        border: 1px solid var(--t4d-border);
        border-radius: 14px;
        box-shadow: var(--t4d-shadow);
        margin-bottom: 1rem;
    }

    .panel-pad { padding: 1.15rem 1.25rem; }

    .panel h2 {
        font-size: 0.95rem;
        font-weight: 800;
        color: var(--t4d-dark);
        margin: 0 0 0.85rem;
        letter-spacing: -0.02em;
    }

    .media-row {
        display: grid;
        grid-template-columns: 160px 1fr;
        gap: 1rem;
        align-items: start;
    }

    .product-thumb {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: 12px;
        background: #E8EEF5;
        display: block;
    }

    .product-thumb-ph {
        width: 100%;
        height: 120px;
        border-radius: 12px;
        background: linear-gradient(145deg, #E8EEF5, #F1F5F9);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94A3B8;
        font-size: 1.75rem;
    }

    .spec-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.6rem;
    }

    .spec-item {
        background: #F8FAFC;
        border: 1px solid var(--t4d-border);
        border-radius: 10px;
        padding: 0.6rem 0.75rem;
    }

    .spec-item .label {
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--t4d-muted);
        margin-bottom: 0.1rem;
    }

    .spec-item .value {
        font-size: 0.88rem;
        font-weight: 700;
        color: var(--t4d-dark);
        line-height: 1.3;
    }

    .pay-badge {
        display: inline-block;
        font-size: 0.72rem;
        font-weight: 600;
        padding: 0.25rem 0.55rem;
        border-radius: 999px;
        background: #F1F5F9;
        border: 1px solid var(--t4d-border);
        color: var(--t4d-dark);
        margin: 0 0.2rem 0.25rem 0;
    }

    .desc-body {
        font-size: 0.95rem;
        line-height: 1.6;
        color: #334155;
        white-space: pre-wrap;
        margin: 0;
    }

    .desc-fallback {
        font-size: 0.92rem;
        line-height: 1.55;
        color: var(--t4d-muted);
        margin: 0;
    }

    .poster-head {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.85rem;
    }

    .poster-avatar {
        width: 44px;
        height: 44px;
        border-radius: 11px;
        background: linear-gradient(135deg, #0B3A6E, #0E7490);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .poster-meta {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.55rem 1rem;
    }

    .poster-meta .item .label {
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--t4d-muted);
    }

    .poster-meta .item .value {
        font-size: 0.88rem;
        font-weight: 600;
        color: var(--t4d-dark);
    }

    .privacy-line {
        font-size: 0.78rem;
        color: var(--t4d-muted);
        margin: 0.85rem 0 0;
        padding-top: 0.75rem;
        border-top: 1px solid var(--t4d-border);
    }

    .contact-card {
        background: var(--t4d-surface);
        border: 1px solid var(--t4d-border);
        border-radius: 14px;
        box-shadow: var(--t4d-shadow);
        padding: 1.2rem 1.25rem;
        position: sticky;
        top: 5.25rem;
    }

    .contact-card .eyebrow {
        font-size: 0.68rem;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--t4d-accent);
        margin-bottom: 0.2rem;
    }

    .contact-card h2 {
        font-size: 1.1rem;
        font-weight: 800;
        margin: 0 0 0.25rem;
        letter-spacing: -0.02em;
    }

    .privacy-note {
        font-size: 0.78rem;
        color: var(--t4d-muted);
        background: #F1F5F9;
        border-radius: 8px;
        padding: 0.55rem 0.7rem;
        margin: 0.75rem 0 1rem;
    }

    .contact-card .form-label {
        font-size: 0.82rem;
        margin-bottom: 0.25rem;
    }

    .contact-card .form-control {
        padding: 0.5rem 0.7rem;
        font-size: 0.9rem;
    }

    .contact-card .mb-3 { margin-bottom: 0.75rem !important; }

    .similar-section { margin-top: 1.75rem; }

    .similar-section .section-title {
        font-size: 1.15rem;
        margin-bottom: 0.15rem;
    }

    .similar-card {
        display: block;
        text-decoration: none;
        color: inherit;
        background: var(--t4d-surface);
        border: 1px solid var(--t4d-border);
        border-radius: 12px;
        overflow: hidden;
        height: 100%;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
        box-shadow: var(--t4d-shadow);
    }

    .similar-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--t4d-shadow-lg);
        color: inherit;
    }

    .similar-card img,
    .similar-card .ph {
        width: 100%;
        height: 110px;
        object-fit: cover;
        display: block;
    }

    .similar-card .ph {
        background: #E8EEF5;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94A3B8;
        font-size: 1.5rem;
    }

    .similar-card .body { padding: 0.85rem 0.95rem 1rem; }

    @media (max-width: 991px) {
        .contact-card { position: static; }
        .media-row { grid-template-columns: 1fr; }
        .product-thumb,
        .product-thumb-ph { height: 160px; }
        .poster-meta { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="lead-page">
    <section class="lead-hero">
        <div class="container">
            <nav class="lead-crumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span class="sep">/</span>
                <a href="{{ route('home') }}#leads">Leads</a>
                <span class="sep">/</span>
                <span class="current">{{ Str::limit($lead->product_interest, 36) }}</span>
            </nav>

            <div class="d-flex flex-wrap gap-1 mb-2">
                <span class="lead-chip"><i class="bi bi-tag"></i> {{ $lead->product_type?->label() ?? 'Product' }}</span>
                <span class="lead-chip"><i class="bi bi-briefcase"></i> {{ $lead->business_type?->label() }}</span>
                <span class="lead-chip"><i class="bi bi-geo-alt"></i> {{ $lead->country }}</span>
            </div>

            <div class="d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
                <div>
                    <h1 class="lead-hero-title">{{ $lead->product_interest }}</h1>
                    <p class="lead-hero-sub">
                        Posted by <strong>{{ $lead->company_name }}</strong>
                        @if ($lead->published_at)
                            · {{ $lead->published_at->diffForHumans() }}
                        @endif
                    </p>
                </div>
                <a href="#contact-supplier" class="btn btn-light btn-sm fw-semibold px-3">
                    <i class="bi bi-envelope-paper me-1"></i>Contact supplier
                </a>
            </div>
        </div>
    </section>

    <section class="lead-body">
        <div class="container">
            <div class="row g-3 g-lg-4">
                <div class="col-lg-7">
                    <div class="panel">
                        <div class="panel-pad">
                            <div class="media-row mb-3">
                                @if ($lead->productImageUrl())
                                    <img src="{{ $lead->productImageUrl() }}" alt="{{ $lead->product_interest }}" class="product-thumb">
                                @else
                                    <div class="product-thumb-ph"><i class="bi bi-box-seam"></i></div>
                                @endif
                                <div>
                                    <h2 class="mb-2">Lead overview</h2>
                                    <div class="spec-grid">
                                        <div class="spec-item">
                                            <div class="label">Category</div>
                                            <div class="value">{{ $lead->product_type?->label() ?? '—' }}</div>
                                        </div>
                                        <div class="spec-item">
                                            <div class="label">Looking for</div>
                                            <div class="value">{{ $lead->business_type?->label() ?? '—' }}</div>
                                        </div>
                                        <div class="spec-item">
                                            <div class="label">Currency</div>
                                            <div class="value">{{ $lead->currency?->label() ?? '—' }}</div>
                                        </div>
                                        <div class="spec-item">
                                            <div class="label">Units</div>
                                            <div class="value">{{ $lead->units?->label() ?? '—' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="small text-uppercase fw-bold text-muted mb-1" style="letter-spacing:0.05em;font-size:0.65rem;">Payment methods</div>
                                @forelse ($lead->paymentMethodEnums() as $method)
                                    <span class="pay-badge">{{ $method->label() }}</span>
                                @empty
                                    <span class="text-muted small">Not specified</span>
                                @endforelse
                            </div>

                            <h2>Description</h2>
                            @if ($lead->message)
                                <p class="desc-body">{{ $lead->message }}</p>
                            @else
                                <p class="desc-fallback">
                                    {{ $lead->company_name }} is seeking opportunities related to
                                    <strong>{{ $lead->product_interest }}</strong>
                                    ({{ $lead->product_type?->label() }}) in {{ $lead->country }}.
                                    Use the contact form to introduce your offer or sourcing needs.
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="panel">
                        <div class="panel-pad">
                            <div class="poster-head">
                                <div class="poster-avatar">{{ strtoupper(substr($lead->company_name, 0, 1)) }}</div>
                                <div>
                                    <div class="small text-uppercase fw-bold text-muted" style="letter-spacing:0.05em;font-size:0.65rem;">Lead poster</div>
                                    <div class="fw-bold" style="letter-spacing:-0.02em;">{{ $lead->company_name }}</div>
                                </div>
                            </div>
                            <div class="poster-meta">
                                <div class="item">
                                    <div class="label">Contact person</div>
                                    <div class="value">{{ $lead->contact_name }}</div>
                                </div>
                                <div class="item">
                                    <div class="label">Country</div>
                                    <div class="value">{{ $lead->country }}</div>
                                </div>
                                <div class="item">
                                    <div class="label">Business type</div>
                                    <div class="value">{{ $lead->business_type?->label() }}</div>
                                </div>
                                <div class="item">
                                    <div class="label">Published</div>
                                    <div class="value">{{ $lead->published_at?->format('M j, Y') ?? '—' }}</div>
                                </div>
                            </div>
                            <p class="privacy-line">
                                <i class="bi bi-shield-lock me-1"></i>
                                Phone number is kept private. Reach this supplier using the contact form.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="contact-card" id="contact-supplier">
                        <div class="eyebrow">Connect securely</div>
                        <h2>Contact with Supplier</h2>
                        <p class="text-muted small mb-0">Your message is emailed directly to the poster.</p>
                        <div class="privacy-note">
                            <i class="bi bi-eye-slash me-1"></i>
                            The supplier's phone is never shown. They reply to the email you provide.
                        </div>

                        <form method="POST" action="{{ route('leads.contact', $lead) }}">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Your name</label>
                                <input id="name" name="name" type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', auth()->user()?->name) }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Your email</label>
                                <input id="email" name="email" type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email', auth()->user()?->email) }}" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label for="company_name" class="form-label">Your company</label>
                                <input id="company_name" name="company_name" type="text"
                                       class="form-control @error('company_name') is-invalid @enderror"
                                       value="{{ old('company_name', auth()->user()?->company_name) }}" required>
                                @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="row g-2 mb-3">
                                <div class="col-md-6">
                                    <label for="country" class="form-label">Country</label>
                                    <input id="country" name="country" type="text"
                                           class="form-control @error('country') is-invalid @enderror"
                                           value="{{ old('country', auth()->user()?->country) }}" required>
                                    @error('country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Phone <span class="text-muted fw-normal">(optional)</span></label>
                                    <input id="phone" name="phone" type="text"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           value="{{ old('phone', auth()->user()?->phone) }}">
                                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea id="message" name="message" rows="4"
                                          class="form-control @error('message') is-invalid @enderror"
                                          placeholder="Introduce your company and how you can help…"
                                          required>{{ old('message') }}</textarea>
                                @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <button type="submit" class="btn btn-primary-t4d text-white w-100 py-2 fw-semibold">
                                <i class="bi bi-send me-2"></i>Send inquiry
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            @if ($similarLeads->isNotEmpty())
                <div class="similar-section">
                    <div class="d-flex align-items-end justify-content-between mb-3 flex-wrap gap-2">
                        <div>
                            <h2 class="section-title">Similar leads</h2>
                            <p class="text-muted small mb-0">More in {{ $lead->product_type?->label() ?? 'this category' }}</p>
                        </div>
                        <a href="{{ route('home') }}#leads" class="fw-semibold text-decoration-none small" style="color: var(--t4d-primary);">
                            Browse all <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                    <div class="row g-3">
                        @foreach ($similarLeads as $similar)
                            <div class="col-md-6 col-xl-3">
                                <a href="{{ route('leads.show', $similar) }}" class="similar-card">
                                    @if ($similar->productImageUrl())
                                        <img src="{{ $similar->productImageUrl() }}" alt="{{ $similar->product_interest }}">
                                    @else
                                        <div class="ph"><i class="bi bi-box-seam"></i></div>
                                    @endif
                                    <div class="body">
                                        <div class="small text-muted mb-1">{{ $similar->country }} · {{ $similar->business_type?->label() }}</div>
                                        <div class="fw-bold mb-1" style="font-size:0.92rem;letter-spacing:-0.02em;">{{ Str::limit($similar->product_interest, 42) }}</div>
                                        <div class="small text-muted">{{ $similar->company_name }}</div>
                                        <div class="small fw-semibold mt-2" style="color: var(--t4d-accent);">
                                            {{ $similar->currency?->value }} · {{ $similar->units?->label() }}
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
</div>
@endsection
