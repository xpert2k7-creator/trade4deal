@extends('layouts.marketplace')

@section('title', $page['title'].' - Trade4Deal')

@php
    $primaryHref = isset($page['cta_route'])
        ? route($page['cta_route']).($page['cta_fragment'] ?? false ? '#'.$page['cta_fragment'] : '')
        : ($page['cta_target'] ?? route('register'));

    $secondaryHref = isset($page['secondary_route'])
        ? route($page['secondary_route']).($page['secondary_fragment'] ?? false ? '#'.$page['secondary_fragment'] : '')
        : ($page['secondary_target'] ?? route('home'));
@endphp

@push('styles')
<style>
    .info-page-hero {
        position: relative;
        overflow: hidden;
        color: #fff;
        background:
            linear-gradient(105deg, rgba(6, 31, 73, 0.96), rgba(6, 68, 117, 0.86) 46%, rgba(245, 130, 32, 0.38)),
            url('https://images.unsplash.com/photo-1494412685616-a5d310fbb07d?auto=format&fit=crop&w=1800&q=85') center/cover no-repeat;
        border-bottom: 1px solid rgba(255, 255, 255, 0.12);
    }

    .info-page-hero .container {
        min-height: 420px;
        display: grid;
        align-items: center;
    }

    .info-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        width: fit-content;
        padding: 0.42rem 0.8rem;
        border: 1px solid rgba(255, 255, 255, 0.24);
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.12);
        font-size: 0.8rem;
        font-weight: 850;
        text-transform: uppercase;
    }

    .info-title {
        max-width: 850px;
        margin: 1rem 0 1rem;
        color: #fff;
        font-size: clamp(2.2rem, 4.8vw, 3.65rem);
        font-weight: 850;
        line-height: 1.06;
    }

    .info-summary {
        max-width: 790px;
        color: rgba(255, 255, 255, 0.84);
        font-size: 1.05rem;
        line-height: 1.7;
    }

    .info-kpi-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 0.8rem;
        margin-top: 1.5rem;
        max-width: 780px;
    }

    .info-kpi {
        border: 1px solid rgba(255, 255, 255, 0.18);
        border-radius: 8px;
        padding: 0.9rem 1rem;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(8px);
    }

    .info-kpi strong {
        display: block;
        color: #fff;
        font-size: 1.35rem;
        line-height: 1.1;
    }

    .info-kpi span {
        display: block;
        margin-top: 0.3rem;
        color: rgba(255, 255, 255, 0.72);
        font-size: 0.78rem;
        font-weight: 700;
    }

    .info-section {
        background: #f5f7fb;
    }

    .info-card-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1rem;
    }

    .info-card {
        min-height: 240px;
        border: 1px solid #d6dce8;
        border-radius: 9px;
        background: #fff;
        padding: 1.25rem;
        box-shadow: 0 8px 22px rgba(15, 23, 42, 0.06);
    }

    .info-card-icon {
        width: 54px;
        height: 54px;
        border-radius: 8px;
        display: grid;
        place-items: center;
        margin-bottom: 1rem;
        background: linear-gradient(135deg, var(--t4d-primary), #0e7490);
        color: #fff;
        font-size: 1.35rem;
    }

    .info-card h2 {
        margin: 0 0 0.55rem;
        color: #061224;
        font-size: 1.08rem;
        font-weight: 850;
        line-height: 1.3;
    }

    .info-card p {
        margin: 0;
        color: #64748b;
        font-size: 0.92rem;
        line-height: 1.65;
    }

    .info-cta-band {
        position: relative;
        overflow: hidden;
        border-radius: 10px;
        padding: clamp(1.4rem, 3vw, 2.1rem);
        background:
            linear-gradient(135deg, rgba(6, 68, 117, 0.96), rgba(30, 49, 146, 0.9)),
            radial-gradient(circle at 88% 12%, rgba(245, 130, 32, 0.34), transparent 26%);
        color: #fff;
        box-shadow: 0 18px 42px rgba(15, 23, 42, 0.14);
    }

    .info-cta-band h2 {
        margin: 0 0 0.45rem;
        color: #fff;
        font-size: clamp(1.4rem, 2.5vw, 2rem);
        font-weight: 850;
    }

    .info-cta-band p {
        max-width: 760px;
        margin: 0;
        color: rgba(255, 255, 255, 0.78);
        line-height: 1.6;
    }

    .info-cta-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-top: 1.2rem;
    }

    .info-mini-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 0.85rem;
        margin-top: 1rem;
    }

    .info-mini {
        border: 1px solid #d6dce8;
        border-radius: 8px;
        background: #fff;
        padding: 1rem;
        color: #64748b;
        font-size: 0.88rem;
        line-height: 1.55;
    }

    .info-mini strong {
        display: block;
        margin-bottom: 0.35rem;
        color: #061224;
        font-size: 0.94rem;
    }

    @media (max-width: 991px) {
        .info-card-grid {
            grid-template-columns: 1fr;
        }

        .info-mini-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 575px) {
        .info-page-hero .container {
            min-height: 360px;
        }

        .info-kpi-grid,
        .info-mini-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<section class="info-page-hero">
    <div class="container py-5">
        <div>
            <span class="info-eyebrow"><i class="bi {{ $page['icon'] }}"></i>{{ $page['eyebrow'] }}</span>
            <h1 class="info-title">{{ $page['title'] }}</h1>
            <p class="info-summary">{{ $page['summary'] }}</p>
            <div class="info-kpi-grid">
                @foreach($page['kpis'] as $kpi)
                    <div class="info-kpi">
                        <strong>{{ $kpi['value'] }}</strong>
                        <span>{{ $kpi['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section class="info-section py-5">
    <div class="container py-4">
        <div class="info-card-grid">
            @foreach($page['sections'] as $section)
                <article class="info-card">
                    <span class="info-card-icon"><i class="bi {{ $page['icon'] }}"></i></span>
                    <h2>{{ $section['title'] }}</h2>
                    <p>{{ $section['body'] }}</p>
                </article>
            @endforeach
        </div>

        <div class="info-cta-band mt-4">
            <h2>Grow business with Trade4Deal</h2>
            <p>Use Trade4Deal to discover buyers, find suppliers, publish requirements, and keep your B2B conversations focused on real trade opportunities.</p>
            <div class="info-cta-actions">
                @if(str_starts_with($primaryHref, '#'))
                    <button type="button" class="btn btn-light fw-bold px-4" data-bs-toggle="modal" data-bs-target="{{ $primaryHref }}">
                        {{ $page['cta_label'] }}
                    </button>
                @else
                    <a href="{{ $primaryHref }}" class="btn btn-light fw-bold px-4">{{ $page['cta_label'] }}</a>
                @endif

                @if(str_starts_with($secondaryHref, '#'))
                    <button type="button" class="btn btn-ghost-light px-4" data-bs-toggle="modal" data-bs-target="{{ $secondaryHref }}">
                        {{ $page['secondary_label'] }}
                    </button>
                @else
                    <a href="{{ $secondaryHref }}" class="btn btn-ghost-light px-4">{{ $page['secondary_label'] }}</a>
                @endif
            </div>
        </div>

        <div class="info-mini-grid">
            <div class="info-mini">
                <strong>Buyer Discovery</strong>
                Submit product needs and find suppliers by category.
            </div>
            <div class="info-mini">
                <strong>Supplier Growth</strong>
                Build a public profile and publish active products.
            </div>
            <div class="info-mini">
                <strong>Lead Visibility</strong>
                Review live business leads according to your plan.
            </div>
            <div class="info-mini">
                <strong>Direct Enquiry</strong>
                Start focused conversations from leads and profiles.
            </div>
        </div>
    </div>
</section>
@endsection
