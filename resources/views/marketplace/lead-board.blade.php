@extends('layouts.marketplace')

@section('title', 'Lead Board - Trade4Deal')

@push('styles')
<style>
    @keyframes boardPulse {
        0%, 100% { transform: scale(1); opacity: 0.55; }
        50% { transform: scale(1.28); opacity: 1; }
    }

    @keyframes cardDrift {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }

    .board-hero {
        position: relative;
        overflow: hidden;
        color: #fff;
        background:
            linear-gradient(105deg, rgba(5, 28, 64, 0.96), rgba(6, 68, 117, 0.86) 48%, rgba(245, 130, 32, 0.42)),
            url('https://images.unsplash.com/photo-1494412685616-a5d310fbb07d?auto=format&fit=crop&w=1900&q=82') center/cover no-repeat;
    }

    .board-hero .container {
        min-height: 520px;
        display: grid;
        align-items: center;
    }

    .board-eyebrow,
    .board-kicker {
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

    .board-eyebrow {
        border: 1px solid rgba(255, 255, 255, 0.24);
        background: rgba(255, 255, 255, 0.13);
    }

    .board-kicker {
        background: #e8f1fb;
        color: var(--t4d-primary);
    }

    .board-title {
        max-width: 930px;
        margin: 1rem 0;
        color: #fff;
        font-size: clamp(2.4rem, 5vw, 4.15rem);
        font-weight: 850;
        line-height: 1.04;
    }

    .board-summary {
        max-width: 840px;
        color: rgba(255, 255, 255, 0.86);
        font-size: 1.06rem;
        line-height: 1.75;
    }

    .board-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.8rem;
        margin-top: 1.45rem;
    }

    .board-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 0.8rem;
        max-width: 960px;
        margin-top: 1.6rem;
    }

    .board-stat {
        position: relative;
        min-height: 96px;
        border: 1px solid rgba(255, 255, 255, 0.18);
        border-radius: 8px;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.11);
        backdrop-filter: blur(8px);
        overflow: hidden;
        transition: transform 0.24s ease, background 0.24s ease;
    }

    .board-stat:hover {
        transform: translateY(-5px);
        background: rgba(255, 255, 255, 0.16);
    }

    .board-stat::after {
        content: "";
        position: absolute;
        top: 14px;
        right: 14px;
        width: 9px;
        height: 9px;
        border-radius: 50%;
        background: var(--t4d-accent);
        box-shadow: 0 0 0 6px rgba(245, 130, 32, 0.18);
        animation: boardPulse 1.9s ease-in-out infinite;
    }

    .board-stat strong {
        display: block;
        color: #fff;
        font-size: 1.35rem;
        line-height: 1.1;
    }

    .board-stat span {
        display: block;
        margin-top: 0.35rem;
        color: rgba(255, 255, 255, 0.72);
        font-size: 0.78rem;
        font-weight: 750;
    }

    .board-page {
        background: #f5f7fb;
    }

    .board-section-title {
        max-width: 820px;
        margin: 0.8rem 0 0;
        color: #061224;
        font-size: clamp(1.75rem, 3.3vw, 2.65rem);
        font-weight: 850;
        line-height: 1.12;
    }

    .board-section-copy {
        max-width: 820px;
        margin: 0.85rem 0 0;
        color: #64748b;
        line-height: 1.7;
    }

    .board-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1rem;
        margin-top: 1.7rem;
    }

    .lead-column,
    .insight-panel,
    .flow-card,
    .feature-card {
        border: 1px solid #dbe3ef;
        border-radius: 10px;
        background: #fff;
        box-shadow: 0 14px 34px rgba(15, 23, 42, 0.07);
    }

    .lead-column {
        min-height: 430px;
        padding: 1rem;
        transition: transform 0.24s ease, box-shadow 0.24s ease;
    }

    .lead-column:hover {
        transform: translateY(-7px);
        box-shadow: 0 22px 48px rgba(15, 23, 42, 0.13);
    }

    .lead-column:nth-child(2) {
        animation: cardDrift 5.5s ease-in-out infinite;
    }

    .column-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.8rem;
        margin-bottom: 1rem;
    }

    .column-head h3 {
        margin: 0;
        color: #061224;
        font-size: 1rem;
        font-weight: 850;
    }

    .column-count {
        border-radius: 999px;
        background: #e8f1fb;
        color: var(--t4d-primary);
        padding: 0.28rem 0.6rem;
        font-size: 0.76rem;
        font-weight: 850;
    }

    .lead-mini-card {
        position: relative;
        overflow: hidden;
        border: 1px solid #dbe3ef;
        border-radius: 9px;
        background: #f8fafc;
        padding: 0.95rem;
        margin-bottom: 0.8rem;
        transition: transform 0.22s ease, background 0.22s ease, border-color 0.22s ease;
    }

    .lead-mini-card::before {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(110deg, transparent 0%, rgba(245, 130, 32, 0.12) 48%, transparent 78%);
        transform: translateX(-120%);
        transition: transform 0.55s ease;
        pointer-events: none;
    }

    .lead-mini-card:hover {
        transform: translateX(7px);
        background: #fff;
        border-color: rgba(6, 68, 117, 0.28);
    }

    .lead-mini-card:hover::before {
        transform: translateX(120%);
    }

    .lead-mini-card strong {
        display: block;
        color: #061224;
        font-size: 0.95rem;
        margin-bottom: 0.45rem;
    }

    .lead-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.38rem;
    }

    .lead-tags span {
        border-radius: 7px;
        background: #eef4fb;
        color: #475569;
        padding: 0.26rem 0.5rem;
        font-size: 0.73rem;
        font-weight: 750;
    }

    .match-score {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.8rem;
        margin-top: 0.85rem;
        color: #64748b;
        font-size: 0.78rem;
        font-weight: 800;
    }

    .score-bar {
        flex: 1;
        height: 7px;
        border-radius: 999px;
        background: #e2e8f0;
        overflow: hidden;
    }

    .score-bar span {
        display: block;
        height: 100%;
        border-radius: inherit;
        background: linear-gradient(90deg, var(--t4d-primary), var(--t4d-accent));
    }

    .insight-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(320px, 0.82fr);
        gap: 1rem;
        margin-top: 1.7rem;
    }

    .insight-panel {
        padding: 1.35rem;
    }

    .insight-panel.featured {
        border-top: 5px solid var(--t4d-accent);
    }

    .insight-panel h3,
    .flow-card h3,
    .feature-card h3 {
        margin: 0 0 0.45rem;
        color: #061224;
        font-size: 1rem;
        font-weight: 850;
    }

    .insight-panel p,
    .flow-card p,
    .feature-card p {
        margin: 0;
        color: #64748b;
        font-size: 0.92rem;
        line-height: 1.65;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.75rem;
        margin-top: 1.1rem;
    }

    .filter-chip {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        border: 1px solid #dbe3ef;
        border-radius: 8px;
        background: #f8fafc;
        padding: 0.85rem;
        color: #475569;
        font-weight: 800;
        transition: transform 0.2s ease, background 0.2s ease;
    }

    .filter-chip:hover {
        transform: translateY(-5px);
        background: #fff;
    }

    .filter-chip i {
        color: var(--t4d-primary);
        font-size: 1.08rem;
    }

    .board-visual {
        min-height: 100%;
        border-radius: 10px;
        background:
            radial-gradient(circle at 25% 20%, rgba(245, 130, 32, 0.24), transparent 18%),
            radial-gradient(circle at 80% 72%, rgba(14, 116, 144, 0.32), transparent 24%),
            linear-gradient(135deg, #061f49, #075985);
        padding: 1.25rem;
        display: grid;
        align-content: center;
        gap: 0.75rem;
    }

    .floating-ticket {
        border: 1px solid rgba(255, 255, 255, 0.22);
        border-radius: 9px;
        background: rgba(255, 255, 255, 0.12);
        color: #fff;
        padding: 0.9rem;
        backdrop-filter: blur(8px);
        animation: cardDrift 4.8s ease-in-out infinite;
    }

    .floating-ticket:nth-child(2) {
        animation-delay: 0.5s;
    }

    .floating-ticket:nth-child(3) {
        animation-delay: 1s;
    }

    .floating-ticket strong {
        display: block;
        color: #fff;
        margin-bottom: 0.3rem;
    }

    .floating-ticket span {
        color: rgba(255, 255, 255, 0.76);
        font-size: 0.82rem;
    }

    .flow-band {
        background:
            linear-gradient(135deg, rgba(6, 68, 117, 0.96), rgba(10, 83, 125, 0.92)),
            url('https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=1800&q=80') center/cover no-repeat;
        color: #fff;
    }

    .flow-band .board-kicker {
        background: rgba(255, 255, 255, 0.13);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.22);
    }

    .flow-band .board-section-title,
    .flow-band .board-section-copy {
        color: #fff;
    }

    .flow-band .board-section-copy {
        color: rgba(255, 255, 255, 0.78);
    }

    .flow-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1rem;
        margin-top: 1.8rem;
    }

    .flow-card {
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

    .feature-icon {
        width: 48px;
        height: 48px;
        border-radius: 8px;
        display: grid;
        place-items: center;
        background: linear-gradient(135deg, var(--t4d-primary), #0e7490);
        color: #fff;
        font-size: 1.12rem;
    }

    .board-cta {
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

    .board-cta h2 {
        max-width: 790px;
        margin: 0 0 0.6rem;
        color: #fff;
        font-size: clamp(1.6rem, 3.2vw, 2.55rem);
        font-weight: 850;
        line-height: 1.12;
    }

    .board-cta p {
        max-width: 780px;
        margin: 0;
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.65;
    }

    .board-cta-actions {
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
        .board-stats,
        .board-grid,
        .flow-grid,
        .feature-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .insight-layout {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 575px) {
        .board-hero .container {
            min-height: 430px;
        }

        .board-stats,
        .board-grid,
        .flow-grid,
        .feature-grid,
        .filter-grid {
            grid-template-columns: 1fr;
        }

        .feature-card {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<section class="board-hero">
    <div class="container py-5">
        <div>
            <span class="board-eyebrow"><i class="bi bi-kanban"></i> Suppliers Tool Kit</span>
            <h1 class="board-title">Lead Board</h1>
            <p class="board-summary">Use the Trade4Deal lead board to scan opportunities, identify matching product needs, and connect with interested companies.</p>
            <div class="board-actions">
                <a href="{{ route('home') }}#leads" class="btn btn-light fw-bold px-4">View Lead Board</a>
                <button type="button" class="btn btn-ghost-light px-4" data-bs-toggle="modal" data-bs-target="#leadModal">
                    Submit Requirement
                </button>
            </div>
            <div class="board-stats">
                <div class="board-stat">
                    <strong>Scan</strong>
                    <span>Compare lead cards</span>
                </div>
                <div class="board-stat">
                    <strong>Match</strong>
                    <span>Find product fit</span>
                </div>
                <div class="board-stat">
                    <strong>Open</strong>
                    <span>View detail pages</span>
                </div>
                <div class="board-stat">
                    <strong>Contact</strong>
                    <span>Start enquiries</span>
                </div>
            </div>
        </div>
    </div>
</section>

<main class="board-page">
    <section class="py-5">
        <div class="container py-4">
            <span class="board-kicker"><i class="bi bi-columns-gap"></i> Board View</span>
            <h2 class="board-section-title">A kanban-style way to scan marketplace opportunities.</h2>
            <p class="board-section-copy">Lead Board gives suppliers a quick way to compare products, countries, categories and payment terms before opening the full lead detail page.</p>

            <div class="board-grid">
                <article class="lead-column">
                    <div class="column-head">
                        <h3>Fresh Leads</h3>
                        <span class="column-count">New</span>
                    </div>
                    <div class="lead-mini-card">
                        <strong>Packaging Material Required</strong>
                        <div class="lead-tags">
                            <span>Buyer</span>
                            <span>India</span>
                            <span>Packaging</span>
                        </div>
                        <div class="match-score">
                            <span>Match</span>
                            <div class="score-bar"><span style="width: 82%"></span></div>
                            <span>82%</span>
                        </div>
                    </div>
                    <div class="lead-mini-card">
                        <strong>Industrial Fasteners Inquiry</strong>
                        <div class="lead-tags">
                            <span>RFQ</span>
                            <span>UAE</span>
                            <span>Machinery</span>
                        </div>
                        <div class="match-score">
                            <span>Match</span>
                            <div class="score-bar"><span style="width: 74%"></span></div>
                            <span>74%</span>
                        </div>
                    </div>
                </article>

                <article class="lead-column">
                    <div class="column-head">
                        <h3>Best Matches</h3>
                        <span class="column-count">Hot</span>
                    </div>
                    <div class="lead-mini-card">
                        <strong>Textile Fabric Bulk Order</strong>
                        <div class="lead-tags">
                            <span>Buyer</span>
                            <span>UK</span>
                            <span>Textiles</span>
                        </div>
                        <div class="match-score">
                            <span>Match</span>
                            <div class="score-bar"><span style="width: 91%"></span></div>
                            <span>91%</span>
                        </div>
                    </div>
                    <div class="lead-mini-card">
                        <strong>Electronics Component Need</strong>
                        <div class="lead-tags">
                            <span>RFQ</span>
                            <span>USA</span>
                            <span>Electronics</span>
                        </div>
                        <div class="match-score">
                            <span>Match</span>
                            <div class="score-bar"><span style="width: 88%"></span></div>
                            <span>88%</span>
                        </div>
                    </div>
                </article>

                <article class="lead-column">
                    <div class="column-head">
                        <h3>Ready to Contact</h3>
                        <span class="column-count">Action</span>
                    </div>
                    <div class="lead-mini-card">
                        <strong>Construction Material Supply</strong>
                        <div class="lead-tags">
                            <span>Supplier</span>
                            <span>Qatar</span>
                            <span>Construction</span>
                        </div>
                        <div class="match-score">
                            <span>Match</span>
                            <div class="score-bar"><span style="width: 79%"></span></div>
                            <span>79%</span>
                        </div>
                    </div>
                    <div class="lead-mini-card">
                        <strong>Medical Consumables Buyer</strong>
                        <div class="lead-tags">
                            <span>Buyer</span>
                            <span>Kenya</span>
                            <span>Medical</span>
                        </div>
                        <div class="match-score">
                            <span>Match</span>
                            <div class="score-bar"><span style="width: 86%"></span></div>
                            <span>86%</span>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section class="py-5 bg-white">
        <div class="container py-4">
            <span class="board-kicker"><i class="bi bi-funnel"></i> Scan Faster</span>
            <h2 class="board-section-title">Filter the right leads before spending time on details.</h2>
            <p class="board-section-copy">Use business signals to shortlist opportunities that fit your catalog, market reach and response capacity.</p>

            <div class="insight-layout">
                <article class="insight-panel featured">
                    <h3>Lead board filters</h3>
                    <p>Compare opportunities through practical B2B signals instead of opening every lead blindly.</p>
                    <div class="filter-grid">
                        <div class="filter-chip"><i class="bi bi-tags"></i> Category</div>
                        <div class="filter-chip"><i class="bi bi-geo-alt"></i> Country</div>
                        <div class="filter-chip"><i class="bi bi-person-lines-fill"></i> Trade Role</div>
                        <div class="filter-chip"><i class="bi bi-credit-card"></i> Payment</div>
                        <div class="filter-chip"><i class="bi bi-box-seam"></i> Product</div>
                        <div class="filter-chip"><i class="bi bi-clock-history"></i> Freshness</div>
                    </div>
                </article>

                <aside class="board-visual">
                    <div class="floating-ticket">
                        <strong>Lead Detail</strong>
                        <span>Open full requirement and contact action</span>
                    </div>
                    <div class="floating-ticket">
                        <strong>Supplier Fit</strong>
                        <span>Compare against your catalog and capability</span>
                    </div>
                    <div class="floating-ticket">
                        <strong>Next Step</strong>
                        <span>Respond with product and delivery context</span>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    <section class="flow-band py-5">
        <div class="container py-4">
            <span class="board-kicker"><i class="bi bi-diagram-3"></i> Board Workflow</span>
            <h2 class="board-section-title">From board scan to relevant business contact.</h2>
            <p class="board-section-copy">Lead Board works best as a simple repeatable workflow for active supplier teams.</p>

            <div class="flow-grid">
                <article class="flow-card">
                    <span class="flow-number">1</span>
                    <h3>Scan faster</h3>
                    <p>Grid cards make it easier to compare products, countries, categories and payment terms.</p>
                </article>
                <article class="flow-card">
                    <span class="flow-number">2</span>
                    <h3>Open details</h3>
                    <p>Each opportunity has a detail page with contact action and business information.</p>
                </article>
                <article class="flow-card">
                    <span class="flow-number">3</span>
                    <h3>Find matches</h3>
                    <p>Suppliers can compare leads with their industries and product catalog.</p>
                </article>
                <article class="flow-card">
                    <span class="flow-number">4</span>
                    <h3>Contact clearly</h3>
                    <p>Respond with relevant product details, quantity ability and next questions.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container py-4">
            <span class="board-kicker"><i class="bi bi-gem"></i> Supplier Benefits</span>
            <h2 class="board-section-title">What the Lead Board helps suppliers do better.</h2>
            <p class="board-section-copy">A focused board makes opportunity review faster, more organized and easier to turn into useful enquiries.</p>

            <div class="feature-grid">
                <article class="feature-card">
                    <span class="feature-icon"><i class="bi bi-speedometer2"></i></span>
                    <div>
                        <h3>Faster review</h3>
                        <p>Scan relevant buyer needs without jumping through scattered pages.</p>
                    </div>
                </article>
                <article class="feature-card">
                    <span class="feature-icon"><i class="bi bi-bullseye"></i></span>
                    <div>
                        <h3>Better fit</h3>
                        <p>Compare leads against products, category and market capability.</p>
                    </div>
                </article>
                <article class="feature-card">
                    <span class="feature-icon"><i class="bi bi-window-stack"></i></span>
                    <div>
                        <h3>Organized pipeline</h3>
                        <p>Use board-style columns to structure review and response priority.</p>
                    </div>
                </article>
                <article class="feature-card">
                    <span class="feature-icon"><i class="bi bi-send-check"></i></span>
                    <div>
                        <h3>Direct action</h3>
                        <p>Open details and contact businesses when the opportunity fits.</p>
                    </div>
                </article>
                <article class="feature-card">
                    <span class="feature-icon"><i class="bi bi-graph-up-arrow"></i></span>
                    <div>
                        <h3>Market signals</h3>
                        <p>Notice product categories and countries generating demand.</p>
                    </div>
                </article>
                <article class="feature-card">
                    <span class="feature-icon"><i class="bi bi-shield-check"></i></span>
                    <div>
                        <h3>Careful follow-up</h3>
                        <p>Review context and verify details before any transaction decision.</p>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section class="pb-5">
        <div class="container pb-4">
            <div class="board-cta">
                <h2>Ready to turn lead scanning into supplier action?</h2>
                <p>Open the Trade4Deal lead board, compare active opportunities and respond where your product catalog fits the requirement.</p>
                <div class="board-cta-actions">
                    <a href="{{ route('home') }}#leads" class="btn btn-light fw-bold px-4">View Lead Board</a>
                    <button type="button" class="btn btn-ghost-light px-4" data-bs-toggle="modal" data-bs-target="#leadModal">
                        Submit Requirement
                    </button>
                    <a href="{{ route('plans.index') }}" class="btn btn-ghost-light px-4">View Plans</a>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
