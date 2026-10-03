@extends('layouts.marketplace')

@section('title', 'Trade4Deal - B2B Marketplace')

@php
    $categoryIcons = [
        'machinery' => 'bi-gear-wide-connected',
        'textiles' => 'bi-bag-heart',
        'electronics' => 'bi-cpu',
        'agriculture' => 'bi-flower1',
        'chemicals' => 'bi-droplet-half',
        'construction' => 'bi-bricks',
        'medical' => 'bi-heart-pulse',
        'food' => 'bi-basket2',
        'other' => 'bi-grid-3x3-gap',
    ];
@endphp

@push('styles')
<style>
    .imart-home {
        background: #f5f7fb;
        color: #08111f;
        overflow-x: clip;
    }

    .imart-container {
        max-width: 1510px;
        margin: 0 auto;
        padding: 0 1.25rem;
    }

    .imart-hero {
        position: relative;
        overflow: hidden;
        color: #fff;
        background: #063b68;
    }

    .imart-hero::before {
        content: "";
        position: absolute;
        inset: 0;
        z-index: 1;
        background:
            radial-gradient(circle at 54% 48%, rgba(255,255,255,0.11) 0 1px, transparent 1px),
            radial-gradient(circle at 54% 48%, transparent 0 360px, rgba(255,255,255,0.11) 361px, transparent 362px),
            linear-gradient(20deg, rgba(255,255,255,0.08), transparent 24%),
            linear-gradient(28deg, rgba(255,255,255,0.06), transparent 18%);
        clip-path: polygon(0 0, 64% 0, 42% 100%, 0 100%);
        pointer-events: none;
    }

    .imart-hero .imart-container {
        position: relative;
        z-index: 2;
    }

    .hero-media-slider {
        position: absolute;
        inset: 0;
        z-index: 0;
        overflow: hidden;
        background: #063b68;
    }

    .hero-media-video,
    .hero-image-slide {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
    }

    .hero-media-video {
        z-index: 2;
        object-fit: cover;
        background: #061f49;
        transition: opacity 0.7s ease, visibility 0.7s ease;
    }

    .hero-media-video:not(.is-active) {
        opacity: 0;
        visibility: hidden;
    }

    .hero-image-slide {
        z-index: 1;
        opacity: 0;
        transform: translateX(7%);
        transition: opacity 0.85s ease, transform 0.85s ease;
    }

    .hero-image-slide.is-active {
        opacity: 1;
        transform: translateX(0);
    }

    .hero-image-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .hero-media-scrim {
        position: absolute;
        inset: 0;
        z-index: 3;
        background:
            linear-gradient(180deg, rgba(6, 59, 104, 0.92) 0%, rgba(11, 79, 131, 0.8) 48%, rgba(255, 242, 229, 0.44) 100%),
            linear-gradient(90deg, rgba(3, 18, 38, 0.14), rgba(3, 18, 38, 0.02));
        pointer-events: none;
    }

    .hero-top {
        position: relative;
        z-index: 1;
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        gap: 1rem;
        align-items: center;
        padding: 2.8rem 0 2rem;
    }

    .hero-title-market {
        display: flex;
        align-items: baseline;
        justify-content: center;
        gap: 0.22em;
        flex-wrap: nowrap;
        margin: 0 0 1.45rem;
        font-size: clamp(2rem, 4vw, 3.05rem);
        font-weight: 800;
        line-height: 1.1;
        text-align: center;
        white-space: nowrap;
    }

    .hero-title-brand {
        color: var(--t4d-accent);
        font-weight: 700;
    }

    .hero-flip-text {
        position: relative;
        display: inline-block;
        min-width: 8.7em;
        min-height: 1.12em;
        text-align: left;
        perspective: 900px;
    }

    .hero-flip-word {
        display: inline-block;
        color: #fff;
        opacity: 1;
        transform: rotateX(0deg) translateY(0);
        transform-origin: 50% 100%;
        transition: opacity 0.28s ease, transform 0.28s ease;
    }

    .hero-flip-text.is-changing .hero-flip-word {
        opacity: 0;
        transform: rotateX(-75deg) translateY(-0.22em);
    }

    .hero-subtitle-market {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: fit-content;
        max-width: min(820px, 100%);
        margin: 0 auto;
        padding: 0.72rem 1.15rem;
        border: 1px solid rgba(255, 255, 255, 0.18);
        border-radius: 999px;
        background:
            linear-gradient(90deg, rgba(255, 255, 255, 0.16), rgba(255, 255, 255, 0.07)),
            rgba(4, 28, 58, 0.18);
        color: rgba(255,255,255,0.92);
        font-size: 1rem;
        line-height: 1.35;
        text-align: center;
        box-shadow: 0 16px 36px rgba(2, 10, 28, 0.18);
        backdrop-filter: blur(8px);
    }

    .hero-subtitle-market::before,
    .hero-subtitle-market::after {
        content: "";
        width: 34px;
        height: 2px;
        border-radius: 999px;
        background: linear-gradient(90deg, transparent, var(--t4d-accent));
    }

    .hero-subtitle-market::after {
        background: linear-gradient(90deg, var(--t4d-accent), transparent);
    }

    .hero-subtitle-highlight {
        color: #fff;
        font-weight: 850;
        text-shadow: 0 6px 20px rgba(245, 130, 32, 0.28);
    }

    .hero-subtitle-accent {
        color: #ffd7ae;
        font-weight: 850;
    }

    .hero-action-pills {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-end;
        gap: 0.85rem;
        min-width: 520px;
    }

    .hero-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.55rem;
        min-height: 48px;
        padding: 0.7rem 1.45rem;
        border: 0;
        border-radius: 999px;
        background: #fff;
        color: var(--t4d-primary);
        font-weight: 800;
        text-decoration: none;
        box-shadow: 0 10px 26px rgba(10, 15, 45, 0.16);
    }

    .hero-pill:hover {
        color: var(--t4d-primary);
        transform: translateY(-1px);
    }

    .hero-pill.seller {
        color: #0F6F54;
    }

    .hero-pill.signin {
        color: var(--t4d-accent-dark);
    }

    .hero-stats-bar {
        position: relative;
        z-index: 1;
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        border-top: 1px solid rgba(255,255,255,0.36);
        border-bottom: 1px solid rgba(255,255,255,0.5);
    }

    .hero-stat-market {
        padding: 1.15rem 1.4rem;
        color: rgba(255,255,255,0.8);
        font-size: 0.78rem;
        font-weight: 800;
        text-transform: uppercase;
    }

    .hero-stat-market strong {
        margin-right: 0.35rem;
        color: #fff;
        font-size: 1.65rem;
        line-height: 1;
        text-transform: none;
    }

    .trend-area {
        position: relative;
        z-index: 1;
        padding: 1.6rem 0 0.3rem;
    }

    .trend-title {
        margin: 0 0 1rem;
        color: #fff;
        font-size: 1.45rem;
        font-weight: 800;
    }

    .category-tile-grid {
        display: grid;
        gap: 1rem;
        overflow: hidden;
        mask-image: linear-gradient(90deg, transparent, #000 5%, #000 95%, transparent);
    }

    .category-marquee-row {
        overflow: hidden;
    }

    .category-marquee-track {
        display: flex;
        width: max-content;
        gap: 1rem;
        animation: categorySlideRight 34s linear infinite;
        will-change: transform;
    }

    .category-marquee-row.reverse .category-marquee-track {
        animation-name: categorySlideLeft;
    }

    .category-marquee-row:hover .category-marquee-track,
    .category-marquee-row:focus-within .category-marquee-track {
        animation-play-state: paused;
    }

    @keyframes categorySlideLeft {
        from { transform: translateX(0); }
        to { transform: translateX(calc(-50% - 0.5rem)); }
    }

    @keyframes categorySlideRight {
        from { transform: translateX(calc(-50% - 0.5rem)); }
        to { transform: translateX(0); }
    }

    .category-tile {
        width: clamp(220px, 15vw, 292px);
        min-height: 140px;
        padding: 1rem 0.9rem;
        border: 1px solid #d6dce8;
        border-radius: 9px;
        background: #fff;
        color: #020617;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        text-align: center;
        text-decoration: none;
        box-shadow: 0 8px 22px rgba(30, 41, 59, 0.08);
    }

    .category-tile:hover,
    .category-tile.is-active {
        color: #020617;
        border-color: var(--t4d-accent);
        transform: translateY(-2px);
    }

    .category-tile.is-active {
        box-shadow: 0 0 0 3px rgba(245, 130, 32, 0.28), 0 12px 26px rgba(30, 41, 59, 0.12);
    }

    .category-tile-icon,
    .product-img-fallback {
        width: 58px;
        height: 58px;
        border-radius: 50%;
        display: grid;
        place-items: center;
        background: linear-gradient(135deg, #eef5ff, #e0fbff);
        color: var(--t4d-primary);
        font-size: 1.7rem;
        flex: 0 0 auto;
    }

    .category-tile-name {
        font-weight: 800;
        line-height: 1.25;
        overflow-wrap: anywhere;
    }

    .market-section {
        padding: 2.35rem 0;
        background: #fff;
    }

    .market-section.alt {
        background: #f5f7fb;
    }

    .section-heading-row {
        display: flex;
        align-items: end;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .section-heading-row h2 {
        margin: 0;
        color: #061224;
        font-size: 1.45rem;
        font-weight: 850;
    }

    .section-heading-row p {
        margin: 0.3rem 0 0;
        color: #64748b;
        font-size: 0.92rem;
    }

    .product-category-block {
        margin-bottom: 1.65rem;
    }

    .category-products-group.is-hidden {
        display: none;
    }

    .product-slider {
        --product-slide-gap: 0.95rem;
        --product-slide-columns: 6;
        display: grid;
        grid-auto-flow: column;
        grid-auto-columns: calc((100% - ((var(--product-slide-columns) - 1) * var(--product-slide-gap))) / var(--product-slide-columns));
        gap: var(--product-slide-gap);
        overflow-x: auto;
        padding: 0.15rem 0.1rem 0.8rem;
        scroll-snap-type: x proximity;
        scroll-behavior: smooth;
        scrollbar-color: #cbd5e1 transparent;
    }

    .product-card-market {
        display: grid;
        grid-template-rows: 190px minmax(128px, auto);
        scroll-snap-align: start;
        min-height: 318px;
        overflow: hidden;
        border: 1px solid #d6dce8;
        border-radius: 9px;
        background: #fff;
        color: #07111f;
        text-decoration: none;
        box-shadow: 0 7px 20px rgba(15, 23, 42, 0.05);
    }

    .product-card-market:hover {
        color: #07111f;
        border-color: var(--t4d-accent);
        transform: translateY(-2px);
    }

    .product-media {
        position: relative;
        height: 190px;
        min-height: 190px;
        display: grid;
        place-items: center;
        background: #fff;
        border-bottom: 1px solid #edf1f7;
        overflow: hidden;
    }

    .product-media img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 0.65rem;
    }

    .product-card-body {
        display: grid;
        align-content: center;
        gap: 0.22rem;
        min-height: 128px;
        padding: 0.85rem 0.85rem 0.95rem;
        text-align: center;
        background: #fff;
    }

    .product-card-body h3 {
        display: -webkit-box;
        min-height: 48px;
        max-height: 48px;
        margin: 0;
        overflow: hidden;
        color: #07111f;
        font-size: 0.93rem;
        font-weight: 850;
        line-height: 1.28;
        text-wrap: balance;
        overflow-wrap: break-word;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .product-meta {
        min-width: 0;
        overflow: hidden;
        color: #64748b;
        font-size: 0.8rem;
        line-height: 1.28;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .product-meta.fw-semibold {
        color: #52647e;
        font-size: 0.8rem;
    }

    .empty-product-strip {
        border: 1px dashed #cbd5e1;
        border-radius: 9px;
        background: #f8fafc;
        padding: 1.1rem;
        color: #64748b;
    }

    .electronics-connect-showcase {
        position: relative;
        overflow: hidden;
        margin: 0.5rem 0 1.85rem;
        padding: clamp(1.4rem, 3vw, 2.2rem);
        border-radius: 10px;
        background:
            linear-gradient(90deg, rgba(6, 31, 73, 0.9), rgba(10, 55, 119, 0.78)),
            url('{{ asset('images/electronics-logistics-bg.jpg') }}') center/cover no-repeat;
        color: #fff;
        box-shadow: 0 18px 48px rgba(15, 23, 42, 0.15);
        isolation: isolate;
    }

    .electronics-connect-showcase::before {
        content: "";
        position: absolute;
        inset: 0;
        z-index: -1;
        background:
            linear-gradient(180deg, rgba(8, 18, 36, 0.02), rgba(8, 18, 36, 0.22)),
            radial-gradient(circle at 85% 12%, rgba(255, 255, 255, 0.2), transparent 28%);
    }

    .electronics-connect-top {
        display: grid;
        grid-template-columns: minmax(0, 1.15fr) minmax(320px, 0.85fr);
        gap: clamp(1.5rem, 4vw, 3rem);
        align-items: center;
    }

    .electronics-benefits {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1.35rem 2rem;
    }

    .electronics-benefit {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        min-width: 0;
        font-weight: 850;
        line-height: 1.25;
        text-shadow: 0 2px 14px rgba(0, 0, 0, 0.18);
    }

    .electronics-benefit-icon {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        display: grid;
        place-items: center;
        flex: 0 0 auto;
        background: rgba(255, 255, 255, 0.18);
        border: 1px solid rgba(255, 255, 255, 0.2);
        font-size: 1.25rem;
    }

    .electronics-video-cta {
        position: relative;
        overflow: hidden;
        padding: clamp(1.3rem, 2.5vw, 2rem);
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.95);
        color: #24314a;
        text-align: center;
        box-shadow: 0 22px 48px rgba(4, 12, 30, 0.18);
    }

    .electronics-video-cta::after {
        content: "";
        position: absolute;
        inset: -30% -14% auto auto;
        width: 260px;
        height: 260px;
        border: 1px solid rgba(6, 68, 117, 0.13);
        border-radius: 50%;
        pointer-events: none;
    }

    .electronics-video-cta h3 {
        position: relative;
        margin: 0 0 0.55rem;
        color: #0b3a6e;
        font-size: clamp(1.45rem, 2.4vw, 2rem);
        font-weight: 850;
        line-height: 1.15;
    }

    .electronics-video-cta p {
        position: relative;
        max-width: 430px;
        margin: 0 auto 1.35rem;
        color: #475569;
        font-size: 0.98rem;
        line-height: 1.55;
    }

    .electronics-join-btn {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.55rem;
        min-height: 48px;
        padding: 0.7rem 1.7rem;
        border-radius: 999px;
        background: #064475;
        color: #fff;
        font-weight: 850;
        text-decoration: none;
        box-shadow: 0 12px 25px rgba(6, 68, 117, 0.24);
    }

    .electronics-join-btn:hover {
        color: #fff;
        background: #042f55;
        transform: translateY(-1px);
    }

    .connected-sellers-title {
        margin: clamp(1.35rem, 3vw, 2.25rem) 0 1rem;
        text-align: center;
        font-size: 1.1rem;
        font-weight: 850;
        text-shadow: 0 2px 14px rgba(0, 0, 0, 0.24);
    }

    .connected-seller-grid {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 0.9rem;
    }

    .connected-seller-card {
        position: relative;
        min-height: 142px;
        overflow: hidden;
        border-radius: 8px;
        background: rgba(5, 16, 40, 0.42);
        color: #fff;
        text-decoration: none;
        box-shadow: 0 12px 24px rgba(3, 9, 25, 0.24);
    }

    .connected-seller-card:hover {
        color: #fff;
        transform: translateY(-2px);
    }

    .connected-seller-card img {
        width: 100%;
        height: 100%;
        min-height: 142px;
        object-fit: cover;
        opacity: 0.82;
    }

    .connected-seller-card::after {
        content: "";
        position: absolute;
        inset: 38% 0 0;
        background: linear-gradient(180deg, transparent, rgba(4, 11, 35, 0.92));
    }

    .connected-seller-name {
        position: absolute;
        left: 0.85rem;
        right: 0.85rem;
        bottom: 0.7rem;
        z-index: 1;
        font-size: 0.9rem;
        font-weight: 850;
        overflow-wrap: anywhere;
    }

    .construction-search-showcase {
        margin: 0.5rem 0 1.85rem;
        padding: clamp(1.35rem, 3vw, 2.35rem) clamp(1.25rem, 3.2vw, 3rem);
        border-radius: 10px;
        background: #2e3192;
        color: #fff;
        box-shadow: 0 18px 42px rgba(30, 41, 59, 0.12);
    }

    .construction-search-wrap {
        display: grid;
        grid-template-columns: minmax(260px, 0.95fr) minmax(360px, 1.05fr);
        gap: clamp(1.25rem, 4vw, 3rem);
        align-items: center;
    }

    .construction-search-kicker {
        margin: 0 0 0.55rem;
        font-size: 0.85rem;
        font-weight: 850;
        letter-spacing: 0;
        text-transform: uppercase;
    }

    .construction-search-showcase h3 {
        margin: 0 0 0.45rem;
        font-size: clamp(1.75rem, 3.4vw, 2.35rem);
        font-weight: 850;
        line-height: 1.08;
    }

    .construction-search-showcase p {
        margin: 0;
        color: rgba(255, 255, 255, 0.9);
        font-size: 1rem;
    }

    .construction-search-form {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        gap: 0.75rem;
    }

    .construction-search-field {
        min-width: 0;
        min-height: 60px;
        border: 0;
        border-radius: 8px;
        padding: 0 1.25rem;
        background: #fff;
        color: #111827;
        font-size: 1rem;
        outline: none;
    }

    .construction-search-field::placeholder {
        color: #7c8797;
    }

    .construction-search-field:focus {
        box-shadow: 0 0 0 3px rgba(245, 130, 32, 0.32);
    }

    .construction-search-submit {
        min-width: 136px;
        min-height: 60px;
        border: 0;
        border-radius: 8px;
        background: #fff;
        color: #2e3192;
        font-weight: 850;
        letter-spacing: 0;
        text-transform: uppercase;
    }

    .construction-search-submit:hover {
        background: #fff7ed;
        color: #1f2474;
    }

    .construction-search-modes {
        display: inline-grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.45rem;
        margin-bottom: 0.75rem;
        padding: 0.25rem;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.13);
    }

    .construction-search-mode {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        min-height: 38px;
        padding: 0.45rem 0.95rem;
        border-radius: 999px;
        border: 0;
        background: transparent;
        color: rgba(255, 255, 255, 0.86);
        font-size: 0.86rem;
        font-weight: 850;
    }

    .construction-search-mode.is-active {
        background: #fff;
        color: #2e3192;
    }

    .construction-search-note {
        margin-top: 0.65rem;
        color: rgba(255, 255, 255, 0.74);
        font-size: 0.8rem;
        line-height: 1.45;
    }

    .lead-grid-wrap {
        border: 1px solid #d6dce8;
        border-radius: 9px;
        background: #fff;
        box-shadow: 0 7px 22px rgba(15, 23, 42, 0.05);
    }

    .lead-card-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1rem;
        padding: 1rem;
    }

    .lead-card-market {
        min-width: 0;
        overflow: hidden;
        border: 1px solid #d6dce8;
        border-radius: 9px;
        background: #fff;
        box-shadow: 0 8px 22px rgba(15, 23, 42, 0.06);
        transition: transform 0.18s ease, border-color 0.18s ease, box-shadow 0.18s ease;
    }

    .lead-card-market:hover {
        border-color: var(--t4d-accent);
        box-shadow: 0 16px 34px rgba(15, 23, 42, 0.1);
        transform: translateY(-2px);
    }

    .lead-card-media {
        height: 142px;
        border: 1px solid #d6dce8;
        border-width: 0 0 1px;
        display: grid;
        place-items: center;
        overflow: hidden;
        background: linear-gradient(135deg, #f8fafc, #eef5ff);
        color: var(--t4d-primary);
        font-size: 2rem;
    }

    .lead-card-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .lead-card-body {
        display: grid;
        gap: 0.8rem;
        padding: 1rem;
    }

    .lead-card-title {
        display: block;
        color: #061224;
        font-size: 1rem;
        font-weight: 850;
        line-height: 1.35;
        text-decoration: none;
        overflow-wrap: anywhere;
    }

    .lead-card-title:hover {
        color: var(--t4d-primary);
    }

    .lead-card-meta {
        color: #64748b;
        font-size: 0.82rem;
        line-height: 1.45;
    }

    .lead-card-company {
        color: #0f172a;
        font-size: 0.9rem;
        font-weight: 800;
        overflow-wrap: anywhere;
    }

    .lead-card-info {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.7rem;
    }

    .lead-info-label {
        display: block;
        margin-bottom: 0.2rem;
        color: #64748b;
        font-size: 0.68rem;
        font-weight: 850;
        text-transform: uppercase;
    }

    .lead-info-value {
        color: #061224;
        font-size: 0.82rem;
        font-weight: 800;
        line-height: 1.35;
    }

    .lead-payment-row {
        display: flex;
        flex-wrap: wrap;
        gap: 0.35rem;
    }

    .chip-market {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.24rem 0.52rem;
        border: 1px solid #d6dce8;
        border-radius: 999px;
        background: #f8fafc;
        color: #0f172a;
        font-size: 0.76rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .how-premium-section {
        position: relative;
        overflow: hidden;
        background:
            linear-gradient(135deg, rgba(6, 68, 117, 0.96), rgba(30, 49, 146, 0.92)),
            radial-gradient(circle at 12% 18%, rgba(245, 130, 32, 0.32), transparent 28%);
        color: #fff;
    }

    .how-premium-section::before {
        content: "";
        position: absolute;
        inset: 0;
        background:
            linear-gradient(90deg, rgba(255,255,255,0.07) 1px, transparent 1px),
            linear-gradient(180deg, rgba(255,255,255,0.06) 1px, transparent 1px);
        background-size: 54px 54px;
        mask-image: linear-gradient(90deg, transparent, #000 18%, #000 82%, transparent);
        opacity: 0.34;
        pointer-events: none;
    }

    .how-premium-section .imart-container {
        position: relative;
        z-index: 1;
    }

    .how-premium-head {
        display: grid;
        grid-template-columns: minmax(0, 0.9fr) minmax(260px, 0.48fr);
        gap: 1.5rem;
        align-items: end;
        margin-bottom: 1.4rem;
    }

    .how-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        margin-bottom: 0.65rem;
        padding: 0.34rem 0.65rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.1);
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.75rem;
        font-weight: 850;
        text-transform: uppercase;
    }

    .how-premium-head h2 {
        margin: 0;
        color: #fff;
        font-size: clamp(1.85rem, 3vw, 2.6rem);
        font-weight: 850;
        line-height: 1.08;
    }

    .how-premium-head p {
        margin: 0.75rem 0 0;
        max-width: 700px;
        color: rgba(255, 255, 255, 0.78);
        font-size: 1rem;
        line-height: 1.6;
    }

    .how-premium-stat {
        border: 1px solid rgba(255, 255, 255, 0.16);
        border-radius: 8px;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.1);
        box-shadow: 0 18px 46px rgba(2, 6, 23, 0.16);
    }

    .how-premium-stat strong {
        display: block;
        font-size: 1.65rem;
        line-height: 1;
    }

    .how-premium-stat span {
        display: block;
        margin-top: 0.38rem;
        color: rgba(255, 255, 255, 0.72);
        font-size: 0.82rem;
        font-weight: 700;
    }

    .how-compact {
        position: relative;
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 0.95rem;
    }

    .how-compact::before {
        content: "";
        position: absolute;
        left: 8%;
        right: 8%;
        top: 43px;
        height: 2px;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.56), transparent);
        pointer-events: none;
    }

    .how-card {
        position: relative;
        min-height: 230px;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.18);
        border-radius: 8px;
        background:
            linear-gradient(180deg, rgba(255,255,255,0.16), rgba(255,255,255,0.08));
        padding: 1.1rem;
        color: #fff;
        box-shadow: 0 20px 46px rgba(2, 6, 23, 0.18);
        backdrop-filter: blur(10px);
    }

    .how-card::before {
        content: attr(data-step);
        position: absolute;
        right: 1rem;
        top: 0.8rem;
        color: rgba(255, 255, 255, 0.12);
        font-size: 3.5rem;
        font-weight: 850;
        line-height: 1;
    }

    .how-card-icon {
        position: relative;
        width: 64px;
        height: 64px;
        margin-bottom: 1rem;
        border-radius: 18px;
        display: grid;
        place-items: center;
        background: #fff;
        color: #f58220;
        font-size: 1.65rem;
        box-shadow: 0 14px 28px rgba(2, 6, 23, 0.18);
    }

    .how-card-icon::after {
        content: "";
        position: absolute;
        inset: -7px;
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 24px;
    }

    .how-card h3 {
        position: relative;
        margin: 0 0 0.55rem;
        color: #fff;
        font-size: 1.05rem;
        font-weight: 850;
    }

    .how-card p {
        position: relative;
        margin: 0;
        color: rgba(255, 255, 255, 0.72) !important;
        font-size: 0.9rem;
        line-height: 1.55;
    }

    .how-card-action {
        position: absolute;
        left: 1.1rem;
        right: 1.1rem;
        bottom: 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        color: rgba(255, 255, 255, 0.82);
        font-size: 0.78rem;
        font-weight: 800;
    }

    .how-card-action i {
        color: #fbbf24;
        font-size: 1rem;
    }

    @media (prefers-reduced-motion: reduce) {
        .category-tile,
        .product-card-market,
        .hero-pill,
        .hero-media-video,
        .hero-image-slide {
            transition: none;
        }

        .hero-flip-word {
            transition: none;
        }

        .category-marquee-track {
            animation: none;
            transform: none;
        }
    }

    @media (max-width: 1199px) {
        .hero-top {
            grid-template-columns: 1fr;
        }

        .hero-action-pills {
            justify-content: center;
            min-width: 0;
        }

        .category-tile {
            width: clamp(200px, 26vw, 270px);
        }

        .product-slider {
            --product-slide-columns: 4;
        }

        .product-card-market {
            grid-template-rows: 178px minmax(128px, auto);
        }

        .product-media {
            height: 178px;
            min-height: 178px;
        }
    }

    @media (max-width: 1450px) {
        .hero-title-market {
            flex-wrap: wrap;
            font-size: clamp(2rem, 3.55vw, 2.75rem);
            white-space: normal;
        }

        .hero-flip-text {
            min-width: min(8.7em, 100%);
        }
    }

    @media (max-width: 991px) {
        .product-slider {
            --product-slide-columns: 3;
        }

        .product-card-market {
            grid-template-rows: 182px minmax(126px, auto);
        }

        .product-media {
            height: 182px;
            min-height: 182px;
        }
    }

    @media (max-width: 767px) {
        .imart-container {
            padding: 0 0.85rem;
        }

        .hero-top {
            padding: 2rem 0 1.35rem;
        }

        .hero-title-market,
        .hero-subtitle-market {
            text-align: left;
        }

        .hero-title-market {
            justify-content: flex-start;
            font-size: clamp(1rem, 4.5vw, 2rem);
        }

        .hero-flip-text {
            min-width: 8.4em;
        }

        .hero-subtitle-market {
            flex-wrap: wrap;
            justify-content: flex-start;
            width: 100%;
            padding: 0.68rem 0.85rem;
            border-radius: 12px;
            font-size: 0.92rem;
        }

        .hero-subtitle-market::before,
        .hero-subtitle-market::after {
            width: 24px;
        }

        .hero-action-pills {
            justify-content: flex-start;
        }

        .hero-pill {
            width: 100%;
        }

        .hero-stats-bar {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .hero-stat-market {
            padding: 0.9rem;
        }

        .how-compact {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.65rem;
        }

        .how-card {
            min-height: 212px;
            padding: 0.78rem;
        }

        .how-card::before {
            right: 0.55rem;
            top: 0.55rem;
            font-size: 2.7rem;
        }

        .how-card-icon {
            width: 54px;
            height: 54px;
            margin-bottom: 0.78rem;
            border-radius: 14px;
            font-size: 1.35rem;
        }

        .how-card h3 {
            font-size: 0.88rem;
        }

        .how-card p {
            font-size: 0.72rem;
            line-height: 1.42;
        }

        .how-card-action {
            left: 0.78rem;
            right: 0.78rem;
            bottom: 0.72rem;
            font-size: 0.66rem;
        }

        .how-premium-head {
            grid-template-columns: 1fr;
        }

        .how-compact::before {
            display: none;
        }

        .category-tile {
            min-height: 128px;
        }

        .section-heading-row {
            align-items: flex-start;
            flex-direction: column;
        }

        .product-slider {
            --product-slide-columns: 2;
            --product-slide-gap: 0.65rem;
        }

        .product-card-market {
            grid-template-rows: 136px minmax(112px, auto);
            min-height: 248px;
        }

        .product-media {
            height: 136px;
            min-height: 136px;
        }

        .product-media img {
            padding: 0.45rem;
        }

        .product-card-body {
            min-height: 112px;
            padding: 0.62rem 0.45rem 0.72rem;
        }

        .product-card-body h3 {
            min-height: 40px;
            max-height: 40px;
            font-size: 0.74rem;
            line-height: 1.25;
        }

        .product-meta {
            font-size: 0.68rem;
        }

        .electronics-connect-showcase {
            margin-top: 0.2rem;
            padding: 1rem;
            border-radius: 8px;
        }

        .electronics-connect-top,
        .electronics-benefits {
            grid-template-columns: 1fr;
        }

        .electronics-benefits {
            gap: 0.75rem;
        }

        .electronics-benefit-icon {
            width: 44px;
            height: 44px;
        }

        .connected-seller-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .construction-search-showcase {
            padding: 1rem;
            border-radius: 8px;
        }

        .construction-search-wrap,
        .construction-search-form {
            grid-template-columns: 1fr;
        }

        .construction-search-submit {
            width: 100%;
        }

        .lead-card-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            padding: 0.85rem;
        }

        .lead-card-media {
            height: 132px;
        }
    }

    @media (max-width: 420px) {
        .product-slider {
            --product-slide-columns: 2;
            --product-slide-gap: 0.55rem;
        }

        .product-card-market {
            grid-template-rows: 124px minmax(108px, auto);
            min-height: 232px;
        }

        .product-media {
            height: 124px;
            min-height: 124px;
        }

        .product-card-body {
            min-height: 108px;
        }

        .product-card-body h3 {
            font-size: 0.7rem;
        }

        .connected-seller-grid,
        .lead-card-grid {
            grid-template-columns: 1fr;
        }

        .how-compact {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.55rem;
        }

        .how-card {
            min-height: 198px;
            padding: 0.68rem;
        }

        .how-card-icon {
            width: 48px;
            height: 48px;
            margin-bottom: 0.68rem;
            border-radius: 12px;
            font-size: 1.2rem;
        }

        .how-card::before {
            font-size: 2.35rem;
        }

        .how-card h3 {
            font-size: 0.82rem;
        }

        .how-card p {
            font-size: 0.68rem;
        }

        .how-card-action {
            left: 0.68rem;
            right: 0.68rem;
            bottom: 0.64rem;
            font-size: 0.62rem;
        }
    }
</style>
@endpush

@section('content')
<div class="imart-home">
    <section class="imart-hero" aria-labelledby="heroTitle">
        <div class="hero-media-slider" aria-hidden="true">
            <video class="hero-media-video is-active" autoplay muted playsinline preload="metadata">
                <source src="{{ asset('images/trade4deal-hero-video.mp4') }}" type="video/mp4">
            </video>
            <div class="hero-image-slide">
                <img src="https://images.unsplash.com/photo-1494412685616-a5d310fbb07d?auto=format&fit=crop&w=2200&q=85" alt="">
            </div>
            <div class="hero-image-slide">
                <img src="{{ asset('images/trade4deal-hero-logistics.jpg') }}" alt="">
            </div>
            <div class="hero-media-scrim"></div>
        </div>
        <div class="imart-container">
            <div class="hero-top">
                <div>
                    <h1 class="hero-title-market" id="heroTitle">
                        <span class="hero-title-brand">Trade4Deal Online</span>
                        <span class="hero-flip-text" aria-label="B2B Marketplace">
                            <span class="hero-flip-word">B2B Marketplace</span>
                        </span>
                    </h1>
                    <p class="hero-subtitle-market">
                        <span class="hero-subtitle-highlight">One Platform</span>
                        <span class="hero-subtitle-accent">Endless Business Opportunities</span>
                    </p>
                </div>
                <div class="hero-action-pills" aria-label="Primary marketplace actions">
                    <button type="button" class="hero-pill" data-bs-toggle="modal" data-bs-target="#leadModal">
                        <i class="bi bi-journal-plus"></i> Submit Requirement <i class="bi bi-arrow-right"></i>
                    </button>
                    <a href="{{ route('register') }}" class="hero-pill seller">
                        <i class="bi bi-bar-chart-line"></i> List Your Business <i class="bi bi-arrow-right"></i>
                    </a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="hero-pill signin">
                            <i class="bi bi-speedometer2"></i> Dashboard <i class="bi bi-arrow-right"></i>
                        </a>
                    @endauth
                </div>
            </div>

            <div class="hero-stats-bar" aria-label="Marketplace overview">
                <div class="hero-stat-market"><strong>{{ number_format($marketplaceStats['visible_leads']) }}</strong> Visible leads</div>
                <div class="hero-stat-market"><strong>{{ number_format($marketplaceStats['sellers']) }}</strong> Public sellers</div>
                <div class="hero-stat-market"><strong>{{ number_format($marketplaceStats['products']) }}</strong> Products</div>
                <div class="hero-stat-market"><strong>{{ number_format($marketplaceStats['categories']) }}</strong> Categories</div>
            </div>

            <div class="trend-area" id="categories">
                <h2 class="trend-title">Explore Trade4Deal Categories</h2>
                @if($categories->isEmpty())
                    <div class="empty-product-strip bg-white">No categories are available yet.</div>
                @else
                    @php($categoryRows = $categories->values()->split(2))
                    <div class="category-tile-grid">
                        @foreach($categoryRows as $rowIndex => $categoryRow)
                            <div class="category-marquee-row {{ $rowIndex === 1 ? 'reverse' : '' }}">
                                <div class="category-marquee-track">
                                    @for($copy = 0; $copy < 2; $copy++)
                                        @foreach($categoryRow as $category)
                                            <a
                                                class="category-tile marketplace-search-item"
                                                href="#category-{{ $category->value }}"
                                                data-category-link="{{ $category->value }}"
                                                data-search-text="{{ strtolower($category->label()) }}"
                                                @if($copy === 1) aria-hidden="true" tabindex="-1" @endif
                                            >
                                                <span class="category-tile-icon" aria-hidden="true"><i class="bi {{ $categoryIcons[$category->value] ?? 'bi-boxes' }}"></i></span>
                                                <span class="category-tile-name">{{ $category->label() }}</span>
                                            </a>
                                        @endforeach
                                    @endfor
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="market-section" id="category-products" aria-labelledby="productsTitle">
        <div class="imart-container">
            <div class="section-heading-row">
                <div>
                    <h2 id="productsTitle">Browse Products by Category</h2>
                    <p>Active seller products organised by Trade4Deal marketplace categories.</p>
                </div>
                <button type="button" class="btn btn-primary-t4d text-white" data-bs-toggle="modal" data-bs-target="#leadModal">
                    <i class="bi bi-plus-circle me-1"></i> Submit Requirement
                </button>
            </div>

            @foreach($categories as $category)
                @php($products = $productsByCategory->get($category->value, collect()))
                <div class="category-products-group" data-category-group="{{ $category->value }}">
                    <div class="product-category-block marketplace-search-item" id="category-{{ $category->value }}" data-search-text="{{ strtolower($category->label().' '.$products->pluck('name')->join(' ')) }}">
                        <div class="section-heading-row mb-2">
                            <div>
                                <h2>{{ $category->label() }}</h2>
                                <p>{{ $products->count() }} active product{{ $products->count() === 1 ? '' : 's' }}</p>
                            </div>
                        </div>

                        @if($products->isEmpty())
                            <div class="empty-product-strip">No active public products in this category yet. Submit a requirement to start a trade conversation.</div>
                        @else
                            <div class="product-slider" aria-label="{{ $category->label() }} products">
                                @foreach($products as $product)
                                    @php($sellerUrl = $product->user?->slug ? route('sellers.show', $product->user->slug) : '#')
                                    <a class="product-card-market marketplace-search-item" href="{{ $sellerUrl }}" data-search-text="{{ strtolower($category->label().' '.$product->name.' '.$product->user?->company_name) }}">
                                        <div class="product-media">
                                            @if($product->imageUrl())
                                                <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" loading="lazy" onerror="this.nextElementSibling.classList.remove('d-none'); this.remove();">
                                                <span class="product-img-fallback d-none" aria-hidden="true"><i class="bi bi-box-seam"></i></span>
                                            @else
                                                <span class="product-img-fallback" aria-hidden="true"><i class="bi bi-box-seam"></i></span>
                                            @endif
                                        </div>
                                        <div class="product-card-body">
                                            <h3>{{ $product->name }}</h3>
                                            <div class="product-meta">{{ $product->user?->company_name ?? 'Trade4Deal seller' }}</div>
                                            <div class="product-meta fw-semibold">{{ $product->priceLabel() }}</div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    @if($category->value === 'electronics')
                        @php($connectedProducts = $products->take(5))
                        <section class="electronics-connect-showcase" aria-labelledby="electronicsConnectTitle">
                        <div class="electronics-connect-top">
                            <div class="electronics-benefits" aria-label="Electronics seller benefits">
                                <div class="electronics-benefit">
                                    <span class="electronics-benefit-icon" aria-hidden="true"><i class="bi bi-check-lg"></i></span>
                                    <span>Trusted electronics sellers</span>
                                </div>
                                <div class="electronics-benefit">
                                    <span class="electronics-benefit-icon" aria-hidden="true"><i class="bi bi-patch-check"></i></span>
                                    <span>Verified supplier profiles</span>
                                </div>
                                <div class="electronics-benefit">
                                    <span class="electronics-benefit-icon" aria-hidden="true"><i class="bi bi-lightning-charge-fill"></i></span>
                                    <span>Hassle-free enquiries</span>
                                </div>
                                <div class="electronics-benefit">
                                    <span class="electronics-benefit-icon" aria-hidden="true"><i class="bi bi-graph-up-arrow"></i></span>
                                    <span>More buyer engagement</span>
                                </div>
                            </div>

                            <div class="electronics-video-cta">
                                <h3 id="electronicsConnectTitle">Join Trade4Deal Videos</h3>
                                <p>Showcase electronic products, machinery demos, and supplier capabilities directly on your public catalog.</p>
                                <a href="{{ route('register') }}" class="electronics-join-btn">
                                    Join Now <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>

                        <h3 class="connected-sellers-title">Successfully Connected Electronics Sellers</h3>
                        @if($connectedProducts->isEmpty())
                            <div class="empty-product-strip bg-white">Electronics sellers will appear here after products are added.</div>
                        @else
                            <div class="connected-seller-grid">
                                @foreach($connectedProducts as $connectedProduct)
                                    @php($sellerUrl = $connectedProduct->user?->slug ? route('sellers.show', $connectedProduct->user->slug) : '#')
                                    <a class="connected-seller-card marketplace-search-item" href="{{ $sellerUrl }}" data-search-text="{{ strtolower('electronics '.$connectedProduct->name.' '.$connectedProduct->user?->company_name) }}">
                                        @if($connectedProduct->imageUrl())
                                            <img src="{{ $connectedProduct->imageUrl() }}" alt="{{ $connectedProduct->user?->company_name ?? $connectedProduct->name }}" loading="lazy">
                                        @endif
                                        <span class="connected-seller-name">{{ $connectedProduct->user?->company_name ?? $connectedProduct->name }}</span>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                        </section>
                    @endif

                    @if($category->value === 'construction')
                        <section class="construction-search-showcase" aria-labelledby="constructionSearchTitle">
                        <div class="construction-search-wrap">
                            <div>
                                <p class="construction-search-kicker">India's Largest B2B Marketplace</p>
                                <h3 id="constructionSearchTitle">Find the Verified Suppliers</h3>
                                <p>Search construction materials, suppliers, and active buyer requirements.</p>
                            </div>

                            <div>
                                <div class="construction-search-modes" aria-label="Choose construction search mode">
                                    <button type="button" class="construction-search-mode is-active" data-construction-mode="seller">
                                        <i class="bi bi-shop-window"></i> Seller
                                    </button>
                                    <button type="button" class="construction-search-mode" data-construction-mode="buyer">
                                        <i class="bi bi-person-lines-fill"></i> Buyer
                                    </button>
                                </div>
                                <form class="construction-search-form" role="search" data-construction-search-form data-search-mode="seller">
                                    <label class="visually-hidden" for="constructionSearchInput">Search construction buyers or sellers</label>
                                    <input class="construction-search-field" id="constructionSearchInput" type="search" placeholder="Enter product / service to search" autocomplete="off">
                                    <button class="construction-search-submit" type="submit">Search</button>
                                </form>
                                <div class="construction-search-note">Seller search checks construction product listings. Buyer search checks live business leads.</div>
                            </div>
                        </div>
                        </section>
                    @endif
                </div>
            @endforeach
        </div>
    </section>

    <section class="market-section alt" id="leads" aria-labelledby="leadsTitle">
        <div class="imart-container">
            <div class="section-heading-row">
                <div>
                    <h2 id="leadsTitle">Live Business Leads</h2>
                    <p>Fresh Trade4Deal buyer and supplier opportunities visible according to your current plan.</p>
                </div>
                <button type="button" class="btn btn-primary-t4d text-white" data-bs-toggle="modal" data-bs-target="#leadModal">
                    <i class="bi bi-send me-1"></i> Submit Trade Lead
                </button>
            </div>

            @if($isStaffViewer ?? false)
                <div class="alert alert-success border-0 shadow-sm mb-3"><strong>Staff view</strong> - all approved leads are visible with no plan delay.</div>
            @elseif($viewerPlan->isGold())
                <div class="alert alert-warning border-0 shadow-sm mb-3"><strong>Gold member</strong> - you see new leads instantly as they are published.</div>
            @else
                <div class="alert alert-info border-0 shadow-sm mb-3 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2">
                    <span><i class="bi bi-clock me-1"></i>Free accounts see leads <strong>24 hours</strong> after they go live.</span>
                    <a href="{{ route('plans.index') }}" class="btn btn-sm btn-primary-t4d text-white">Upgrade to Gold</a>
                </div>
            @endif

            <div class="lead-grid-wrap">
                @if($leads->isEmpty())
                    <div class="empty-state">
                        <h3 class="h5 fw-bold">No leads visible yet</h3>
                        <p class="mb-3">New leads appear here after review. @unless(($isStaffViewer ?? false) || $viewerPlan->isGold()) Gold members see them 24 hours sooner. @endunless</p>
                        <button type="button" class="btn btn-primary-t4d text-white" data-bs-toggle="modal" data-bs-target="#leadModal">Submit a Lead</button>
                    </div>
                @else
                    <div class="lead-card-grid">
                        @foreach($leads as $lead)
                            <article class="lead-card-market marketplace-search-item" data-search-text="{{ strtolower($lead->product_interest.' '.$lead->company_name.' '.$lead->country.' '.$lead->product_type?->label().' '.$lead->business_type?->label()) }}">
                                <a href="{{ route('leads.show', $lead) }}" class="lead-card-media" aria-label="View {{ $lead->product_interest }}">
                                    @if($lead->productImageUrl())
                                        <img src="{{ $lead->productImageUrl() }}" alt="{{ $lead->product_interest }}" loading="lazy">
                                    @else
                                        <i class="bi bi-image"></i>
                                    @endif
                                </a>
                                <div class="lead-card-body">
                                    <div>
                                        <a href="{{ route('leads.show', $lead) }}" class="lead-card-title">{{ $lead->product_interest }}</a>
                                        <div class="lead-card-meta">{{ $lead->published_at?->diffForHumans() }}</div>
                                    </div>

                                    <div>
                                        <div class="lead-card-company">{{ $lead->company_name }}</div>
                                        <div class="lead-card-meta"><i class="bi bi-geo-alt me-1"></i>{{ $lead->country }}</div>
                                    </div>

                                    <div class="lead-card-info">
                                        <div>
                                            <span class="lead-info-label">Category</span>
                                            <span class="chip-market">{{ $lead->product_type?->label() ?? 'Product' }}</span>
                                        </div>
                                        <div>
                                            <span class="lead-info-label">Trade</span>
                                            <div class="lead-info-value">{{ $lead->currency?->value }} &middot; {{ $lead->units?->label() }}</div>
                                            <div class="lead-card-meta">{{ $lead->business_type?->label() }}</div>
                                        </div>
                                    </div>

                                    @if ($lead->payment_terms)
                                        <div>
                                            <span class="lead-info-label">Payment terms</span>
                                            <div class="lead-payment-row">
                                                <span class="chip-market">{{ $lead->payment_terms->label() }}</span>
                                            </div>
                                        </div>
                                    @endif

                                    <a href="{{ route('leads.show', $lead) }}" class="btn btn-sm btn-outline-t4d w-100">View &amp; contact</a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 px-4 py-3 border-top">
                        <div class="small text-muted">Showing {{ $leads->firstItem() }}–{{ $leads->lastItem() }} of {{ $leads->total() }} leads</div>
                        {{ $leads->onEachSide(1)->links() }}
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="market-section how-premium-section" id="how-it-works" aria-labelledby="howTitle">
        <div class="imart-container">
            <div class="how-premium-head">
                <div>
                    <span class="how-eyebrow"><i class="bi bi-stars"></i> Simple Trade Flow</span>
                    <h2 id="howTitle">How Trade4Deal Works</h2>
                    <p>Move from business requirement to verified supplier conversation with a clear, guided marketplace flow built for serious B2B trade.</p>
                </div>
                <div class="how-premium-stat">
                    <strong>{{ number_format($marketplaceStats['products']) }}+</strong>
                    <span>active product opportunities across marketplace categories</span>
                </div>
            </div>
            <div class="how-compact">
                <article class="how-card" data-step="01">
                    <span class="how-card-icon"><i class="bi bi-person-plus"></i></span>
                    <h3>Register</h3>
                    <p>Create your buyer or seller account and unlock a cleaner way to manage trade interest.</p>
                    <span class="how-card-action">Create profile <i class="bi bi-arrow-right"></i></span>
                </article>
                <article class="how-card" data-step="02">
                    <span class="how-card-icon"><i class="bi bi-journal-plus"></i></span>
                    <h3>Submit Requirement</h3>
                    <p>Share product, quantity, payment, and category details so the right parties can respond.</p>
                    <span class="how-card-action">Add details <i class="bi bi-arrow-right"></i></span>
                </article>
                <article class="how-card" data-step="03">
                    <span class="how-card-icon"><i class="bi bi-shop"></i></span>
                    <h3>Browse Suppliers</h3>
                    <p>Explore public sellers, product listings, and category-specific opportunities in one place.</p>
                    <span class="how-card-action">Compare options <i class="bi bi-arrow-right"></i></span>
                </article>
                <article class="how-card" data-step="04">
                    <span class="how-card-icon"><i class="bi bi-chat-dots"></i></span>
                    <h3>Connect</h3>
                    <p>Use enquiry actions to start a focused business conversation with verified trade context.</p>
                    <span class="how-card-action">Start enquiry <i class="bi bi-arrow-right"></i></span>
                </article>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const hero = document.querySelector('.imart-hero');
        const video = document.querySelector('.hero-media-video');
        const imageSlides = Array.from(document.querySelectorAll('.hero-image-slide'));
        if (!hero || !video || imageSlides.length === 0) return;

        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        let currentImage = 0;
        let slideTimer = null;

        function showImage(index) {
            currentImage = index % imageSlides.length;
            video.classList.remove('is-active');
            imageSlides.forEach((slide, slideIndex) => {
                slide.classList.toggle('is-active', slideIndex === currentImage);
            });
        }

        function showVideo() {
            window.clearTimeout(slideTimer);
            imageSlides.forEach((slide) => slide.classList.remove('is-active'));
            video.classList.add('is-active');
            video.currentTime = 0;

            const playPromise = video.play();
            if (playPromise && typeof playPromise.catch === 'function') {
                playPromise.catch(() => {
                    showImage(0);
                    queueNextImage();
                });
            }
        }

        function queueNextImage() {
            if (reduceMotion) return;
            slideTimer = window.setTimeout(() => {
                if (currentImage + 1 < imageSlides.length) {
                    showImage(currentImage + 1);
                    queueNextImage();
                } else {
                    showVideo();
                }
            }, 5200);
        }

        video.addEventListener('ended', () => {
            showImage(0);
            queueNextImage();
        });
        video.addEventListener('error', () => {
            showImage(0);
            queueNextImage();
        });

        showVideo();
    })();

    (function () {
        const flipText = document.querySelector('.hero-flip-text');
        const flipWord = document.querySelector('.hero-flip-word');
        if (!flipText || !flipWord) return;

        const words = [
            'B2B Marketplace',
            'Buyer Network',
            'Seller Hub',
            'Export Leads',
            'Import Deals',
            'Verified Suppliers',
            'Product Showcase',
            'Trade Leads',
            'Business Growth',
        ];
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        let wordIndex = 0;

        if (reduceMotion) return;

        window.setInterval(() => {
            flipText.classList.add('is-changing');
            window.setTimeout(() => {
                wordIndex = (wordIndex + 1) % words.length;
                flipWord.textContent = words[wordIndex];
                flipText.classList.remove('is-changing');
            }, 280);
        }, 2500);
    })();

    (function () {
        const categoryLinks = Array.from(document.querySelectorAll('[data-category-link]'));
        const categoryGroups = Array.from(document.querySelectorAll('[data-category-group]'));
        const productsSection = document.getElementById('category-products');
        if (categoryLinks.length === 0 || categoryGroups.length === 0) return;

        function selectCategory(category) {
            categoryLinks.forEach((link) => {
                link.classList.toggle('is-active', link.getAttribute('data-category-link') === category);
            });

            categoryGroups.forEach((group) => {
                group.classList.toggle('is-hidden', group.getAttribute('data-category-group') !== category);
            });

            productsSection?.scrollIntoView({
                behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth',
                block: 'start',
            });
        }

        categoryLinks.forEach((link) => {
            link.addEventListener('click', function (event) {
                event.preventDefault();
                const category = link.getAttribute('data-category-link');
                if (category) {
                    selectCategory(category);
                }
            });
        });
    })();

    (function () {
        const sliders = Array.from(document.querySelectorAll('.product-slider'));
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (sliders.length === 0 || reduceMotion) return;

        sliders.forEach((slider) => {
            let paused = false;

            function cardStep() {
                const firstCard = slider.querySelector('.product-card-market');
                if (!firstCard) return 0;

                const gap = parseFloat(window.getComputedStyle(slider).columnGap || '0');
                return firstCard.getBoundingClientRect().width + gap;
            }

            function canSlide() {
                return slider.scrollWidth > slider.clientWidth + 8;
            }

            function slideNext() {
                if (paused || document.hidden || !canSlide()) return;

                const step = cardStep();
                if (step <= 0) return;

                const maxScroll = slider.scrollWidth - slider.clientWidth;
                if (slider.scrollLeft + step >= maxScroll - 4) {
                    slider.scrollTo({ left: 0, behavior: 'smooth' });
                    return;
                }

                slider.scrollBy({ left: step, behavior: 'smooth' });
            }

            slider.addEventListener('mouseenter', () => { paused = true; });
            slider.addEventListener('mouseleave', () => { paused = false; });
            slider.addEventListener('focusin', () => { paused = true; });
            slider.addEventListener('focusout', () => { paused = false; });

            window.setInterval(slideNext, 3200);
        });
    })();

    (function () {
        const form = document.querySelector('[data-construction-search-form]');
        const input = document.getElementById('constructionSearchInput');
        const modeButtons = Array.from(document.querySelectorAll('[data-construction-mode]'));
        if (!form || !input || modeButtons.length === 0) return;

        function setMode(mode) {
            const normalized = mode === 'buyer' ? 'buyer' : 'seller';
            form.setAttribute('data-search-mode', normalized);
            input.placeholder = normalized === 'buyer'
                ? 'Enter buyer requirement to search'
                : 'Enter product / service to search';
            modeButtons.forEach((button) => {
                button.classList.toggle('is-active', button.getAttribute('data-construction-mode') === normalized);
            });
        }

        function scrollToTarget(target) {
            target?.scrollIntoView({
                behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth',
                block: 'start',
            });
        }

        modeButtons.forEach((button) => {
            button.addEventListener('click', function () {
                setMode(button.getAttribute('data-construction-mode'));
                input.focus();
            });
        });

        form.addEventListener('submit', function (event) {
            event.preventDefault();
            event.stopPropagation();
            const mode = form.getAttribute('data-search-mode') === 'buyer' ? 'buyer' : 'seller';
            const query = input.value.trim().toLowerCase();
            const fallback = document.getElementById(mode === 'buyer' ? 'leads' : 'category-construction');
            const candidates = Array.from(document.querySelectorAll(
                mode === 'buyer' ? '#leads .marketplace-search-item' : '#category-construction .marketplace-search-item'
            ));
            const target = query
                ? candidates.find((item) => (item.getAttribute('data-search-text') || '').includes(query))
                : fallback;

            scrollToTarget(target || fallback);
        });
    })();
</script>
@endpush
