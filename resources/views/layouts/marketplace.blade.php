<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Trade4Deal — Global B2B Marketplace connecting buyers and sellers worldwide.">
    <title>@yield('title', config('app.name', 'Trade4Deal'))</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        /* Trade4Deal brand theme — Soft light + Deep navy (permanent) */
        :root {
            --t4d-primary: #0B3A6E;
            --t4d-primary-dark: #082F58;
            --t4d-accent: #0E7490;
            --t4d-dark: #0F172A;
            --t4d-muted: #64748B;
            --t4d-border: #E2E8F0;
            --t4d-bg: #F7F8FA;
            --t4d-surface: #FFFFFF;
            --t4d-card: #FFFFFF;
            --t4d-radius: 16px;
            --t4d-shadow: 0 4px 24px rgba(15, 23, 42, 0.06);
            --t4d-shadow-lg: 0 20px 50px rgba(15, 23, 42, 0.10);
            --t4d-font: 'Open Sans', system-ui, -apple-system, sans-serif;
        }

        [data-bs-theme="dark"] {
            --t4d-primary: #3B82F6;
            --t4d-primary-dark: #2563EB;
            --t4d-accent: #22D3EE;
            --t4d-dark: #F8FAFC;
            --t4d-muted: #94A3B8;
            --t4d-border: #334155;
            --t4d-bg: #0B1220;
            --t4d-surface: #111827;
            --t4d-card: #1E293B;
            --t4d-shadow: 0 4px 24px rgba(0, 0, 0, 0.35);
            --t4d-shadow-lg: 0 20px 50px rgba(0, 0, 0, 0.45);
        }

        body {
            font-family: var(--t4d-font);
            background: var(--t4d-bg);
            color: var(--t4d-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .page-shell {
            flex: 1 0 auto;
            display: flex;
            flex-direction: column;
        }

        .page-shell > main,
        .page-main {
            flex: 1 0 auto;
        }

        h1, h2, h3, h4, h5, h6, .hero-title, .section-title, .brand-logo {
            font-family: var(--t4d-font);
        }

        .navbar-t4d {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--t4d-border);
        }

        [data-bs-theme="dark"] .navbar-t4d {
            background: rgba(11, 18, 32, 0.92);
        }

        .navbar-t4d .nav-link {
            color: var(--t4d-muted) !important;
            font-weight: 500;
        }

        .navbar-t4d .nav-link:hover,
        .navbar-t4d .nav-link.active {
            color: var(--t4d-primary) !important;
        }

        .brand-logo {
            font-weight: 800;
            font-size: 1.4rem;
            letter-spacing: -0.03em;
            color: var(--t4d-primary);
        }

        .brand-logo span { color: var(--t4d-dark); }

        .hero-section {
            position: relative;
            min-height: 88vh;
            display: flex;
            align-items: center;
            overflow: hidden;
            border-bottom: 1px solid var(--t4d-border);
        }

        .hero-bg {
            position: absolute;
            inset: 0;
            background:
                linear-gradient(105deg, rgba(11, 58, 110, 0.92) 0%, rgba(8, 47, 88, 0.85) 45%, rgba(14, 116, 144, 0.55) 100%),
                url('https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=2000&q=80') center/cover no-repeat;
            z-index: 0;
        }

        .hero-section .container { position: relative; z-index: 1; }

        .hero-badge {
            background: rgba(255, 255, 255, 0.14);
            color: #fff;
            font-weight: 600;
            font-size: 0.8rem;
            padding: 0.45rem 0.95rem;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.22);
        }

        .hero-title {
            font-size: clamp(2.3rem, 5.5vw, 3.6rem);
            font-weight: 700;
            letter-spacing: -0.03em;
            line-height: 1.12;
            color: #fff;
        }

        .hero-title em {
            font-style: normal;
            color: #A5F3FC;
            font-weight: 600;
        }

        .hero-lead {
            color: rgba(255, 255, 255, 0.82);
            font-size: 1.05rem;
            max-width: 34rem;
        }

        .btn-primary-t4d {
            background: var(--t4d-primary);
            border: none;
            border-radius: 10px;
            font-weight: 600;
            padding: 0.7rem 1.35rem;
            color: #fff;
            transition: transform 0.15s ease, background 0.15s ease;
        }

        .btn-primary-t4d:hover {
            background: var(--t4d-primary-dark);
            transform: translateY(-1px);
            color: #fff;
        }

        .btn-ghost-light {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.28);
            color: #fff;
            border-radius: 10px;
            font-weight: 600;
        }

        .btn-ghost-light:hover {
            background: rgba(255, 255, 255, 0.22);
            color: #fff;
        }

        .btn-outline-t4d {
            border: 1px solid var(--t4d-border);
            border-radius: 10px;
            font-weight: 600;
            color: var(--t4d-dark);
            background: var(--t4d-card);
        }

        .btn-outline-t4d:hover {
            border-color: var(--t4d-primary);
            color: var(--t4d-primary);
            background: #EFF6FF;
        }

        .hero-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-top: 2.5rem;
        }

        @media (max-width: 767px) {
            .hero-stats { grid-template-columns: repeat(2, 1fr); }
        }

        .hero-stat {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 14px;
            padding: 1rem 1.1rem;
            color: #fff;
        }

        .hero-stat .value {
            font-size: 1.55rem;
            font-weight: 800;
            line-height: 1.2;
        }

        .hero-stat .label {
            font-size: 0.78rem;
            opacity: 0.8;
        }

        .section-title {
            font-size: clamp(1.75rem, 3vw, 2.25rem);
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--t4d-dark);
        }

        .infographic-step {
            position: relative;
            text-align: center;
            padding: 1.5rem 1rem;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .infographic-step .step-visual {
            position: relative;
            width: 88px;
            height: 88px;
            margin: 0 auto 1.25rem;
            flex-shrink: 0;
        }

        .infographic-step .step-icon {
            width: 88px;
            height: 88px;
            border-radius: 22px;
            background: var(--t4d-card);
            border: 1px solid var(--t4d-border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.85rem;
            color: var(--t4d-primary);
            box-shadow: var(--t4d-shadow);
            margin: 0;
        }

        .infographic-step .step-num {
            position: absolute;
            top: -10px;
            right: -10px;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--t4d-primary), var(--t4d-accent));
            color: #fff;
            font-weight: 800;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 16px rgba(11, 58, 110, 0.3);
            border: 3px solid var(--t4d-bg);
            margin: 0;
            z-index: 2;
            line-height: 1;
        }

        .infographic-step h5 {
            margin-bottom: 0.5rem;
            color: var(--t4d-dark);
        }

        .infographic-step p { max-width: 220px; }

        .infographic-connector {
            position: absolute;
            top: 44px;
            left: calc(50% + 52px);
            width: calc(100% - 104px);
            height: 2px;
            background: linear-gradient(90deg, var(--t4d-primary), var(--t4d-accent));
            opacity: 0.35;
            z-index: 0;
            pointer-events: none;
        }

        @media (max-width: 991px) {
            .infographic-connector { display: none !important; }
        }

        .category-card {
            position: relative;
            border-radius: var(--t4d-radius);
            overflow: hidden;
            height: 220px;
            box-shadow: var(--t4d-shadow);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            background: var(--t4d-card);
        }

        .category-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--t4d-shadow-lg);
        }

        .category-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .category-card .overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(11, 58, 110, 0.88) 0%, rgba(11, 58, 110, 0.15) 60%);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 1.25rem;
            color: #fff;
        }

        .category-card .overlay h5 {
            font-weight: 700;
            margin-bottom: 0.15rem;
        }

        .feature-visual {
            border-radius: var(--t4d-radius);
            overflow: hidden;
            box-shadow: var(--t4d-shadow-lg);
            position: relative;
        }

        .feature-visual img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            min-height: 340px;
        }

        .feature-float {
            position: absolute;
            background: var(--t4d-card);
            border: 1px solid var(--t4d-border);
            border-radius: 14px;
            padding: 0.85rem 1rem;
            box-shadow: var(--t4d-shadow-lg);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--t4d-dark);
        }

        .feature-float .icon-box {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: #fff;
            background: var(--t4d-primary) !important;
        }

        .card-t4d {
            background: var(--t4d-card);
            border: 1px solid var(--t4d-border);
            border-radius: var(--t4d-radius);
            box-shadow: var(--t4d-shadow);
            color: var(--t4d-dark);
        }

        .card-t4d .card-header {
            background: transparent;
            border-bottom: 1px solid var(--t4d-border);
            font-weight: 600;
            padding: 1.25rem 1.5rem;
            color: var(--t4d-dark);
        }

        .form-control, .form-select {
            border-radius: 10px;
            border: 1.5px solid #CBD5E1;
            padding: 0.7rem 0.9rem;
            background-color: #E8EEF5 !important;
            color: #0F172A !important;
            -webkit-text-fill-color: #0F172A;
            caret-color: #0F172A;
            transition: border-color 0.15s ease, box-shadow 0.15s ease, background-color 0.15s ease;
        }

        .form-control:hover, .form-select:hover {
            border-color: #94A3B8;
            background-color: #E2EAF3 !important;
        }

        .form-control:focus, .form-select:focus,
        .form-control:focus-visible, .form-select:focus-visible {
            border-color: #0B3A6E !important;
            box-shadow: 0 0 0 3px rgba(11, 58, 110, 0.22) !important;
            background-color: #E8EEF5 !important;
            color: #0F172A !important;
            -webkit-text-fill-color: #0F172A;
            outline: none;
        }

        /* Browser autofill must not force a white field */
        .form-control:-webkit-autofill,
        .form-control:-webkit-autofill:hover,
        .form-control:-webkit-autofill:focus,
        .form-control:-webkit-autofill:active,
        .form-select:-webkit-autofill,
        .form-select:-webkit-autofill:hover,
        .form-select:-webkit-autofill:focus,
        .form-select:-webkit-autofill:active {
            -webkit-text-fill-color: #0F172A !important;
            caret-color: #0F172A;
            border-color: #CBD5E1;
            box-shadow: 0 0 0 1000px #E8EEF5 inset !important;
            transition: background-color 9999s ease-in-out 0s;
        }

        .form-control:-webkit-autofill:focus,
        .form-select:-webkit-autofill:focus {
            border-color: #0B3A6E !important;
            box-shadow: 0 0 0 1000px #E8EEF5 inset, 0 0 0 3px rgba(11, 58, 110, 0.22) !important;
        }

        .form-control::placeholder,
        .form-select::placeholder {
            color: #64748B !important;
            opacity: 1;
        }

        .form-label { color: #0F172A; font-weight: 500; }

        /* Override Bootstrap CSS variables that force white inputs */
        .card-t4d,
        .card-body,
        form {
            --bs-body-bg: #E8EEF5;
            --bs-secondary-bg: #E8EEF5;
            --bs-tertiary-bg: #E8EEF5;
        }

        .lead-table {
            --bs-table-bg: transparent;
            --bs-table-color: var(--t4d-dark);
            --bs-table-border-color: var(--t4d-border);
        }

        .lead-table thead th {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--t4d-muted);
            font-weight: 600;
            border-bottom: 1px solid var(--t4d-border);
            background: transparent;
        }

        .lead-table tbody td {
            vertical-align: middle;
            border-color: var(--t4d-border);
            padding: 1rem 0.75rem;
            color: var(--t4d-dark);
        }

        .badge-buyer { background: rgba(14, 116, 144, 0.12); color: #0E7490; }
        .badge-seller { background: rgba(5, 150, 105, 0.12); color: #059669; }
        .badge-both { background: rgba(11, 58, 110, 0.12); color: #0B3A6E; }

        .empty-state {
            text-align: center;
            padding: 3rem 1.5rem;
            color: var(--t4d-muted);
        }

        .toast-container-t4d { z-index: 1090; }

        footer.t4d-footer {
            border-top: 1px solid var(--t4d-border);
            padding: 1.5rem 0;
            color: var(--t4d-muted);
            font-size: 0.875rem;
            background: var(--t4d-surface);
            margin-top: auto;
            flex-shrink: 0;
        }

        .modal-lead .modal-content {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--t4d-shadow-lg);
            background: var(--t4d-card);
            color: var(--t4d-dark);
        }

        .modal-lead .modal-side {
            background:
                linear-gradient(160deg, rgba(11, 58, 110, 0.94), rgba(14, 116, 144, 0.85)),
                url('https://images.unsplash.com/photo-1578574577315-52ac42c5d0f4?auto=format&fit=crop&w=900&q=80') center/cover;
            color: #fff;
            padding: 2.5rem 2rem;
            min-height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .modal-lead .modal-side .side-stat {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 0.85rem 1rem;
        }

        .modal-lead .modal-body {
            padding: 2rem;
            max-height: 85vh;
            overflow-y: auto;
            background: var(--t4d-card);
        }

        .modal-lead .btn-close-custom {
            position: absolute;
            top: 1rem;
            right: 1rem;
            z-index: 5;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 1px solid var(--t4d-border);
            background: var(--t4d-surface);
            color: var(--t4d-dark);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cta-banner {
            background:
                linear-gradient(120deg, rgba(11, 58, 110, 0.95), rgba(8, 47, 88, 0.92)),
                url('https://images.unsplash.com/photo-1494412574643-ff11b0a5c1c3?auto=format&fit=crop&w=1600&q=80') center/cover;
            border-radius: var(--t4d-radius);
            color: #fff;
            padding: 3rem 2.5rem;
            box-shadow: var(--t4d-shadow-lg);
        }

        .world-map-strip {
            background: var(--t4d-card);
            border: 1px solid var(--t4d-border);
            border-radius: var(--t4d-radius);
            padding: 1.5rem;
            box-shadow: var(--t4d-shadow);
        }

        .region-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: var(--t4d-bg);
            border: 1px solid var(--t4d-border);
            border-radius: 999px;
            padding: 0.4rem 0.85rem;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--t4d-dark);
        }

        .region-pill .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .text-muted { color: var(--t4d-muted) !important; }
        .text-primary { color: var(--t4d-primary) !important; }

        .badge.bg-primary {
            background-color: var(--t4d-primary) !important;
        }

        .badge.text-bg-primary,
        .text-bg-primary {
            background-color: rgba(11, 58, 110, 0.1) !important;
            color: var(--t4d-primary) !important;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-in { animation: fadeUp 0.6s ease both; }
        .animate-in-delay-1 { animation-delay: 0.1s; }
        .animate-in-delay-2 { animation-delay: 0.2s; }
        .animate-in-delay-3 { animation-delay: 0.3s; }

        #pageLoader {
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: #F7F8FA;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: opacity 0.35s ease, visibility 0.35s ease;
        }

        #pageLoader.is-hidden {
            opacity: 0 !important;
            visibility: hidden !important;
            pointer-events: none !important;
            display: none !important;
        }

        .t4d-spinner {
            width: 48px;
            height: 48px;
            border: 3px solid #E2E8F0;
            border-top-color: var(--t4d-primary);
            border-radius: 50%;
            animation: spin 0.75s linear infinite;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        .t4d-loader-text {
            margin-top: 1rem;
            color: var(--t4d-primary);
            font-size: 0.9rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            font-family: var(--t4d-font);
        }

        .skeleton {
            background: linear-gradient(90deg, #F1F5F9 25%, #E2E8F0 50%, #F1F5F9 75%);
            background-size: 200% 100%;
            animation: shimmer 1.4s ease infinite;
            border-radius: 8px;
        }

        @keyframes shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        .btn-loading {
            position: relative;
            pointer-events: none;
            opacity: 0.85;
        }

        .btn-loading .btn-label { opacity: 0; }

        .btn-loading::after {
            content: '';
            position: absolute;
            width: 1.1rem;
            height: 1.1rem;
            top: 50%;
            left: 50%;
            margin: -0.55rem 0 0 -0.55rem;
            border: 2px solid rgba(255,255,255,0.35);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div id="pageLoader" aria-live="polite" aria-busy="true">
        <div class="t4d-spinner" role="status" aria-label="Loading"></div>
        <div class="t4d-loader-text">Trade4Deal</div>
    </div>
    <script>
        setTimeout(function () {
            var el = document.getElementById('pageLoader');
            if (el) el.classList.add('is-hidden');
        }, 1500);
    </script>

    <nav class="navbar navbar-expand-lg navbar-t4d sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center py-1 text-decoration-none" href="{{ route('home') }}">
                <x-brand-logo :height="48" />
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMain">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#how-it-works">How it works</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#industries">Industries</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#leads">Leads</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('plans.index') }}">Plans</a></li>
                    <li class="nav-item">
                        <button type="button" class="nav-link btn btn-link text-decoration-none" data-bs-toggle="modal" data-bs-target="#leadModal">
                            Submit Lead
                        </button>
                    </li>
                    @auth
                        <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a></li>
                        @if(auth()->user()->isSeller() && auth()->user()->slug)
                            <li class="nav-item"><a class="nav-link" href="{{ route('sellers.show', auth()->user()->slug) }}">My page</a></li>
                        @endif
                        <li class="nav-item ms-lg-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-t4d btn-sm">Logout</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Sign In</a></li>
                        <li class="nav-item ms-lg-2">
                            <a href="{{ route('register') }}" class="btn btn-primary-t4d btn-sm text-white">Register Free</a>
                        </li>
                    @endauth
                    <li class="nav-item ms-lg-2">
                        <button class="btn btn-outline-t4d btn-sm" id="themeToggle" type="button" aria-label="Toggle theme">
                            <i class="bi bi-moon-stars"></i>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    @if(session('success'))
        <div class="toast-container toast-container-t4d position-fixed top-0 end-0 p-3">
            <div class="toast show align-items-center text-bg-success border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        </div>
    @endif

    <div class="page-shell">
        <main class="page-main">
            @yield('content')
        </main>

        @include('marketplace.partials.lead-modal')

        <footer class="t4d-footer">
            <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <div>&copy; {{ date('Y') }} Trade4Deal. Global B2B Marketplace.</div>
                <div class="d-flex gap-3">
                    <a href="#" class="text-decoration-none text-muted">Privacy</a>
                    <a href="#" class="text-decoration-none text-muted">Terms</a>
                    <a href="#" class="text-decoration-none text-muted">Support</a>
                </div>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
    <script>
        (function () {
            const root = document.documentElement;
            const toggle = document.getElementById('themeToggle');
            // Force brand default: light + navy. Only honor stored preference if set.
            const stored = localStorage.getItem('t4d-theme');
            const theme = stored || 'light';
            root.setAttribute('data-bs-theme', theme);
            if (toggle) {
                toggle.querySelector('i').className = theme === 'dark' ? 'bi bi-sun' : 'bi bi-moon-stars';
                toggle.addEventListener('click', () => {
                    const next = root.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
                    root.setAttribute('data-bs-theme', next);
                    localStorage.setItem('t4d-theme', next);
                    toggle.querySelector('i').className = next === 'dark' ? 'bi bi-sun' : 'bi bi-moon-stars';
                });
            }

            function hidePageLoader() {
                const loader = document.getElementById('pageLoader');
                if (loader) {
                    loader.classList.add('is-hidden');
                    loader.setAttribute('aria-busy', 'false');
                }
            }

            if (document.readyState === 'complete' || document.readyState === 'interactive') {
                setTimeout(hidePageLoader, 300);
            } else {
                document.addEventListener('DOMContentLoaded', function () {
                    setTimeout(hidePageLoader, 300);
                });
            }
            window.addEventListener('load', hidePageLoader);
            setTimeout(hidePageLoader, 2000);

            document.addEventListener('submit', function (e) {
                const form = e.target;
                if (!(form instanceof HTMLFormElement)) return;
                const btn = form.querySelector('button[type="submit"]');
                if (btn && !btn.classList.contains('btn-loading')) {
                    btn.classList.add('btn-loading');
                    if (!btn.querySelector('.btn-label')) {
                        btn.innerHTML = '<span class="btn-label">' + btn.innerHTML + '</span>';
                    }
                }
            });

            @if (isset($errors) && $errors->any())
                document.addEventListener('DOMContentLoaded', function () {
                    const modalEl = document.getElementById('leadModal');
                    if (modalEl) new bootstrap.Modal(modalEl).show();
                });
            @endif
        })();
    </script>
    @stack('scripts')
</body>
</html>
