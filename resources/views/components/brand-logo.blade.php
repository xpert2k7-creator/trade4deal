@props([
    'height' => 48,
    'class' => '',
])

<img
    src="{{ asset('images/trade4deal-logo.jpg') }}"
    alt="Trade4Deal — Connecting Buyer. Connecting Supplier. Creating Value."
    height="{{ $height }}"
    class="t4d-logo {{ $class }}"
    style="height: {{ $height }}px; width: auto; max-width: 100%; object-fit: contain;"
>
