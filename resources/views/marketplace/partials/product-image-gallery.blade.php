@php
    /** @var array<int, string> $urls */
    $urls = array_values(array_filter($urls ?? []));
    $alt = $alt ?? 'Product';
    $showCount = (bool) ($showCount ?? false);
    $count = count($urls);
@endphp

@if ($count === 1)
    <img
        src="{{ $urls[0] }}"
        alt="{{ $alt }}"
        loading="lazy"
        onerror="this.nextElementSibling?.classList?.remove('d-none'); this.remove();"
    >
    @if ($showCount)
        <span class="product-photo-count"><i class="bi bi-images"></i> 1</span>
    @endif
@elseif ($count > 1)
    <div class="product-image-gallery" data-product-gallery data-count="{{ $count }}">
        <div class="product-image-track">
            @foreach ($urls as $index => $url)
                <img
                    src="{{ $url }}"
                    alt="{{ $alt }}"
                    loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                    class="{{ $index === 0 ? 'is-active' : '' }}"
                    draggable="false"
                >
            @endforeach
        </div>
        <div class="product-image-dots" aria-hidden="true">
            @foreach ($urls as $index => $url)
                <span class="{{ $index === 0 ? 'is-active' : '' }}"></span>
            @endforeach
        </div>
        <div class="product-image-progress" aria-hidden="true">
            @foreach ($urls as $index => $url)
                <span class="{{ $index === 0 ? 'is-active' : '' }}"></span>
            @endforeach
        </div>
    </div>
    @if ($showCount)
        <span class="product-photo-count"><i class="bi bi-images"></i> {{ $count }}</span>
    @endif
@endif
