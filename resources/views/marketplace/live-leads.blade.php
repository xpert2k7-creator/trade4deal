@extends('layouts.marketplace')

@section('title', 'Live Business Leads - Trade4Deal')

@push('styles')
<style>
    .leads-hero {
        position: relative;
        overflow: hidden;
        color: #fff;
        background:
            linear-gradient(105deg, rgba(5, 28, 64, 0.96), rgba(6, 68, 117, 0.86) 48%, rgba(245, 130, 32, 0.42)),
            url('https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=1900&q=82') center/cover no-repeat;
    }

    .leads-hero .container {
        min-height: 510px;
        display: grid;
        align-items: center;
    }

    .leads-eyebrow,
    .leads-kicker {
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

    .leads-eyebrow {
        border: 1px solid rgba(255, 255, 255, 0.24);
        background: rgba(255, 255, 255, 0.13);
    }

    .leads-kicker {
        background: #e8f1fb;
        color: var(--t4d-primary);
    }

    .leads-title {
        max-width: 930px;
        margin: 1rem 0;
        color: #fff;
        font-size: clamp(2.4rem, 5vw, 4.1rem);
        font-weight: 850;
        line-height: 1.05;
    }

    .leads-summary {
        max-width: 830px;
        color: rgba(255, 255, 255, 0.86);
        font-size: 1.06rem;
        line-height: 1.75;
    }

    .leads-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.8rem;
        margin-top: 1.45rem;
    }

    .leads-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 0.8rem;
        max-width: 950px;
        margin-top: 1.6rem;
    }

    .leads-stat {
        min-height: 96px;
        border: 1px solid rgba(255, 255, 255, 0.18);
        border-radius: 8px;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.11);
        backdrop-filter: blur(8px);
    }

    .leads-stat strong {
        display: block;
        color: #fff;
        font-size: 1.35rem;
        line-height: 1.1;
    }

    .leads-stat span {
        display: block;
        margin-top: 0.35rem;
        color: rgba(255, 255, 255, 0.72);
        font-size: 0.78rem;
        font-weight: 750;
    }

    .leads-page {
        background: #f5f7fb;
    }

    .leads-section-title {
        max-width: 800px;
        margin: 0.8rem 0 0;
        color: #061224;
        font-size: clamp(1.75rem, 3.3vw, 2.65rem);
        font-weight: 850;
        line-height: 1.12;
    }

    .leads-section-copy {
        max-width: 800px;
        margin: 0.85rem 0 0;
        color: #64748b;
        line-height: 1.7;
    }

    .lead-grid,
    .benefit-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1rem;
        margin-top: 1.6rem;
    }

    .lead-card,
    .lead-panel,
    .process-card,
    .benefit-card {
        border: 1px solid #dbe3ef;
        border-radius: 10px;
        background: #fff;
        box-shadow: 0 14px 34px rgba(15, 23, 42, 0.07);
    }

    .lead-card {
        min-height: 255px;
        padding: 1.25rem;
    }

    .lead-icon {
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

    .lead-card h3,
    .lead-panel h3,
    .process-card h3,
    .benefit-card h3 {
        margin: 0 0 0.45rem;
        color: #061224;
        font-size: 1rem;
        font-weight: 850;
    }

    .lead-card p,
    .lead-panel p,
    .process-card p,
    .benefit-card p {
        margin: 0;
        color: #64748b;
        font-size: 0.92rem;
        line-height: 1.65;
    }

    .lead-card ul {
        display: grid;
        gap: 0.45rem;
        margin: 1rem 0 0;
        padding-left: 1.1rem;
        color: #64748b;
        font-size: 0.9rem;
        line-height: 1.55;
    }

    .lead-board-preview {
        display: grid;
        grid-template-columns: minmax(0, 0.92fr) minmax(0, 1.08fr);
        gap: 1rem;
        align-items: stretch;
        margin-top: 1.6rem;
    }

    .lead-panel {
        padding: 1.35rem;
        overflow: hidden;
    }

    .lead-panel.featured {
        border-top: 5px solid var(--t4d-accent);
    }

    .mock-lead-list {
        display: grid;
        gap: 0.8rem;
        margin-top: 1.1rem;
    }

    .mock-lead {
        border: 1px solid #dbe3ef;
        border-radius: 8px;
        background: #f8fafc;
        padding: 0.95rem;
    }

    .mock-lead-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.8rem;
        margin-bottom: 0.5rem;
    }

    .mock-lead-top strong {
        color: #061224;
        font-size: 0.95rem;
    }

    .mock-lead-top span {
        border-radius: 999px;
        background: #e8f1fb;
        color: var(--t4d-primary);
        padding: 0.22rem 0.55rem;
        font-size: 0.72rem;
        font-weight: 850;
        white-space: nowrap;
    }

    .mock-lead-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 0.45rem;
        color: #64748b;
        font-size: 0.82rem;
    }

    .visibility-list {
        display: grid;
        gap: 0.85rem;
        margin-top: 1.1rem;
    }

    .visibility-item {
        display: grid;
        grid-template-columns: 44px 1fr;
        gap: 0.8rem;
        align-items: start;
        border: 1px solid #dbe3ef;
        border-radius: 8px;
        background: #f8fafc;
        padding: 0.9rem;
    }

    .visibility-item span {
        width: 44px;
        height: 44px;
        border-radius: 8px;
        display: grid;
        place-items: center;
        background: #edf5fc;
        color: var(--t4d-primary);
        font-size: 1.1rem;
    }

    .visibility-item strong {
        display: block;
        color: #061224;
        margin-bottom: 0.12rem;
    }

    .visibility-item small {
        color: #64748b;
        line-height: 1.55;
    }

    .process-band {
        background:
            linear-gradient(135deg, rgba(6, 68, 117, 0.96), rgba(10, 83, 125, 0.92)),
            url('https://images.unsplash.com/photo-1494412685616-a5d310fbb07d?auto=format&fit=crop&w=1800&q=80') center/cover no-repeat;
        color: #fff;
    }

    .process-band .leads-kicker {
        background: rgba(255, 255, 255, 0.13);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.22);
    }

    .process-band .leads-section-title,
    .process-band .leads-section-copy {
        color: #fff;
    }

    .process-band .leads-section-copy {
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

    .benefit-card .lead-icon {
        width: 48px;
        height: 48px;
        margin: 0;
    }

    .leads-cta {
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

    .leads-cta h2 {
        max-width: 790px;
        margin: 0 0 0.6rem;
        color: #fff;
        font-size: clamp(1.6rem, 3.2vw, 2.55rem);
        font-weight: 850;
        line-height: 1.12;
    }

    .leads-cta p {
        max-width: 780px;
        margin: 0;
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.65;
    }

    .leads-cta-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.8rem;
        margin-top: 1.35rem;
    }

    @media (max-width: 991px) {
        .leads-stats,
        .lead-grid,
        .process-grid,
        .benefit-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .lead-board-preview {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 575px) {
        .leads-hero .container {
            min-height: 430px;
        }

        .leads-stats,
        .lead-grid,
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
<section class="leads-hero">
    <div class="container py-5">
        <div>
            <span class="leads-eyebrow"><i class="bi bi-broadcast"></i> Marketplace Leads</span>
            <h1 class="leads-title">Live Business Leads</h1>
            <p class="leads-summary">Discover active buyer and supplier opportunities published on Trade4Deal after review. Browse product interest, country, category, business role and contact actions from one focused lead board.</p>
            <div class="leads-actions">
                <a href="{{ route('home') }}#leads" class="btn btn-light fw-bold px-4">View Leads</a>
                <button type="button" class="btn btn-ghost-light px-4" data-bs-toggle="modal" data-bs-target="#leadModal">
                    Submit Requirement
                </button>
            </div>
            <div class="leads-stats">
                <div class="leads-stat">
                    <strong>Fresh</strong>
                    <span>Reviewed trade opportunities</span>
                </div>
                <div class="leads-stat">
                    <strong>Gold</strong>
                    <span>Instant lead visibility</span>
                </div>
                <div class="leads-stat">
                    <strong>Global</strong>
                    <span>Buyer and supplier reach</span>
                </div>
                <div class="leads-stat">
                    <strong>Direct</strong>
                    <span>Lead detail contact action</span>
                </div>
            </div>
        </div>
    </div>
</section>

<main class="leads-page">
    <section class="py-5">
        <div class="container py-4">
            <span class="leads-kicker"><i class="bi bi-radar"></i> Lead Discovery</span>
            <h2 class="leads-section-title">A practical board for active B2B opportunities.</h2>
            <p class="leads-section-copy">Live leads help suppliers and buyers see marketplace intent faster. Each lead is structured so businesses can quickly decide whether the opportunity is worth opening.</p>

            <div class="lead-grid">
                <article class="lead-card">
                    <span class="lead-icon"><i class="bi bi-lightning-charge"></i></span>
                    <h3>Fresh business opportunities</h3>
                    <p>Live leads include the product interest, country, category, trade role, payment method and contact options.</p>
                    <ul>
                        <li>Product requirement context</li>
                        <li>Buyer or supplier role</li>
                        <li>Country and category signals</li>
                    </ul>
                </article>
                <article class="lead-card">
                    <span class="lead-icon"><i class="bi bi-eye"></i></span>
                    <h3>Plan-based visibility</h3>
                    <p>Gold members can see new leads instantly. Free accounts see eligible leads after the configured visibility delay.</p>
                    <ul>
                        <li>Instant access for Gold users</li>
                        <li>Delayed free visibility</li>
                        <li>Clear opportunity timing</li>
                    </ul>
                </article>
                <article class="lead-card">
                    <span class="lead-icon"><i class="bi bi-send-check"></i></span>
                    <h3>Direct action</h3>
                    <p>Each lead page gives visitors a clear way to view details and contact the business with relevant context.</p>
                    <ul>
                        <li>Open lead details</li>
                        <li>Review business information</li>
                        <li>Start a focused enquiry</li>
                    </ul>
                </article>
            </div>
        </div>
    </section>

    <section class="py-5 bg-white">
        <div class="container py-4">
            <span class="leads-kicker"><i class="bi bi-kanban"></i> Lead Board Preview</span>
            <h2 class="leads-section-title">Scan the right details before you respond.</h2>
            <p class="leads-section-copy">Trade4Deal lead cards are designed for quick comparison, so suppliers can identify suitable buyer requirements and buyers can discover relevant supplier opportunities.</p>

            <div class="lead-board-preview">
                <article class="lead-panel featured">
                    <h3>Example lead signals</h3>
                    <p>Real lead cards may include product, category, country, role, payment preference and enquiry path.</p>
                    <div class="mock-lead-list">
                        <div class="mock-lead">
                            <div class="mock-lead-top">
                                <strong>Industrial Bearings Required</strong>
                                <span>Buyer Lead</span>
                            </div>
                            <div class="mock-lead-meta">
                                <span><i class="bi bi-geo-alt"></i> UAE</span>
                                <span><i class="bi bi-tags"></i> Machinery</span>
                                <span><i class="bi bi-credit-card"></i> Bank Transfer</span>
                            </div>
                        </div>
                        <div class="mock-lead">
                            <div class="mock-lead-top">
                                <strong>Textile Supplier Available</strong>
                                <span>Supplier Lead</span>
                            </div>
                            <div class="mock-lead-meta">
                                <span><i class="bi bi-geo-alt"></i> India</span>
                                <span><i class="bi bi-tags"></i> Textiles</span>
                                <span><i class="bi bi-box"></i> Bulk MOQ</span>
                            </div>
                        </div>
                        <div class="mock-lead">
                            <div class="mock-lead-top">
                                <strong>Electronics Components Inquiry</strong>
                                <span>RFQ</span>
                            </div>
                            <div class="mock-lead-meta">
                                <span><i class="bi bi-geo-alt"></i> UK</span>
                                <span><i class="bi bi-tags"></i> Electronics</span>
                                <span><i class="bi bi-clock"></i> Urgent</span>
                            </div>
                        </div>
                    </div>
                </article>

                <aside class="lead-panel">
                    <h3>Visibility and response tips</h3>
                    <p>Use lead details carefully and respond only where your business can genuinely support the requirement.</p>
                    <div class="visibility-list">
                        <div class="visibility-item">
                            <span><i class="bi bi-person-check"></i></span>
                            <div>
                                <strong>Complete your profile</strong>
                                <small>Buyers respond better when supplier details, products and contact data are clear.</small>
                            </div>
                        </div>
                        <div class="visibility-item">
                            <span><i class="bi bi-chat-dots"></i></span>
                            <div>
                                <strong>Reply with context</strong>
                                <small>Mention product fit, quantity capability, delivery basics and next questions.</small>
                            </div>
                        </div>
                        <div class="visibility-item">
                            <span><i class="bi bi-shield-check"></i></span>
                            <div>
                                <strong>Verify before transaction</strong>
                                <small>Confirm company, product, payment, delivery and legal details independently.</small>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    <section class="process-band py-5">
        <div class="container py-4">
            <span class="leads-kicker"><i class="bi bi-diagram-3"></i> Lead Process</span>
            <h2 class="leads-section-title">How live leads move through Trade4Deal.</h2>
            <p class="leads-section-copy">The process keeps buyer requirements and supplier opportunities structured before they become visible on the marketplace.</p>

            <div class="process-grid">
                <article class="process-card">
                    <span class="process-number">1</span>
                    <h3>Submit requirement</h3>
                    <p>A buyer or business user submits product interest, category, country and message details.</p>
                </article>
                <article class="process-card">
                    <span class="process-number">2</span>
                    <h3>Review and publish</h3>
                    <p>Submitted leads are reviewed before they appear publicly in the marketplace lead board.</p>
                </article>
                <article class="process-card">
                    <span class="process-number">3</span>
                    <h3>Discover and compare</h3>
                    <p>Suppliers and buyers scan product, country, payment and business role signals.</p>
                </article>
                <article class="process-card">
                    <span class="process-number">4</span>
                    <h3>Contact with context</h3>
                    <p>Relevant businesses open the lead detail page and start a focused enquiry.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container py-4">
            <span class="leads-kicker"><i class="bi bi-gem"></i> Lead Benefits</span>
            <h2 class="leads-section-title">Why live leads matter for trade teams.</h2>
            <p class="leads-section-copy">Live leads help sourcing and sales teams spend less time guessing and more time responding to visible marketplace demand.</p>

            <div class="benefit-grid">
                <article class="benefit-card">
                    <span class="lead-icon"><i class="bi bi-bullseye"></i></span>
                    <div>
                        <h3>Better targeting</h3>
                        <p>Focus on leads that match your category, location and supply capability.</p>
                    </div>
                </article>
                <article class="benefit-card">
                    <span class="lead-icon"><i class="bi bi-clock-history"></i></span>
                    <div>
                        <h3>Faster response</h3>
                        <p>Gold visibility helps teams respond early when timing matters.</p>
                    </div>
                </article>
                <article class="benefit-card">
                    <span class="lead-icon"><i class="bi bi-card-checklist"></i></span>
                    <div>
                        <h3>Clear context</h3>
                        <p>Lead cards show useful details before you open a full opportunity.</p>
                    </div>
                </article>
                <article class="benefit-card">
                    <span class="lead-icon"><i class="bi bi-people"></i></span>
                    <div>
                        <h3>More conversations</h3>
                        <p>Turn visible demand into direct buyer-supplier communication.</p>
                    </div>
                </article>
                <article class="benefit-card">
                    <span class="lead-icon"><i class="bi bi-window-stack"></i></span>
                    <div>
                        <h3>Organized workflow</h3>
                        <p>Use a structured lead board instead of scattered enquiry tracking.</p>
                    </div>
                </article>
                <article class="benefit-card">
                    <span class="lead-icon"><i class="bi bi-graph-up-arrow"></i></span>
                    <div>
                        <h3>Growth signal</h3>
                        <p>Understand what products and markets are creating new interest.</p>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section class="pb-5">
        <div class="container pb-4">
            <div class="leads-cta">
                <h2>Ready to explore live trade opportunities?</h2>
                <p>Browse the Trade4Deal lead board, submit your own requirement, or create a supplier profile so relevant buyers can understand your business.</p>
                <div class="leads-cta-actions">
                    <a href="{{ route('home') }}#leads" class="btn btn-light fw-bold px-4">View Leads</a>
                    <button type="button" class="btn btn-ghost-light px-4" data-bs-toggle="modal" data-bs-target="#leadModal">
                        Submit Requirement
                    </button>
                    <a href="{{ route('register') }}" class="btn btn-ghost-light px-4">Register Free</a>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
