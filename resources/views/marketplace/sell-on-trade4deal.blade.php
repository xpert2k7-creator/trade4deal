@extends('layouts.marketplace')

@section('title', 'Sell on Trade4Deal - Supplier Toolkit')

@push('styles')
<style>
    .sell-hero {
        position: relative;
        overflow: hidden;
        color: #fff;
        background:
            linear-gradient(105deg, rgba(5, 28, 64, 0.96), rgba(6, 68, 117, 0.86) 48%, rgba(245, 130, 32, 0.42)),
            url('https://images.unsplash.com/photo-1494412685616-a5d310fbb07d?auto=format&fit=crop&w=1900&q=82') center/cover no-repeat;
    }

    .sell-hero .container {
        min-height: 510px;
        display: grid;
        align-items: center;
    }

    .sell-eyebrow,
    .sell-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        width: fit-content;
        padding: 0.42rem 0.8rem;
        border-radius: 999px;
        font-size: 0.78rem;
        font-weight: 850;
        text-transform: uppercase;
    }

    .sell-eyebrow {
        border: 1px solid rgba(255, 255, 255, 0.24);
        background: rgba(255, 255, 255, 0.13);
    }

    .sell-kicker {
        background: #e8f1fb;
        color: var(--t4d-primary);
    }

    .sell-title {
        max-width: 930px;
        margin: 1rem 0;
        color: #fff;
        font-size: clamp(2.4rem, 5vw, 4.1rem);
        font-weight: 850;
        line-height: 1.05;
    }

    .sell-summary {
        max-width: 830px;
        color: rgba(255, 255, 255, 0.86);
        font-size: 1.06rem;
        line-height: 1.75;
    }

    .sell-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.8rem;
        margin-top: 1.45rem;
    }

    .sell-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 0.8rem;
        max-width: 950px;
        margin-top: 1.6rem;
    }

    .sell-stat {
        min-height: 96px;
        border: 1px solid rgba(255, 255, 255, 0.18);
        border-radius: 8px;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.11);
        backdrop-filter: blur(8px);
    }

    .sell-stat strong {
        display: block;
        color: #fff;
        font-size: 1.35rem;
        line-height: 1.1;
    }

    .sell-stat span {
        display: block;
        margin-top: 0.35rem;
        color: rgba(255, 255, 255, 0.72);
        font-size: 0.78rem;
        font-weight: 750;
    }

    .sell-page {
        background: #f5f7fb;
    }

    .sell-section-title {
        max-width: 800px;
        margin: 0.8rem 0 0;
        color: #061224;
        font-size: clamp(1.75rem, 3.3vw, 2.65rem);
        font-weight: 850;
        line-height: 1.12;
    }

    .sell-section-copy {
        max-width: 800px;
        margin: 0.85rem 0 0;
        color: #64748b;
        line-height: 1.7;
    }

    .sell-grid,
    .benefit-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1rem;
        margin-top: 1.6rem;
    }

    .sell-card,
    .seller-panel,
    .process-card,
    .benefit-card {
        border: 1px solid #dbe3ef;
        border-radius: 10px;
        background: #fff;
        box-shadow: 0 14px 34px rgba(15, 23, 42, 0.07);
    }

    .sell-card {
        min-height: 255px;
        padding: 1.25rem;
    }

    .sell-icon {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        display: grid;
        place-items: center;
        margin-bottom: 1rem;
        background: linear-gradient(135deg, var(--t4d-primary), #0e7490);
        color: #fff;
        font-size: 1.22rem;
    }

    .sell-card h3,
    .seller-panel h3,
    .process-card h3,
    .benefit-card h3 {
        margin: 0 0 0.45rem;
        color: #061224;
        font-size: 1rem;
        font-weight: 850;
    }

    .sell-card p,
    .seller-panel p,
    .process-card p,
    .benefit-card p {
        margin: 0;
        color: #64748b;
        font-size: 0.92rem;
        line-height: 1.65;
    }

    .sell-card ul {
        display: grid;
        gap: 0.45rem;
        margin: 1rem 0 0;
        padding-left: 1.1rem;
        color: #64748b;
        font-size: 0.9rem;
        line-height: 1.55;
    }

    .seller-preview {
        display: grid;
        grid-template-columns: minmax(0, 0.95fr) minmax(0, 1.05fr);
        gap: 1rem;
        align-items: stretch;
        margin-top: 1.6rem;
    }

    .seller-panel {
        padding: 1.35rem;
        overflow: hidden;
    }

    .seller-panel.featured {
        border-top: 5px solid var(--t4d-accent);
    }

    .profile-card-preview {
        border: 1px solid #dbe3ef;
        border-radius: 10px;
        background: #f8fafc;
        overflow: hidden;
        margin-top: 1.1rem;
    }

    .profile-card-preview img {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }

    .profile-card-body {
        padding: 1rem;
    }

    .profile-card-body strong {
        display: block;
        color: #061224;
        font-size: 1.05rem;
        margin-bottom: 0.35rem;
    }

    .profile-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.45rem;
        margin-top: 0.85rem;
    }

    .profile-tags span {
        border-radius: 999px;
        background: #e8f1fb;
        color: var(--t4d-primary);
        padding: 0.28rem 0.6rem;
        font-size: 0.75rem;
        font-weight: 850;
    }

    .checklist {
        display: grid;
        gap: 0.85rem;
        margin-top: 1.1rem;
    }

    .checklist-item {
        display: grid;
        grid-template-columns: 44px 1fr;
        gap: 0.8rem;
        align-items: start;
        border: 1px solid #dbe3ef;
        border-radius: 8px;
        background: #f8fafc;
        padding: 0.9rem;
    }

    .checklist-item span {
        width: 44px;
        height: 44px;
        border-radius: 8px;
        display: grid;
        place-items: center;
        background: #edf5fc;
        color: var(--t4d-primary);
        font-size: 1.1rem;
    }

    .checklist-item strong {
        display: block;
        color: #061224;
        margin-bottom: 0.12rem;
    }

    .checklist-item small {
        color: #64748b;
        line-height: 1.55;
    }

    .process-band {
        background:
            linear-gradient(135deg, rgba(6, 68, 117, 0.96), rgba(10, 83, 125, 0.92)),
            url('https://images.unsplash.com/photo-1504917595217-d4dc5ebe6122?auto=format&fit=crop&w=1800&q=80') center/cover no-repeat;
        color: #fff;
    }

    .process-band .sell-kicker {
        background: rgba(255, 255, 255, 0.13);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.22);
    }

    .process-band .sell-section-title,
    .process-band .sell-section-copy {
        color: #fff;
    }

    .process-band .sell-section-copy {
        color: rgba(255, 255, 255, 0.78);
    }

    .process-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1rem;
        margin-top: 1.8rem;
    }

    .process-card {
        min-height: 230px;
        padding: 1.25rem;
        background: rgba(255, 255, 255, 0.96);
    }

    .process-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        margin-bottom: 1rem;
        border-radius: 50%;
        background: var(--t4d-accent);
        color: #fff;
        font-weight: 850;
    }

    .benefit-card {
        display: grid;
        grid-template-columns: 48px 1fr;
        gap: 0.9rem;
        align-items: start;
        min-height: 150px;
        padding: 1.15rem;
    }

    .benefit-card .sell-icon {
        width: 48px;
        height: 48px;
        margin: 0;
    }

    .sell-cta {
        position: relative;
        overflow: hidden;
        border-radius: 10px;
        padding: clamp(1.5rem, 4vw, 2.5rem);
        background:
            linear-gradient(135deg, rgba(6, 68, 117, 0.98), rgba(33, 56, 154, 0.9)),
            radial-gradient(circle at 90% 15%, rgba(245, 130, 32, 0.4), transparent 30%);
        color: #fff;
        box-shadow: 0 18px 46px rgba(15, 23, 42, 0.16);
    }

    .sell-cta h2 {
        max-width: 790px;
        margin: 0 0 0.6rem;
        color: #fff;
        font-size: clamp(1.6rem, 3.2vw, 2.55rem);
        font-weight: 850;
        line-height: 1.12;
    }

    .sell-cta p {
        max-width: 780px;
        margin: 0;
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.65;
    }

    .sell-cta-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.8rem;
        margin-top: 1.35rem;
    }

    @media (max-width: 991px) {
        .sell-stats,
        .sell-grid,
        .process-grid,
        .benefit-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .seller-preview {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 575px) {
        .sell-hero .container {
            min-height: 430px;
        }

        .sell-stats,
        .sell-grid,
        .process-grid,
        .benefit-grid {
            grid-template-columns: 1fr;
        }

        .benefit-card {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<section class="sell-hero">
    <div class="container py-5">
        <div>
            <span class="sell-eyebrow"><i class="bi bi-shop-window"></i> Suppliers Tool Kit</span>
            <h1 class="sell-title">Sell on Trade4Deal</h1>
            <p class="sell-summary">Create a public seller profile, add products, and make your business easier for buyers to discover across Trade4Deal's B2B marketplace.</p>
            <div class="sell-actions">
                <a href="{{ route('register') }}" class="btn btn-light fw-bold px-4">Join as Seller</a>
                <a href="{{ route('home') }}#leads" class="btn btn-ghost-light px-4">View Buyer Leads</a>
            </div>
            <div class="sell-stats">
                <div class="sell-stat">
                    <strong>Profile</strong>
                    <span>Public seller storefront</span>
                </div>
                <div class="sell-stat">
                    <strong>Catalog</strong>
                    <span>Product visibility</span>
                </div>
                <div class="sell-stat">
                    <strong>Leads</strong>
                    <span>Buyer requirements</span>
                </div>
                <div class="sell-stat">
                    <strong>Direct</strong>
                    <span>Enquiry conversations</span>
                </div>
            </div>
        </div>
    </div>
</section>

<main class="sell-page">
    <section class="py-5">
        <div class="container py-4">
            <span class="sell-kicker"><i class="bi bi-briefcase"></i> Supplier Growth</span>
            <h2 class="sell-section-title">Build a supplier presence buyers can evaluate quickly.</h2>
            <p class="sell-section-copy">Trade4Deal helps suppliers present business details, publish products and respond to active buyer requirements from one focused B2B marketplace workflow.</p>

            <div class="sell-grid">
                <article class="sell-card">
                    <span class="sell-icon"><i class="bi bi-window-sidebar"></i></span>
                    <h3>Public storefront</h3>
                    <p>Show company details, industries, location, contact options and product listings in one profile.</p>
                    <ul>
                        <li>Company identity</li>
                        <li>Business location</li>
                        <li>Direct profile enquiries</li>
                    </ul>
                </article>
                <article class="sell-card">
                    <span class="sell-icon"><i class="bi bi-box-seam"></i></span>
                    <h3>Product catalog</h3>
                    <p>Add product names, categories, pricing range, units and images to support buyer evaluation.</p>
                    <ul>
                        <li>Category placement</li>
                        <li>Product images</li>
                        <li>Price and unit labels</li>
                    </ul>
                </article>
                <article class="sell-card">
                    <span class="sell-icon"><i class="bi bi-broadcast"></i></span>
                    <h3>Lead access</h3>
                    <p>Use the lead board to spot relevant buyer requirements and start focused conversations.</p>
                    <ul>
                        <li>Buyer requirement scanning</li>
                        <li>Plan-based visibility</li>
                        <li>Direct lead contact</li>
                    </ul>
                </article>
            </div>
        </div>
    </section>

    <section class="py-5 bg-white">
        <div class="container py-4">
            <span class="sell-kicker"><i class="bi bi-person-vcard"></i> Seller Profile</span>
            <h2 class="sell-section-title">A complete profile makes your business easier to trust.</h2>
            <p class="sell-section-copy">Buyers need clarity before starting a conversation. Use your seller profile to show capability, product fit and contact readiness.</p>

            <div class="seller-preview">
                <article class="seller-panel featured">
                    <h3>Seller profile preview</h3>
                    <p>Your storefront can bring company details and products together in one place.</p>
                    <div class="profile-card-preview">
                        <img src="{{ asset(config('marketplace_assets.pages.electronics_logistics_bg')) }}" alt="Seller profile product catalog preview">
                        <div class="profile-card-body">
                            <strong>Prime Industrial Supplies</strong>
                            <p>Manufacturer and exporter serving machinery, packaging and electronic component buyers.</p>
                            <div class="profile-tags">
                                <span>Machinery</span>
                                <span>Export Ready</span>
                                <span>Bulk MOQ</span>
                                <span>India</span>
                            </div>
                        </div>
                    </div>
                </article>

                <aside class="seller-panel">
                    <h3>Profile checklist</h3>
                    <p>Complete these areas to help buyers understand your business faster.</p>
                    <div class="checklist">
                        <div class="checklist-item">
                            <span><i class="bi bi-building"></i></span>
                            <div>
                                <strong>Company details</strong>
                                <small>Add legal/business name, logo, country, city and business description.</small>
                            </div>
                        </div>
                        <div class="checklist-item">
                            <span><i class="bi bi-tags"></i></span>
                            <div>
                                <strong>Industries and products</strong>
                                <small>Select relevant categories and publish clear product listings.</small>
                            </div>
                        </div>
                        <div class="checklist-item">
                            <span><i class="bi bi-chat-dots"></i></span>
                            <div>
                                <strong>Contact readiness</strong>
                                <small>Keep phone, email and enquiry response details accurate and active.</small>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    <section class="process-band py-5">
        <div class="container py-4">
            <span class="sell-kicker"><i class="bi bi-diagram-3"></i> Selling Process</span>
            <h2 class="sell-section-title">Start selling in a simple supplier workflow.</h2>
            <p class="sell-section-copy">Trade4Deal keeps supplier onboarding practical: register, complete your profile, add products and respond to relevant leads.</p>

            <div class="process-grid">
                <article class="process-card">
                    <span class="process-number">1</span>
                    <h3>Create account</h3>
                    <p>Register as a seller and verify your account so your business can access supplier tools.</p>
                </article>
                <article class="process-card">
                    <span class="process-number">2</span>
                    <h3>Build storefront</h3>
                    <p>Add company details, industries, logo, location and description for buyer confidence.</p>
                </article>
                <article class="process-card">
                    <span class="process-number">3</span>
                    <h3>Publish products</h3>
                    <p>Add product names, categories, images, units and price ranges to support discovery.</p>
                </article>
                <article class="process-card">
                    <span class="process-number">4</span>
                    <h3>Respond to leads</h3>
                    <p>Review live buyer requirements and start enquiries where your business can help.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container py-4">
            <span class="sell-kicker"><i class="bi bi-gem"></i> Seller Benefits</span>
            <h2 class="sell-section-title">Why suppliers use Trade4Deal.</h2>
            <p class="sell-section-copy">The supplier toolkit is designed to help serious businesses improve visibility and turn buyer intent into better conversations.</p>

            <div class="benefit-grid">
                <article class="benefit-card">
                    <span class="sell-icon"><i class="bi bi-search"></i></span>
                    <div>
                        <h3>Better discovery</h3>
                        <p>Appear through product categories and marketplace browsing paths.</p>
                    </div>
                </article>
                <article class="benefit-card">
                    <span class="sell-icon"><i class="bi bi-award"></i></span>
                    <div>
                        <h3>Stronger credibility</h3>
                        <p>Complete company and product details make your business easier to evaluate.</p>
                    </div>
                </article>
                <article class="benefit-card">
                    <span class="sell-icon"><i class="bi bi-lightning-charge"></i></span>
                    <div>
                        <h3>Faster opportunities</h3>
                        <p>Spot fresh buyer requirements and respond when timing matters.</p>
                    </div>
                </article>
                <article class="benefit-card">
                    <span class="sell-icon"><i class="bi bi-people"></i></span>
                    <div>
                        <h3>Direct buyer contact</h3>
                        <p>Move from product interest to enquiries with fewer scattered steps.</p>
                    </div>
                </article>
                <article class="benefit-card">
                    <span class="sell-icon"><i class="bi bi-card-checklist"></i></span>
                    <div>
                        <h3>Organized catalog</h3>
                        <p>Keep product information structured by category, unit, price and image.</p>
                    </div>
                </article>
                <article class="benefit-card">
                    <span class="sell-icon"><i class="bi bi-globe2"></i></span>
                    <div>
                        <h3>Global B2B reach</h3>
                        <p>Present your company for cross-border and domestic trade discovery.</p>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section class="pb-5">
        <div class="container pb-4">
            <div class="sell-cta">
                <h2>Ready to make your products easier for buyers to find?</h2>
                <p>Join Trade4Deal, create your seller profile, publish products and use live leads to start more focused B2B conversations.</p>
                <div class="sell-cta-actions">
                    <a href="{{ route('register') }}" class="btn btn-light fw-bold px-4">Join as Seller</a>
                    <a href="{{ route('home') }}#leads" class="btn btn-ghost-light px-4">View Buyer Leads</a>
                    <a href="{{ route('plans.index') }}" class="btn btn-ghost-light px-4">View Plans</a>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
