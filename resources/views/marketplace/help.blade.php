@extends('layouts.marketplace')

@section('title', 'Trade4Deal Help - Buyer & Supplier Support')

@push('styles')
<style>
    .help-hero {
        position: relative;
        overflow: hidden;
        color: #fff;
        background:
            linear-gradient(105deg, rgba(5, 28, 64, 0.96), rgba(6, 68, 117, 0.86) 48%, rgba(245, 130, 32, 0.42)),
            url('https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=1900&q=82') center/cover no-repeat;
    }

    .help-hero .container {
        min-height: 500px;
        display: grid;
        align-items: center;
    }

    .help-eyebrow,
    .help-kicker {
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

    .help-eyebrow {
        border: 1px solid rgba(255, 255, 255, 0.24);
        background: rgba(255, 255, 255, 0.13);
    }

    .help-kicker {
        background: #e8f1fb;
        color: var(--t4d-primary);
    }

    .help-title {
        max-width: 900px;
        margin: 1rem 0;
        color: #fff;
        font-size: clamp(2.4rem, 5vw, 4.1rem);
        font-weight: 850;
        line-height: 1.05;
    }

    .help-summary {
        max-width: 800px;
        color: rgba(255, 255, 255, 0.86);
        font-size: 1.06rem;
        line-height: 1.75;
    }

    .help-search-panel {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 0.8rem;
        max-width: 820px;
        margin-top: 1.6rem;
        padding: 0.75rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(10px);
    }

    .help-search-panel input {
        min-height: 48px;
        border: 0;
        border-radius: 8px;
        padding: 0 1rem;
        color: #0f172a;
        outline: 0;
    }

    .help-search-panel button {
        min-height: 48px;
        border-radius: 8px;
        white-space: nowrap;
    }

    .help-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 0.8rem;
        max-width: 930px;
        margin-top: 1.6rem;
    }

    .help-stat {
        min-height: 96px;
        border: 1px solid rgba(255, 255, 255, 0.18);
        border-radius: 8px;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.11);
        backdrop-filter: blur(8px);
    }

    .help-stat strong {
        display: block;
        color: #fff;
        font-size: 1.35rem;
        line-height: 1.1;
    }

    .help-stat span {
        display: block;
        margin-top: 0.35rem;
        color: rgba(255, 255, 255, 0.72);
        font-size: 0.78rem;
        font-weight: 750;
    }

    .help-page {
        background: #f5f7fb;
    }

    .help-section-title {
        max-width: 760px;
        margin: 0.8rem 0 0;
        color: #061224;
        font-size: clamp(1.75rem, 3.3vw, 2.65rem);
        font-weight: 850;
        line-height: 1.12;
    }

    .help-section-copy {
        max-width: 760px;
        margin: 0.85rem 0 0;
        color: #64748b;
        line-height: 1.7;
    }

    .help-card-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1rem;
        margin-top: 1.6rem;
    }

    .help-card,
    .guide-panel,
    .topic-card,
    .step-card {
        border: 1px solid #dbe3ef;
        border-radius: 10px;
        background: #fff;
        box-shadow: 0 14px 34px rgba(15, 23, 42, 0.07);
    }

    .help-card {
        min-height: 245px;
        padding: 1.25rem;
    }

    .help-icon {
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

    .help-card h3,
    .guide-panel h3,
    .topic-card h3,
    .step-card h3 {
        margin: 0 0 0.45rem;
        color: #061224;
        font-size: 1rem;
        font-weight: 850;
    }

    .help-card p,
    .guide-panel p,
    .topic-card p,
    .step-card p {
        margin: 0;
        color: #64748b;
        font-size: 0.92rem;
        line-height: 1.65;
    }

    .help-card a,
    .guide-panel a {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        margin-top: 1rem;
        color: var(--t4d-primary);
        font-weight: 850;
        text-decoration: none;
    }

    .guide-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1rem;
        margin-top: 1.6rem;
    }

    .guide-panel {
        position: relative;
        overflow: hidden;
        padding: 1.4rem;
    }

    .guide-panel::before {
        content: "";
        position: absolute;
        inset: 0 0 auto;
        height: 5px;
        background: linear-gradient(90deg, var(--t4d-primary), var(--t4d-accent));
    }

    .guide-list {
        display: grid;
        gap: 0.8rem;
        margin-top: 1.1rem;
    }

    .guide-list-item {
        display: grid;
        grid-template-columns: 38px 1fr;
        gap: 0.75rem;
        align-items: start;
    }

    .guide-list-item span {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        display: grid;
        place-items: center;
        background: #edf5fc;
        color: var(--t4d-primary);
        font-weight: 850;
    }

    .process-band {
        background:
            linear-gradient(135deg, rgba(6, 68, 117, 0.96), rgba(10, 83, 125, 0.92)),
            url('https://images.unsplash.com/photo-1521791136064-7986c2920216?auto=format&fit=crop&w=1800&q=80') center/cover no-repeat;
        color: #fff;
    }

    .process-band .help-kicker {
        background: rgba(255, 255, 255, 0.13);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.22);
    }

    .process-band .help-section-title,
    .process-band .help-section-copy {
        color: #fff;
    }

    .process-band .help-section-copy {
        color: rgba(255, 255, 255, 0.78);
    }

    .step-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1rem;
        margin-top: 1.8rem;
    }

    .step-card {
        min-height: 230px;
        padding: 1.25rem;
        background: rgba(255, 255, 255, 0.96);
    }

    .step-number {
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

    .topic-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1rem;
        margin-top: 1.6rem;
    }

    .topic-card {
        display: grid;
        grid-template-columns: 48px 1fr;
        gap: 0.9rem;
        align-items: start;
        min-height: 150px;
        padding: 1.15rem;
    }

    .topic-card .help-icon {
        width: 48px;
        height: 48px;
        margin: 0;
    }

    .support-strip {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        gap: 1rem;
        align-items: center;
        margin-top: 1.5rem;
        border: 1px solid #dbe3ef;
        border-radius: 10px;
        background: #fff;
        padding: 1.2rem;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
    }

    .support-strip strong {
        display: block;
        color: #061224;
        font-size: 1.05rem;
        margin-bottom: 0.25rem;
    }

    .support-strip span {
        color: #64748b;
    }

    .help-cta {
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

    .help-cta h2 {
        max-width: 780px;
        margin: 0 0 0.6rem;
        color: #fff;
        font-size: clamp(1.6rem, 3.2vw, 2.55rem);
        font-weight: 850;
        line-height: 1.12;
    }

    .help-cta p {
        max-width: 760px;
        margin: 0;
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.65;
    }

    .help-cta-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.8rem;
        margin-top: 1.35rem;
    }

    @media (max-width: 991px) {
        .help-stats,
        .help-card-grid,
        .step-grid,
        .topic-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 767px) {
        .guide-grid,
        .support-strip {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 575px) {
        .help-hero .container {
            min-height: 460px;
        }

        .help-search-panel,
        .help-stats,
        .help-card-grid,
        .step-grid,
        .topic-grid {
            grid-template-columns: 1fr;
        }

        .topic-card {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<section class="help-hero">
    <div class="container py-5">
        <div>
            <span class="help-eyebrow"><i class="bi bi-life-preserver"></i> Help &amp; Support</span>
            <h1 class="help-title">Trade4Deal Help Center</h1>
            <p class="help-summary">Find quick guidance for using Trade4Deal as a buyer, supplier, or marketplace member. Get help with registration, product listings, RFQs, leads, enquiries, plans and profile updates.</p>
            <form class="help-search-panel" action="{{ route('contact') }}" method="get">
                <input type="search" name="q" placeholder="Search help: account, RFQ, seller profile, leads, products">
                <button type="submit" class="btn btn-light fw-bold px-4">
                    <i class="bi bi-search me-1"></i> Get Help
                </button>
            </form>
            <div class="help-stats">
                <div class="help-stat">
                    <strong>Account</strong>
                    <span>Login, registration, profile</span>
                </div>
                <div class="help-stat">
                    <strong>RFQ</strong>
                    <span>Submit requirements</span>
                </div>
                <div class="help-stat">
                    <strong>Leads</strong>
                    <span>Find opportunities</span>
                </div>
                <div class="help-stat">
                    <strong>Support</strong>
                    <span>Contact Trade4Deal team</span>
                </div>
            </div>
        </div>
    </div>
</section>

<main class="help-page">
    <section class="py-5">
        <div class="container py-4">
            <span class="help-kicker"><i class="bi bi-grid-1x2"></i> Quick Help</span>
            <h2 class="help-section-title">Choose the help path that matches your work.</h2>
            <p class="help-section-copy">Whether you are finding suppliers, responding to buyer needs or managing a company profile, these quick sections take you to the right next step.</p>

            <div class="help-card-grid">
                <article class="help-card">
                    <span class="help-icon"><i class="bi bi-person-plus"></i></span>
                    <h3>Create your account</h3>
                    <p>Register as a buyer or seller, verify your email, and complete your basic profile so other businesses can trust your details.</p>
                    <a href="{{ route('register') }}">Register now <i class="bi bi-arrow-right"></i></a>
                </article>
                <article class="help-card">
                    <span class="help-icon"><i class="bi bi-send"></i></span>
                    <h3>Submit a requirement</h3>
                    <p>Share product name, category, country, payment preference and message so suitable suppliers can understand your need.</p>
                    <a href="#leadModal" data-bs-toggle="modal" data-bs-target="#leadModal">Submit RFQ <i class="bi bi-arrow-right"></i></a>
                </article>
                <article class="help-card">
                    <span class="help-icon"><i class="bi bi-shop-window"></i></span>
                    <h3>Set up seller profile</h3>
                    <p>Add company details, industries, logo, description and product listings to improve buyer confidence.</p>
                    <a href="{{ route('marketplace.page', ['page' => 'sell-on-trade4deal']) }}">Seller guide <i class="bi bi-arrow-right"></i></a>
                </article>
                <article class="help-card">
                    <span class="help-icon"><i class="bi bi-broadcast"></i></span>
                    <h3>Browse live leads</h3>
                    <p>Review visible buyer requirements and open lead details to contact relevant business opportunities.</p>
                    <a href="{{ route('home') }}#leads">View leads <i class="bi bi-arrow-right"></i></a>
                </article>
                <article class="help-card">
                    <span class="help-icon"><i class="bi bi-box-seam"></i></span>
                    <h3>Find products</h3>
                    <p>Use categories and marketplace search to discover supplier products and open company profiles.</p>
                    <a href="{{ route('home') }}#category-products">Search products <i class="bi bi-arrow-right"></i></a>
                </article>
                <article class="help-card">
                    <span class="help-icon"><i class="bi bi-headset"></i></span>
                    <h3>Contact support</h3>
                    <p>For account, lead, listing, plan or enquiry questions, reach the Trade4Deal support team directly.</p>
                    <a href="{{ route('contact') }}">Contact support <i class="bi bi-arrow-right"></i></a>
                </article>
            </div>
        </div>
    </section>

    <section class="py-5 bg-white">
        <div class="container py-4">
            <span class="help-kicker"><i class="bi bi-book"></i> Buyer &amp; Supplier Guides</span>
            <h2 class="help-section-title">Practical guidance for both sides of the marketplace.</h2>
            <p class="help-section-copy">Trade4Deal works best when buyers share clear requirements and suppliers maintain complete, accurate profiles.</p>

            <div class="guide-grid">
                <article class="guide-panel">
                    <span class="help-icon"><i class="bi bi-cart-check"></i></span>
                    <h3>For Buyers</h3>
                    <p>Use Trade4Deal to search categories, publish requirements and contact suppliers with enough context for a useful response.</p>
                    <div class="guide-list">
                        <div class="guide-list-item">
                            <span>1</span>
                            <p>Search products or browse categories before submitting your requirement.</p>
                        </div>
                        <div class="guide-list-item">
                            <span>2</span>
                            <p>Add quantity, country, payment preference and product details in your RFQ.</p>
                        </div>
                        <div class="guide-list-item">
                            <span>3</span>
                            <p>Review supplier profiles and verify commercial details before ordering.</p>
                        </div>
                    </div>
                    <a href="{{ route('marketplace.page', ['page' => 'submit-requirement']) }}">Buyer toolkit <i class="bi bi-arrow-right"></i></a>
                </article>

                <article class="guide-panel">
                    <span class="help-icon"><i class="bi bi-building-check"></i></span>
                    <h3>For Suppliers</h3>
                    <p>Use Trade4Deal to build visibility, publish products and respond to buyer requirements that match your capability.</p>
                    <div class="guide-list">
                        <div class="guide-list-item">
                            <span>1</span>
                            <p>Complete company profile details, business location and industry categories.</p>
                        </div>
                        <div class="guide-list-item">
                            <span>2</span>
                            <p>Add product names, descriptions, pricing labels, units and product images.</p>
                        </div>
                        <div class="guide-list-item">
                            <span>3</span>
                            <p>Check live leads regularly and contact buyers with relevant product context.</p>
                        </div>
                    </div>
                    <a href="{{ route('marketplace.page', ['page' => 'sell-on-trade4deal']) }}">Supplier toolkit <i class="bi bi-arrow-right"></i></a>
                </article>
            </div>
        </div>
    </section>

    <section class="process-band py-5">
        <div class="container py-4">
            <span class="help-kicker"><i class="bi bi-diagram-3"></i> Support Process</span>
            <h2 class="help-section-title">How to get help faster.</h2>
            <p class="help-section-copy">A clear request helps the Trade4Deal team understand the issue and reply with the right next step.</p>

            <div class="step-grid">
                <article class="step-card">
                    <span class="step-number">1</span>
                    <h3>Identify the topic</h3>
                    <p>Choose account, seller profile, product listing, RFQ, lead access, plan, enquiry or payment-related help.</p>
                </article>
                <article class="step-card">
                    <span class="step-number">2</span>
                    <h3>Share details</h3>
                    <p>Include your registered email, company name, page link, product name or lead reference where available.</p>
                </article>
                <article class="step-card">
                    <span class="step-number">3</span>
                    <h3>Send request</h3>
                    <p>Use the contact page or support email so the team receives the full context in one place.</p>
                </article>
                <article class="step-card">
                    <span class="step-number">4</span>
                    <h3>Follow guidance</h3>
                    <p>Complete any requested verification, profile update or correction so your marketplace workflow can continue.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container py-4">
            <span class="help-kicker"><i class="bi bi-patch-question"></i> Common Topics</span>
            <h2 class="help-section-title">Most requested help areas.</h2>
            <p class="help-section-copy">Use these topics to understand what information to prepare before contacting support.</p>

            <div class="topic-grid">
                <article class="topic-card">
                    <span class="help-icon"><i class="bi bi-key"></i></span>
                    <div>
                        <h3>Login issues</h3>
                        <p>Check email, password reset, verification status and browser session before raising a request.</p>
                    </div>
                </article>
                <article class="topic-card">
                    <span class="help-icon"><i class="bi bi-person-badge"></i></span>
                    <div>
                        <h3>Profile updates</h3>
                        <p>Keep company name, phone, country, industries, description and logo accurate.</p>
                    </div>
                </article>
                <article class="topic-card">
                    <span class="help-icon"><i class="bi bi-images"></i></span>
                    <div>
                        <h3>Product listings</h3>
                        <p>Use clear names, realistic pricing labels, correct category and professional product images.</p>
                    </div>
                </article>
                <article class="topic-card">
                    <span class="help-icon"><i class="bi bi-megaphone"></i></span>
                    <div>
                        <h3>Lead visibility</h3>
                        <p>Visible leads may depend on review status and your current membership or access plan.</p>
                    </div>
                </article>
                <article class="topic-card">
                    <span class="help-icon"><i class="bi bi-shield-check"></i></span>
                    <div>
                        <h3>Trust and safety</h3>
                        <p>Report suspicious businesses, incorrect listings, spam enquiries or misleading information.</p>
                    </div>
                </article>
                <article class="topic-card">
                    <span class="help-icon"><i class="bi bi-credit-card"></i></span>
                    <div>
                        <h3>Plans and billing</h3>
                        <p>Contact support for subscription, plan access, upgrade, payment or invoice questions.</p>
                    </div>
                </article>
            </div>

            <div class="support-strip">
                <div>
                    <strong>Need direct support?</strong>
                    <span>Email info@trade4deal.com or call +91 91422 72080 with your account and issue details.</span>
                </div>
                <a href="{{ route('contact') }}" class="btn btn-primary-t4d px-4">Contact Support</a>
            </div>
        </div>
    </section>

    <section class="pb-5">
        <div class="container pb-4">
            <div class="help-cta">
                <h2>Ready to continue your Trade4Deal workflow?</h2>
                <p>Create your account, submit a product requirement, browse live leads or contact the support team for help with the next step.</p>
                <div class="help-cta-actions">
                    <a href="{{ route('register') }}" class="btn btn-light fw-bold px-4">Register Free</a>
                    <button type="button" class="btn btn-ghost-light px-4" data-bs-toggle="modal" data-bs-target="#leadModal">
                        Submit Requirement
                    </button>
                    <a href="{{ route('contact') }}" class="btn btn-ghost-light px-4">Contact Support</a>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
