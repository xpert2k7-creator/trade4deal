@php
    $favicons = config('marketplace_assets.favicons', []);
    $assetVersion = config('marketplace_assets.version');
@endphp
<link rel="icon" href="{{ asset($favicons['ico'] ?? 'images/favicons/favicon.ico') }}?v={{ $assetVersion }}" sizes="any">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset($favicons['32'] ?? 'images/favicons/favicon-32x32.png') }}?v={{ $assetVersion }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset($favicons['16'] ?? 'images/favicons/favicon-16x16.png') }}?v={{ $assetVersion }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset($favicons['apple'] ?? 'images/favicons/apple-touch-icon.png') }}?v={{ $assetVersion }}">
