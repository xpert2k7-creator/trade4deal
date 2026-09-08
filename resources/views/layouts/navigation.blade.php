<nav class="navbar navbar-expand-lg navbar-t4d sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center py-1" href="{{ route('home') }}">
            <x-brand-logo :height="48" />
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#appNav" aria-controls="appNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="appNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') || request()->routeIs('seller.*') ? 'active fw-semibold' : '' }}" href="{{ route('dashboard') }}">
                        Dashboard
                    </a>
                </li>
                @if(Auth::user()->isSeller())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('seller.products.*') ? 'active fw-semibold' : '' }}" href="{{ route('seller.products.index') }}">
                            Products
                        </a>
                    </li>
                    @if(Auth::user()->slug)
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('sellers.show', Auth::user()->slug) }}" target="_blank">My page</a>
                        </li>
                    @endif
                @endif
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}#leads">Leads</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('plans.*') ? 'active fw-semibold' : '' }}" href="{{ route('plans.index') }}">Plans</a>
                </li>
            </ul>

            <ul class="navbar-nav align-items-lg-center gap-lg-2">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">Log Out</button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
