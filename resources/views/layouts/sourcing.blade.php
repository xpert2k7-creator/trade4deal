<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sourcing') — Trade4Deal</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    @include('layouts.partials.staff-panel-styles')
    @stack('styles')
</head>
<body>
<div class="emp-shell">
    <aside class="emp-sidebar">
        <div class="logo-wrap">
            <a href="{{ route('sourcing.dashboard') }}" class="d-inline-block text-decoration-none">
                <img src="{{ asset('images/trade4deal-logo.jpg') }}" alt="Trade4Deal">
            </a>
            <div class="small mt-2" style="opacity:0.7;letter-spacing:0.06em;text-transform:uppercase;font-size:0.68rem;font-weight:700;">
                Sourcing Panel
            </div>
        </div>

        <nav class="emp-nav">
            <a href="{{ route('sourcing.dashboard') }}" class="{{ request()->routeIs('sourcing.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i> Overview
            </a>
            <a href="{{ route('sourcing.leads.create') }}" class="{{ request()->routeIs('sourcing.leads.create') ? 'active' : '' }}">
                <i class="bi bi-plus-circle"></i> Add buy lead
            </a>
            <a href="{{ route('sourcing.leads.index', ['status' => 'pending']) }}" class="{{ request()->routeIs('sourcing.leads.index') ? 'active' : '' }}">
                <i class="bi bi-inbox"></i> My submissions
            </a>
        </nav>

        <div class="emp-user">
            <div class="name">{{ auth()->user()->name }}</div>
            <div class="role mb-2">{{ auth()->user()->user_type?->label() ?? 'Sourcing' }}</div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-light w-100">Logout</button>
            </form>
        </div>
    </aside>

    <div class="emp-main">
        <header class="emp-topbar">
            <h1>@yield('page-title', 'Sourcing Dashboard')</h1>
            <div class="d-flex align-items-center gap-2">
                @yield('topbar-actions')
            </div>
        </header>

        <div class="emp-content">
            @if (session('success'))
                <div class="alert alert-success border-0 shadow-sm mb-3">
                    <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger border-0 shadow-sm mb-3">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
