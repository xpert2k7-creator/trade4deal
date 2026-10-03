@extends('layouts.marketplace')

@section('title', 'Share Feedback - Trade4Deal')

@push('styles')
<style>
    .feedback-hero {
        position: relative;
        overflow: hidden;
        color: #fff;
        background:
            linear-gradient(105deg, rgba(5, 28, 64, 0.96), rgba(6, 68, 117, 0.86) 48%, rgba(245, 130, 32, 0.42)),
            url('https://images.unsplash.com/photo-1556740758-90de374c12ad?auto=format&fit=crop&w=1900&q=82') center/cover no-repeat;
    }

    .feedback-hero .container {
        min-height: 500px;
        display: grid;
        align-items: center;
    }

    .feedback-eyebrow,
    .feedback-kicker {
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

    .feedback-eyebrow {
        border: 1px solid rgba(255, 255, 255, 0.24);
        background: rgba(255, 255, 255, 0.13);
    }

    .feedback-kicker {
        background: #e8f1fb;
        color: var(--t4d-primary);
    }

    .feedback-title {
        max-width: 900px;
        margin: 1rem 0;
        color: #fff;
        font-size: clamp(2.4rem, 5vw, 4.1rem);
        font-weight: 850;
        line-height: 1.05;
    }

    .feedback-summary {
        max-width: 800px;
        color: rgba(255, 255, 255, 0.86);
        font-size: 1.06rem;
        line-height: 1.75;
    }

    .feedback-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.8rem;
        margin-top: 1.45rem;
    }

    .feedback-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 0.8rem;
        max-width: 930px;
        margin-top: 1.6rem;
    }

    .feedback-stat {
        min-height: 96px;
        border: 1px solid rgba(255, 255, 255, 0.18);
        border-radius: 8px;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.11);
        backdrop-filter: blur(8px);
    }

    .feedback-stat strong {
        display: block;
        color: #fff;
        font-size: 1.35rem;
        line-height: 1.1;
    }

    .feedback-stat span {
        display: block;
        margin-top: 0.35rem;
        color: rgba(255, 255, 255, 0.72);
        font-size: 0.78rem;
        font-weight: 750;
    }

    .feedback-page {
        background: #f5f7fb;
    }

    .feedback-section-title {
        max-width: 760px;
        margin: 0.8rem 0 0;
        color: #061224;
        font-size: clamp(1.75rem, 3.3vw, 2.65rem);
        font-weight: 850;
        line-height: 1.12;
    }

    .feedback-section-copy {
        max-width: 760px;
        margin: 0.85rem 0 0;
        color: #64748b;
        line-height: 1.7;
    }

    .feedback-grid,
    .impact-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1rem;
        margin-top: 1.6rem;
    }

    .feedback-card,
    .feedback-panel,
    .process-card,
    .impact-card {
        border: 1px solid #dbe3ef;
        border-radius: 10px;
        background: #fff;
        box-shadow: 0 14px 34px rgba(15, 23, 42, 0.07);
    }

    .feedback-card {
        min-height: 245px;
        padding: 1.25rem;
    }

    .feedback-icon {
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

    .feedback-card h3,
    .feedback-panel h3,
    .process-card h3,
    .impact-card h3 {
        margin: 0 0 0.45rem;
        color: #061224;
        font-size: 1rem;
        font-weight: 850;
    }

    .feedback-card p,
    .feedback-panel p,
    .process-card p,
    .impact-card p {
        margin: 0;
        color: #64748b;
        font-size: 0.92rem;
        line-height: 1.65;
    }

    .feedback-card ul,
    .feedback-panel ul {
        display: grid;
        gap: 0.45rem;
        margin: 1rem 0 0;
        padding-left: 1.1rem;
        color: #64748b;
        font-size: 0.9rem;
        line-height: 1.55;
    }

    .feedback-panel-grid {
        display: grid;
        grid-template-columns: minmax(0, 0.95fr) minmax(0, 1.05fr);
        gap: 1rem;
        align-items: stretch;
        margin-top: 1.6rem;
    }

    .feedback-panel {
        overflow: hidden;
    }

    .feedback-panel-content {
        padding: 1.35rem;
    }

    .feedback-panel img {
        width: 100%;
        height: 100%;
        min-height: 430px;
        object-fit: cover;
    }

    .feedback-form-card {
        border-top: 5px solid var(--t4d-accent);
    }

    .feedback-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.85rem;
        margin-top: 1rem;
    }

    .feedback-input,
    .feedback-textarea,
    .feedback-select {
        width: 100%;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        background: #f8fafc;
        color: #0f172a;
        outline: 0;
        padding: 0.78rem 0.9rem;
    }

    .feedback-textarea {
        min-height: 150px;
        resize: vertical;
    }

    .feedback-field-wide {
        grid-column: 1 / -1;
    }

    .process-band {
        background:
            linear-gradient(135deg, rgba(6, 68, 117, 0.96), rgba(10, 83, 125, 0.92)),
            url('https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=1800&q=80') center/cover no-repeat;
        color: #fff;
    }

    .process-band .feedback-kicker {
        background: rgba(255, 255, 255, 0.13);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.22);
    }

    .process-band .feedback-section-title,
    .process-band .feedback-section-copy {
        color: #fff;
    }

    .process-band .feedback-section-copy {
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

    .impact-card {
        display: grid;
        grid-template-columns: 48px 1fr;
        gap: 0.9rem;
        align-items: start;
        min-height: 150px;
        padding: 1.15rem;
    }

    .impact-card .feedback-icon {
        width: 48px;
        height: 48px;
        margin: 0;
    }

    .feedback-cta {
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

    .feedback-cta h2 {
        max-width: 780px;
        margin: 0 0 0.6rem;
        color: #fff;
        font-size: clamp(1.6rem, 3.2vw, 2.55rem);
        font-weight: 850;
        line-height: 1.12;
    }

    .feedback-cta p {
        max-width: 760px;
        margin: 0;
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.65;
    }

    .feedback-cta-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.8rem;
        margin-top: 1.35rem;
    }

    @media (max-width: 991px) {
        .feedback-stats,
        .feedback-grid,
        .process-grid,
        .impact-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .feedback-panel-grid {
            grid-template-columns: 1fr;
        }

        .feedback-panel img {
            min-height: 280px;
        }
    }

    @media (max-width: 575px) {
        .feedback-hero .container {
            min-height: 430px;
        }

        .feedback-stats,
        .feedback-grid,
        .process-grid,
        .impact-grid,
        .feedback-form-grid {
            grid-template-columns: 1fr;
        }

        .impact-card {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<section class="feedback-hero">
    <div class="container py-5">
        <div>
            <span class="feedback-eyebrow"><i class="bi bi-chat-square-text"></i> Help &amp; Support</span>
            <h1 class="feedback-title">Share Feedback</h1>
            <p class="feedback-summary">Tell Trade4Deal what can be improved across leads, seller profiles, product discovery, enquiry flows, support quality and marketplace workflows.</p>
            <div class="feedback-actions">
                <a href="#feedback-form" class="btn btn-light fw-bold px-4">Send Feedback</a>
                <a href="{{ route('contact') }}" class="btn btn-ghost-light px-4">Contact Support</a>
            </div>
            <div class="feedback-stats">
                <div class="feedback-stat">
                    <strong>Product</strong>
                    <span>Search, listings, categories</span>
                </div>
                <div class="feedback-stat">
                    <strong>Business</strong>
                    <span>Buyer and supplier needs</span>
                </div>
                <div class="feedback-stat">
                    <strong>Support</strong>
                    <span>Care and onboarding</span>
                </div>
                <div class="feedback-stat">
                    <strong>Trust</strong>
                    <span>Safety and marketplace quality</span>
                </div>
            </div>
        </div>
    </div>
</section>

<main class="feedback-page">
    <section class="py-5">
        <div class="container py-4">
            <span class="feedback-kicker"><i class="bi bi-lightbulb"></i> Feedback Areas</span>
            <h2 class="feedback-section-title">Your feedback helps improve the marketplace experience.</h2>
            <p class="feedback-section-copy">Share what is confusing, slow, missing or useful. Specific examples help the team understand what buyers and suppliers need before making trade decisions.</p>

            <div class="feedback-grid">
                <article class="feedback-card">
                    <span class="feedback-icon"><i class="bi bi-window-stack"></i></span>
                    <h3>Product feedback</h3>
                    <p>Tell us how search, categories, product cards, lead pages and seller profiles can be clearer.</p>
                    <ul>
                        <li>Product discovery</li>
                        <li>Listing quality</li>
                        <li>Page layout and filters</li>
                    </ul>
                </article>
                <article class="feedback-card">
                    <span class="feedback-icon"><i class="bi bi-briefcase"></i></span>
                    <h3>Business feedback</h3>
                    <p>Share what buyers and suppliers need to evaluate opportunities, pricing, terms and trust.</p>
                    <ul>
                        <li>RFQ details</li>
                        <li>Supplier information</li>
                        <li>Buyer requirement clarity</li>
                    </ul>
                </article>
                <article class="feedback-card">
                    <span class="feedback-icon"><i class="bi bi-headset"></i></span>
                    <h3>Support feedback</h3>
                    <p>Help us improve response quality for onboarding, profile updates, customer care and marketplace help.</p>
                    <ul>
                        <li>Support speed</li>
                        <li>Guidance quality</li>
                        <li>Issue resolution</li>
                    </ul>
                </article>
                <article class="feedback-card">
                    <span class="feedback-icon"><i class="bi bi-shield-check"></i></span>
                    <h3>Trust and safety</h3>
                    <p>Report confusing, suspicious, duplicate or misleading information that affects marketplace confidence.</p>
                    <ul>
                        <li>Incorrect listings</li>
                        <li>Spam enquiries</li>
                        <li>Suspicious profiles</li>
                    </ul>
                </article>
                <article class="feedback-card">
                    <span class="feedback-icon"><i class="bi bi-phone"></i></span>
                    <h3>Mobile experience</h3>
                    <p>Tell us where buttons, forms, product cards or lead details could work better on mobile screens.</p>
                    <ul>
                        <li>Form usability</li>
                        <li>Content readability</li>
                        <li>Navigation flow</li>
                    </ul>
                </article>
                <article class="feedback-card">
                    <span class="feedback-icon"><i class="bi bi-stars"></i></span>
                    <h3>New ideas</h3>
                    <p>Suggest marketplace features that could help sourcing teams, exporters, manufacturers or distributors.</p>
                    <ul>
                        <li>New tools</li>
                        <li>Better notifications</li>
                        <li>Lead matching ideas</li>
                    </ul>
                </article>
            </div>
        </div>
    </section>

    <section id="feedback-form" class="py-5 bg-white">
        <div class="container py-4">
            <span class="feedback-kicker"><i class="bi bi-pencil-square"></i> Send Feedback</span>
            <h2 class="feedback-section-title">Share clear details so we can understand the issue faster.</h2>
            <p class="feedback-section-copy">Use this page as your guide, then send feedback through Trade4Deal support. Include links, product names, screenshots or examples when available.</p>

            <div class="feedback-panel-grid">
                <article class="feedback-panel feedback-form-card">
                    <div class="feedback-panel-content">
                        <h3>Feedback details</h3>
                        <p>This quick format helps organize your message before sending it to the team.</p>
                        <form class="feedback-form-grid" action="{{ route('contact') }}" method="get">
                            <select class="feedback-select" name="topic" aria-label="Feedback topic">
                                <option value="">Feedback topic</option>
                                <option value="product">Product experience</option>
                                <option value="business">Business workflow</option>
                                <option value="support">Support quality</option>
                                <option value="trust">Trust and safety</option>
                            </select>
                            <input class="feedback-input" type="text" name="role" placeholder="Your role: Buyer / Supplier">
                            <input class="feedback-input feedback-field-wide" type="text" name="subject" placeholder="Short feedback title">
                            <textarea class="feedback-textarea feedback-field-wide" name="message" placeholder="Describe what happened, what should improve, and any page/product/lead reference."></textarea>
                            <button type="submit" class="btn btn-primary-t4d px-4 feedback-field-wide">Continue to Contact Page</button>
                        </form>
                    </div>
                </article>

                <aside class="feedback-panel">
                    <img src="{{ asset('images/electronics-logistics-bg.jpg') }}" alt="Trade4Deal feedback and marketplace improvement">
                </aside>
            </div>
        </div>
    </section>

    <section class="process-band py-5">
        <div class="container py-4">
            <span class="feedback-kicker"><i class="bi bi-diagram-3"></i> Feedback Process</span>
            <h2 class="feedback-section-title">How your feedback moves through Trade4Deal.</h2>
            <p class="feedback-section-copy">Good feedback becomes a clearer product decision when it is specific, practical and connected to a real business workflow.</p>

            <div class="process-grid">
                <article class="process-card">
                    <span class="process-number">1</span>
                    <h3>Share context</h3>
                    <p>Tell us where the issue happened: search, lead page, seller profile, product listing, account or support.</p>
                </article>
                <article class="process-card">
                    <span class="process-number">2</span>
                    <h3>Review internally</h3>
                    <p>The team checks the feedback against marketplace workflows, support history and product priorities.</p>
                </article>
                <article class="process-card">
                    <span class="process-number">3</span>
                    <h3>Plan improvements</h3>
                    <p>Useful patterns become updates for forms, pages, lead visibility, seller tools or support processes.</p>
                </article>
                <article class="process-card">
                    <span class="process-number">4</span>
                    <h3>Improve experience</h3>
                    <p>Changes are designed to make Trade4Deal clearer, faster and more useful for buyers and suppliers.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container py-4">
            <span class="feedback-kicker"><i class="bi bi-graph-up-arrow"></i> Impact</span>
            <h2 class="feedback-section-title">The kind of improvements feedback can create.</h2>
            <p class="feedback-section-copy">Trade4Deal feedback is most valuable when it improves trade clarity, marketplace trust and response quality.</p>

            <div class="impact-grid">
                <article class="impact-card">
                    <span class="feedback-icon"><i class="bi bi-search"></i></span>
                    <div>
                        <h3>Better discovery</h3>
                        <p>Improve category browsing, product search and lead scanning.</p>
                    </div>
                </article>
                <article class="impact-card">
                    <span class="feedback-icon"><i class="bi bi-card-checklist"></i></span>
                    <div>
                        <h3>Clearer RFQs</h3>
                        <p>Help buyers submit requirements suppliers can understand quickly.</p>
                    </div>
                </article>
                <article class="impact-card">
                    <span class="feedback-icon"><i class="bi bi-shop-window"></i></span>
                    <div>
                        <h3>Stronger profiles</h3>
                        <p>Make seller storefronts more complete, credible and useful.</p>
                    </div>
                </article>
                <article class="impact-card">
                    <span class="feedback-icon"><i class="bi bi-send-check"></i></span>
                    <div>
                        <h3>Smoother enquiries</h3>
                        <p>Reduce friction when buyers and suppliers start conversations.</p>
                    </div>
                </article>
                <article class="impact-card">
                    <span class="feedback-icon"><i class="bi bi-life-preserver"></i></span>
                    <div>
                        <h3>Improved support</h3>
                        <p>Make account, listing and lead assistance easier to understand.</p>
                    </div>
                </article>
                <article class="impact-card">
                    <span class="feedback-icon"><i class="bi bi-shield-lock"></i></span>
                    <div>
                        <h3>Marketplace quality</h3>
                        <p>Identify misleading data, spam, duplicate content and trust gaps.</p>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section class="pb-5">
        <div class="container pb-4">
            <div class="feedback-cta">
                <h2>Your feedback can shape the next Trade4Deal improvement.</h2>
                <p>Send a practical suggestion, report a workflow issue, or tell us what would make sourcing and supplier discovery easier for your business.</p>
                <div class="feedback-cta-actions">
                    <a href="{{ route('contact') }}" class="btn btn-light fw-bold px-4">Send Feedback</a>
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
