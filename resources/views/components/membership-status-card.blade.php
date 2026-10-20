@props(['user'])

@php
    $plan = $user->userPlan();
    $isGold = $plan->isGold();
    $expiresAt = $isGold ? $user->gold_expires_at : null;
    $validityLabel = null;
    if ($expiresAt) {
        $daysLeft = max(0, (int) now()->diffInDays($expiresAt, false));
        $validityLabel = 'Valid until '.$expiresAt->format('d M Y').' · '.$daysLeft.' days left';
    }
@endphp

<style>
    .t4d-membership-card {
        position: relative;
        overflow: hidden;
        height: 100%;
        border-radius: 16px;
        padding: 1.1rem 1.15rem;
    }
    .t4d-membership-card.is-gold {
        border: 1px solid rgba(245, 158, 11, .45);
        background:
            radial-gradient(circle at 92% 8%, rgba(255,255,255,.35), transparent 42%),
            linear-gradient(135deg, #92400e 0%, #d97706 52%, #f58220 100%);
        color: #fff;
        box-shadow: 0 14px 30px rgba(217, 119, 6, .25);
    }
    .t4d-membership-card.is-free {
        border: 1px solid var(--t4d-border, #e2e8f0);
        background: linear-gradient(180deg, #ffffff, #f8fafc);
        color: var(--t4d-dark, #0f172a);
        box-shadow: 0 8px 22px rgba(15, 23, 42, .06);
    }
    .t4d-membership-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: .75rem;
        margin-bottom: .8rem;
    }
    .t4d-membership-icon {
        display: grid;
        place-items: center;
        width: 46px;
        height: 46px;
        border-radius: 14px;
        font-size: 1.3rem;
        flex: 0 0 auto;
    }
    .is-gold .t4d-membership-icon { background: rgba(255,255,255,.2); border: 1px solid rgba(255,255,255,.3); color: #fff; }
    .is-free .t4d-membership-icon { background: #e8f1fb; color: var(--t4d-primary, #064475); }
    .t4d-membership-status {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        padding: .28rem .6rem;
        border-radius: 999px;
        font-size: .68rem;
        font-weight: 800;
        letter-spacing: .04em;
        text-transform: uppercase;
        white-space: nowrap;
    }
    .is-gold .t4d-membership-status { background: rgba(255,255,255,.22); color: #fff; }
    .is-free .t4d-membership-status { background: #eef2f7; color: #64748b; }
    .t4d-membership-title {
        margin: 0;
        font-size: 1.15rem;
        font-weight: 800;
        line-height: 1.25;
    }
    .t4d-membership-copy {
        margin: .35rem 0 0;
        font-size: .84rem;
        line-height: 1.55;
        opacity: .92;
    }
    .t4d-membership-meta {
        display: flex;
        align-items: center;
        gap: .4rem;
        margin-top: .8rem;
        font-size: .8rem;
        font-weight: 700;
    }
    .t4d-membership-cta {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .4rem;
        width: 100%;
        margin-top: 1rem;
        padding: .62rem 1rem;
        border-radius: 10px;
        font-size: .88rem;
        font-weight: 800;
        text-decoration: none;
        transition: transform .15s ease, filter .15s ease;
    }
    .t4d-membership-cta:hover { transform: translateY(-1px); filter: brightness(1.05); }
    .is-gold .t4d-membership-cta { background: #fff; color: #b45309; }
    .is-free .t4d-membership-cta { background: linear-gradient(135deg, var(--t4d-accent, #f58220), var(--t4d-accent-dark, #d96a0b)); color: #fff; }
</style>

<div {{ $attributes->merge(['class' => 't4d-membership-card '.($isGold ? 'is-gold' : 'is-free')]) }}>
    <div class="t4d-membership-top">
        <span class="t4d-membership-icon" aria-hidden="true">
            <i class="bi {{ $isGold ? 'bi-award-fill' : 'bi-person-badge' }}"></i>
        </span>
        <span class="t4d-membership-status">
            <i class="bi {{ $isGold ? 'bi-patch-check-fill' : 'bi-circle' }}"></i>
            {{ $isGold ? 'Active' : 'Free Plan' }}
        </span>
    </div>

    @if ($isGold)
        <h3 class="t4d-membership-title"><i class="bi bi-star-fill me-1"></i>Gold Member</h3>
        <p class="t4d-membership-copy">You have instant access to verified buyer leads and Gold member benefits.</p>
        @if ($validityLabel)
            <div class="t4d-membership-meta">
                <i class="bi bi-calendar-check"></i>
                <span>{{ $validityLabel }}</span>
            </div>
        @endif
        <a href="{{ route('plans.index') }}" class="t4d-membership-cta">
            <i class="bi bi-arrow-repeat"></i> Renew Gold
        </a>
    @else
        <h3 class="t4d-membership-title">Free Member</h3>
        <p class="t4d-membership-copy">Upgrade to Gold for instant verified leads, a verified supplier badge, and priority support.</p>
        <a href="{{ route('plans.index') }}" class="t4d-membership-cta">
            <i class="bi bi-star-fill"></i> Get Gold Plan
        </a>
    @endif
</div>
