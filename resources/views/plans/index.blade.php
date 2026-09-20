@extends('layouts.marketplace')

@section('title', 'Plans — Trade4Deal')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h1 class="section-title mb-2">Choose your plan</h1>
            <p class="text-muted mb-0">Unlock verified buyer access, supplier trust signals, and export support.</p>
            @auth
                <p class="mt-2 mb-0">Current plan: <span class="badge {{ auth()->user()->userPlan()->badgeClass() }}">{{ auth()->user()->userPlan()->label() }}</span></p>
            @endauth
        </div>

        @if(session('error'))
            <div class="alert alert-danger border-0 shadow-sm">{{ session('error') }}</div>
        @endif

        <div class="row g-4 justify-content-center">
            <div class="col-md-5">
                <div class="card card-t4d h-100 {{ ($currentPlan ?? \App\Support\Enums\UserPlan::Free)->isGold() ? '' : 'border-primary border-2' }}">
                    <div class="card-body p-4">
                        <div class="text-muted small fw-bold text-uppercase mb-2">Free</div>
                        <h2 class="fw-bold mb-3">$0 <span class="fs-6 text-muted fw-normal">/ month</span></h2>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>Submit business leads</li>
                            <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>View leads after 24h delay</li>
                            <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>Basic marketplace access</li>
                        </ul>
                        @guest
                            <a href="{{ route('register') }}" class="btn btn-outline-t4d w-100">Get started free</a>
                        @else
                            @if(auth()->user()->userPlan()->isGold())
                                <button class="btn btn-outline-secondary w-100" disabled>Included with upgrade</button>
                            @else
                                <button class="btn btn-outline-t4d w-100" disabled>Current plan</button>
                            @endif
                        @endguest
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card card-t4d h-100 border-warning border-2 shadow">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="text-warning small fw-bold text-uppercase">Gold</div>
                            <span class="badge bg-warning text-dark">Popular</span>
                        </div>
                        <h2 class="fw-bold mb-3">$174 <span class="fs-6 text-muted fw-normal">/ half year</span></h2>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i><strong>Instant</strong> verified buyer details</li>
                            <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>Verified supplier tag mark by Trade4Deal</li>
                            <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>Weekly 14 verified buy leads</li>
                            <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>Relationship manager support</li>
                            <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>Export business exposure</li>
                        </ul>
                        @auth
                            @if(auth()->user()->userPlan()->isGold())
                                <button class="btn btn-warning w-100 fw-semibold" disabled>You're on Gold</button>
                            @else
                                <form method="POST" action="{{ route('plans.gold.checkout') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-primary-t4d text-white w-100 py-2">
                                        <i class="bi bi-star-fill me-1"></i> Upgrade to Gold
                                    </button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary-t4d text-white w-100">Sign in to upgrade</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
