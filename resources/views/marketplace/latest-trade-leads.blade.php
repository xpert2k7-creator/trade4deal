@extends('layouts.marketplace')

@section('title', 'Latest Trade Leads - Trade4Deal')

@push('styles')
<style>
    @keyframes pulseSignal {
        0%, 100% { transform: scale(1); opacity: 0.55; }
        50% { transform: scale(1.35); opacity: 1; }
    }

    @keyframes floatCard {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-7px); }
    }

    .latest-hero {
        position: relative;
        overflow: hidden;
        color: #fff;
        background:
            linear-gradient(105deg, rgba(5, 28, 64, 0.96), rgba(6, 68, 117, 0.86) 48%, rgba(245, 130, 32, 0.42)),
            url('https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=1900&q=82') center/cover no-repeat;
    }

    .latest-hero::after {
        content: "";
        position: absolute;
        inset: auto -10% -42%;
        height: 240px;
        background: radial-gradient(circle, rgba(245, 130, 32, 0.26), transparent 62%);
        pointer-events: none;
    }

    .latest-hero .container {
        min-height: 520px;
        display: grid;
        align-items: center;
        position: relative;
        z-index: 1;
    }

    .latest-eyebrow,
    .latest-kicker {
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

    .latest-eyebrow {
        border: 1px solid rgba(255, 255, 255, 0.24);
        background: rgba(255, 255, 255, 0.13);
    }

    .latest-kicker {
        background: #e8f1fb;
        color: var(--t4d-primary);
    }

    .latest-title {
        max-width: 930px;
        margin: 1rem 0;
        color: #fff;
        font-size: clamp(2.4rem, 5vw, 4.15rem);
        font-weight: 850;
        line-height: 1.04;
    }

    .latest-summary {
        max-width: 830px;
        color: rgba(255, 255, 255, 0.86);
        font-size: 1.06rem;
        line-height: 1.75;
    }

    .latest-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.8rem;
        margin-top: 1.45rem;
    }

    .latest-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 0.8rem;
        max-width: 950px;
        margin-top: 1.6rem;
    }

    .latest-stat {
        position: relative;
        min-height: 96px;
        border: 1px solid rgba(255, 255, 255, 0.18);
        border-radius: 8px;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.11);
        backdrop-filter: blur(8px);
        overflow: hidden;
        transition: transform 0.22s ease, background 0.22s ease;
    }

    .latest-stat:hover {
        transform: translateY(-5px);
        background: rgba(255, 255, 255, 0.16);
    }

    .latest-stat::after {
        content: "";
        position: absolute;
        top: 13px;
        right: 13px;
        width: 9px;
        height: 9px;
        border-radius: 50%;
        background: var(--t4d-accent);
        box-shadow: 0 0 0 6px rgba(245, 130, 32, 0.18);
        animation: pulseSignal 1.8s ease-in-out infinite;
    }

    .latest-stat strong {
        display: block;
        color: #fff;
        font-size: 1.35rem;
        line-height: 1.1;
    }

    .latest-stat span {
        display: block;
        margin-top: 0.35rem;
        color: rgba(255, 255, 255, 0.72);
        font-size: 0.78rem;
        font-weight: 750;
    }

    .latest-page {
        background: #f5f7fb;
    }

    .latest-section-title {
        max-width: 800px;
        margin: 0.8rem 0 0;
        color: #061224;
        font-size: clamp(1.75rem, 3.3vw, 2.65rem);
        font-weight: 850;
        line-height: 1.12;
    }

    .latest-section-copy {
        max-width: 800px;
        margin: 0.85rem 0 0;
        color: #64748b;
        line-height: 1.7;
    }

    .lead-spotlight-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1rem;
        margin-top: 1.7rem;
    }

    .spotlight-card,
    .signal-panel,
    .flow-card,
    .feature-card {
        border: 1px solid #dbe3ef;
        border-radius: 10px;
        background: #fff;
        box-shadow: 0 14px 34px rgba(15, 23, 42, 0.07);
    }

    .spotlight-card {
        position: relative;
        min-height: 280px;
        padding: 1.2rem;
        overflow: hidden;
        transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease;
    }

    .spotlight-card::before {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(120deg, transparent 0%, rgba(245, 130, 32, 0.12) 48%, transparent 78%);
        transform: translateX(-110%);
        transition: transform 0.6s ease;
        pointer-events: none;
    }

    .spotlight-card:hover {
        transform: translateY(-9px);
        border-color: rgba(6, 68, 117, 0.35);
        box-shadow: 0 22px 48px rgba(15, 23, 42, 0.13);
    }

    .spotlight-card:hover::before {
        transform: translateX(110%);
    }

    .spotlight-card:nth-child(2) {
        animation: floatCard 5.2s ease-in-out infinite;
    }

    .lead-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        border-radius: 999px;
        background: #e8f1fb;
        color: var(--t4d-primary);
        padding: 0.32rem 0.65rem;
        font-size: 0.74rem;
        font-weight: 850;
    }

    .spotlight-icon {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        display: grid;
        place-items: center;
        margin: 1rem 0;
        background: linear-gradient(135deg, var(--t4d-primary), #0e7490);
        color: #fff;
        font-size: 1.22rem;
    }

    .spotlight-card h3,
    .signal-panel h3,
    .flow-card h3,
    .feature-card h3 {
        margin: 0 0 0.45rem;
        color: #061224;
        font-size: 1rem;
        font-weight: 850;
    }

    .spotlight-card p,
    .signal-panel p,
    .flow-card p,
    .feature-card p {
        margin: 0;
        color: #64748b;
        font-size: 0.92rem;
        line-height: 1.65;
    }

    .lead-meta-row {
        display: flex;
        flex-wrap: wrap;
        gap: 0.45rem;
        margin-top: 1rem;
    }

    .lead-meta-row span {
        border-radius: 7px;
        background: #f1f5f9;
        color: #475569;
        padding: 0.35rem 0.55rem;
        font-size: 0.76rem;
        font-weight: 750;
    }

    .signal-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(320px, 0.82fr);
        gap: 1rem;
        margin-top: 1.7rem;
    }

    .signal-panel {
        padding: 1.35rem;
    }

    .signal-panel.featured {
        border-top: 5px solid var(--t4d-accent);
    }

    .signal-list {
        display: grid;
        gap: 0.8rem;
        margin-top: 1.1rem;
    }

    .signal-item {
        display: grid;
        grid-template-columns: 44px 1fr;
        gap: 0.8rem;
        align-items: start;
        border: 1px solid #dbe3ef;
        border-radius: 8px;
        background: #f8fafc;
        padding: 0.9rem;
        transition: transform 0.2s ease, background 0.2s ease;
    }

    .signal-item:hover {
        transform: translateX(6px);
        background: #fff;
    }

    .signal-item span {
        width: 44px;
        height: 44px;
        border-radius: 8px;
        display: grid;
        place-items: center;
        background: #edf5fc;
        color: var(--t4d-primary);
        font-size: 1.1rem;
    }

    .signal-item strong {
        display: block;
        color: #061224;
        margin-bottom: 0.12rem;
    }

    .signal-item small {
        color: #64748b;
        line-height: 1.55;
    }

    .live-radar {
        min-height: 100%;
        border-radius: 10px;
        background:
            radial-gradient(circle at center, rgba(245, 130, 32, 0.22), transparent 18%),
            radial-gradient(circle at center, rgba(6, 68, 117, 0.18), transparent 42%),
            linear-gradient(135deg, #061f49, #075985);
        display: grid;
        place-items: center;
        padding: 1.4rem;
        color: #fff;
        text-align: center;
        overflow: hidden;
    }

    .radar-ring {
        position: relative;
        width: min(270px, 72vw);
        aspect-ratio: 1;
        border-radius: 50%;
        border: 1px solid rgba(255, 255, 255, 0.24);
        display: grid;
        place-items: center;
    }

    .radar-ring::before,
    .radar-ring::after {
        content: "";
        position: absolute;
        inset: 18%;
        border-radius: 50%;
        border: 1px solid rgba(255, 255, 255, 0.18);
    }

    .radar-ring::after {
        inset: 34%;
    }

    .radar-core {
        position: relative;
        z-index: 1;
    }

    .radar-core strong {
        display: block;
        color: #fff;
        font-size: 2.4rem;
        line-height: 1;
    }

    .radar-core span {
        display: block;
        margin-top: 0.45rem;
        color: rgba(255, 255, 255, 0.78);
        font-weight: 750;
    }

    .flow-band {
        background:
            linear-gradient(135deg, rgba(6, 68, 117, 0.96), rgba(10, 83, 125, 0.92)),
            url('https://images.unsplash.com/photo-1494412685616-a5d310fbb07d?auto=format&fit=crop&w=1800&q=80') center/cover no-repeat;
        color: #fff;
    }

    .flow-band .latest-kicker {
        background: rgba(255, 255, 255, 0.13);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.22);
    }

    .flow-band .latest-section-title,
    .flow-band .latest-section-copy {
        color: #fff;
    }

    .flow-band .latest-section-copy {
        color: rgba(255, 255, 255, 0.78);
    }

    .flow-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1rem;
        margin-top: 1.8rem;
    }

    .flow-card {
        position: relative;
        min-height: 230px;
        padding: 1.25rem;
        background: rgba(255, 255, 255, 0.96);
        transition: transform 0.22s ease;
    }

    .flow-card:hover {
        transform: translateY(-7px);
    }

    .flow-number {
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

    .feature-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1rem;
        margin-top: 1.6rem;
    }

    .feature-card {
        display: grid;
        grid-template-columns: 48px 1fr;
        gap: 0.9rem;
        align-items: start;
        min-height: 150px;
        padding: 1.15rem;
        transition: transform 0.22s ease, box-shadow 0.22s ease;
    }

    .feature-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 42px rgba(15, 23, 42, 0.12);
    }

    .feature-card .spotlight-icon {
        width: 48px;
        height: 48px;
        margin: 0;
    }

    .latest-cta {
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

    .latest-cta h2 {
        max-width: 790px;
        margin: 0 0 0.6rem;
        color: #fff;
        font-size: clamp(1.6rem, 3.2vw, 2.55rem);
        font-weight: 850;
        line-height: 1.12;
    }

    .latest-cta p {
        max-width: 780px;
        margin: 0;
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.65;
    }

    .latest-cta-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.8rem;
        margin-top: 1.35rem;
    }

    @media (prefers-reduced-motion: reduce) {
        *,
        *::before,
        *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }
    }

    @media (max-width: 991px) {
        .latest-stats,
        .lead-spotlight-grid,
        .flow-grid,
        .feature-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .signal-layout {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 575px) {
        .latest-hero .container {
            min-height: 430px;
        }

        .latest-stats,
        .lead-spotlight-grid,
        .flow-grid,
        .feature-grid {
            grid-template-columns: 1fr;
        }

        .feature-card {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<section class="latest-hero">
    <div class="container py-5">
        <div>
            <span class="latest-eyebrow"><i class="bi bi-lightning-charge"></i> Suppliers Tool Kit</span>
            <h1 class="latest-title">Latest Trade Leads</h1>
            <p class="latest-summary">Review newly published Trade4Deal leads and respond to relevant buyer requirements with speed, context and better timing.</p>
            <div class="latest-actions">
                <a href="{{ route('home') }}#leads" class="btn btn-light fw-bold px-4">Browse Latest Leads</a>
                <button type="button" class="btn btn-ghost-light px-4" data-bs-toggle="modal" data-bs-target="#leadModal">
                    Submit Requirement
                </button>
            </div>
            <div class="latest-stats">
                <div class="latest-stat">
                    <strong>New</strong>
                    <span>Recently published leads</span>
                </div>
                <div class="latest-stat">
                    <strong>Fast</strong>
                    <span>Supplier response timing</span>
                </div>
                <div class="latest-stat">
                    <strong>Smart</strong>
                    <span>Category and country signals</span>
                </div>
                <div class="latest-stat">
                    <strong>Direct</strong>
                    <span>Open details and enquire</span>
                </div>
            </div>
        </div>
    </div>
</section>

<main class="latest-page">
    <section class="py-5">
        <div class="container py-4">
            <span class="latest-kicker"><i class="bi bi-stars"></i> Lead Spotlight</span>
            <h2 class="latest-section-title">Fresh opportunities with quick-read trade signals.</h2>
            <p class="latest-section-copy">Latest leads are designed for supplier teams that need to scan buyer intent quickly and shortlist the right opportunities before competitors move first.</p>

            <div class="lead-spotlight-grid">
                <article class="spotlight-card">
                    <span class="lead-badge"><i class="bi bi-broadcast"></i> Lead Card</span>
                    <span class="spotlight-icon"><i class="bi bi-card-checklist"></i></span>
                    <h3>Lead cards</h3>
                    <p>Each lead card highlights product, company, category, country, trade role and payment method.</p>
                    <div class="lead-meta-row">
                        <span>Product</span>
                        <span>Country</span>
                        <span>Category</span>
                    </div>
                </article>
                <article class="spotlight-card">
                    <span class="lead-badge"><i class="bi bi-person-lines-fill"></i> Supplier Flow</span>
                    <span class="spotlight-icon"><i class="bi bi-kanban"></i></span>
                    <h3>Supplier workflow</h3>
                    <p>Shortlist the right opportunities, open the lead detail and contact the business with context.</p>
                    <div class="lead-meta-row">
                        <span>Shortlist</span>
                        <span>Open</span>
                        <span>Respond</span>
                    </div>
                </article>
                <article class="spotlight-card">
                    <span class="lead-badge"><i class="bi bi-clock-history"></i> Timing</span>
                    <span class="spotlight-icon"><i class="bi bi-lightning-charge"></i></span>
                    <h3>Upgrade when timing matters</h3>
                    <p>Gold access helps suppliers view fresh opportunities as soon as they go live.</p>
                    <div class="lead-meta-row">
                        <span>Instant</span>
                        <span>Visible</span>
                        <span>Actionable</span>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section class="py-5 bg-white">
        <div class="container py-4">
            <span class="latest-kicker"><i class="bi bi-radar"></i> Live Signals</span>
            <h2 class="latest-section-title">Use signals to decide what deserves your response.</h2>
            <p class="latest-section-copy">Suppliers can compare relevance before opening the full lead, then respond with better product and delivery context.</p>

            <div class="signal-layout">
                <article class="signal-panel featured">
                    <h3>What to look for in a latest lead</h3>
                    <p>Every useful lead gives your team a reason to act or skip quickly.</p>
                    <div class="signal-list">
                        <div class="signal-item">
                            <span><i class="bi bi-box-seam"></i></span>
                            <div>
                                <strong>Product fit</strong>
                                <small>Match the requested product with your catalog, MOQ and supply capability.</small>
                            </div>
                        </div>
                        <div class="signal-item">
                            <span><i class="bi bi-geo-alt"></i></span>
                            <div>
                                <strong>Market location</strong>
                                <small>Check country and delivery context before starting a conversation.</small>
                            </div>
                        </div>
                        <div class="signal-item">
                            <span><i class="bi bi-credit-card"></i></span>
                            <div>
                                <strong>Commercial terms</strong>
                                <small>Review payment preference and discuss quantity, sample and shipping details.</small>
                            </div>
                        </div>
                    </div>
                </article>

                <aside class="live-radar">
                    <div class="radar-ring">
                        <div class="radar-core">
                            <strong>24/7</strong>
                            <span>Lead discovery rhythm</span>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    <section class="flow-band py-5">
        <div class="container py-4">
            <span class="latest-kicker"><i class="bi bi-diagram-3"></i> Action Flow</span>
            <h2 class="latest-section-title">From latest lead to focused business enquiry.</h2>
            <p class="latest-section-copy">A simple response flow helps suppliers move fast without losing commercial discipline.</p>

            <div class="flow-grid">
                <article class="flow-card">
                    <span class="flow-number">1</span>
                    <h3>Scan latest leads</h3>
                    <p>Review newly published product interests and buyer requirements on the lead board.</p>
                </article>
                <article class="flow-card">
                    <span class="flow-number">2</span>
                    <h3>Shortlist matches</h3>
                    <p>Compare product, country, category, payment method and business type with your supply capability.</p>
                </article>
                <article class="flow-card">
                    <span class="flow-number">3</span>
                    <h3>Open details</h3>
                    <p>Read the full lead page before deciding what to say and what to verify.</p>
                </article>
                <article class="flow-card">
                    <span class="flow-number">4</span>
                    <h3>Contact with context</h3>
                    <p>Respond with relevant product details, quantity capability, delivery basics and next questions.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container py-4">
            <span class="latest-kicker"><i class="bi bi-gem"></i> Supplier Advantage</span>
            <h2 class="latest-section-title">Why latest leads help active suppliers.</h2>
            <p class="latest-section-copy">Fresh lead visibility supports sales teams that depend on speed, relevance and well-timed outreach.</p>

            <div class="feature-grid">
                <article class="feature-card">
                    <span class="spotlight-icon"><i class="bi bi-stopwatch"></i></span>
                    <div>
                        <h3>Timing advantage</h3>
                        <p>Move early on new opportunities where response speed matters.</p>
                    </div>
                </article>
                <article class="feature-card">
                    <span class="spotlight-icon"><i class="bi bi-bullseye"></i></span>
                    <div>
                        <h3>Sharper targeting</h3>
                        <p>Focus on leads that match your product category and market reach.</p>
                    </div>
                </article>
                <article class="feature-card">
                    <span class="spotlight-icon"><i class="bi bi-chat-dots"></i></span>
                    <div>
                        <h3>Better conversations</h3>
                        <p>Use lead context to begin with useful details instead of cold outreach.</p>
                    </div>
                </article>
                <article class="feature-card">
                    <span class="spotlight-icon"><i class="bi bi-window-stack"></i></span>
                    <div>
                        <h3>Organized pipeline</h3>
                        <p>Turn a lead board into a repeatable supplier opportunity workflow.</p>
                    </div>
                </article>
                <article class="feature-card">
                    <span class="spotlight-icon"><i class="bi bi-graph-up-arrow"></i></span>
                    <div>
                        <h3>Market signals</h3>
                        <p>Notice which products and countries are generating buyer interest.</p>
                    </div>
                </article>
                <article class="feature-card">
                    <span class="spotlight-icon"><i class="bi bi-award"></i></span>
                    <div>
                        <h3>Profile leverage</h3>
                        <p>A complete seller profile makes every lead response more credible.</p>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section class="pb-5">
        <div class="container pb-4">
            <div class="latest-cta">
                <h2>Ready to act on the latest marketplace demand?</h2>
                <p>Browse latest leads, upgrade when timing matters, and keep your supplier profile ready for the next buyer conversation.</p>
                <div class="latest-cta-actions">
                    <a href="{{ route('home') }}#leads" class="btn btn-light fw-bold px-4">Browse Latest Leads</a>
                    <a href="{{ route('plans.index') }}" class="btn btn-ghost-light px-4">View Plans</a>
                    <a href="{{ route('register') }}" class="btn btn-ghost-light px-4">Join Trade4Deal</a>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
