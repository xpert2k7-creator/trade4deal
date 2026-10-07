<?php

declare(strict_types=1);

/**
 * Static files under public/images (and subfolders). Use with asset() or t4d_asset().
 */
return [
    'version' => env('T4D_ASSET_VERSION', '20261007'),

    'logo' => 'images/trade4deal-logo.jpg',

    'favicons' => [
        'ico' => 'images/favicons/favicon.ico',
        '16' => 'images/favicons/favicon-16x16.png',
        '32' => 'images/favicons/favicon-32x32.png',
        '48' => 'images/favicons/favicon-48x48.png',
        'apple' => 'images/favicons/apple-touch-icon.png',
    ],

    'hero' => [
        'video' => 'images/trade4deal-hero-video.mp4',
        'logistics' => 'images/trade4deal-hero-logistics.jpg',
        'slide_freight' => 'images/electronics-logistics-bg.jpg',
    ],

    'pages' => [
        'electronics_logistics_bg' => 'images/electronics-logistics-bg.jpg',
    ],

    'clients' => [
        ['name' => 'ZYFUD', 'src' => 'images/clients/zyfud.jpeg'],
        ['name' => 'Navarna Group', 'src' => 'images/clients/navarna-group.jpg'],
        ['name' => 'Zuari Industries', 'src' => 'images/clients/zuari.jpeg'],
        ['name' => 'Uttam Sugar', 'src' => 'images/clients/uttam-sugar.jpeg'],
        ['name' => 'Shhe', 'src' => 'images/clients/shhefoods.png'],
    ],

    'category_banners' => [
        'machinery' => [
            ['src' => 'images/category-banners/Medical equipment for clinics and hospitals-1.png', 'alt' => 'Trade4Deal medical equipment banner'],
            ['src' => 'images/category-banners/Electronics & Electrical Product Showcase-2.png', 'alt' => 'Trade4Deal electronics and electrical banner'],
            ['src' => 'images/category-banners/Trade4Deal Machinery & Tools Showcase-3.png', 'alt' => 'Trade4Deal machinery and tools banner'],
            ['src' => 'images/category-banners/Construction materials sourcing showcase-3.png', 'alt' => 'Trade4Deal construction materials banner'],
            ['src' => 'images/category-banners/Golden grains and pulses marketplace-1.png', 'alt' => 'Trade4Deal agriculture and food banner'],
            ['src' => 'images/category-banners/Trade4Deal textiles and apparel showcase-2.png', 'alt' => 'Trade4Deal textiles and apparel banner'],
        ],
        'chemicals' => [
            ['src' => 'images/category-banners/Golden grains and pulses marketplace-1.png', 'alt' => 'Trade4Deal agriculture and food banner'],
            ['src' => 'images/category-banners/Trade4Deal textiles and apparel showcase-2.png', 'alt' => 'Trade4Deal textiles and apparel banner'],
            ['src' => 'images/category-banners/Construction materials sourcing showcase-3.png', 'alt' => 'Trade4Deal construction materials banner'],
            ['src' => 'images/category-banners/Medical equipment for clinics and hospitals-1.png', 'alt' => 'Trade4Deal medical equipment banner'],
            ['src' => 'images/category-banners/Electronics & Electrical Product Showcase-2.png', 'alt' => 'Trade4Deal electronics and electrical banner'],
            ['src' => 'images/category-banners/Trade4Deal Machinery & Tools Showcase-3.png', 'alt' => 'Trade4Deal machinery and tools banner'],
        ],
    ],
];
