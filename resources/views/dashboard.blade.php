<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
            <div>
                <h1 class="h4 fw-bold mb-1">Welcome back, {{ Auth::user()->name }}</h1>
                <p class="text-muted small mb-0">
                    {{ Auth::user()->company_name ?? 'Your company' }} · {{ ucfirst(Auth::user()->user_type?->value ?? 'member') }}
                </p>
            </div>
            <a href="{{ route('home') }}" class="btn btn-primary-t4d text-white btn-sm">
                <i class="bi bi-shop me-1"></i> View Marketplace
            </a>
        </div>
    </x-slot>

    <div class="container">
        <div class="alert alert-success border-0 shadow-sm mb-4">
            <i class="bi bi-check-circle-fill me-2"></i>
            You're logged in to Trade4Deal.
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card card-t4d h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Membership</div>
                        <div class="fw-bold fs-5">
                            <span class="badge {{ Auth::user()->userPlan()->badgeClass() }}">{{ Auth::user()->userPlan()->label() }}</span>
                        </div>
                        @unless(Auth::user()->userPlan()->isGold())
                            <a href="{{ route('plans.index') }}" class="small text-primary text-decoration-none">Upgrade to Gold</a>
                        @endunless
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-t4d h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Account type</div>
                        <div class="fw-bold fs-5">{{ Auth::user()->user_type?->label() ?? 'Member' }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-t4d h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Country</div>
                        <div class="fw-bold fs-5">{{ Auth::user()->country ?? '—' }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-t4d h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Email</div>
                        <div class="fw-bold text-truncate">{{ Auth::user()->email }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-t4d">
            <div class="card-body p-4">
                <h2 class="h5 fw-bold mb-2">Quick actions</h2>
                <p class="text-muted small mb-3">Continue growing your global trade network.</p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('home') }}" class="btn btn-primary-t4d text-white">Submit a Lead</a>
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-t4d">Edit Profile</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
