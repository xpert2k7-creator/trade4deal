@php
    $isDeclined = $user->status === \App\Support\Enums\RecordStatus::Inactive;
    $supportEmail = config('trade4deal.contact_email', 'info@trade4deal.com');
@endphp

<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="h4 fw-bold mb-1">
                {{ $isDeclined ? 'Seller access not approved' : 'We’re reviewing your seller profile' }}
            </h1>
            <p class="text-muted small mb-0">
                {{ $user->company_name ?? 'Your company' }} · Seller account
            </p>
        </div>
    </x-slot>

    <style>
        .t4d-pending-wrap {
            max-width: 760px;
            margin: 0 auto;
        }
        .t4d-pending-hero {
            position: relative;
            overflow: hidden;
            border-radius: 20px;
            border: 1px solid var(--t4d-border);
            background:
                radial-gradient(circle at 12% 18%, rgba(14, 116, 144, 0.12), transparent 42%),
                radial-gradient(circle at 88% 12%, rgba(245, 130, 32, 0.16), transparent 38%),
                linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            box-shadow: 0 12px 36px rgba(15, 23, 42, 0.06);
            padding: 2rem 1.75rem 1.75rem;
        }
        .t4d-pending-icon {
            width: 64px;
            height: 64px;
            border-radius: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            margin-bottom: 1rem;
        }
        .t4d-pending-icon.is-review {
            background: rgba(14, 116, 144, 0.12);
            color: #0E7490;
        }
        .t4d-pending-icon.is-declined {
            background: rgba(100, 116, 139, 0.12);
            color: #475569;
        }
        .t4d-status-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            border-radius: 999px;
            padding: 0.35rem 0.75rem;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            text-transform: uppercase;
        }
        .t4d-status-chip.is-review {
            background: rgba(14, 116, 144, 0.1);
            color: #0E7490;
        }
        .t4d-status-chip.is-declined {
            background: rgba(100, 116, 139, 0.12);
            color: #475569;
        }
        .t4d-pending-steps {
            display: grid;
            gap: 0.85rem;
        }
        .t4d-pending-step {
            display: flex;
            gap: 0.85rem;
            align-items: flex-start;
            padding: 0.95rem 1rem;
            border-radius: 14px;
            border: 1px solid var(--t4d-border);
            background: #fff;
        }
        .t4d-pending-step .step-num {
            width: 28px;
            height: 28px;
            border-radius: 999px;
            background: #0B3A6E;
            color: #fff;
            font-size: 0.78rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 1px;
        }
        .t4d-support-card {
            border-radius: 16px;
            border: 1px solid rgba(11, 58, 110, 0.12);
            background: linear-gradient(135deg, rgba(11, 58, 110, 0.04), rgba(14, 116, 144, 0.06));
            padding: 1.15rem 1.2rem;
        }
        @media (max-width: 576px) {
            .t4d-pending-hero { padding: 1.5rem 1.15rem; }
        }
    </style>

    <div class="container py-4 t4d-pending-wrap">
        <div class="t4d-pending-hero mb-3">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                <div class="t4d-pending-icon {{ $isDeclined ? 'is-declined' : 'is-review' }}">
                    <i class="bi {{ $isDeclined ? 'bi-x-circle' : 'bi-shield-check' }}"></i>
                </div>
                <span class="t4d-status-chip {{ $isDeclined ? 'is-declined' : 'is-review' }}">
                    <i class="bi {{ $isDeclined ? 'bi-slash-circle' : 'bi-hourglass-split' }}"></i>
                    {{ $isDeclined ? 'Declined' : 'Under review' }}
                </span>
            </div>

            @if ($isDeclined)
                <h2 class="h4 fw-bold mb-2" style="letter-spacing:-0.02em;">
                    Your seller application was declined
                </h2>
                <p class="text-muted mb-0" style="line-height:1.65;max-width:52ch;">
                    Seller tools and your public storefront are not available on this account.
                    If you believe this is a mistake or need help, please contact support.
                </p>
            @else
                <h2 class="h4 fw-bold mb-2" style="letter-spacing:-0.02em;">
                    Welcome aboard, {{ $user->name }}
                </h2>
                <p class="text-muted mb-0" style="line-height:1.65;max-width:58ch;">
                    Thank you for joining Trade4Deal. Your seller profile is in our onboarding queue.
                    We’re verifying a few business details so buyers meet trusted suppliers. You’ll get full seller access as soon as you’re approved.
                </p>
            @endif
        </div>

        @unless ($isDeclined)
            <div class="card card-t4d border-0 mb-3">
                <div class="card-body p-4">
                    <h3 class="h6 fw-bold mb-3">What happens next</h3>
                    <div class="t4d-pending-steps">
                        <div class="t4d-pending-step">
                            <span class="step-num">1</span>
                            <div>
                                <div class="fw-semibold">We review your company profile</div>
                                <div class="small text-muted">Our sales team checks your registration details so the marketplace stays reliable for everyone.</div>
                            </div>
                        </div>
                        <div class="t4d-pending-step">
                            <span class="step-num">2</span>
                            <div>
                                <div class="fw-semibold">You may get a short follow-up</div>
                                <div class="small text-muted">If we need anything extra, we’ll reach out on your registered email. Most profiles are cleared smoothly.</div>
                            </div>
                        </div>
                        <div class="t4d-pending-step">
                            <span class="step-num">3</span>
                            <div>
                                <div class="fw-semibold">Your seller dashboard goes live</div>
                                <div class="small text-muted">Once approved, you can manage products, respond to buyers, and publish your company page.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endunless

        <div class="t4d-support-card mb-4">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div>
                    <div class="fw-bold mb-1">
                        <i class="bi bi-headset me-1" style="color:#0B3A6E;"></i>
                        {{ $isDeclined ? 'Contact support' : 'Need help or have a question?' }}
                    </div>
                    <p class="small text-muted mb-0" style="line-height:1.55;">
                        @if ($isDeclined)
                            Our team can explain the decision and guide you on next steps.
                        @else
                            Our support team is happy to help while your account is being reviewed.
                        @endif
                    </p>
                    <div class="small mt-2">
                        <a href="mailto:{{ $supportEmail }}" class="fw-semibold text-decoration-none" style="color:#0B3A6E;">
                            {{ $supportEmail }}
                        </a>
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-2 flex-shrink-0">
                    <a href="{{ route('contact') }}" class="btn btn-primary-t4d text-white btn-sm">
                        <i class="bi bi-chat-dots me-1"></i> Contact support
                    </a>
                    <a href="mailto:{{ $supportEmail }}" class="btn btn-outline-t4d btn-sm">
                        <i class="bi bi-envelope me-1"></i> Email us
                    </a>
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
            <a href="{{ route('home') }}" class="btn btn-outline-t4d btn-sm">
                <i class="bi bi-shop me-1"></i> Browse marketplace
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-link btn-sm text-muted text-decoration-none">Logout</button>
            </form>
        </div>
    </div>
</x-app-layout>
