@extends('layouts.marketplace')

@section('title', 'Contact Trade4Deal')

@push('styles')
    <style>
        .contact-hero {
            position: relative;
            overflow: hidden;
            color: #fff;
            background:
                linear-gradient(110deg, rgba(11, 58, 110, 0.95), rgba(14, 116, 144, 0.72)),
                url('https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=1800&q=80') center/cover no-repeat;
            border-bottom: 1px solid var(--t4d-border);
        }

        .contact-hero .container {
            min-height: 420px;
            display: flex;
            align-items: center;
        }

        .contact-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.45rem 0.9rem;
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.13);
            font-size: 0.82rem;
            font-weight: 700;
        }

        .contact-title {
            font-size: clamp(2.25rem, 5vw, 3.5rem);
            font-weight: 800;
            letter-spacing: -0.03em;
            line-height: 1.08;
        }

        .contact-card {
            background: var(--t4d-card);
            border: 1px solid var(--t4d-border);
            border-radius: var(--t4d-radius);
            box-shadow: var(--t4d-shadow);
            height: 100%;
        }

        .contact-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            background: linear-gradient(135deg, var(--t4d-primary), var(--t4d-accent));
            font-size: 1.25rem;
            flex: 0 0 auto;
        }

        .contact-link {
            color: var(--t4d-dark);
            font-weight: 700;
            text-decoration: none;
            overflow-wrap: anywhere;
        }

        .contact-link:hover {
            color: var(--t4d-primary);
        }

        .office-strip {
            position: relative;
            overflow: hidden;
            background:
                radial-gradient(circle at 18% 20%, rgba(14, 116, 144, 0.12), transparent 30%),
                radial-gradient(circle at 84% 78%, rgba(11, 58, 110, 0.12), transparent 28%),
                var(--t4d-surface);
            border-top: 1px solid var(--t4d-border);
            border-bottom: 1px solid var(--t4d-border);
        }

        .office-strip::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(11, 58, 110, 0.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(11, 58, 110, 0.06) 1px, transparent 1px);
            background-size: 42px 42px;
            mask-image: linear-gradient(90deg, transparent, #000 16%, #000 84%, transparent);
            pointer-events: none;
        }

        .office-strip .container {
            position: relative;
            z-index: 1;
        }

        .office-card {
            position: relative;
            min-height: 178px;
            padding: 1.35rem;
            border: 1px solid var(--t4d-border);
            border-radius: 16px;
            background: var(--t4d-card);
            color: var(--t4d-dark);
            box-shadow: var(--t4d-shadow);
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }

        .office-card:hover {
            transform: translateY(-4px);
            border-color: rgba(11, 58, 110, 0.24);
            box-shadow: var(--t4d-shadow-lg);
        }

        .office-card::after {
            content: '';
            position: absolute;
            width: 130px;
            height: 130px;
            right: -48px;
            bottom: -52px;
            border-radius: 50%;
            background: rgba(14, 116, 144, 0.1);
        }

        .office-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.25rem;
        }

        .office-flag {
            width: 54px;
            height: 54px;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            background: linear-gradient(135deg, var(--t4d-primary), var(--t4d-accent));
            font-size: 1.55rem;
            box-shadow: 0 12px 26px rgba(11, 58, 110, 0.22);
        }

        .office-code {
            font-size: 0.75rem;
            font-weight: 800;
            color: var(--t4d-primary);
            background: rgba(11, 58, 110, 0.08);
            border-radius: 999px;
            padding: 0.35rem 0.65rem;
        }

        .office-card h5 {
            font-weight: 800;
            margin-bottom: 0.35rem;
        }

        .office-card p {
            position: relative;
            z-index: 1;
        }

        .contact-form-card {
            background: var(--t4d-card);
            border: 1px solid var(--t4d-border);
            border-radius: var(--t4d-radius);
            box-shadow: var(--t4d-shadow-lg);
        }

        .contact-map {
            min-height: 340px;
            border-radius: var(--t4d-radius);
            overflow: hidden;
            background:
                linear-gradient(145deg, rgba(11, 58, 110, 0.92), rgba(14, 116, 144, 0.76)),
                url('https://images.unsplash.com/photo-1524661135-423995f22d0b?auto=format&fit=crop&w=1400&q=80') center/cover no-repeat;
            color: #fff;
            display: flex;
            align-items: end;
            padding: 2rem;
            box-shadow: var(--t4d-shadow-lg);
        }
    </style>
@endpush

@section('content')
<section class="contact-hero">
    <div class="container py-5">
        <div class="col-lg-8">
            <div class="contact-eyebrow mb-3">
                <i class="bi bi-headset"></i> Trade4Deal Contact
            </div>
            <h1 class="contact-title mb-3">Speak with our global trade team.</h1>
            <p class="hero-lead mb-4">
                Connect with Trade4Deal for buyer inquiries, seller onboarding, marketplace support,
                partnerships, and international B2B opportunities.
            </p>
            <div class="d-flex flex-wrap gap-3">
                <a href="tel:+911204633260" class="btn btn-light fw-semibold px-4">
                    <i class="bi bi-telephone me-2"></i>Call Now
                </a>
                <a href="mailto:info@trade4deal.com" class="btn btn-ghost-light px-4">
                    <i class="bi bi-envelope me-2"></i>Email Us
                </a>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container py-4">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="contact-card p-4">
                    <div class="contact-icon mb-3"><i class="bi bi-telephone"></i></div>
                    <h5 class="fw-bold mb-2">Phone</h5>
                    <a class="contact-link" href="tel:+911204633260">+91 120 463 3260</a>
                    <p class="text-muted small mt-2 mb-0">For sales, onboarding, and marketplace support.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="contact-card p-4">
                    <div class="contact-icon mb-3"><i class="bi bi-envelope"></i></div>
                    <h5 class="fw-bold mb-2">Email</h5>
                    <a class="contact-link" href="mailto:info@trade4deal.com">info@trade4deal.com</a>
                    <p class="text-muted small mt-2 mb-0">Send product, partnership, or account queries.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="contact-card p-4">
                    <div class="contact-icon mb-3"><i class="bi bi-geo-alt"></i></div>
                    <h5 class="fw-bold mb-2">Head Office</h5>
                    <p class="mb-0 fw-semibold">H-119, Sector 63 Noida</p>
                    <p class="text-muted small mb-0">Uttar Pradesh - 201301 - India</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="office-strip py-5">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-5">
                <span class="badge rounded-pill text-bg-primary px-3 py-2 mb-3">Global Presence</span>
                <h2 class="section-title mb-2">One marketplace, three trade hubs</h2>
                <p class="text-muted mb-0">
                    Trade4Deal supports B2B conversations across sourcing, partnerships, and international growth markets.
                </p>
            </div>
            <div class="col-lg-7">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="office-card">
                            <div class="office-top">
                                <div class="office-flag"><i class="bi bi-pin-map"></i></div>
                                <span class="office-code">IN</span>
                            </div>
                            <h5>India</h5>
                            <p class="text-muted small mb-0">Marketplace operations, supplier onboarding, and buyer support.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="office-card">
                            <div class="office-top">
                                <div class="office-flag"><i class="bi bi-globe-europe-africa"></i></div>
                                <span class="office-code">UK</span>
                            </div>
                            <h5>UK</h5>
                            <p class="text-muted small mb-0">Business development for European trade relationships.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="office-card">
                            <div class="office-top">
                                <div class="office-flag"><i class="bi bi-buildings"></i></div>
                                <span class="office-code">UAE</span>
                            </div>
                            <h5>Dubai</h5>
                            <p class="text-muted small mb-0">GCC trade connections, distribution channels, and partnerships.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container py-4">
        <div class="row g-5 align-items-stretch">
            <div class="col-lg-6">
                <div class="contact-form-card p-4 p-lg-5 h-100">
                    <span class="badge rounded-pill text-bg-primary px-3 py-2 mb-3">Write to us</span>
                    <h2 class="section-title mb-3">Tell us what you need</h2>
                    <form action="mailto:info@trade4deal.com" method="POST" enctype="text/plain">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="name">Name</label>
                                <input class="form-control" id="name" name="name" type="text" placeholder="Your name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="company">Company</label>
                                <input class="form-control" id="company" name="company" type="text" placeholder="Company name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="email">Email</label>
                                <input class="form-control" id="email" name="email" type="email" placeholder="you@company.com">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="phone">Phone</label>
                                <input class="form-control" id="phone" name="phone" type="tel" placeholder="+91">
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="message">Message</label>
                                <textarea class="form-control" id="message" name="message" rows="5" placeholder="Share your requirement"></textarea>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary-t4d text-white px-4" type="submit">
                                    <i class="bi bi-send me-2"></i>Send Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="contact-map h-100">
                    <div>
                        <div class="contact-eyebrow mb-3">
                            <i class="bi bi-geo-alt"></i> Head Office
                        </div>
                        <h3 class="fw-bold mb-2">H-119, Sector 63 Noida</h3>
                        <p class="mb-0 opacity-75">Uttar Pradesh - 201301 - India</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
