@extends('layouts.marketplace')

@section('title', ($seller->company_name ?? 'Seller').' — Trade4Deal')

@push('styles')
<style>
    .seller-store {
        background:
            radial-gradient(ellipse 70% 40% at 100% 0%, rgba(14,116,144,.08), transparent 55%),
            var(--t4d-bg);
    }
    .seller-cover {
        position: relative;
        height: clamp(160px, 28vw, 260px);
        background:
            linear-gradient(135deg, rgba(11,58,110,.92), rgba(14,116,144,.78)),
            {{ $seller->coverImageUrl() ? "url('".$seller->coverImageUrl()."') center/cover" : "#0B3A6E" }};
        overflow: hidden;
    }
    .seller-cover::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(11,18,32,.45), transparent 55%);
    }
    .seller-identity {
        margin-top: -3.25rem;
        position: relative;
        z-index: 2;
        padding-bottom: 2.5rem;
    }
    .seller-logo {
        width: 96px;
        height: 96px;
        border-radius: 18px;
        object-fit: cover;
        border: 4px solid #fff;
        background: #fff;
        box-shadow: var(--t4d-shadow-lg);
    }
    .seller-logo-ph {
        width: 96px;
        height: 96px;
        border-radius: 18px;
        border: 4px solid #fff;
        background: linear-gradient(135deg, #0B3A6E, #0E7490);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-weight: 800;
        box-shadow: var(--t4d-shadow-lg);
    }
    .seller-title {
        font-size: clamp(1.45rem, 2.6vw, 2rem);
        font-weight: 800;
        letter-spacing: -.03em;
        margin: 0 0 .35rem;
    }
    .seller-tagline {
        color: var(--t4d-muted);
        margin: 0;
        font-size: .98rem;
    }
    .store-chip {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        padding: .28rem .65rem;
        border-radius: 999px;
        background: var(--t4d-bg);
        border: 1px solid var(--t4d-border);
        font-size: .75rem;
        font-weight: 600;
        color: var(--t4d-dark);
        margin: 0 .3rem .35rem 0;
    }
    .verified-supplier-badge {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        padding: .32rem .7rem;
        border-radius: 999px;
        background: rgba(245, 130, 32, .14);
        border: 1px solid rgba(245, 130, 32, .35);
        color: #9A4B09;
        font-size: .76rem;
        font-weight: 800;
        margin-bottom: .45rem;
    }
    .store-panel {
        background: var(--t4d-surface);
        border: 1px solid var(--t4d-border);
        border-radius: 16px;
        box-shadow: var(--t4d-shadow);
        padding: 1.25rem 1.35rem;
        margin-bottom: 1rem;
    }
    .store-panel h2 {
        font-size: 1.05rem;
        font-weight: 800;
        margin: 0 0 .85rem;
        letter-spacing: -.02em;
    }
    .about-text {
        font-size: .98rem;
        line-height: 1.7;
        color: var(--t4d-muted);
        white-space: pre-wrap;
        margin: 0;
    }
    .meta-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: .75rem;
    }
    .meta-item .label {
        font-size: .65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: var(--t4d-muted);
    }
    .meta-item .value {
        font-size: .9rem;
        font-weight: 600;
        color: var(--t4d-dark);
    }
    .enquire-card {
        background: var(--t4d-surface);
        border: 1px solid var(--t4d-border);
        border-radius: 16px;
        box-shadow: var(--t4d-shadow-lg);
        padding: 1.25rem 1.35rem;
        position: sticky;
        top: 5.25rem;
    }
    .enquire-card .eyebrow {
        font-size: .68rem;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: var(--t4d-accent);
    }
    .enquire-card h2 {
        font-size: 1.15rem;
        font-weight: 800;
        margin: .2rem 0 .35rem;
    }
    .privacy-note {
        font-size: .78rem;
        color: var(--t4d-muted);
        background: var(--t4d-bg);
        border: 1px solid var(--t4d-border);
        border-radius: 8px;
        padding: .55rem .7rem;
        margin: .75rem 0 1rem;
    }
    .product-card {
        display: block;
        text-decoration: none;
        color: inherit;
        background: var(--t4d-surface);
        border: 1px solid var(--t4d-border);
        border-radius: 14px;
        overflow: hidden;
        height: 100%;
        box-shadow: var(--t4d-shadow);
        transition: transform .15s ease, box-shadow .15s ease;
    }
    .product-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--t4d-shadow-lg);
        color: inherit;
    }
    .product-card img,
    .product-card .ph {
        width: 100%;
        height: 160px;
        object-fit: cover;
        display: block;
    }
    .product-card .ph {
        background: var(--t4d-bg);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--t4d-muted);
        font-size: 1.75rem;
    }
    .product-card .body { padding: 1rem 1.05rem 1.15rem; }
    @media (max-width: 991px) {
        .enquire-card { position: static; }
        .meta-grid { grid-template-columns: 1fr; }
        .seller-identity { margin-top: -2.5rem; }
    }
</style>
@endpush

@section('content')
<div class="seller-store">
    <div class="seller-cover" aria-hidden="true"></div>

    <section class="seller-identity">
        <div class="container">
            <div class="d-flex flex-column flex-md-row align-items-md-end gap-3 mb-4">
                @if ($seller->logoUrl())
                    <img src="{{ $seller->logoUrl() }}" alt="{{ $seller->company_name }}" class="seller-logo">
                @else
                    <div class="seller-logo-ph">{{ strtoupper(substr($seller->company_name ?? 'S', 0, 1)) }}</div>
                @endif
                <div class="flex-grow-1">
                    <div class="mb-2">
                        <span class="store-chip"><i class="bi bi-geo-alt"></i> {{ collect([$seller->city, $seller->country])->filter()->implode(', ') }}</span>
                        @if ($seller->year_established)
                            <span class="store-chip"><i class="bi bi-calendar3"></i> Est. {{ $seller->year_established }}</span>
                        @endif
                        @if ($seller->employees_range)
                            <span class="store-chip"><i class="bi bi-people"></i> {{ $seller->employees_range->label() }}</span>
                        @endif
                    </div>
                    @if ($seller->userPlan()->isGold())
                        <div class="verified-supplier-badge">
                            <i class="bi bi-patch-check-fill"></i>
                            Verified supplier by Trade4Deal
                        </div>
                    @endif
                    <h1 class="seller-title">{{ $seller->company_name }}</h1>
                    <p class="seller-tagline">{{ $seller->tagline ?: 'Verified Trade4Deal seller' }}</p>
                </div>
                <a href="#enquire" class="btn btn-primary-t4d text-white px-4">
                    <i class="bi bi-envelope-paper me-1"></i> Enquire
                </a>
            </div>

            <div class="row g-3 g-lg-4">
                <div class="col-lg-8">
                    <div class="store-panel">
                        <h2>About us</h2>
                        @if ($seller->about)
                            <p class="about-text">{{ $seller->about }}</p>
                        @else
                            <p class="text-muted mb-0">{{ $seller->company_name }} is a Trade4Deal seller based in {{ $seller->country }}. Use the enquiry form to connect.</p>
                        @endif
                    </div>

                    @if (count($seller->industryEnums()) > 0)
                        <div class="store-panel">
                            <h2>Industries</h2>
                            @foreach ($seller->industryEnums() as $industry)
                                <span class="store-chip">{{ $industry->label() }}</span>
                            @endforeach
                        </div>
                    @endif

                    <div class="store-panel" id="products">
                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                            <h2 class="mb-0">Products</h2>
                            <span class="small text-muted">{{ $products->total() }} listed</span>
                        </div>
                        @if ($products->isEmpty())
                            <p class="text-muted mb-0">No live products yet.</p>
                        @else
                            <div class="row g-3">
                                @foreach ($products as $product)
                                    <div class="col-md-6">
                                        <div class="product-card">
                                            @if ($product->imageUrl())
                                                <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}">
                                            @else
                                                <div class="ph"><i class="bi bi-box-seam"></i></div>
                                            @endif
                                            <div class="body">
                                                <div class="small text-muted mb-1">{{ $product->product_type?->label() }}</div>
                                                <div class="fw-bold mb-1" style="letter-spacing:-.02em;">{{ $product->name }}</div>
                                                <div class="small text-muted mb-2">{{ \Illuminate\Support\Str::limit($product->description, 90) }}</div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="fw-semibold" style="color:var(--t4d-accent);">{{ $product->priceLabel() }}</span>
                                                    <span class="small text-muted">{{ $product->units?->label() }}</span>
                                                </div>
                                                @if ($product->min_order_qty)
                                                    <div class="small text-muted mt-1">MOQ: {{ $product->min_order_qty }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-3">
                                {{ $products->links() }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="store-panel">
                        <h2>Company details</h2>
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
                        <p class="small text-muted mb-0 mt-3">
                            <i class="bi bi-shield-lock me-1"></i>
                            Phone number is private. Reach this seller with the enquiry form.
                        </p>
                    </div>

                    <div class="enquire-card" id="enquire">
                        <div class="eyebrow">Connect</div>
                        <h2>Enquire with seller</h2>
                        <p class="text-muted small mb-0">Your message is emailed directly to {{ $seller->company_name }}.</p>
                        <div class="privacy-note">
                            <i class="bi bi-eye-slash me-1"></i>
                            Seller phone is never shown publicly.
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
                                          placeholder="Tell them what you need…" required>{{ old('message') }}</textarea>
                                @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <button type="submit" class="btn btn-primary-t4d text-white w-100">
                                <i class="bi bi-send me-1"></i> Send enquiry
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
