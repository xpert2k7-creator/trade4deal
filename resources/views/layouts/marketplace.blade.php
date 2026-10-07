<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Trade4Deal — Global B2B Marketplace connecting buyers and sellers worldwide.">
    <title>@yield('title', config('app.name', 'Trade4Deal'))</title>

    @include('layouts.partials.favicon')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&family=Plus+Jakarta+Sans:ital,wght@0,600;0,700;0,800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icons/7.5.0/css/flag-icons.min.css" integrity="sha512-+WVTaUIzUw5LFzqIqXOT3JVAc5SrMuvHm230I9QAZa6s+QRk8NDPswbHo2miIZj3yiFyV9lAgzO1wVrjdoO4tw==" crossorigin="anonymous" referrerpolicy="no-referrer">

    <style>
        /* Trade4Deal brand theme — Soft light + Deep navy (permanent) */
        :root {
            --t4d-primary: #064475;
            --t4d-primary-dark: #042f55;
            --t4d-accent: #F58220;
            --t4d-accent-dark: #D96A0B;
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
            --t4d-heading: 'Plus Jakarta Sans', 'Open Sans', system-ui, -apple-system, sans-serif;
        }

        [data-bs-theme="dark"] {
            --t4d-primary: #064475;
            --t4d-primary-dark: #042f55;
            --t4d-accent: #F58220;
            --t4d-accent-dark: #D96A0B;
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
            font-family: var(--t4d-heading);
        }

        .navbar-t4d {
            background: #fff;
            border-bottom: 1px solid #E5E7EB;
            box-shadow: 0 2px 12px rgba(15, 23, 42, 0.08);
        }

        [data-bs-theme="dark"] .navbar-t4d {
            background: #fff;
        }

        .market-topbar {
            border-bottom: 1px solid #e5e7eb;
            background: #fff;
            color: #475569;
            font-size: 0.78rem;
            font-weight: 800;
        }

        .market-topbar-inner {
            max-width: 1510px;
            min-height: 34px;
            display: grid;
            grid-template-columns: minmax(170px, auto) minmax(0, 1fr) minmax(170px, auto);
            align-items: center;
            gap: 1rem;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .topbar-links,
        .topbar-country {
            display: inline-flex;
            align-items: center;
            gap: 0.8rem;
            min-width: 0;
        }

        .topbar-links a {
            color: #334155;
            text-decoration: none;
            white-space: nowrap;
        }

        .topbar-links a:hover {
            color: var(--t4d-primary);
        }

        .topbar-marquee {
            min-width: 0;
            overflow: hidden;
            color: var(--t4d-primary);
            white-space: nowrap;
        }

        .topbar-marquee-track {
            display: inline-flex;
            gap: 2.5rem;
            width: max-content;
            animation: topbarMarquee 24s linear infinite;
            will-change: transform;
        }

        .topbar-marquee:hover .topbar-marquee-track {
            animation-play-state: paused;
        }

        .topbar-marquee-track span {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
        }

        .topbar-country {
            justify-content: flex-end;
            color: #334155;
            white-space: nowrap;
        }

        .topbar-country i {
            color: var(--t4d-accent);
            font-size: 0.95rem;
        }

        @keyframes topbarMarquee {
            from { transform: translateX(0); }
            to { transform: translateX(calc(-50% - 1.25rem)); }
        }

        .navbar-t4d .nav-link {
            color: #344054 !important;
            font-weight: 700;
            font-size: 0.82rem;
        }

        .navbar-t4d .nav-link:hover,
        .navbar-t4d .nav-link.active {
            color: var(--t4d-primary) !important;
        }

        .navbar-t4d .market-nav-wrap {
            max-width: 1510px;
            min-height: 72px;
            flex-wrap: nowrap;
            overflow: hidden;
            justify-content: flex-start;
        }

        .navbar-t4d .navbar-brand {
            flex-shrink: 0;
            margin-right: 0.35rem;
            padding-top: 0;
            padding-bottom: 0;
        }

        .navbar-t4d .navbar-brand .t4d-logo {
            display: block;
            width: auto;
            max-width: min(280px, 34vw);
            height: clamp(52px, 4.2vw, 62px) !important;
            max-height: 62px;
            object-fit: contain;
            object-position: left center;
        }

        .market-search {
            display: grid;
            grid-template-columns: minmax(126px, 154px) minmax(220px, 1fr) 52px;
            flex: 1 1 520px;
            max-width: 620px;
            height: 48px;
            border: 1px solid #D7DCE5;
            border-radius: 8px;
            overflow: hidden;
            background: #fff;
            box-shadow: inset 0 1px 0 rgba(15, 23, 42, 0.03);
        }

        .market-search-wrap {
            position: relative;
            flex: 1 1 520px;
            max-width: 620px;
        }

        .market-search-wrap .market-search {
            width: 100%;
            max-width: none;
        }

        .market-search-location,
        .market-search-input {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            min-width: 0;
            padding: 0 0.9rem;
            border: 0;
            background: #fff;
            color: #334155;
        }

        .market-search-location {
            border-right: 1px solid #D7DCE5;
            font-weight: 700;
            white-space: nowrap;
            cursor: pointer;
            overflow: hidden;
            appearance: none;
        }

        .market-search-location i {
            color: var(--t4d-accent);
            font-size: 1.2rem;
        }

        .market-location-label {
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .market-search-input input {
            width: 100%;
            border: 0;
            outline: 0;
            color: #0f172a;
            font-size: 0.92rem;
        }

        .market-search-input input::placeholder {
            color: #94a3b8;
        }

        .market-search .market-search-submit {
            border: 0;
            background: var(--t4d-primary);
            color: #fff;
            font-size: 1.1rem;
        }

        .location-popover {
            position: fixed;
            z-index: 1080;
            width: min(520px, calc(100vw - 1.5rem));
            max-height: min(620px, calc(100vh - 1.5rem));
            border: 1px solid #D7DCE5;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 22px 48px rgba(15, 23, 42, 0.18);
            padding: 0.85rem;
            display: none;
            overflow: hidden;
        }

        .location-popover.is-open {
            display: grid;
            grid-template-rows: auto auto auto auto minmax(0, 1fr);
            gap: 0.68rem;
        }

        .location-popover-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            color: #0f172a;
            font-weight: 850;
        }

        .location-popover-title button {
            border: 0;
            background: transparent;
            color: #64748b;
            font-size: 1.05rem;
        }

        .location-search-input {
            width: 100%;
            min-height: 42px;
            border: 1px solid #CBD5E1;
            border-radius: 8px;
            padding: 0 0.8rem;
            color: #0f172a;
            outline: 0;
        }

        .location-search-input:focus {
            border-color: var(--t4d-primary);
            box-shadow: 0 0 0 3px rgba(6, 68, 117, 0.12);
        }

        .location-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.55rem;
        }

        .location-action-btn,
        .location-option-btn {
            min-height: 46px;
            border: 1px solid #D7DCE5;
            border-radius: 8px;
            background: #fff;
            color: #0f172a;
            padding: 0.55rem 0.75rem;
            text-align: left;
            font-weight: 750;
        }

        .location-option-btn {
            display: grid;
            gap: 0.16rem;
            min-height: 62px;
            line-height: 1.18;
        }

        .location-action-btn:hover,
        .location-option-btn:hover,
        .location-option-btn:focus {
            border-color: var(--t4d-accent);
            outline: none;
        }

        .location-option-btn:hover,
        .location-option-btn:focus {
            background: #fffaf5;
            box-shadow: 0 0 0 2px rgba(245, 130, 32, 0.12);
        }

        .location-results {
            display: grid;
            gap: 0.5rem;
            max-height: 330px;
            overflow-y: auto;
            overscroll-behavior: contain;
            padding-right: 0.2rem;
            scrollbar-width: thin;
        }

        .location-results:empty {
            display: none;
        }

        .location-option-btn span {
            display: block;
            color: #64748b;
            font-size: 0.78rem;
            font-weight: 650;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            line-height: 1.25;
        }

        .location-status {
            color: #64748b;
            font-size: 0.8rem;
            line-height: 1.4;
            min-height: 1.1rem;
        }

        .market-header-actions {
            display: flex;
            align-items: center;
            gap: 0.55rem;
            flex: 0 0 auto;
            white-space: nowrap;
        }

        .mobile-finder-actions {
            display: none;
        }

        .mobile-register-inline,
        .mobile-bottom-nav {
            display: none;
        }

        .mobile-nav-toggle {
            display: none;
            width: 38px;
            height: 38px;
            border-radius: 8px;
            background: #f1f5f9;
            color: #334155;
            align-items: center;
            justify-content: center;
        }

        .mobile-nav-icon {
            font-size: 1.35rem;
            line-height: 1;
        }

        .market-header-action {
            display: inline-flex;
            min-width: 54px;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.1rem;
            color: #475569;
            font-size: 0.75rem;
            font-weight: 800;
            text-decoration: none;
            border: 0;
            background: transparent;
        }

        .market-header-action i {
            color: #52525b;
            font-size: 1.15rem;
        }

        .market-finder-btn,
        .market-register-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            min-height: 40px;
            padding: 0.45rem 0.9rem;
            border-radius: 10px;
            font-weight: 800;
            text-decoration: none;
            border: 1px solid #d7dce5;
            background: #fff;
            color: #1f2474;
            white-space: nowrap;
        }

        .market-finder-btn {
            color: var(--t4d-primary);
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08);
        }

        .market-finder-btn.find-buyer {
            color: var(--t4d-accent-dark);
        }

        .market-finder-btn i {
            font-size: 1rem;
        }

        .market-register-btn {
            border-color: var(--t4d-primary);
            background: var(--t4d-primary);
            color: #fff;
        }

        .market-register-btn:hover {
            color: #fff;
            background: var(--t4d-primary-dark);
        }

        .market-finder-btn:hover {
            color: var(--t4d-primary);
            border-color: #aeb8c8;
        }

        .market-finder-btn.find-buyer:hover {
            color: var(--t4d-accent-dark);
        }

        .finder-modal .modal-content {
            border: 0;
            border-radius: 14px;
            box-shadow: 0 24px 70px rgba(15, 23, 42, 0.22);
        }

        .finder-modal .modal-header {
            border-bottom: 1px solid var(--t4d-border);
            background: linear-gradient(135deg, rgba(6,68,117,0.08), rgba(245,130,32,0.08));
        }

        .finder-search-box {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 0.65rem;
        }

        .finder-search-box input {
            min-height: 48px;
            border: 1px solid #CBD5E1;
            border-radius: 10px;
            padding: 0 0.95rem;
            outline: 0;
        }

        .finder-mode-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.4rem 0.75rem;
            border-radius: 999px;
            background: #F8FAFC;
            color: var(--t4d-primary);
            font-weight: 800;
            font-size: 0.82rem;
        }

        @media (min-width: 1200px) {
            .navbar-t4d .navbar-collapse {
                display: none !important;
            }

            .navbar-t4d .navbar-toggler {
                display: none;
            }
        }

        @media (max-width: 1450px) and (min-width: 1200px) {
            .market-nav-wrap {
                gap: 0.45rem !important;
                padding-left: 0.55rem;
                padding-right: 0.55rem;
            }

            .market-search {
                grid-template-columns: minmax(108px, 132px) minmax(160px, 1fr) 44px;
                flex: 1 1 430px;
                max-width: 520px;
                height: 46px;
            }

            .market-search-location,
            .market-search-input {
                padding: 0 0.62rem;
            }

            .market-search-input input {
                font-size: 0.86rem;
            }

            .market-search-location {
                display: flex;
                font-size: 0.88rem;
                gap: 0.36rem;
            }

            .market-header-actions {
                gap: 0.28rem;
            }

            .market-finder-btn,
            .market-register-btn {
                min-height: 42px;
                padding: 0.48rem 0.62rem;
                font-size: 0.82rem;
            }

            .market-header-action {
                min-width: 44px;
                font-size: 0.66rem;
            }

            .market-header-action i {
                font-size: 1.05rem;
            }
        }

        @media (max-width: 1280px) and (min-width: 1200px) {
            .market-search {
                grid-template-columns: minmax(96px, 116px) minmax(140px, 1fr) 42px;
                flex-basis: 360px;
                max-width: 390px;
            }

            .market-search-location {
                display: flex;
                font-size: 0.78rem;
            }

            .market-finder-btn {
                min-width: 98px;
                padding-left: 0.44rem;
                padding-right: 0.44rem;
                font-size: 0.74rem;
                gap: 0.3rem;
            }

            .market-finder-btn i {
                margin: 0;
                font-size: 0.92rem;
            }

            .market-register-btn {
                padding-left: 0.54rem;
                padding-right: 0.54rem;
                font-size: 0.78rem;
            }

            .market-header-action {
                min-width: 40px;
                font-size: 0.62rem;
            }
        }

        @media (max-width: 1199px) {
            .market-topbar-inner {
                grid-template-columns: auto minmax(0, 1fr) auto;
                gap: 0.7rem;
                padding: 0 0.75rem;
            }

            .topbar-links,
            .topbar-country {
                gap: 0.55rem;
            }

            .topbar-country-text {
                max-width: 115px;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .navbar-t4d .market-nav-wrap {
                flex-wrap: wrap;
                overflow: visible;
            }

            .mobile-nav-toggle {
                display: inline-flex;
            }

            .market-search {
                order: 3;
                flex-basis: 100%;
                max-width: none;
            }

            .market-search-wrap {
                order: 3;
                flex-basis: 100%;
                max-width: none;
            }

            .market-header-actions {
                margin-left: auto;
            }
        }

        @media (max-width: 767px) {
            .market-topbar-inner {
                grid-template-columns: 1fr auto;
                min-height: 36px;
            }

            .topbar-links {
                display: none;
            }

            .topbar-marquee {
                order: 1;
            }

            .topbar-country {
                order: 2;
                font-size: 0.68rem;
            }

            .topbar-country-text {
                max-width: 82px;
            }

            .page-shell {
                padding-bottom: 76px;
            }

            .navbar-t4d {
                overflow: hidden;
            }

            .navbar-t4d .market-nav-wrap {
                position: relative;
                display: grid;
                grid-template-columns: auto minmax(0, 1fr) auto;
                grid-template-areas:
                    "brand search register"
                    "collapse collapse collapse";
                align-items: center;
                column-gap: 0.4rem !important;
                row-gap: 0.5rem !important;
                padding-left: 0.5rem;
                padding-right: 0.5rem;
                width: 100%;
                max-width: 100vw;
                min-width: 0;
            }

            .navbar-t4d .navbar-brand {
                grid-area: brand;
                margin-right: 0;
                flex: 0 0 auto;
            }

            .navbar-t4d .navbar-brand .t4d-logo {
                height: clamp(46px, 12vw, 52px) !important;
                max-height: 52px;
                max-width: min(200px, 42vw);
            }

            .market-search {
                grid-area: search;
                grid-template-columns: minmax(92px, 112px) minmax(0, 1fr) 42px;
                height: 44px;
                width: 100%;
                margin-top: 0;
            }

            .market-search-input {
                padding: 0 0.65rem;
            }

            .market-search-input input {
                font-size: 0.78rem;
            }

            .mobile-register-inline {
                grid-area: register;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-height: 44px;
                padding: 0 0.62rem;
                border-radius: 8px;
                background: var(--t4d-primary);
                color: #fff;
                font-size: 0.72rem;
                font-weight: 850;
                line-height: 1.1;
                text-decoration: none;
                white-space: nowrap;
            }

            .market-search-location {
                display: flex;
                padding: 0 0.52rem;
                gap: 0.28rem;
                font-size: 0.72rem;
            }

            .market-search-location i {
                font-size: 1rem;
            }

            .location-popover {
                left: 0.5rem !important;
                right: 0.5rem;
                width: auto;
                max-height: min(560px, calc(100vh - 1rem));
            }

            .location-actions {
                grid-template-columns: 1fr;
            }

            .mobile-finder-actions,
            .market-header-actions {
                display: none;
            }

            .market-header-actions .market-header-action:not(.signin-action),
            .market-finder-btn {
                display: none;
            }

            .navbar-t4d .navbar-collapse {
                grid-area: collapse;
            }

            .mobile-bottom-nav {
                position: fixed;
                left: 0.75rem;
                right: 0.75rem;
                bottom: 0.65rem;
                z-index: 1040;
                display: grid;
                grid-template-columns: repeat(5, minmax(0, 1fr));
                align-items: center;
                min-height: 58px;
                padding: 0.35rem 0.55rem;
                border: 1px solid rgba(148, 163, 184, 0.35);
                border-radius: 14px;
                background: rgba(255, 255, 255, 0.96);
                box-shadow: 0 12px 36px rgba(15, 23, 42, 0.22);
                backdrop-filter: blur(14px);
            }

            .mobile-bottom-nav a,
            .mobile-bottom-nav button {
                min-width: 0;
                border: 0;
                background: transparent;
                color: #334155;
                text-decoration: none;
                display: inline-flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 0.16rem;
                min-height: 46px;
                font-size: 0.58rem;
                font-weight: 850;
                line-height: 1.05;
            }

            .mobile-bottom-nav i {
                font-size: 1.18rem;
                line-height: 1;
            }

            .mobile-bottom-nav .bottom-seller {
                color: var(--t4d-primary);
            }

            .mobile-bottom-nav .bottom-buyer {
                color: var(--t4d-accent-dark);
            }

            .mobile-bottom-nav .bottom-theme {
                color: #475569;
            }

            .mobile-bottom-nav .bottom-menu {
                position: relative;
                justify-self: center;
                width: 48px;
                height: 48px;
                min-height: 48px;
                border-radius: 50%;
                background: var(--t4d-primary);
                color: #fff;
                box-shadow: 0 10px 24px rgba(6, 68, 117, 0.32);
            }

            .mobile-bottom-nav .bottom-menu .mobile-nav-icon {
                font-size: 1.55rem;
                line-height: 1;
            }
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
            border-top: 1px solid #263246;
            padding: 0;
            color: #B8C4D8;
            font-size: 0.92rem;
            background:
                linear-gradient(90deg, rgba(17, 24, 39, 0.94), rgba(17, 24, 39, 0.88)),
                url('https://images.unsplash.com/photo-1494412685616-a5d310fbb07d?auto=format&fit=crop&w=1800&q=85') center/cover no-repeat;
            margin-top: auto;
            flex-shrink: 0;
        }

        .footer-marketplace {
            max-width: 1620px;
            margin: 0 auto;
            padding: 1.1rem 1.5rem 1.25rem;
        }

        .footer-market-grid {
            display: grid;
            grid-template-columns: minmax(260px, 1.45fr) repeat(5, minmax(140px, 1fr));
            gap: 2rem;
            align-items: start;
        }

        .footer-brand-copy {
            max-width: 290px;
            line-height: 1.65;
        }

        .footer-contact-stack {
            display: grid;
            gap: 0.65rem;
            margin: 1rem 0;
        }

        .footer-contact-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.7rem;
            color: #fff;
            font-weight: 800;
            text-decoration: none;
        }

        .footer-contact-pill i,
        .footer-social a {
            width: 40px;
            height: 40px;
            border-radius: 7px;
            display: inline-grid;
            place-items: center;
            background: #202b3d;
            color: #dbeafe;
        }

        .footer-social {
            display: flex;
            gap: 0.7rem;
            flex-wrap: wrap;
            margin-top: 0.8rem;
        }

        .footer-social a {
            text-decoration: none;
            font-size: 1.15rem;
        }

        .footer-col h3 {
            margin: 0 0 0.7rem;
            color: #fff;
            font-size: 0.98rem;
            font-weight: 850;
        }

        .footer-col a,
        .footer-col button,
        .footer-col span {
            display: block;
            margin-bottom: 0.58rem;
            color: #B8C4D8;
            text-decoration: none;
            font-size: 0.92rem;
        }

        .footer-col a:hover,
        .footer-col button:hover {
            color: #fff;
        }

        .footer-bottom {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
            margin-top: 0.8rem;
            padding-top: 0.7rem;
            border-top: 1px solid #263246;
            color: #8998ae;
            font-size: 0.82rem;
        }

        .footer-bottom a {
            color: #8998ae;
            text-decoration: none;
            margin-right: 1.2rem;
        }

        @media (max-width: 1199px) {
            .footer-market-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 767px) {
            .footer-marketplace {
                padding: 1.1rem 1rem 1.25rem;
            }

            .footer-market-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 1.2rem 1rem;
            }

            .footer-market-grid > div:first-child {
                grid-column: 1 / -1;
            }

            .footer-col h3 {
                font-size: 0.92rem;
                margin-bottom: 0.55rem;
            }

            .footer-col a,
            .footer-col button,
            .footer-col span {
                font-size: 0.82rem;
                line-height: 1.35;
                margin-bottom: 0.5rem;
            }
        }

        .testimonial-strip {
            border-top: 1px solid var(--t4d-border);
            background:
                linear-gradient(180deg, rgba(11, 58, 110, 0.035), rgba(14, 116, 144, 0.055)),
                var(--t4d-bg);
        }

        .testimonial-slider {
            position: relative;
            padding-bottom: 2.75rem;
        }

        .testimonial-card {
            height: 100%;
            min-height: 265px;
            padding: 1.35rem;
            border: 1px solid var(--t4d-border);
            border-radius: 14px;
            background: var(--t4d-card);
            box-shadow: var(--t4d-shadow);
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .testimonial-quote {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            background: linear-gradient(135deg, var(--t4d-primary), var(--t4d-accent));
            flex: 0 0 auto;
        }

        .testimonial-card p {
            color: var(--t4d-dark);
            line-height: 1.62;
        }

        .testimonial-person {
            margin-top: auto;
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .testimonial-avatar {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 800;
            background: var(--t4d-primary);
            box-shadow: 0 10px 24px rgba(11, 58, 110, 0.18);
        }

        .testimonial-rating {
            color: #F59E0B;
            font-size: 0.85rem;
            letter-spacing: 0.05em;
        }

        .testimonial-slider .carousel-indicators {
            bottom: 0;
            margin-bottom: 0;
        }

        .testimonial-slider .carousel-indicators [data-bs-target] {
            width: 9px;
            height: 9px;
            border-radius: 999px;
            background-color: var(--t4d-primary);
        }

        .testimonial-control {
            width: 42px;
            height: 42px;
            top: auto;
            bottom: -0.85rem;
            opacity: 1;
        }

        .testimonial-control.carousel-control-prev {
            left: auto;
            right: 52px;
        }

        .testimonial-control.carousel-control-next {
            right: 0;
        }

        .testimonial-control span {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background-color: var(--t4d-primary);
            background-size: 48%;
            box-shadow: var(--t4d-shadow);
        }

        .client-logo-slider {
            margin-top: 2.4rem;
            padding-top: 1.7rem;
            border-top: 1px solid rgba(148, 163, 184, 0.35);
            overflow: hidden;
        }

        .client-logo-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .client-logo-head h3 {
            margin: 0;
            color: var(--t4d-dark);
            font-size: 1.05rem;
            font-weight: 850;
            letter-spacing: 0;
        }

        .client-logo-head span {
            color: var(--t4d-muted);
            font-size: 0.86rem;
            font-weight: 700;
        }

        .client-logo-marquee {
            overflow: hidden;
            mask-image: linear-gradient(90deg, transparent, #000 8%, #000 92%, transparent);
        }

        .client-logo-track {
            display: flex;
            width: max-content;
            gap: 1rem;
            animation: clientLogoSlide 28s linear infinite;
            will-change: transform;
        }

        .client-logo-marquee:hover .client-logo-track,
        .client-logo-marquee:focus-within .client-logo-track {
            animation-play-state: paused;
        }

        .client-logo-card {
            width: clamp(170px, 18vw, 250px);
            height: 96px;
            padding: 1rem 1.15rem;
            border: 1px solid var(--t4d-border);
            border-radius: 10px;
            background: #fff;
            display: grid;
            place-items: center;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
            flex: 0 0 auto;
        }

        .client-logo-card img {
            max-width: 100%;
            max-height: 70px;
            object-fit: contain;
        }

        @keyframes clientLogoSlide {
            from { transform: translateX(0); }
            to { transform: translateX(calc(-50% - 0.5rem)); }
        }

        @media (prefers-reduced-motion: reduce) {
            .client-logo-track {
                animation: none;
            }
        }

        @media (max-width: 767px) {
            .testimonial-strip {
                padding-top: 1.5rem !important;
                padding-bottom: 1.75rem !important;
            }

            .testimonial-strip .container {
                padding-left: 0.85rem;
                padding-right: 0.85rem;
            }

            .testimonial-strip .badge {
                padding: 0.42rem 0.85rem !important;
                font-size: 0.74rem;
            }

            .testimonial-strip .section-title {
                max-width: 100%;
                font-size: 1.45rem;
                line-height: 1.18;
                letter-spacing: 0;
            }

            .testimonial-strip .text-muted {
                font-size: 0.94rem;
                line-height: 1.55;
            }

            .testimonial-slider {
                padding-bottom: 2rem;
            }

            .testimonial-slider .row {
                --bs-gutter-y: 0.75rem;
            }

            .testimonial-slider .carousel-item .row > div {
                display: none;
            }

            .testimonial-slider .carousel-item .row > div:first-child {
                display: block;
                flex: 0 0 100%;
                max-width: 100%;
                width: 100%;
            }

            .testimonial-card {
                min-height: 0;
                padding: 1rem;
                border-radius: 10px;
                gap: 0.75rem;
            }

            .testimonial-quote {
                width: 34px;
                height: 34px;
                border-radius: 9px;
                font-size: 0.92rem;
            }

            .testimonial-rating {
                font-size: 0.72rem;
                letter-spacing: 0.02em;
            }

            .testimonial-card p {
                font-size: 0.9rem;
                line-height: 1.55;
            }

            .testimonial-person {
                gap: 0.65rem;
                align-items: center;
            }

            .testimonial-avatar {
                width: 40px;
                height: 40px;
                font-size: 0.82rem;
                flex: 0 0 40px;
            }

            .testimonial-person .fw-bold {
                font-size: 0.95rem;
                line-height: 1.2;
            }

            .testimonial-person .small {
                font-size: 0.78rem;
                line-height: 1.3;
            }

            .testimonial-control {
                display: none;
            }

            .testimonial-slider .carousel-indicators {
                bottom: 0.25rem;
            }

            .client-logo-slider {
                margin-top: 1.5rem;
                padding-top: 1.2rem;
            }

            .client-logo-head {
                align-items: flex-start;
                flex-direction: column;
                gap: 0.25rem;
            }

            .client-logo-head h3 {
                font-size: 0.98rem;
            }

            .client-logo-card {
                width: 158px;
                height: 78px;
                padding: 0.75rem;
                border-radius: 8px;
            }

            .client-logo-card img {
                max-height: 56px;
            }
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
            padding: 1.65rem 1.5rem;
            min-height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .modal-lead .modal-side .side-stat {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 0.65rem 0.75rem;
        }

        .modal-lead .modal-body {
            padding: 1.35rem 1.6rem 1.45rem;
            max-height: 76vh;
            overflow-y: auto;
            background: var(--t4d-card);
        }

        .modal-lead .modal-dialog {
            max-width: min(1120px, calc(100vw - 2rem));
        }

        .modal-lead h3 {
            font-size: 1.42rem !important;
            line-height: 1.14;
        }

        .modal-lead .modal-side p,
        .modal-lead .modal-side li,
        .modal-lead .modal-body p {
            font-size: 0.82rem !important;
            line-height: 1.42;
        }

        .modal-lead .modal-body h4 {
            font-size: 1.32rem;
        }

        .modal-lead .row.g-3 {
            --bs-gutter-x: 0.85rem;
            --bs-gutter-y: 0.6rem;
        }

        .modal-lead .mb-3 {
            margin-bottom: 0.65rem !important;
        }

        .modal-lead .mb-4 {
            margin-bottom: 0.9rem !important;
        }

        .modal-lead .form-label {
            margin-bottom: 0.28rem;
            font-size: 0.83rem;
        }

        .modal-lead .form-control,
        .modal-lead .form-select {
            min-height: 40px;
            padding-top: 0.42rem;
            padding-bottom: 0.42rem;
            font-size: 0.88rem;
            border-radius: 8px;
        }

        .modal-lead textarea.form-control {
            min-height: 74px;
        }

        .modal-lead .form-check {
            min-height: 0;
            margin-bottom: 0.1rem;
            font-size: 0.82rem;
        }

        .modal-lead .btn-close-custom {
            position: absolute;
            top: 0.8rem;
            right: 0.8rem;
            z-index: 5;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            border: 1px solid var(--t4d-border);
            background: var(--t4d-surface);
            color: var(--t4d-dark);
            font-size: 0.9rem;
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

        body.t4d-popup-open {
            overflow: hidden;
        }

        .join-popup-backdrop {
            position: fixed;
            inset: 0;
            z-index: 1075;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            background:
                linear-gradient(140deg, rgba(3, 18, 38, 0.72), rgba(6, 68, 117, 0.58)),
                rgba(15, 23, 42, 0.52);
            backdrop-filter: blur(10px);
        }

        .join-popup-backdrop.is-visible {
            display: flex;
        }

        .join-popup-card {
            position: relative;
            width: min(100%, 900px);
            max-height: min(92vh, 760px);
            display: grid;
            grid-template-columns: minmax(255px, 0.88fr) minmax(0, 1.12fr);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.34);
            border-radius: 14px;
            background: #fff;
            box-shadow: 0 30px 90px rgba(3, 18, 38, 0.36);
            animation: joinPopupIn 0.34s ease both;
        }

        @keyframes joinPopupIn {
            from { opacity: 0; transform: translateY(18px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .join-popup-visual {
            position: relative;
            min-height: 100%;
            padding: 1.45rem;
            background:
                linear-gradient(145deg, rgba(6, 68, 117, 0.94), rgba(3, 29, 56, 0.88)),
                url('https://images.unsplash.com/photo-1494412574643-ff11b0a5c1c3?auto=format&fit=crop&w=900&q=78') center/cover no-repeat;
            color: #fff;
            isolation: isolate;
        }

        .join-popup-visual::after {
            content: "";
            position: absolute;
            inset: 0;
            z-index: -1;
            background:
                linear-gradient(180deg, rgba(245, 130, 32, 0.1), rgba(245, 130, 32, 0.32)),
                radial-gradient(circle at 16% 18%, rgba(255, 255, 255, 0.18), transparent 26%);
        }

        .join-popup-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.42rem;
            min-height: 32px;
            padding: 0.35rem 0.72rem;
            border: 1px solid rgba(255, 255, 255, 0.34);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.13);
            font-size: 0.74rem;
            font-weight: 850;
            backdrop-filter: blur(8px);
        }

        .join-popup-visual h2 {
            margin: 1.15rem 0 0.65rem;
            font-size: clamp(1.45rem, 2.4vw, 2rem);
            font-weight: 900;
            line-height: 1.12;
        }

        .join-popup-visual p {
            margin: 0;
            color: rgba(255, 255, 255, 0.88);
            font-size: 0.92rem;
            line-height: 1.55;
        }

        .join-popup-proof {
            display: grid;
            gap: 0.7rem;
            margin-top: 1.4rem;
        }

        .join-popup-proof span {
            display: flex;
            align-items: center;
            gap: 0.55rem;
            font-size: 0.84rem;
            font-weight: 800;
        }

        .join-popup-proof i {
            color: #ffd4a8;
            font-size: 1rem;
        }

        .join-popup-body {
            position: relative;
            overflow-y: auto;
            padding: clamp(1.1rem, 2.2vw, 1.7rem);
            background:
                linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        }

        .join-popup-close {
            position: absolute;
            top: 0.78rem;
            right: 0.78rem;
            z-index: 2;
            width: 34px;
            height: 34px;
            border: 0;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: var(--t4d-accent);
            color: #fff;
            font-size: 1.05rem;
            box-shadow: 0 10px 24px rgba(245, 130, 32, 0.32);
        }

        .join-popup-kicker {
            margin: 0 2.25rem 0.28rem 0;
            color: var(--t4d-accent-dark);
            font-size: 0.75rem;
            font-weight: 900;
            text-transform: uppercase;
        }

        .join-popup-title {
            margin: 0 2.2rem 0.95rem 0;
            color: #07111f;
            font-size: clamp(1.42rem, 3vw, 2rem);
            font-weight: 900;
            line-height: 1.12;
        }

        .join-role-switch {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.55rem;
            margin-bottom: 0.85rem;
            border-radius: 10px;
            background: #eef3f8;
            padding: 0.36rem;
        }

        .join-role-switch label {
            cursor: pointer;
            margin: 0;
        }

        .join-role-switch input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .join-role-switch span {
            min-height: 42px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.42rem;
            color: #475569;
            font-size: 0.88rem;
            font-weight: 850;
            transition: background 0.16s ease, color 0.16s ease, box-shadow 0.16s ease;
        }

        .join-role-switch input:checked + span {
            background: #fff;
            color: var(--t4d-primary);
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.1);
        }

        .join-popup-form {
            display: grid;
            gap: 0.65rem;
        }

        .join-field-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.65rem;
        }

        .join-popup-field {
            position: relative;
        }

        .join-popup-field i {
            position: absolute;
            top: 50%;
            left: 0.82rem;
            transform: translateY(-50%);
            color: #64748b;
            font-size: 0.98rem;
            pointer-events: none;
        }

        .join-country-selected-flag {
            position: absolute;
            top: 50%;
            left: 0.82rem;
            transform: translateY(-50%);
            width: 1.25em;
            border-radius: 50%;
            box-shadow: 0 0 0 1px rgba(15, 23, 42, 0.08);
            pointer-events: none;
        }

        .join-popup-field input,
        .join-popup-field select {
            width: 100%;
            min-height: 45px;
            border: 1px solid #d5dde8;
            border-radius: 9px;
            background: #fff;
            color: #0f172a;
            padding: 0 0.88rem 0 2.45rem;
            font-size: 0.9rem;
            outline: 0;
        }

        .join-popup-field input::placeholder {
            color: #94a3b8;
        }

        .join-popup-field select {
            appearance: none;
            padding-right: 2.3rem;
            cursor: pointer;
        }

        .join-popup-field.has-select::after {
            content: "\F282";
            position: absolute;
            top: 50%;
            right: 0.88rem;
            transform: translateY(-50%);
            color: #64748b;
            font-family: "bootstrap-icons";
            font-size: 0.76rem;
            pointer-events: none;
        }

        .join-popup-field input:focus,
        .join-popup-field select:focus {
            border-color: var(--t4d-primary);
            box-shadow: 0 0 0 3px rgba(6, 68, 117, 0.12);
        }

        .join-phone-row {
            display: grid;
            grid-template-columns: 124px minmax(0, 1fr);
            gap: 0.65rem;
        }

        .join-phone-code {
            min-height: 45px;
            border: 1px solid #d5dde8;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.55rem;
            background: #fff;
            color: #0f172a;
            font-size: 0.95rem;
            font-weight: 800;
        }

        .join-phone-code .fi {
            width: 1.4em;
            border-radius: 50%;
            box-shadow: 0 0 0 1px rgba(15, 23, 42, 0.08);
        }

        .join-phone-field input {
            padding-left: 0.95rem;
        }

        .join-phone-error {
            display: none;
            margin: -0.35rem 0 0;
            color: #b91c1c;
            font-size: 0.72rem;
            font-weight: 700;
        }

        .join-phone-error.is-visible {
            display: block;
        }

        .join-seller-only.is-hidden {
            display: none;
        }

        .join-popup-submit {
            min-height: 48px;
            margin-top: 0.12rem;
            border: 0;
            border-radius: 9px;
            background: linear-gradient(135deg, var(--t4d-accent), #ff6b00);
            color: #fff;
            font-size: 0.98rem;
            font-weight: 900;
            box-shadow: 0 14px 26px rgba(245, 130, 32, 0.24);
        }

        .join-popup-submit:hover {
            filter: brightness(0.98);
            transform: translateY(-1px);
        }

        .join-popup-terms {
            margin: 0.05rem 0 0;
            color: #64748b;
            font-size: 0.72rem;
            line-height: 1.45;
        }

        .join-popup-terms a {
            color: var(--t4d-primary);
            font-weight: 800;
            text-decoration: none;
        }

        @media (max-width: 767px) {
            .join-popup-backdrop {
                align-items: flex-end;
                padding: 0.65rem;
            }

            .join-popup-card {
                max-height: 92vh;
                grid-template-columns: 1fr;
                border-radius: 14px;
            }

            .join-popup-visual {
                min-height: auto;
                padding: 1rem 1.05rem 0.95rem;
            }

            .join-popup-visual h2 {
                margin-top: 0.75rem;
                font-size: 1.22rem;
            }

            .join-popup-visual p,
            .join-popup-proof {
                display: none;
            }

            .join-popup-body {
                padding: 1rem;
            }

            .join-popup-title {
                font-size: 1.32rem;
                margin-bottom: 0.72rem;
            }

            .join-field-grid {
                grid-template-columns: 1fr;
                gap: 0.52rem;
            }

            .join-popup-form {
                gap: 0.52rem;
            }

            .join-role-switch span {
                min-height: 38px;
                font-size: 0.78rem;
            }

            .join-popup-field input,
            .join-popup-field select,
            .join-phone-code {
                min-height: 42px;
                font-size: 0.8rem;
            }

            .join-phone-row {
                grid-template-columns: 112px minmax(0, 1fr);
                gap: 0.5rem;
            }
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

    <div class="market-topbar" aria-label="Marketplace updates">
        <div class="market-topbar-inner">
            <div class="topbar-links">
                <a href="{{ route('marketplace.page', ['page' => 'about-us']) }}">About Us</a>
                <a href="{{ route('marketplace.page', ['page' => 'customer-care']) }}">Support</a>
            </div>

            <div class="topbar-marquee" aria-label="Trade4Deal live updates">
                <div class="topbar-marquee-track">
                    <span><i class="bi bi-lightning-charge"></i> Connect with verified buyers and suppliers worldwide</span>
                    <span><i class="bi bi-megaphone"></i> Submit your business requirement and receive relevant trade responses</span>
                    <span><i class="bi bi-shield-check"></i> Trade smarter with Trade4Deal B2B marketplace tools</span>
                    <span><i class="bi bi-lightning-charge"></i> Connect with verified buyers and suppliers worldwide</span>
                    <span><i class="bi bi-megaphone"></i> Submit your business requirement and receive relevant trade responses</span>
                    <span><i class="bi bi-shield-check"></i> Trade smarter with Trade4Deal B2B marketplace tools</span>
                </div>
            </div>

            <div class="topbar-country" title="Your current country">
                <i class="bi bi-geo-alt-fill"></i>
                <span>Country:</span>
                <span class="topbar-country-text" id="topbarCountryName">Detecting...</span>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-xl navbar-t4d sticky-top">
        <div class="container-fluid market-nav-wrap gap-3 py-2">
            <a class="navbar-brand d-flex align-items-center text-decoration-none" href="{{ route('home') }}">
                <x-brand-logo :height="62" />
            </a>

            <div class="market-search-wrap" data-location-selector>
                <form class="market-search" id="marketplaceSearch" role="search" method="GET" action="{{ route('marketplace.page', ['page' => 'product-directory']) }}">
                    <button
                        class="market-search-location"
                        id="marketLocationButton"
                        type="button"
                        aria-haspopup="dialog"
                        aria-expanded="false"
                        aria-controls="marketLocationPopover"
                    >
                        <i class="bi bi-geo-alt-fill" aria-hidden="true"></i>
                        <span class="market-location-label" id="marketLocationLabel">Select location</span>
                    </button>
                    <label class="market-search-input" for="marketplaceSearchInput">
                        <span class="visually-hidden">Search products and categories</span>
                        <input id="marketplaceSearchInput" name="search" type="search" value="{{ request('search') }}" placeholder="Search Trade4Deal products or categories" autocomplete="off">
                    </label>
                    <input id="marketLocationIdInput" type="hidden" name="location_id" value="{{ request('location_id') }}">
                    <input id="marketLocationLabelInput" type="hidden" name="location_label" value="{{ request('location_label') }}">
                    <button class="market-search-submit" type="submit" aria-label="Search products"><i class="bi bi-search"></i></button>
                </form>

                <div class="location-popover" id="marketLocationPopover" role="dialog" aria-modal="false" aria-labelledby="marketLocationTitle">
                    <div class="location-popover-title">
                        <span id="marketLocationTitle">Choose location</span>
                        <button type="button" id="marketLocationClose" aria-label="Close location selector"><i class="bi bi-x-lg"></i></button>
                    </div>
                    <input class="location-search-input" id="marketLocationSearch" type="search" placeholder="Search city, country, UAE, USA, UK..." autocomplete="off">
                    <div class="location-actions">
                        <button class="location-action-btn" id="marketUseCurrentLocation" type="button"><i class="bi bi-crosshair me-1"></i>Use current</button>
                        <button class="location-action-btn" id="marketAllLocations" type="button"><i class="bi bi-globe2 me-1"></i>All locations</button>
                    </div>
                    <div class="location-status" id="marketLocationStatus" aria-live="polite"></div>
                    <div class="location-results" id="marketLocationResults" role="listbox" aria-label="Location suggestions"></div>
                </div>
            </div>
            <a href="{{ route('register') }}" class="mobile-register-inline">Register Free</a>

            <div class="mobile-finder-actions" aria-label="Find marketplace users">
                <button type="button" class="market-finder-btn" data-bs-toggle="modal" data-bs-target="#finderModal" data-finder-mode="seller">
                    <i class="bi bi-shop-window"></i>Find Seller
                </button>
                <button type="button" class="market-finder-btn find-buyer" data-bs-toggle="modal" data-bs-target="#finderModal" data-finder-mode="buyer">
                    <i class="bi bi-person-lines-fill"></i>Find Buyer
                </button>
            </div>

            <div class="market-header-actions">
                <button type="button" class="market-finder-btn" data-bs-toggle="modal" data-bs-target="#finderModal" data-finder-mode="seller">
                    <i class="bi bi-shop-window"></i>Find Seller
                </button>
                <button type="button" class="market-finder-btn find-buyer" data-bs-toggle="modal" data-bs-target="#finderModal" data-finder-mode="buyer">
                    <i class="bi bi-person-lines-fill"></i>Find Buyer
                </button>
                <a href="{{ route('marketplace.page', ['page' => 'product-directory']) }}" class="market-header-action">
                    <i class="bi bi-globe2"></i>
                    <span>Products</span>
                </a>
                <a href="{{ route('register') }}" class="market-header-action">
                    <i class="bi bi-shop-window"></i>
                    <span>Sell</span>
                </a>
                <a href="{{ route('contact') }}" class="market-header-action">
                    <i class="bi bi-question-circle"></i>
                    <span>Help</span>
                </a>
                <button type="button" class="market-header-action" data-bs-toggle="modal" data-bs-target="#leadModal">
                    <i class="bi bi-chat-left-text"></i>
                    <span>Messages</span>
                </button>
                @guest
                    <a href="{{ route('login') }}" class="market-header-action signin-action">
                        <i class="bi bi-person-circle"></i>
                        <span>Sign In</span>
                    </a>
                    <a href="{{ route('register') }}" class="market-register-btn">Register Free</a>
                @else
                    <a href="{{ route('dashboard') }}" class="market-header-action signin-action">
                        <i class="bi bi-person-circle"></i>
                        <span>Account</span>
                    </a>
                @endguest
                <button class="mobile-nav-toggle border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMain" aria-controls="navMain" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="mobile-nav-icon" aria-hidden="true">&#9776;</span>
                </button>
            </div>
            <div class="collapse navbar-collapse" id="navMain">
                <ul class="navbar-nav ms-auto align-items-xl-center gap-xl-2">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#categories">Categories</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('marketplace.page', ['page' => 'product-directory']) }}">Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#leads">Leads</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#how-it-works">How it works</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('plans.index') }}">Plans</a></li>
                    <li class="nav-item">
                        <button type="button" class="nav-link btn btn-link text-decoration-none fw-bold" data-bs-toggle="modal" data-bs-target="#finderModal" data-finder-mode="seller">
                            <i class="bi bi-shop-window me-1"></i>Find Seller
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link btn btn-link text-decoration-none fw-bold" data-bs-toggle="modal" data-bs-target="#finderModal" data-finder-mode="buyer">
                            <i class="bi bi-person-lines-fill me-1"></i>Find Buyer
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
                        <button class="btn btn-outline-t4d btn-sm" id="themeToggle" data-theme-toggle type="button" aria-label="Toggle theme">
                            <i class="bi bi-moon-stars"></i>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="mobile-bottom-nav" aria-label="Mobile quick actions">
        @guest
            <a href="{{ route('login') }}" class="bottom-signin">
                <i class="bi bi-person-circle"></i>
                <span>Sign In</span>
            </a>
        @else
            <a href="{{ route('dashboard') }}" class="bottom-signin">
                <i class="bi bi-person-circle"></i>
                <span>Account</span>
            </a>
        @endguest
        <button type="button" class="bottom-seller" data-bs-toggle="modal" data-bs-target="#finderModal" data-finder-mode="seller" aria-label="Find Seller">
            <i class="bi bi-shop-window"></i>
        </button>
        <button class="bottom-menu" type="button" data-bs-toggle="collapse" data-bs-target="#navMain" aria-controls="navMain" aria-expanded="false" aria-label="Toggle navigation">
            <span class="mobile-nav-icon" aria-hidden="true">&#9776;</span>
        </button>
        <button type="button" class="bottom-buyer" data-bs-toggle="modal" data-bs-target="#finderModal" data-finder-mode="buyer" aria-label="Find Buyer">
            <i class="bi bi-person-lines-fill"></i>
        </button>
        <button type="button" class="bottom-theme" data-theme-toggle aria-label="Toggle theme">
            <i class="bi bi-moon-stars"></i>
        </button>
    </div>

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

        <div class="modal fade finder-modal" id="finderModal" tabindex="-1" aria-labelledby="finderModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <div>
                            <span class="finder-mode-pill" id="finderModePill"><i class="bi bi-shop-window"></i> Find Seller</span>
                            <h2 class="modal-title h5 fw-bold mt-2" id="finderModalTitle">Search Trade4Deal Marketplace</h2>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted small mb-3" id="finderModalHelp">
                            Search visible products, sellers, and categories on this page.
                        </p>
                        <form id="finderSearchForm" class="finder-search-box" role="search">
                            <label class="visually-hidden" for="finderSearchInput">Search Trade4Deal</label>
                            <input id="finderSearchInput" type="search" placeholder="Search product, category, or company" autocomplete="off">
                            <button type="submit" class="btn btn-primary-t4d text-white px-4">
                                <i class="bi bi-search me-1"></i>Search
                            </button>
                        </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-outline-t4d" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-outline-t4d" data-bs-toggle="modal" data-bs-target="#leadModal" data-bs-dismiss="modal">
                            Submit Requirement
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @guest
            @unless(request()->routeIs('register', 'login', 'password.*', 'verification.*'))
                <div class="join-popup-backdrop" id="joinAutoPopup" aria-hidden="true">
                    <section class="join-popup-card" role="dialog" aria-modal="true" aria-labelledby="joinPopupTitle">
                        <div class="join-popup-visual">
                            <span class="join-popup-badge"><i class="bi bi-stars"></i> Free B2B account</span>
                            <h2>Start finding buyers and suppliers faster.</h2>
                            <p>Join Trade4Deal to connect with verified business opportunities, product listings, and active marketplace leads.</p>
                            <div class="join-popup-proof">
                                <span><i class="bi bi-check-circle-fill"></i> Buyer and seller profiles</span>
                                <span><i class="bi bi-check-circle-fill"></i> Live trade leads</span>
                                <span><i class="bi bi-check-circle-fill"></i> Global B2B reach</span>
                            </div>
                        </div>

                        <div class="join-popup-body">
                            <button type="button" class="join-popup-close" data-join-popup-close aria-label="Close create account popup">
                                <i class="bi bi-x-lg"></i>
                            </button>
                            <p class="join-popup-kicker">Trade4Deal Marketplace</p>
                            <h2 class="join-popup-title" id="joinPopupTitle">Create Account</h2>

                            <form class="join-popup-form" method="POST" action="{{ route('register') }}" data-join-popup-form>
                                @csrf

                                <div class="join-role-switch" aria-label="Select account type">
                                    <label>
                                        <input type="radio" name="user_type" value="buyer" data-join-role>
                                        <span><i class="bi bi-bag-check"></i> Buyer</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="user_type" value="seller" data-join-role checked>
                                        <span><i class="bi bi-shop-window"></i> Seller</span>
                                    </label>
                                </div>

                                <div class="join-field-grid">
                                    <div class="join-popup-field">
                                        <i class="bi bi-person"></i>
                                        <input type="text" name="name" placeholder="Name" autocomplete="name" required>
                                    </div>
                                    <div class="join-popup-field has-select">
                                        <span class="fi fi-in join-country-selected-flag" data-join-country-select-flag></span>
                                        <select name="country" data-join-country-select required>
                                            <option value="India" data-iso="in" data-dial="+91" data-min="10" data-max="10" selected>🇮🇳 India</option>
                                            <option value="United States" data-iso="us" data-dial="+1" data-min="10" data-max="10">🇺🇸 United States</option>
                                            <option value="United Kingdom" data-iso="gb" data-dial="+44" data-min="10" data-max="10">🇬🇧 United Kingdom</option>
                                            <option value="United Arab Emirates" data-iso="ae" data-dial="+971" data-min="9" data-max="9">🇦🇪 United Arab Emirates</option>
                                            <option value="Saudi Arabia" data-iso="sa" data-dial="+966" data-min="9" data-max="9">🇸🇦 Saudi Arabia</option>
                                            <option value="Canada" data-iso="ca" data-dial="+1" data-min="10" data-max="10">🇨🇦 Canada</option>
                                            <option value="Australia" data-iso="au" data-dial="+61" data-min="9" data-max="9">🇦🇺 Australia</option>
                                            <option value="Singapore" data-iso="sg" data-dial="+65" data-min="8" data-max="8">🇸🇬 Singapore</option>
                                            <option value="Germany" data-iso="de" data-dial="+49" data-min="10" data-max="11">🇩🇪 Germany</option>
                                            <option value="France" data-iso="fr" data-dial="+33" data-min="9" data-max="9">🇫🇷 France</option>
                                            <option value="China" data-iso="cn" data-dial="+86" data-min="11" data-max="11">🇨🇳 China</option>
                                            <option value="Japan" data-iso="jp" data-dial="+81" data-min="10" data-max="10">🇯🇵 Japan</option>
                                            <option value="Bangladesh" data-iso="bd" data-dial="+880" data-min="10" data-max="10">🇧🇩 Bangladesh</option>
                                            <option value="Pakistan" data-iso="pk" data-dial="+92" data-min="10" data-max="10">🇵🇰 Pakistan</option>
                                            <option value="Nepal" data-iso="np" data-dial="+977" data-min="10" data-max="10">🇳🇵 Nepal</option>
                                            <option value="Sri Lanka" data-iso="lk" data-dial="+94" data-min="9" data-max="9">🇱🇰 Sri Lanka</option>
                                            <option value="Nigeria" data-iso="ng" data-dial="+234" data-min="10" data-max="10">🇳🇬 Nigeria</option>
                                            <option value="South Africa" data-iso="za" data-dial="+27" data-min="9" data-max="9">🇿🇦 South Africa</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="join-phone-row">
                                    <div class="join-phone-code" aria-label="Selected country calling code">
                                        <span class="fi fi-in" data-join-phone-flag></span>
                                        <span data-join-phone-code>+91</span>
                                    </div>
                                    <div class="join-popup-field join-phone-field">
                                        <input type="tel" data-join-phone-local placeholder="Mobile number" inputmode="numeric" autocomplete="tel-national" maxlength="10" required>
                                    </div>
                                </div>
                                <input type="hidden" name="phone" data-join-phone-full>
                                <p class="join-phone-error" data-join-phone-error>Please enter a valid mobile number for selected country.</p>

                                <div class="join-popup-field">
                                    <i class="bi bi-envelope"></i>
                                    <input type="email" name="email" placeholder="Email" autocomplete="email" required>
                                </div>

                                <div class="join-field-grid">
                                    <div class="join-popup-field join-seller-only">
                                        <i class="bi bi-box-seam"></i>
                                        <input type="text" name="selling_products" placeholder="Selling Products">
                                    </div>
                                    <div class="join-popup-field">
                                        <i class="bi bi-building"></i>
                                        <input type="text" name="company_name" placeholder="Company Name" autocomplete="organization" required>
                                    </div>
                                </div>

                                <input type="hidden" name="password" data-join-generated-password>
                                <input type="hidden" name="password_confirmation" data-join-generated-password-confirmation>

                                <button type="submit" class="join-popup-submit">Submit</button>

                                <p class="join-popup-terms">
                                    * By joining, I agree to Trade4Deal
                                    <a href="{{ route('marketplace.page', ['page' => 'terms-of-use']) }}">terms of use</a>
                                    and
                                    <a href="{{ route('marketplace.page', ['page' => 'privacy-policy']) }}">Privacy Policy</a>.
                                </p>
                            </form>
                        </div>
                    </section>
                </div>
            @endunless
        @endguest

        <section class="testimonial-strip py-5" aria-labelledby="testimonialTitle">
            <div class="container">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-4">
                    <div>
                        <span class="badge rounded-pill text-bg-primary px-3 py-2 mb-2">Testimonials</span>
                        <h2 class="section-title mb-2" id="testimonialTitle">Trusted by growing trade businesses</h2>
                        <p class="text-muted mb-0" style="max-width: 620px;">
                            Practical feedback from buyers, sellers, and sourcing teams using Trade4Deal to move conversations faster.
                        </p>
                    </div>
                    <div class="d-flex align-items-center gap-2 text-muted small">
                        <i class="bi bi-shield-check text-primary"></i>
                        Global B2B marketplace support
                    </div>
                </div>

                <div id="testimonialCarousel" class="carousel slide testimonial-slider" data-bs-ride="carousel" data-bs-interval="5500">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#testimonialCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Testimonials page 1"></button>
                        <button type="button" data-bs-target="#testimonialCarousel" data-bs-slide-to="1" aria-label="Testimonials page 2"></button>
                        <button type="button" data-bs-target="#testimonialCarousel" data-bs-slide-to="2" aria-label="Testimonials page 3"></button>
                    </div>

                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <div class="row g-3">
                                <div class="col-lg-4">
                                    <article class="testimonial-card">
                                        <div class="d-flex justify-content-between align-items-start gap-3">
                                            <span class="testimonial-quote"><i class="bi bi-quote"></i></span>
                                            <span class="testimonial-rating">★★★★★</span>
                                        </div>
                                        <p class="mb-0">Trade4Deal helped us find serious machinery buyers without wasting days on cold outreach. The lead quality felt relevant and easy to follow up.</p>
                                        <div class="testimonial-person">
                                            <span class="testimonial-avatar">AS</span>
                                            <div>
                                                <div class="fw-bold">Amit Sharma</div>
                                                <div class="small text-muted">Apex Industrial Supplies, India</div>
                                            </div>
                                        </div>
                                    </article>
                                </div>
                                <div class="col-lg-4">
                                    <article class="testimonial-card">
                                        <div class="d-flex justify-content-between align-items-start gap-3">
                                            <span class="testimonial-quote"><i class="bi bi-quote"></i></span>
                                            <span class="testimonial-rating">★★★★★</span>
                                        </div>
                                        <p class="mb-0">We posted a textile sourcing requirement and received supplier responses that matched our quantity and payment preferences. It made comparison much simpler.</p>
                                        <div class="testimonial-person">
                                            <span class="testimonial-avatar">NK</span>
                                            <div>
                                                <div class="fw-bold">Nisha Kapoor</div>
                                                <div class="small text-muted">Urban Loom Exports, Delhi NCR</div>
                                            </div>
                                        </div>
                                    </article>
                                </div>
                                <div class="col-lg-4">
                                    <article class="testimonial-card">
                                        <div class="d-flex justify-content-between align-items-start gap-3">
                                            <span class="testimonial-quote"><i class="bi bi-quote"></i></span>
                                            <span class="testimonial-rating">★★★★★</span>
                                        </div>
                                        <p class="mb-0">The platform is clean, professional, and focused on business. Our sales team uses Trade4Deal to track fresh international enquiries every week.</p>
                                        <div class="testimonial-person">
                                            <span class="testimonial-avatar">RK</span>
                                            <div>
                                                <div class="fw-bold">Rahul Khanna</div>
                                                <div class="small text-muted">Nova Components Pvt. Ltd.</div>
                                            </div>
                                        </div>
                                    </article>
                                </div>
                            </div>
                        </div>

                        <div class="carousel-item">
                            <div class="row g-3">
                                <div class="col-lg-4">
                                    <article class="testimonial-card">
                                        <div class="d-flex justify-content-between align-items-start gap-3">
                                            <span class="testimonial-quote"><i class="bi bi-quote"></i></span>
                                            <span class="testimonial-rating">★★★★★</span>
                                        </div>
                                        <p class="mb-0">For export enquiries, timing matters. Trade4Deal gave us a steady way to discover buyer interest before competitors reached the same conversation.</p>
                                        <div class="testimonial-person">
                                            <span class="testimonial-avatar">MP</span>
                                            <div>
                                                <div class="fw-bold">Mehul Patel</div>
                                                <div class="small text-muted">Patel Agro Traders, Gujarat</div>
                                            </div>
                                        </div>
                                    </article>
                                </div>
                                <div class="col-lg-4">
                                    <article class="testimonial-card">
                                        <div class="d-flex justify-content-between align-items-start gap-3">
                                            <span class="testimonial-quote"><i class="bi bi-quote"></i></span>
                                            <span class="testimonial-rating">★★★★★</span>
                                        </div>
                                        <p class="mb-0">Our procurement team liked the direct contact flow. We could explain requirements clearly and connect with sellers for pricing and samples quickly.</p>
                                        <div class="testimonial-person">
                                            <span class="testimonial-avatar">FA</span>
                                            <div>
                                                <div class="fw-bold">Farhan Ali</div>
                                                <div class="small text-muted">GulfStar Trading LLC, Dubai</div>
                                            </div>
                                        </div>
                                    </article>
                                </div>
                                <div class="col-lg-4">
                                    <article class="testimonial-card">
                                        <div class="d-flex justify-content-between align-items-start gap-3">
                                            <span class="testimonial-quote"><i class="bi bi-quote"></i></span>
                                            <span class="testimonial-rating">★★★★★</span>
                                        </div>
                                        <p class="mb-0">Trade4Deal gave our packaging business a better online presence. The seller profile and product listing pages look trustworthy to new buyers.</p>
                                        <div class="testimonial-person">
                                            <span class="testimonial-avatar">PG</span>
                                            <div>
                                                <div class="fw-bold">Priya Gupta</div>
                                                <div class="small text-muted">PrimePack Solutions, Noida</div>
                                            </div>
                                        </div>
                                    </article>
                                </div>
                            </div>
                        </div>

                        <div class="carousel-item">
                            <div class="row g-3">
                                <div class="col-lg-6">
                                    <article class="testimonial-card">
                                        <div class="d-flex justify-content-between align-items-start gap-3">
                                            <span class="testimonial-quote"><i class="bi bi-quote"></i></span>
                                            <span class="testimonial-rating">★★★★★</span>
                                        </div>
                                        <p class="mb-0">We needed reliable electronics component suppliers. Trade4Deal made it easier to shortlist companies by category and start focused discussions.</p>
                                        <div class="testimonial-person">
                                            <span class="testimonial-avatar">SM</span>
                                            <div>
                                                <div class="fw-bold">Sarah Mitchell</div>
                                                <div class="small text-muted">BrightLine Retail Group, UK</div>
                                            </div>
                                        </div>
                                    </article>
                                </div>
                                <div class="col-lg-6">
                                    <article class="testimonial-card">
                                        <div class="d-flex justify-content-between align-items-start gap-3">
                                            <span class="testimonial-quote"><i class="bi bi-quote"></i></span>
                                            <span class="testimonial-rating">★★★★★</span>
                                        </div>
                                        <p class="mb-0">The lead board is simple but useful. We can see buyer intent, product category, country, and payment details before starting the conversation.</p>
                                        <div class="testimonial-person">
                                            <span class="testimonial-avatar">VT</span>
                                            <div>
                                                <div class="fw-bold">Vikram Taneja</div>
                                                <div class="small text-muted">Taneja BuildMart, Rajasthan</div>
                                            </div>
                                        </div>
                                    </article>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button class="carousel-control-prev testimonial-control" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev" aria-label="Previous testimonials">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    </button>
                    <button class="carousel-control-next testimonial-control" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next" aria-label="Next testimonials">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    </button>
                </div>

                @php($clientLogos = config('marketplace_assets.clients', []))
                <div class="client-logo-slider" aria-labelledby="clientLogoTitle">
                    <div class="client-logo-head">
                        <h3 id="clientLogoTitle">Trusted company network</h3>
                        <span>Brands connected through Trade4Deal</span>
                    </div>
                    <div class="client-logo-marquee">
                        <div class="client-logo-track">
                            @foreach(array_merge($clientLogos, $clientLogos) as $logo)
                                <div class="client-logo-card">
                                    <img src="{{ asset($logo['src']) }}" alt="{{ $logo['name'] }} logo" loading="lazy">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <footer class="t4d-footer">
            <div class="footer-marketplace">
                <div class="footer-market-grid">
                    <div>
                        <x-brand-logo :height="54" />
                        <p class="footer-brand-copy mt-3 mb-0">Discover Buyers. Find Suppliers. Grow Business. Powering smarter B2B connections worldwide.</p>
                        <div class="footer-contact-stack">
                            <a class="footer-contact-pill" href="tel:+911204633260"><i class="bi bi-telephone"></i>+91 120 463 3260</a>
                            <a class="footer-contact-pill" href="mailto:info@trade4deal.com"><i class="bi bi-envelope"></i>info@trade4deal.com</a>
                        </div>
                        <div class="footer-social" aria-label="Social links">
                            <a href="https://www.facebook.com/profile.php?id=61577958366872" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                            <a href="#" aria-label="X"><i class="bi bi-twitter-x"></i></a>
                            <a href="#" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
                            <a href="https://www.youtube.com/@Trade4Deal" target="_blank" rel="noopener noreferrer" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
                            <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                        </div>
                    </div>

                    <div class="footer-col">
                        <h3>Company</h3>
                        <a href="{{ route('marketplace.page', ['page' => 'about-us']) }}">About Us</a>
                        <a href="{{ route('contact') }}">Contact Us</a>
                        <a href="{{ route('plans.index') }}">Plans</a>
                        <a href="{{ route('register') }}">Join Trade4Deal</a>
                    </div>

                    <div class="footer-col">
                        <h3>Help &amp; Support</h3>
                        <a href="{{ route('marketplace.page', ['page' => 'help']) }}">Help</a>
                        <a href="{{ route('marketplace.page', ['page' => 'feedback']) }}">Feedback</a>
                        <a href="{{ route('marketplace.page', ['page' => 'customer-care']) }}">Customer Care</a>
                        <a href="{{ route('marketplace.page', ['page' => 'live-leads']) }}">Live Leads</a>
                    </div>

                    <div class="footer-col">
                        <h3>Suppliers Tool Kit</h3>
                        <a href="{{ route('marketplace.page', ['page' => 'sell-on-trade4deal']) }}">Sell on Trade4Deal</a>
                        <a href="{{ route('marketplace.page', ['page' => 'latest-trade-leads']) }}">Latest Trade Leads</a>
                        <a href="{{ route('marketplace.page', ['page' => 'product-directory']) }}">Product Directory</a>
                        <a href="{{ route('marketplace.page', ['page' => 'lead-board']) }}">Lead Board</a>
                    </div>

                    <div class="footer-col">
                        <h3>Buyers Tool Kit</h3>
                        <a href="{{ route('marketplace.page', ['page' => 'submit-requirement']) }}">Submit Requirement</a>
                        <a href="{{ route('marketplace.page', ['page' => 'search-products']) }}">Search Products</a>
                        <a href="{{ route('marketplace.page', ['page' => 'payment-safety']) }}">Payment Safety</a>
                        <a href="{{ route('marketplace.page', ['page' => 'seller-verification']) }}">Seller Verification</a>
                    </div>

                    <div class="footer-col">
                        <h3>Trade4Deal Coverage</h3>
                        <a href="{{ route('marketplace.page', ['page' => 'global-buyers']) }}">Global Buyers</a>
                        <a href="{{ route('marketplace.page', ['page' => 'verified-suppliers']) }}">Verified Suppliers</a>
                        <a href="{{ route('marketplace.page', ['page' => 'marketplace-leads']) }}">Marketplace Leads</a>
                        <h3 class="mt-3">Business Tools</h3>
                        <a href="{{ route('marketplace.page', ['page' => 'categories']) }}">Categories</a>
                        <a href="{{ route('marketplace.page', ['page' => 'enquiries']) }}">Enquiries</a>
                    </div>
                </div>

                <div class="footer-bottom">
                    <div>
                        <a href="{{ route('marketplace.page', ['page' => 'terms-of-use']) }}">Terms of Use</a>
                        <a href="{{ route('marketplace.page', ['page' => 'privacy-policy']) }}">Privacy Policy</a>
                    </div>
                    <div>&copy; {{ date('Y') }} Trade4Deal. All rights reserved.</div>
                </div>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
    <script>
        (function () {
            const root = document.documentElement;
            const themeToggles = Array.from(document.querySelectorAll('[data-theme-toggle]'));
            // Force brand default: light + navy. Only honor stored preference if set.
            const stored = localStorage.getItem('t4d-theme');
            const theme = stored || 'light';
            root.setAttribute('data-bs-theme', theme);

            function syncThemeIcons(currentTheme) {
                themeToggles.forEach((toggle) => {
                    const icon = toggle.querySelector('i');
                    if (icon) icon.className = currentTheme === 'dark' ? 'bi bi-sun' : 'bi bi-moon-stars';
                });
            }

            if (themeToggles.length) {
                syncThemeIcons(theme);
                themeToggles.forEach((toggle) => toggle.addEventListener('click', () => {
                    const next = root.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
                    root.setAttribute('data-bs-theme', next);
                    localStorage.setItem('t4d-theme', next);
                    syncThemeIcons(next);
                }));
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

            const topbarCountryName = document.getElementById('topbarCountryName');
            if (topbarCountryName) {
                const timezoneCountryMap = {
                    'Asia/Calcutta': 'India',
                    'Asia/Kolkata': 'India',
                    'Asia/Dubai': 'United Arab Emirates',
                    'Asia/Riyadh': 'Saudi Arabia',
                    'Asia/Singapore': 'Singapore',
                    'Asia/Dhaka': 'Bangladesh',
                    'Asia/Karachi': 'Pakistan',
                    'Asia/Kathmandu': 'Nepal',
                    'Asia/Colombo': 'Sri Lanka',
                    'Asia/Shanghai': 'China',
                    'Asia/Tokyo': 'Japan',
                    'Europe/London': 'United Kingdom',
                    'Europe/Berlin': 'Germany',
                    'Europe/Paris': 'France',
                    'Australia/Sydney': 'Australia',
                    'America/New_York': 'United States',
                    'America/Chicago': 'United States',
                    'America/Denver': 'United States',
                    'America/Los_Angeles': 'United States',
                    'America/Toronto': 'Canada',
                    'Africa/Lagos': 'Nigeria',
                    'Africa/Johannesburg': 'South Africa',
                };

                function countryFromLocationLabel(label) {
                    const cleaned = (label || '').trim();
                    if (!cleaned || cleaned === 'All locations' || cleaned === 'Select location') return '';
                    const parts = cleaned.split(',').map((part) => part.trim()).filter(Boolean);

                    return parts[parts.length - 1] || cleaned;
                }

                function fallbackCountry() {
                    const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone || '';
                    return timezoneCountryMap[timezone] || timezone.split('/').pop()?.replace(/_/g, ' ') || 'Your country';
                }

                function setTopbarCountry(label) {
                    topbarCountryName.textContent = countryFromLocationLabel(label) || fallbackCountry();
                }

                try {
                    const storedLocation = JSON.parse(localStorage.getItem('t4d-location-selection') || 'null');
                    setTopbarCountry(storedLocation?.full_label || storedLocation?.label || '');
                } catch (error) {
                    setTopbarCountry('');
                }

                window.addEventListener('t4d-location-change', (event) => {
                    setTopbarCountry(event.detail?.label || '');
                });
            }

            const joinPopup = document.getElementById('joinAutoPopup');
            if (joinPopup) {
                const closeButtons = Array.from(joinPopup.querySelectorAll('[data-join-popup-close]'));
                const roleInputs = Array.from(joinPopup.querySelectorAll('[data-join-role]'));
                const sellerFields = Array.from(joinPopup.querySelectorAll('.join-seller-only'));
                const popupForm = joinPopup.querySelector('[data-join-popup-form]');
                const countrySelect = joinPopup.querySelector('[data-join-country-select]');
                const phoneLocal = joinPopup.querySelector('[data-join-phone-local]');
                const phoneFull = joinPopup.querySelector('[data-join-phone-full]');
                const phoneFlag = joinPopup.querySelector('[data-join-phone-flag]');
                const countrySelectFlag = joinPopup.querySelector('[data-join-country-select-flag]');
                const phoneCode = joinPopup.querySelector('[data-join-phone-code]');
                const phoneError = joinPopup.querySelector('[data-join-phone-error]');
                const generatedPassword = joinPopup.querySelector('[data-join-generated-password]');
                const generatedPasswordConfirmation = joinPopup.querySelector('[data-join-generated-password-confirmation]');
                const sessionKey = 't4d-join-popup-dismissed';
                let hasOpened = false;

                function syncJoinRole() {
                    const selected = roleInputs.find((input) => input.checked)?.value || 'seller';
                    sellerFields.forEach((field) => {
                        field.classList.toggle('is-hidden', selected !== 'seller');
                    });
                }

                function selectedCountryMeta() {
                    const option = countrySelect?.selectedOptions?.[0];

                    return {
                        dial: option?.dataset.dial || '+91',
                        iso: option?.dataset.iso || 'in',
                        min: Number(option?.dataset.min || 10),
                        max: Number(option?.dataset.max || 10),
                    };
                }

                function syncJoinCountry() {
                    const country = selectedCountryMeta();
                    if (phoneFlag) phoneFlag.className = `fi fi-${country.iso}`;
                    if (countrySelectFlag) countrySelectFlag.className = `fi fi-${country.iso} join-country-selected-flag`;
                    if (phoneCode) phoneCode.textContent = country.dial;
                    if (phoneLocal) {
                        phoneLocal.maxLength = country.max;
                        phoneLocal.placeholder = country.max === country.min
                            ? `Mobile number (${country.max} digits)`
                            : `Mobile number (${country.min}-${country.max} digits)`;
                    }
                    validateJoinPhone(false);
                }

                function normalizePhoneDigits(value) {
                    return (value || '').replace(/\D/g, '');
                }

                function validateJoinPhone(showError = true) {
                    if (!phoneLocal || !phoneFull) return true;
                    const country = selectedCountryMeta();
                    const digits = normalizePhoneDigits(phoneLocal.value).slice(0, country.max);
                    if (phoneLocal.value !== digits) phoneLocal.value = digits;

                    const isValid = digits.length >= country.min && digits.length <= country.max;
                    phoneFull.value = isValid ? `${country.dial}${digits}` : '';

                    if (phoneError) phoneError.classList.toggle('is-visible', showError && !isValid);
                    phoneLocal.setCustomValidity(isValid ? '' : 'Enter a valid mobile number for selected country.');

                    return isValid;
                }

                function generatePopupPassword() {
                    const bytes = new Uint32Array(2);
                    if (window.crypto?.getRandomValues) {
                        window.crypto.getRandomValues(bytes);
                    } else {
                        bytes[0] = Math.floor(Math.random() * 1000000000);
                        bytes[1] = Date.now();
                    }

                    return `T4d#${bytes[0].toString(36)}${bytes[1].toString(36)}X9`;
                }

                function openJoinPopup() {
                    if (hasOpened || sessionStorage.getItem(sessionKey) === 'true') return;
                    hasOpened = true;
                    joinPopup.classList.add('is-visible');
                    joinPopup.setAttribute('aria-hidden', 'false');
                    document.body.classList.add('t4d-popup-open');
                    window.setTimeout(() => {
                        joinPopup.querySelector('input[name="name"]')?.focus({ preventScroll: true });
                    }, 120);
                }

                function closeJoinPopup(remember = true) {
                    joinPopup.classList.remove('is-visible');
                    joinPopup.setAttribute('aria-hidden', 'true');
                    document.body.classList.remove('t4d-popup-open');
                    if (remember) sessionStorage.setItem(sessionKey, 'true');
                }

                function maybeOpenJoinPopup() {
                    if (hasOpened || sessionStorage.getItem(sessionKey) === 'true') return;
                    const scrollable = document.documentElement.scrollHeight - window.innerHeight;
                    const progress = scrollable > 0 ? window.scrollY / scrollable : 0;
                    if (window.scrollY > 260 || progress > 0.18) openJoinPopup();
                }

                syncJoinRole();
                syncJoinCountry();
                roleInputs.forEach((input) => input.addEventListener('change', syncJoinRole));
                countrySelect?.addEventListener('change', syncJoinCountry);
                phoneLocal?.addEventListener('input', () => validateJoinPhone(false));
                phoneLocal?.addEventListener('blur', () => validateJoinPhone(true));
                popupForm?.addEventListener('submit', (event) => {
                    if (!validateJoinPhone(true)) {
                        event.preventDefault();
                        event.stopPropagation();
                        phoneLocal?.focus();
                        return;
                    }

                    const password = generatePopupPassword();
                    if (generatedPassword) generatedPassword.value = password;
                    if (generatedPasswordConfirmation) generatedPasswordConfirmation.value = password;
                });
                closeButtons.forEach((button) => button.addEventListener('click', () => closeJoinPopup()));
                joinPopup.addEventListener('click', (event) => {
                    if (event.target === joinPopup) closeJoinPopup();
                });
                document.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape' && joinPopup.classList.contains('is-visible')) closeJoinPopup();
                });
                window.addEventListener('scroll', maybeOpenJoinPopup, { passive: true });
                window.setTimeout(maybeOpenJoinPopup, 1800);
            }

            function scrollToMarketplaceResult(query) {
                const normalized = (query || '').trim().toLowerCase();
                const target = normalized
                    ? Array.from(document.querySelectorAll('.marketplace-search-item')).find((item) => {
                        return (item.getAttribute('data-search-text') || '').includes(normalized);
                    })
                    : document.getElementById('category-products');

                (target || document.getElementById('category-products') || document.getElementById('categories'))?.scrollIntoView({
                    behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth',
                    block: 'start',
                });
            }

            const locationRoot = document.querySelector('[data-location-selector]');
            const locationButton = document.getElementById('marketLocationButton');
            const locationLabel = document.getElementById('marketLocationLabel');
            const locationPopover = document.getElementById('marketLocationPopover');
            const locationClose = document.getElementById('marketLocationClose');
            const locationSearch = document.getElementById('marketLocationSearch');
            const locationResults = document.getElementById('marketLocationResults');
            const locationStatus = document.getElementById('marketLocationStatus');
            const useCurrentLocation = document.getElementById('marketUseCurrentLocation');
            const allLocations = document.getElementById('marketAllLocations');
            const locationIdInput = document.getElementById('marketLocationIdInput');
            const locationLabelInput = document.getElementById('marketLocationLabelInput');
            const marketplaceSearch = document.getElementById('marketplaceSearch');

            if (locationRoot && locationButton && locationPopover && locationLabel && locationIdInput && locationLabelInput) {
                const storageKey = 't4d-location-selection';
                const detectionKey = 't4d-location-detection-attempted';
                const url = new URL(window.location.href);
                const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                let activeLocation = null;
                let searchTimer = null;
                let searchController = null;
                let searchRequestId = 0;
                let detectionRequestId = 0;

                function validStoredLocation(value) {
                    if (!value || typeof value !== 'object') return null;
                    if (value.expires_at && Date.parse(value.expires_at) < Date.now()) return null;
                    if (value.id === '' && value.source === 'all') return value;
                    if (!value.id || !value.label) return null;

                    return value;
                }

                function storedLocation() {
                    try {
                        return validStoredLocation(JSON.parse(localStorage.getItem(storageKey) || 'null'));
                    } catch (error) {
                        return null;
                    }
                }

                function saveLocation(location) {
                    if (!location) {
                        localStorage.removeItem(storageKey);
                        return;
                    }

                    localStorage.setItem(storageKey, JSON.stringify({
                        id: location.id || '',
                        label: location.label || 'Select location',
                        full_label: location.full_label || location.label || 'Select location',
                        source: location.source || 'manual',
                        expires_at: location.expires_at || new Date(Date.now() + 180 * 24 * 60 * 60 * 1000).toISOString(),
                    }));
                }

                function locationFromUrl() {
                    const id = (url.searchParams.get('location_id') || '').trim();
                    const label = (url.searchParams.get('location_label') || '').trim();
                    if (!id) return null;

                    return {
                        id,
                        label: label || 'Selected location',
                        full_label: label || 'Selected location',
                        source: 'url',
                        expires_at: new Date(Date.now() + 24 * 60 * 60 * 1000).toISOString(),
                    };
                }

                function setStatus(message) {
                    if (locationStatus) locationStatus.textContent = message || '';
                }

                function applyLocation(location, options = {}) {
                    activeLocation = location?.id || location?.source === 'all' ? location : null;
                    const label = activeLocation?.label || 'Select location';
                    const fullLabel = activeLocation?.full_label || label;

                    locationLabel.textContent = label;
                    locationButton.title = fullLabel;
                    locationIdInput.value = activeLocation?.id || '';
                    locationLabelInput.value = activeLocation?.id ? fullLabel : '';
                    window.dispatchEvent(new CustomEvent('t4d-location-change', {
                        detail: {
                            id: activeLocation?.id || '',
                            label: activeLocation?.id ? fullLabel : '',
                        },
                    }));

                    if (options.save) saveLocation(activeLocation);
                    if (options.close) closeLocationPopover();
                    if (options.submit && marketplaceSearch) marketplaceSearch.requestSubmit();
                }

                function openLocationPopover() {
                    const rect = locationButton.getBoundingClientRect();
                    locationPopover.classList.add('is-open');
                    locationPopover.style.top = `${Math.min(rect.bottom + 8, window.innerHeight - 24)}px`;
                    locationPopover.style.left = `${Math.min(Math.max(12, rect.left), window.innerWidth - locationPopover.offsetWidth - 12)}px`;
                    locationButton.setAttribute('aria-expanded', 'true');
                    setTimeout(() => locationSearch?.focus(), 30);
                    fetchLocationSuggestions(locationSearch?.value || '');
                }

                function closeLocationPopover() {
                    locationPopover.classList.remove('is-open');
                    locationButton.setAttribute('aria-expanded', 'false');
                }

                function renderLocations(locations) {
                    if (!locationResults) return;
                    locationResults.innerHTML = '';
                    locations.forEach((location) => {
                        if (!location?.id) return;
                        const button = document.createElement('button');
                        button.type = 'button';
                        button.className = 'location-option-btn';
                        button.setAttribute('role', 'option');
                        button.innerHTML = `${escapeHtml(location.label || 'Location')}<span>${escapeHtml(location.full_label || location.label || '')}</span>`;
                        button.addEventListener('click', () => {
                            applyLocation({
                                ...location,
                                source: 'manual',
                                expires_at: new Date(Date.now() + 180 * 24 * 60 * 60 * 1000).toISOString(),
                            }, { save: true, close: true, submit: isProductDirectory() });
                        });
                        locationResults.appendChild(button);
                    });
                }

                function escapeHtml(value) {
                    return String(value).replace(/[&<>"']/g, (char) => ({
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;',
                        '"': '&quot;',
                        "'": '&#039;',
                    }[char]));
                }

                function fetchLocationSuggestions(query) {
                    window.clearTimeout(searchTimer);
                    searchTimer = window.setTimeout(() => {
                        const trimmed = query.trim();
                        if (trimmed.length === 1) {
                            searchController?.abort();
                            renderLocations([]);
                            setStatus('Type at least 2 characters to search.');
                            return;
                        }

                        searchController?.abort();
                        searchController = new AbortController();
                        const requestId = ++searchRequestId;
                        const params = new URLSearchParams();
                        if (trimmed !== '') params.set('q', trimmed);
                        setStatus('Loading locations...');

                        fetch(`{{ route('locations.search') }}?${params.toString()}`, {
                            headers: { Accept: 'application/json' },
                            signal: searchController.signal,
                        })
                            .then((response) => response.ok ? response.json() : Promise.reject())
                            .then((payload) => {
                                if (requestId !== searchRequestId) return;
                                const locations = Array.isArray(payload.locations) ? payload.locations : [];
                                renderLocations(locations);
                                setStatus(locations.length ? 'Select a city to filter product results.' : 'No matching locations found. Try city or country name.');
                            })
                            .catch((error) => {
                                if (error?.name === 'AbortError') return;
                                if (requestId !== searchRequestId) return;
                                renderLocations([]);
                                setStatus('Location search is temporarily unavailable.');
                            });
                    }, query.trim() === '' ? 0 : 220);
                }

                function detectLocation(options = {}) {
                    if (!navigator.geolocation) {
                        setStatus('Your browser does not support location detection. Search for a city instead.');
                        return;
                    }

                    if (!options.manual && localStorage.getItem(detectionKey)) return;
                    localStorage.setItem(detectionKey, 'true');
                    setStatus('Requesting location permission...');
                    const requestId = ++detectionRequestId;

                    navigator.geolocation.getCurrentPosition((position) => {
                        if (requestId !== detectionRequestId) return;
                        if (!options.manual && (activeLocation?.source === 'manual' || activeLocation?.source === 'all')) return;

                        const lat = Math.round(position.coords.latitude * 1000) / 1000;
                        const lng = Math.round(position.coords.longitude * 1000) / 1000;
                        setStatus('Finding your city...');

                        fetch(`{{ route('locations.reverse') }}`, {
                            method: 'POST',
                            headers: {
                                Accept: 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrf,
                            },
                            body: JSON.stringify({ lat, lng }),
                        })
                            .then(async (response) => {
                                if (response.ok) {
                                    return response.json();
                                }

                                const payload = await response.json().catch(() => ({}));
                                const error = new Error(payload.message || 'reverse_failed');
                                error.status = response.status;
                                throw error;
                            })
                            .then((payload) => {
                                if (requestId !== detectionRequestId) return;
                                if (!options.manual && (activeLocation?.source === 'manual' || activeLocation?.source === 'all')) return;
                                if (!payload.location?.id) throw new Error('unresolved');

                                applyLocation({
                                    ...payload.location,
                                    source: 'detected',
                                    expires_at: new Date(Date.now() + 7 * 24 * 60 * 60 * 1000).toISOString(),
                                }, { save: true, submit: isProductDirectory() && !hasUrlLocation() });
                                setStatus(`Detected ${payload.location.full_label || payload.location.label}.`);
                            })
                            .catch((error) => {
                                if (requestId !== detectionRequestId) return;
                                if (error?.status === 503) {
                                    setStatus('Current location lookup is not enabled. Search for a city instead.');
                                } else {
                                    setStatus('Could not detect your city. Search for a location instead.');
                                }
                                if (!activeLocation) applyLocation(null);
                            });
                    }, (error) => {
                        if (requestId !== detectionRequestId) return;
                        const denied = error.code === error.PERMISSION_DENIED;
                        setStatus(denied ? 'Location permission was denied. Search for a city instead.' : 'Location detection timed out. Search for a city instead.');
                        if (!activeLocation) applyLocation(null);
                    }, {
                        enableHighAccuracy: false,
                        timeout: 8000,
                        maximumAge: 0,
                    });
                }

                function hasUrlLocation() {
                    return Boolean((new URL(window.location.href)).searchParams.get('location_id'));
                }

                function isProductDirectory() {
                    return window.location.pathname.includes('/pages/product-directory');
                }

                locationButton.addEventListener('click', openLocationPopover);
                locationClose?.addEventListener('click', closeLocationPopover);
                useCurrentLocation?.addEventListener('click', () => detectLocation({ manual: true }));
                allLocations?.addEventListener('click', () => {
                    applyLocation({
                        id: '',
                        label: 'All locations',
                        full_label: 'All locations',
                        source: 'all',
                        expires_at: new Date(Date.now() + 180 * 24 * 60 * 60 * 1000).toISOString(),
                    }, { save: true, close: true, submit: isProductDirectory() });
                });
                locationSearch?.addEventListener('input', () => fetchLocationSuggestions(locationSearch.value));

                document.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape' && locationPopover.classList.contains('is-open')) closeLocationPopover();
                });
                document.addEventListener('click', (event) => {
                    if (!locationPopover.classList.contains('is-open')) return;
                    if (locationRoot.contains(event.target)) return;
                    closeLocationPopover();
                });
                window.addEventListener('resize', () => {
                    if (locationPopover.classList.contains('is-open')) openLocationPopover();
                });

                const initial = locationFromUrl() || storedLocation();
                if (initial) {
                    applyLocation(initial);
                    if (isProductDirectory() && initial.id && !hasUrlLocation()) {
                        marketplaceSearch?.requestSubmit();
                    }
                } else {
                    applyLocation(null);
                    detectLocation();
                }
            }

            const finderModal = document.getElementById('finderModal');
            if (finderModal) {
                finderModal.addEventListener('show.bs.modal', function (event) {
                    const trigger = event.relatedTarget;
                    const mode = trigger?.getAttribute('data-finder-mode') === 'buyer' ? 'buyer' : 'seller';
                    const title = mode === 'buyer' ? 'Find Buyer' : 'Find Seller';
                    const icon = mode === 'buyer' ? 'bi bi-person-lines-fill' : 'bi bi-shop-window';
                    const pill = document.getElementById('finderModePill');
                    const input = document.getElementById('finderSearchInput');
                    const help = document.getElementById('finderModalHelp');

                    if (pill) pill.innerHTML = '<i class="' + icon + '"></i> ' + title;
                    if (help) {
                        help.textContent = mode === 'buyer'
                            ? 'Search visible leads and buyer requirements on this page.'
                            : 'Search visible products, sellers, and categories on this page.';
                    }
                    if (input) {
                        input.placeholder = mode === 'buyer'
                            ? 'Search buyer lead, product need, or country'
                            : 'Search product, category, or seller company';
                        setTimeout(() => input.focus(), 250);
                    }
                });
            }

            document.addEventListener('submit', function (e) {
                const form = e.target;
                if (!(form instanceof HTMLFormElement)) return;
                if (form.id === 'finderSearchForm') {
                    e.preventDefault();
                    scrollToMarketplaceResult(document.getElementById('finderSearchInput')?.value || '');
                    const modal = bootstrap.Modal.getInstance(document.getElementById('finderModal'));
                    modal?.hide();
                    return;
                }
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
