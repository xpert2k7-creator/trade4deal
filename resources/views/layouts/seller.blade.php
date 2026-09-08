<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Seller') — Trade4Deal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --t4d-primary: #0B3A6E;
            --t4d-primary-dark: #082F58;
            --t4d-accent: #0E7490;
            --t4d-orange: #F58220;
            --t4d-dark: #0F172A;
            --t4d-muted: #64748B;
            --t4d-border: #E2E8F0;
            --t4d-bg: #F4F7FB;
            --t4d-card: #FFFFFF;
            --t4d-radius: 14px;
            --t4d-font: 'Open Sans', system-ui, sans-serif;
            --t4d-shadow: 0 4px 18px rgba(15, 23, 42, 0.05);
        }
        * { box-sizing: border-box; }
        body {
            font-family: var(--t4d-font);
            background: var(--t4d-bg);
            color: var(--t4d-dark);
            min-height: 100vh;
            margin: 0;
        }
        .seller-shell {
            display: grid;
            grid-template-columns: 260px 1fr;
            min-height: 100vh;
        }
        .seller-sidebar {
            background: linear-gradient(180deg, #0B3A6E 0%, #082F58 100%);
            color: #fff;
            padding: 1.35rem 1.1rem;
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            height: 100vh;
        }
        .seller-sidebar .logo-wrap {
            padding: 0.2rem 0.45rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.12);
            margin-bottom: 1.1rem;
        }
        .seller-sidebar .logo-wrap img {
            height: 42px;
            width: auto;
            max-width: 100%;
            object-fit: contain;
            filter: brightness(0) invert(1);
        }
        .seller-nav {
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
            flex: 1;
        }
        .seller-nav a {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            padding: 0.7rem 0.85rem;
            border-radius: 10px;
            color: rgba(255,255,255,0.78);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: background .15s ease, color .15s ease;
        }
        .seller-nav a:hover { background: rgba(255,255,255,0.1); color: #fff; }
        .seller-nav a.active { background: rgba(255,255,255,0.16); color: #fff; }
        .seller-nav a i { font-size: 1.05rem; opacity: .9; }
        .seller-user {
            border-top: 1px solid rgba(255,255,255,0.12);
            padding-top: 1rem;
            margin-top: 1rem;
        }
        .seller-user .name { font-weight: 700; font-size: 0.9rem; }
        .seller-user .role { font-size: 0.75rem; color: rgba(255,255,255,0.65); }
        .seller-main { min-width: 0; display: flex; flex-direction: column; }
        .seller-topbar {
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--t4d-border);
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            position: sticky;
            top: 0;
            z-index: 20;
        }
        .seller-topbar h1 {
            margin: 0;
            font-size: 1.15rem;
            font-weight: 700;
        }
        .seller-content { padding: 1.35rem 1.5rem 2.25rem; flex: 1; }
        .panel {
            background: var(--t4d-card);
            border: 1px solid var(--t4d-border);
            border-radius: var(--t4d-radius);
            box-shadow: var(--t4d-shadow);
            overflow: hidden;
        }
        .panel-header {
            padding: 1rem 1.2rem;
            border-bottom: 1px solid var(--t4d-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .panel-header h2 { margin: 0; font-size: 1rem; font-weight: 700; }
        .panel-body { padding: 1.2rem; }
        .kpi {
            background: var(--t4d-card);
            border: 1px solid var(--t4d-border);
            border-radius: var(--t4d-radius);
            padding: 1.1rem 1.2rem;
            height: 100%;
            box-shadow: var(--t4d-shadow);
        }
        .kpi .label {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: .04em;
            text-transform: uppercase;
            color: var(--t4d-muted);
            margin-bottom: .3rem;
        }
        .kpi .value {
            font-size: 1.7rem;
            font-weight: 800;
            letter-spacing: -.03em;
            color: var(--t4d-primary);
            line-height: 1.1;
        }
        .btn-primary-t4d {
            background: var(--t4d-primary);
            border: none;
            border-radius: 10px;
            font-weight: 600;
            color: #fff;
        }
        .btn-primary-t4d:hover { background: var(--t4d-primary-dark); color: #fff; }
        .btn-outline-t4d {
            border: 1.5px solid #CBD5E1;
            border-radius: 10px;
            font-weight: 600;
            color: var(--t4d-dark);
            background: #fff;
        }
        .btn-outline-t4d:hover { border-color: var(--t4d-primary); color: var(--t4d-primary); }
        .form-control, .form-select {
            border-radius: 10px;
            border: 1.5px solid #CBD5E1;
            background-color: #E8EEF5 !important;
            color: #0F172A !important;
            padding: .65rem .85rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: #0B3A6E !important;
            box-shadow: 0 0 0 3px rgba(11,58,110,.18) !important;
            background-color: #E8EEF5 !important;
        }
        .form-label { font-weight: 600; color: var(--t4d-dark); font-size: .9rem; }
        .table-seller { margin: 0; }
        .table-seller thead th {
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: var(--t4d-muted);
            font-weight: 700;
            border-bottom-color: var(--t4d-border);
            white-space: nowrap;
        }
        .table-seller td {
            vertical-align: middle;
            border-color: var(--t4d-border);
            font-size: .92rem;
        }
        .progress-t4d {
            height: 8px;
            border-radius: 999px;
            background: #E2E8F0;
        }
        .progress-t4d .bar {
            height: 100%;
            border-radius: 999px;
            background: linear-gradient(90deg, #0E7490, #0B3A6E);
        }
        @media (max-width: 991px) {
            .seller-shell { grid-template-columns: 1fr; }
            .seller-sidebar {
                position: relative;
                height: auto;
                padding-bottom: .85rem;
            }
            .seller-nav {
                flex-direction: row;
                flex-wrap: wrap;
            }
            .seller-content { padding: 1rem; }
            .seller-topbar { padding: .85rem 1rem; }
        }
    </style>
    @stack('styles')
</head>
<body>
<div class="seller-shell">
    <aside class="seller-sidebar">
        <div class="logo-wrap">
            <a href="{{ route('seller.dashboard') }}" class="d-inline-block text-decoration-none">
                <img src="{{ asset('images/trade4deal-logo.jpg') }}" alt="Trade4Deal">
            </a>
            <div class="small mt-2" style="opacity:.7;letter-spacing:.06em;text-transform:uppercase;font-size:.68rem;font-weight:700;">
                Seller Hub
            </div>
        </div>
        <nav class="seller-nav">
            <a href="{{ route('seller.dashboard') }}" class="{{ request()->routeIs('seller.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i> Overview
            </a>
            <a href="{{ route('seller.profile.edit') }}" class="{{ request()->routeIs('seller.profile.*') ? 'active' : '' }}">
                <i class="bi bi-building"></i> Company Profile
            </a>
            <a href="{{ route('seller.products.index') }}" class="{{ request()->routeIs('seller.products.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i> Products
            </a>
            @if(auth()->user()->slug)
                <a href="{{ route('sellers.show', auth()->user()->slug) }}" target="_blank">
                    <i class="bi bi-box-arrow-up-right"></i> Public page
                </a>
            @endif
            <a href="{{ route('home') }}">
                <i class="bi bi-shop"></i> Marketplace
            </a>
        </nav>
        <div class="seller-user">
            <div class="name">{{ auth()->user()->name }}</div>
            <div class="role mb-2">{{ auth()->user()->company_name ?? 'Seller' }}</div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-light w-100">Logout</button>
            </form>
        </div>
    </aside>

    <div class="seller-main">
        <header class="seller-topbar">
            <h1>@yield('page-title', 'Seller Dashboard')</h1>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                @yield('topbar-actions')
            </div>
        </header>
        <div class="seller-content">
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
