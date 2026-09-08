<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Trade4Deal') }} — Dashboard</title>

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
            --t4d-dark: #0F172A;
            --t4d-muted: #64748B;
            --t4d-border: #E2E8F0;
            --t4d-bg: #F7F8FA;
            --t4d-card: #FFFFFF;
            --t4d-radius: 16px;
            --t4d-shadow: 0 4px 24px rgba(15, 23, 42, 0.06);
            --t4d-font: 'Open Sans', system-ui, sans-serif;
        }

        body {
            font-family: var(--t4d-font);
            background: var(--t4d-bg);
            color: var(--t4d-dark);
            min-height: 100vh;
        }

        .navbar-t4d {
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--t4d-border);
        }

        .navbar-t4d .nav-link { color: var(--t4d-muted) !important; font-weight: 500; }
        .navbar-t4d .nav-link:hover,
        .navbar-t4d .nav-link.active { color: var(--t4d-primary) !important; }

        .brand-logo {
            font-weight: 800;
            font-size: 1.35rem;
            letter-spacing: -0.03em;
            color: var(--t4d-primary);
            text-decoration: none;
        }

        .brand-logo span { color: var(--t4d-dark); }

        .btn-primary-t4d {
            background: var(--t4d-primary);
            border: none;
            border-radius: 10px;
            font-weight: 600;
            color: #fff;
        }

        .btn-primary-t4d:hover {
            background: var(--t4d-primary-dark);
            color: #fff;
        }

        .btn-outline-t4d {
            border: 1px solid var(--t4d-border);
            border-radius: 10px;
            font-weight: 600;
            color: var(--t4d-dark);
            background: var(--t4d-card);
        }

        .card-t4d {
            background: var(--t4d-card);
            border: 1px solid var(--t4d-border);
            border-radius: var(--t4d-radius);
            box-shadow: var(--t4d-shadow);
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
            box-shadow: 0 0 0 1000px #E8EEF5 inset !important;
            transition: background-color 9999s ease-in-out 0s;
        }

        .form-control:-webkit-autofill:focus,
        .form-select:-webkit-autofill:focus {
            border-color: #0B3A6E !important;
            box-shadow: 0 0 0 1000px #E8EEF5 inset, 0 0 0 3px rgba(11, 58, 110, 0.22) !important;
        }

        .page-header {
            border-bottom: 1px solid var(--t4d-border);
            background: var(--t4d-card);
            padding: 1.25rem 0;
        }

        .text-muted { color: var(--t4d-muted) !important; }

        footer.t4d-footer {
            border-top: 1px solid var(--t4d-border);
            padding: 1.5rem 0;
            color: var(--t4d-muted);
            font-size: 0.875rem;
            margin-top: auto;
            background: #fff;
        }

        .dropdown-menu {
            background: #fff;
            border-color: var(--t4d-border);
        }

        .dropdown-item { color: var(--t4d-dark); }
        .dropdown-item:hover { background: #F1F5F9; color: var(--t4d-primary); }

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
    </style>
</head>
<body class="d-flex flex-column">
    <div id="pageLoader">
        <div class="t4d-spinner"></div>
    </div>
    <script>
        setTimeout(function () {
            var el = document.getElementById('pageLoader');
            if (el) el.classList.add('is-hidden');
        }, 1500);
    </script>

    @include('layouts.navigation')

    @isset($header)
        <div class="page-header">
            <div class="container">
                {{ $header }}
            </div>
        </div>
    @endisset

    <main class="flex-grow-1 py-4">
        {{ $slot }}
    </main>

    <footer class="t4d-footer">
        <div class="container text-center text-md-start">
            &copy; {{ date('Y') }} Trade4Deal. Global B2B Marketplace.
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
    <script>
        (function () {
            function hidePageLoader() {
                document.getElementById('pageLoader')?.classList.add('is-hidden');
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
        })();
    </script>
</body>
</html>
