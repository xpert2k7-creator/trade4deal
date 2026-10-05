@extends('layouts.marketplace')

@section('title', 'Customer Care - Trade4Deal')

@push('styles')
<style>
    .care-hero {
        position: relative;
        overflow: hidden;
        color: #fff;
        background:
            linear-gradient(105deg, rgba(5, 28, 64, 0.96), rgba(6, 68, 117, 0.86) 48%, rgba(245, 130, 32, 0.42)),
            url('https://images.unsplash.com/photo-1556761175-b413da4baf72?auto=format&fit=crop&w=1900&q=82') center/cover no-repeat;
    }

    .care-hero .container {
        min-height: 500px;
        display: grid;
        align-items: center;
    }

    .care-eyebrow,
    .care-kicker {
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

    .care-eyebrow {
        border: 1px solid rgba(255, 255, 255, 0.24);
        background: rgba(255, 255, 255, 0.13);
    }

    .care-kicker {
        background: #e8f1fb;
        color: var(--t4d-primary);
    }

    .care-title {
        max-width: 900px;
        margin: 1rem 0;
        color: #fff;
        font-size: clamp(2.4rem, 5vw, 4.1rem);
        font-weight: 850;
        line-height: 1.05;
    }

    .care-summary {
        max-width: 820px;
        color: rgba(255, 255, 255, 0.86);
        font-size: 1.06rem;
        line-height: 1.75;
    }

    .care-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.8rem;
        margin-top: 1.45rem;
    }

    .care-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 0.8rem;
        max-width: 930px;
        margin-top: 1.6rem;
    }

    .care-stat {
        min-height: 96px;
        border: 1px solid rgba(255, 255, 255, 0.18);
        border-radius: 8px;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.11);
        backdrop-filter: blur(8px);
    }

    .care-stat strong {
        display: block;
        color: #fff;
        font-size: 1.35rem;
        line-height: 1.1;
    }

    .care-stat span {
        display: block;
        margin-top: 0.35rem;
        color: rgba(255, 255, 255, 0.72);
        font-size: 0.78rem;
        font-weight: 750;
    }

    .care-page {
        background: #f5f7fb;
    }

    .care-section-title {
        max-width: 780px;
        margin: 0.8rem 0 0;
        color: #061224;
        font-size: clamp(1.75rem, 3.3vw, 2.65rem);
        font-weight: 850;
        line-height: 1.12;
    }

    .care-section-copy {
        max-width: 780px;
        margin: 0.85rem 0 0;
        color: #64748b;
        line-height: 1.7;
    }

    .care-grid,
    .topic-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1rem;
        margin-top: 1.6rem;
    }

    .care-card,
    .contact-panel,
    .process-card,
    .topic-card {
        border: 1px solid #dbe3ef;
        border-radius: 10px;
        background: #fff;
        box-shadow: 0 14px 34px rgba(15, 23, 42, 0.07);
    }

    .care-card {
        min-height: 260px;
        padding: 1.25rem;
    }

    .care-icon {
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

    .care-card h3,
    .contact-panel h3,
    .process-card h3,
    .topic-card h3 {
        margin: 0 0 0.45rem;
        color: #061224;
        font-size: 1rem;
        font-weight: 850;
    }

    .care-card p,
    .contact-panel p,
    .process-card p,
    .topic-card p {
        margin: 0;
        color: #64748b;
        font-size: 0.92rem;
        line-height: 1.65;
    }

    .care-card ul {
        display: grid;
        gap: 0.45rem;
        margin: 1rem 0 0;
        padding-left: 1.1rem;
        color: #64748b;
        font-size: 0.9rem;
        line-height: 1.55;
    }

    .contact-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(320px, 0.75fr);
        gap: 1rem;
        align-items: stretch;
        margin-top: 1.6rem;
    }

    .contact-panel {
        overflow: hidden;
        padding: 1.35rem;
    }

    .contact-panel.featured {
        border-top: 5px solid var(--t4d-accent);
    }

    .contact-methods {
        display: grid;
        gap: 0.85rem;
        margin-top: 1.2rem;
    }

    .contact-method {
        display: grid;
        grid-template-columns: 44px 1fr;
        gap: 0.8rem;
        align-items: center;
        border: 1px solid #dbe3ef;
        border-radius: 8px;
        background: #f8fafc;
        padding: 0.9rem;
    }

    .contact-method span {
        width: 44px;
        height: 44px;
        border-radius: 8px;
        display: grid;
        place-items: center;
        background: #edf5fc;
        color: var(--t4d-primary);
        font-size: 1.1rem;
    }

    .contact-method strong {
        display: block;
        color: #061224;
        margin-bottom: 0.12rem;
    }

    .contact-method a,
    .contact-method small {
        color: #64748b;
        text-decoration: none;
    }

    .care-note {
        margin-top: 1rem;
        border-radius: 8px;
        background: #fff7ed;
        color: #9a3412;
        padding: 0.9rem;
        font-size: 0.9rem;
        line-height: 1.55;
    }

    .process-band {
        background:
            linear-gradient(135deg, rgba(6, 68, 117, 0.96), rgba(10, 83, 125, 0.92)),
            url('https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=1800&q=80') center/cover no-repeat;
        color: #fff;
    }

    .process-band .care-kicker {
        background: rgba(255, 255, 255, 0.13);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.22);
    }

    .process-band .care-section-title,
    .process-band .care-section-copy {
        color: #fff;
    }

    .process-band .care-section-copy {
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

    .topic-card {
        display: grid;
        grid-template-columns: 48px 1fr;
        gap: 0.9rem;
        align-items: start;
        min-height: 150px;
        padding: 1.15rem;
    }

    .topic-card .care-icon {
        width: 48px;
        height: 48px;
        margin: 0;
    }

    .care-cta {
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

    .care-cta h2 {
        max-width: 780px;
        margin: 0 0 0.6rem;
        color: #fff;
        font-size: clamp(1.6rem, 3.2vw, 2.55rem);
        font-weight: 850;
        line-height: 1.12;
    }

    .care-cta p {
        max-width: 760px;
        margin: 0;
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.65;
    }

    .care-cta-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.8rem;
        margin-top: 1.35rem;
    }

    @media (max-width: 991px) {
        .care-stats,
        .care-grid,
        .process-grid,
        .topic-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .contact-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 575px) {
        .care-hero .container {
            min-height: 430px;
        }

        .care-stats,
        .care-grid,
        .process-grid,
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
<section class="care-hero">
    <div class="container py-5">
        <div>
            <span class="care-eyebrow"><i class="bi bi-headset"></i> Help &amp; Support</span>
            <h1 class="care-title">Customer Care</h1>
            <p class="care-summary">Get help with buyer requirements, seller onboarding, profile updates, product listings, lead visibility, plans, enquiries and marketplace account questions.</p>
            <div class="care-actions">
                <a href="{{ route('contact') }}" class="btn btn-light fw-bold px-4">Contact Customer Care</a>
                <a href="tel:+919142272080" class="btn btn-ghost-light px-4">Call +91 91422 72080</a>
            </div>
            <div class="care-stats">
                <div class="care-stat">
                    <strong>Buyer</strong>
                    <span>Requirements and suppliers</span>
                </div>
                <div class="care-stat">
                    <strong>Seller</strong>
                    <span>Profiles and products</span>
                </div>
                <div class="care-stat">
                    <strong>Account</strong>
                    <span>Login, plan, verification</span>
                </div>
                <div class="care-stat">
                    <strong>Care</strong>
                    <span>Email and phone support</span>
                </div>
            </div>
        </div>
    </div>
</section>

<main class="care-page">
    <section class="py-5">
        <div class="container py-4">
            <span class="care-kicker"><i class="bi bi-life-preserver"></i> Support Areas</span>
            <h2 class="care-section-title">Customer care for every Trade4Deal workflow.</h2>
            <p class="care-section-copy">Choose the support area that matches your issue. Clear details help our team guide you faster and keep your marketplace activity moving.</p>

            <div class="care-grid">
                <article class="care-card">
                    <span class="care-icon"><i class="bi bi-cart-check"></i></span>
                    <h3>Buyer support</h3>
                    <p>Get assistance with submitting requirements, browsing suppliers and contacting marketplace businesses.</p>
                    <ul>
                        <li>RFQ and requirement help</li>
                        <li>Supplier discovery guidance</li>
                        <li>Lead enquiry support</li>
                    </ul>
                </article>
                <article class="care-card">
                    <span class="care-icon"><i class="bi bi-shop-window"></i></span>
                    <h3>Seller support</h3>
                    <p>Get help creating a storefront, adding products and understanding Trade4Deal lead access.</p>
                    <ul>
                        <li>Seller profile setup</li>
                        <li>Product listing guidance</li>
                        <li>Live lead access questions</li>
                    </ul>
                </article>
                <article class="care-card">
                    <span class="care-icon"><i class="bi bi-person-gear"></i></span>
                    <h3>Account support</h3>
                    <p>Contact Trade4Deal for login, verification, plan, billing and profile questions.</p>
                    <ul>
                        <li>Login and password help</li>
                        <li>Email verification support</li>
                        <li>Plan and profile updates</li>
                    </ul>
                </article>
                <article class="care-card">
                    <span class="care-icon"><i class="bi bi-broadcast"></i></span>
                    <h3>Lead visibility</h3>
                    <p>Understand how leads appear, what affects access and how to respond to relevant opportunities.</p>
                    <ul>
                        <li>Lead board guidance</li>
                        <li>Gold access questions</li>
                        <li>Contact flow support</li>
                    </ul>
                </article>
                <article class="care-card">
                    <span class="care-icon"><i class="bi bi-shield-check"></i></span>
                    <h3>Trust and safety</h3>
                    <p>Report suspicious listings, duplicate profiles, spam enquiries or misleading marketplace information.</p>
                    <ul>
                        <li>Fraud report support</li>
                        <li>Incorrect listing review</li>
                        <li>Marketplace quality issues</li>
                    </ul>
                </article>
                <article class="care-card">
                    <span class="care-icon"><i class="bi bi-credit-card"></i></span>
                    <h3>Plans and billing</h3>
                    <p>Ask about plan access, upgrades, payments, subscription status and billing-related concerns.</p>
                    <ul>
                        <li>Plan comparison</li>
                        <li>Payment status</li>
                        <li>Access troubleshooting</li>
                    </ul>
                </article>
            </div>
        </div>
    </section>

    <section class="py-5 bg-white">
        <div class="container py-4">
            <span class="care-kicker"><i class="bi bi-telephone-forward"></i> Contact Options</span>
            <h2 class="care-section-title">Reach the Trade4Deal customer care team.</h2>
            <p class="care-section-copy">Send your registered email, company name, issue type and related page or listing link so the team can help with context.</p>

            <div class="contact-grid">
                <article class="contact-panel featured">
                    <h3>Prepare these details before contacting support</h3>
                    <p>Better context usually means a faster, more accurate response.</p>
                    <div class="contact-methods">
                        <div class="contact-method">
                            <span><i class="bi bi-person-badge"></i></span>
                            <div>
                                <strong>Account details</strong>
                                <small>Registered email, phone and company name.</small>
                            </div>
                        </div>
                        <div class="contact-method">
                            <span><i class="bi bi-link-45deg"></i></span>
                            <div>
                                <strong>Page reference</strong>
                                <small>Product, seller profile, lead or plan page link if available.</small>
                            </div>
                        </div>
                        <div class="contact-method">
                            <span><i class="bi bi-chat-square-text"></i></span>
                            <div>
                                <strong>Clear issue summary</strong>
                                <small>What happened, what you expected and what help you need.</small>
                            </div>
                        </div>
                    </div>
                </article>

                <aside class="contact-panel">
                    <h3>Direct care channels</h3>
                    <p>Use email or phone for account, listing, lead and marketplace support.</p>
                    <div class="contact-methods">
                        <div class="contact-method">
                            <span><i class="bi bi-envelope"></i></span>
                            <div>
                                <strong>Email</strong>
                                <a href="mailto:info@trade4deal.com">info@trade4deal.com</a>
                            </div>
                        </div>
                        <div class="contact-method">
                            <span><i class="bi bi-telephone"></i></span>
                            <div>
                                <strong>Phone</strong>
                                <a href="tel:+919142272080">+91 91422 72080</a>
                            </div>
                        </div>
                        <div class="contact-method">
                            <span><i class="bi bi-window-sidebar"></i></span>
                            <div>
                                <strong>Contact page</strong>
                                <a href="{{ route('contact') }}">Open support form</a>
                            </div>
                        </div>
                    </div>
                    <div class="care-note">For transaction decisions, always verify supplier, buyer, product, payment and delivery details independently before committing.</div>
                </aside>
            </div>
        </div>
    </section>

    <section class="process-band py-5">
        <div class="container py-4">
            <span class="care-kicker"><i class="bi bi-diagram-3"></i> Care Process</span>
            <h2 class="care-section-title">How customer care handles your request.</h2>
            <p class="care-section-copy">A simple support process keeps communication focused and helps the team resolve practical marketplace issues.</p>

            <div class="process-grid">
                <article class="process-card">
                    <span class="process-number">1</span>
                    <h3>Receive request</h3>
                    <p>The care team reviews your message, account details and issue category.</p>
                </article>
                <article class="process-card">
                    <span class="process-number">2</span>
                    <h3>Check context</h3>
                    <p>Relevant profile, listing, lead, plan or enquiry details are checked where available.</p>
                </article>
                <article class="process-card">
                    <span class="process-number">3</span>
                    <h3>Guide next step</h3>
                    <p>You receive practical guidance, correction steps or a request for missing information.</p>
                </article>
                <article class="process-card">
                    <span class="process-number">4</span>
                    <h3>Close the loop</h3>
                    <p>The team helps you continue your buyer or supplier workflow with clearer direction.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container py-4">
            <span class="care-kicker"><i class="bi bi-patch-question"></i> Priority Topics</span>
            <h2 class="care-section-title">Common reasons businesses contact customer care.</h2>
            <p class="care-section-copy">These are the support needs we see across buyers, sellers and marketplace members.</p>

            <div class="topic-grid">
                <article class="topic-card">
                    <span class="care-icon"><i class="bi bi-key"></i></span>
                    <div>
                        <h3>Login or verification</h3>
                        <p>Resolve sign-in, email verification and access problems.</p>
                    </div>
                </article>
                <article class="topic-card">
                    <span class="care-icon"><i class="bi bi-images"></i></span>
                    <div>
                        <h3>Product listing issues</h3>
                        <p>Fix product details, images, categories or visibility problems.</p>
                    </div>
                </article>
                <article class="topic-card">
                    <span class="care-icon"><i class="bi bi-card-checklist"></i></span>
                    <div>
                        <h3>RFQ corrections</h3>
                        <p>Improve buyer requirements and product need clarity.</p>
                    </div>
                </article>
                <article class="topic-card">
                    <span class="care-icon"><i class="bi bi-person-vcard"></i></span>
                    <div>
                        <h3>Company profile</h3>
                        <p>Update business identity, location, description and contact details.</p>
                    </div>
                </article>
                <article class="topic-card">
                    <span class="care-icon"><i class="bi bi-lightning-charge"></i></span>
                    <div>
                        <h3>Lead access</h3>
                        <p>Understand plan-based lead access and latest opportunity visibility.</p>
                    </div>
                </article>
                <article class="topic-card">
                    <span class="care-icon"><i class="bi bi-exclamation-triangle"></i></span>
                    <div>
                        <h3>Report marketplace misuse</h3>
                        <p>Flag spam, suspicious activity or misleading business information.</p>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section class="pb-5">
        <div class="container pb-4">
            <div class="care-cta">
                <h2>Need help moving forward on Trade4Deal?</h2>
                <p>Contact customer care for buyer requirements, seller onboarding, product listings, lead visibility, account access or marketplace safety questions.</p>
                <div class="care-cta-actions">
                    <a href="{{ route('contact') }}" class="btn btn-light fw-bold px-4">Contact Customer Care</a>
                    <button type="button" class="btn btn-ghost-light px-4" data-bs-toggle="modal" data-bs-target="#leadModal">
                        Submit Requirement
                    </button>
                    <a href="{{ route('marketplace.page', ['page' => 'help']) }}" class="btn btn-ghost-light px-4">Open Help Center</a>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
