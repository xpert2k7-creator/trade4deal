@extends('layouts.marketplace')

@section('title', 'About Trade4Deal - Global B2B Marketplace')

@push('styles')
<style>
    .about-hero {
        position: relative;
        overflow: hidden;
        color: #fff;
        background:
            linear-gradient(105deg, rgba(5, 28, 64, 0.96), rgba(6, 68, 117, 0.86) 48%, rgba(245, 130, 32, 0.42)),
            url('https://images.unsplash.com/photo-1494412685616-a5d310fbb07d?auto=format&fit=crop&w=1800&q=85') center/cover no-repeat;
    }

    .about-hero .container {
        min-height: 470px;
        display: grid;
        align-items: center;
    }

    .about-eyebrow,
    .section-kicker {
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

    .about-eyebrow {
        border: 1px solid rgba(255, 255, 255, 0.24);
        background: rgba(255, 255, 255, 0.13);
    }

    .section-kicker {
        background: #e8f1fb;
        color: var(--t4d-primary);
    }

    .about-title {
        max-width: 920px;
        margin: 1rem 0;
        color: #fff;
        font-size: clamp(2.4rem, 5vw, 4.15rem);
        font-weight: 850;
        line-height: 1.04;
    }

    .about-summary {
        max-width: 820px;
        color: rgba(255, 255, 255, 0.86);
        font-size: 1.06rem;
        line-height: 1.75;
    }

    .about-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 0.8rem;
        max-width: 930px;
        margin-top: 1.6rem;
    }

    .about-stat {
        min-height: 96px;
        border: 1px solid rgba(255, 255, 255, 0.18);
        border-radius: 8px;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.11);
        backdrop-filter: blur(8px);
    }

    .about-stat strong {
        display: block;
        color: #fff;
        font-size: 1.45rem;
        line-height: 1.1;
    }

    .about-stat span {
        display: block;
        margin-top: 0.35rem;
        color: rgba(255, 255, 255, 0.72);
        font-size: 0.78rem;
        font-weight: 750;
    }

    .about-page {
        background: #f5f7fb;
    }

    .section-title {
        max-width: 760px;
        margin: 0.8rem 0 0;
        color: #061224;
        font-size: clamp(1.75rem, 3.3vw, 2.65rem);
        font-weight: 850;
        line-height: 1.12;
    }

    .section-copy {
        max-width: 760px;
        margin: 0.85rem 0 0;
        color: #64748b;
        line-height: 1.7;
    }

    .company-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.05fr) minmax(320px, 0.95fr);
        gap: 1.2rem;
        align-items: stretch;
    }

    .company-panel,
    .visual-panel,
    .choose-card,
    .process-step,
    .benefit-card {
        border: 1px solid #dbe3ef;
        border-radius: 10px;
        background: #fff;
        box-shadow: 0 14px 34px rgba(15, 23, 42, 0.07);
    }

    .company-panel {
        padding: clamp(1.25rem, 3vw, 2rem);
    }

    .company-list {
        display: grid;
        gap: 0.8rem;
        margin-top: 1.4rem;
    }

    .company-list-item {
        display: grid;
        grid-template-columns: 46px 1fr;
        gap: 0.85rem;
        align-items: start;
    }

    .icon-box {
        width: 46px;
        height: 46px;
        border-radius: 8px;
        display: grid;
        place-items: center;
        background: linear-gradient(135deg, var(--t4d-primary), #0e7490);
        color: #fff;
        font-size: 1.15rem;
    }

    .company-list-item h3,
    .choose-card h3,
    .benefit-card h3 {
        margin: 0 0 0.3rem;
        color: #061224;
        font-size: 1rem;
        font-weight: 850;
    }

    .company-list-item p,
    .choose-card p,
    .benefit-card p,
    .process-step p {
        margin: 0;
        color: #64748b;
        font-size: 0.92rem;
        line-height: 1.65;
    }

    .visual-panel {
        overflow: hidden;
        min-height: 100%;
    }

    .visual-panel img {
        width: 100%;
        height: 270px;
        object-fit: cover;
    }

    .visual-panel-content {
        padding: 1.2rem;
    }

    .visual-metric-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.75rem;
        margin-top: 1rem;
    }

    .visual-metric {
        border-radius: 8px;
        padding: 0.9rem;
        background: #f1f6fb;
    }

    .visual-metric strong {
        display: block;
        color: var(--t4d-primary);
        font-size: 1.3rem;
        line-height: 1;
    }

    .visual-metric span {
        display: block;
        margin-top: 0.35rem;
        color: #64748b;
        font-size: 0.78rem;
        font-weight: 750;
    }

    .choose-grid,
    .benefit-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1rem;
        margin-top: 1.6rem;
    }

    .choose-card,
    .benefit-card {
        padding: 1.25rem;
    }

    .choose-card {
        min-height: 230px;
    }

    .choose-card .icon-box,
    .benefit-card .icon-box {
        margin-bottom: 1rem;
    }

    .process-band {
        background:
            linear-gradient(135deg, rgba(6, 68, 117, 0.96), rgba(10, 83, 125, 0.92)),
            url('https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1800&q=80') center/cover no-repeat;
        color: #fff;
    }

    .process-band .section-kicker {
        background: rgba(255, 255, 255, 0.13);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.22);
    }

    .process-band .section-title,
    .process-band .section-copy {
        color: #fff;
    }

    .process-band .section-copy {
        color: rgba(255, 255, 255, 0.78);
    }

    .process-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1rem;
        margin-top: 1.8rem;
    }

    .process-step {
        position: relative;
        min-height: 245px;
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

    .process-step h3 {
        margin: 0 0 0.45rem;
        color: #061224;
        font-size: 1rem;
        font-weight: 850;
    }

    .benefit-card {
        display: grid;
        grid-template-columns: 50px 1fr;
        gap: 0.9rem;
        align-items: start;
        min-height: 150px;
    }

    .benefit-card .icon-box {
        margin: 0;
    }

    .about-cta {
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

    .about-cta h2 {
        max-width: 780px;
        margin: 0 0 0.6rem;
        color: #fff;
        font-size: clamp(1.6rem, 3.2vw, 2.55rem);
        font-weight: 850;
        line-height: 1.12;
    }

    .about-cta p {
        max-width: 760px;
        margin: 0;
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.65;
    }

    .about-cta-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.8rem;
        margin-top: 1.35rem;
    }

    @media (max-width: 991px) {
        .about-stats,
        .choose-grid,
        .process-grid,
        .benefit-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .company-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 575px) {
        .about-hero .container {
            min-height: 420px;
        }

        .about-stats,
        .choose-grid,
        .process-grid,
        .benefit-grid,
        .visual-metric-grid {
            grid-template-columns: 1fr;
        }

        .benefit-card {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<section class="about-hero">
    <div class="container py-5">
        <div>
            <span class="about-eyebrow"><i class="bi bi-buildings"></i> Company</span>
            <h1 class="about-title">About Trade4Deal</h1>
            <p class="about-summary">Trade4Deal is a global B2B marketplace built to help buyers discover suppliers, suppliers showcase products, and businesses convert trade interest into real conversations.</p>
            <div class="about-stats">
                <div class="about-stat">
                    <strong>B2B</strong>
                    <span>Marketplace Focus</span>
                </div>
                <div class="about-stat">
                    <strong>24/7</strong>
                    <span>Lead Access</span>
                </div>
                <div class="about-stat">
                    <strong>Global</strong>
                    <span>Trade Reach</span>
                </div>
                <div class="about-stat">
                    <strong>Direct</strong>
                    <span>Buyer-Supplier Contact</span>
                </div>
            </div>
        </div>
    </div>
</section>

<main class="about-page">
    <section class="py-5">
        <div class="container py-4">
            <div class="company-grid">
                <div class="company-panel">
                    <span class="section-kicker"><i class="bi bi-compass"></i> About Company</span>
                    <h2 class="section-title">A practical marketplace for serious trade conversations.</h2>
                    <p class="section-copy">Trade4Deal brings buyers, sellers, exporters, importers, manufacturers, distributors and traders into one focused B2B environment. The platform is designed around clear business intent: product needs, supplier capability, trade location, payment preferences and fast enquiry actions.</p>
                    <div class="company-list">
                        <div class="company-list-item">
                            <span class="icon-box"><i class="bi bi-search"></i></span>
                            <div>
                                <h3>Buyer discovery</h3>
                                <p>Buyers can explore products, publish requirements and connect with relevant suppliers across categories.</p>
                            </div>
                        </div>
                        <div class="company-list-item">
                            <span class="icon-box"><i class="bi bi-shop-window"></i></span>
                            <div>
                                <h3>Supplier visibility</h3>
                                <p>Suppliers can create a public profile, publish products and respond to live trade opportunities.</p>
                            </div>
                        </div>
                        <div class="company-list-item">
                            <span class="icon-box"><i class="bi bi-chat-dots"></i></span>
                            <div>
                                <h3>Focused enquiries</h3>
                                <p>Every listing, lead and profile is built to move visitors toward practical business communication.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <aside class="visual-panel">
                    <img src="{{ asset(config('marketplace_assets.pages.electronics_logistics_bg')) }}" alt="Trade4Deal global B2B logistics and electronics marketplace">
                    <div class="visual-panel-content">
                        <span class="section-kicker"><i class="bi bi-graph-up-arrow"></i> Marketplace Engine</span>
                        <p class="section-copy">From product discovery to lead visibility, Trade4Deal keeps sourcing actions simple, structured and business-first.</p>
                        <div class="visual-metric-grid">
                            <div class="visual-metric">
                                <strong>RFQ</strong>
                                <span>Buyer requirements</span>
                            </div>
                            <div class="visual-metric">
                                <strong>Catalog</strong>
                                <span>Supplier products</span>
                            </div>
                            <div class="visual-metric">
                                <strong>Leads</strong>
                                <span>Fresh opportunities</span>
                            </div>
                            <div class="visual-metric">
                                <strong>Trust</strong>
                                <span>Profile details</span>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    <section class="py-5 bg-white">
        <div class="container py-4">
            <span class="section-kicker"><i class="bi bi-stars"></i> Why Choose Us</span>
            <h2 class="section-title">Built for speed, clarity and trade confidence.</h2>
            <p class="section-copy">Trade4Deal is made for repeat business users who need to compare opportunities quickly and start better conversations with enough context.</p>

            <div class="choose-grid">
                <article class="choose-card">
                    <span class="icon-box"><i class="bi bi-grid-3x3-gap"></i></span>
                    <h3>Structured marketplace data</h3>
                    <p>Product categories, business type, country, payment methods and company details are presented in a scannable format.</p>
                </article>
                <article class="choose-card">
                    <span class="icon-box"><i class="bi bi-lightning-charge"></i></span>
                    <h3>Fresh business opportunities</h3>
                    <p>Live leads help suppliers spot buyer intent and act faster when timing matters.</p>
                </article>
                <article class="choose-card">
                    <span class="icon-box"><i class="bi bi-person-check"></i></span>
                    <h3>Better supplier presence</h3>
                    <p>Seller profiles and product catalogs give buyers a clearer view of capability before they enquire.</p>
                </article>
                <article class="choose-card">
                    <span class="icon-box"><i class="bi bi-shield-check"></i></span>
                    <h3>Marketplace moderation</h3>
                    <p>Lead review, profile controls and account workflows support a cleaner B2B experience.</p>
                </article>
                <article class="choose-card">
                    <span class="icon-box"><i class="bi bi-globe2"></i></span>
                    <h3>Global trade mindset</h3>
                    <p>The platform supports buyers and suppliers who operate across regions, categories and international markets.</p>
                </article>
                <article class="choose-card">
                    <span class="icon-box"><i class="bi bi-send-check"></i></span>
                    <h3>Direct enquiry actions</h3>
                    <p>Visitors can move from product interest to contact quickly through profile, listing and lead enquiry flows.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="process-band py-5">
        <div class="container py-4">
            <span class="section-kicker"><i class="bi bi-diagram-3"></i> Process</span>
            <h2 class="section-title">From requirement to business conversation.</h2>
            <p class="section-copy">Trade4Deal keeps the workflow simple so buyers and suppliers can focus on what matters: the product, the company, and the next conversation.</p>

            <div class="process-grid">
                <article class="process-step">
                    <span class="process-number">1</span>
                    <h3>Create your profile</h3>
                    <p>Register as a buyer or seller and add clear business details that help other companies understand who you are.</p>
                </article>
                <article class="process-step">
                    <span class="process-number">2</span>
                    <h3>Publish intent</h3>
                    <p>Buyers submit requirements and suppliers publish products with category, specifications and commercial context.</p>
                </article>
                <article class="process-step">
                    <span class="process-number">3</span>
                    <h3>Discover matches</h3>
                    <p>Use product sections, seller profiles and live leads to find relevant business opportunities faster.</p>
                </article>
                <article class="process-step">
                    <span class="process-number">4</span>
                    <h3>Start enquiry</h3>
                    <p>Contact the buyer or supplier with context and continue due diligence before making commercial decisions.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container py-4">
            <span class="section-kicker"><i class="bi bi-gem"></i> Benefits</span>
            <h2 class="section-title">What businesses gain with Trade4Deal.</h2>
            <p class="section-copy">Whether you are sourcing products or growing supplier visibility, Trade4Deal gives your team a more focused way to work with marketplace demand.</p>

            <div class="benefit-grid">
                <article class="benefit-card">
                    <span class="icon-box"><i class="bi bi-bullseye"></i></span>
                    <div>
                        <h3>Relevant discovery</h3>
                        <p>Find categories, products and leads aligned with actual business requirements.</p>
                    </div>
                </article>
                <article class="benefit-card">
                    <span class="icon-box"><i class="bi bi-window-stack"></i></span>
                    <div>
                        <h3>Public business presence</h3>
                        <p>Showcase products, company details and contact routes through a professional storefront.</p>
                    </div>
                </article>
                <article class="benefit-card">
                    <span class="icon-box"><i class="bi bi-clock-history"></i></span>
                    <div>
                        <h3>Faster follow-up</h3>
                        <p>Move from interest to enquiry without losing time across scattered channels.</p>
                    </div>
                </article>
                <article class="benefit-card">
                    <span class="icon-box"><i class="bi bi-card-checklist"></i></span>
                    <div>
                        <h3>Clear trade context</h3>
                        <p>Review product type, country, payment terms, category and business role before responding.</p>
                    </div>
                </article>
                <article class="benefit-card">
                    <span class="icon-box"><i class="bi bi-people"></i></span>
                    <div>
                        <h3>Buyer-supplier connection</h3>
                        <p>Bring both sides of a B2B opportunity into one direct communication flow.</p>
                    </div>
                </article>
                <article class="benefit-card">
                    <span class="icon-box"><i class="bi bi-award"></i></span>
                    <div>
                        <h3>Stronger credibility</h3>
                        <p>Complete company information and product listings help buyers evaluate suppliers with more confidence.</p>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section class="pb-5">
        <div class="container pb-4">
            <div class="about-cta">
                <h2>Ready to turn product interest into real B2B conversations?</h2>
                <p>Join Trade4Deal to publish requirements, explore supplier products, review live leads and build a stronger digital presence for your business.</p>
                <div class="about-cta-actions">
                    <a href="{{ route('register') }}" class="btn btn-light fw-bold px-4">Register Free</a>
                    <button type="button" class="btn btn-ghost-light px-4" data-bs-toggle="modal" data-bs-target="#leadModal">
                        Submit Requirement
                    </button>
                    <a href="{{ route('contact') }}" class="btn btn-ghost-light px-4">Contact Trade4Deal</a>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
