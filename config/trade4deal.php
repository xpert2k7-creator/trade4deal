<?php

declare(strict_types=1);

return [
    // Gold plan: one-time Razorpay payment for 1 year. Change prices here only.
    'gold_price_inr' => 25000,
    'gold_price_usd' => 265,
    // Optional local/testing override for IP pricing, e.g. IN or US. Leave empty in production.
    'pricing_country_override' => env('PRICING_COUNTRY_OVERRIDE'),
    'lead_visibility_delay_hours' => (int) env('LEAD_VISIBILITY_DELAY_HOURS', 24),
    'contact_email' => env('CONTACT_EMAIL', 'info@trade4deal.com'),

    'sourcing_lead_defaults' => [
        'business_type' => 'buyer',
        'currency' => 'USD',
        'units' => 'kg',
        'payment_methods' => ['wire_transfer'],
    ],
];
