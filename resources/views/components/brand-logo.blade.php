@props([
    'height' => 48,
    'class' => '',
])

<img
    src="{{ \App\Support\MarketplaceAssets::url(config('marketplace_assets.logo')) }}"
    alt="Trade4Deal — Connecting Buyer Seller Globally"
    height="{{ $height }}"
    class="t4d-logo {{ $class }}"
    style="height: {{ $height }}px; width: auto; max-width: 100%; object-fit: contain;"
>
