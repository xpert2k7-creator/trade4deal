<?php

declare(strict_types=1);

return [
    'lead_visibility_delay_hours' => (int) env('LEAD_VISIBILITY_DELAY_HOURS', 24),

    'sourcing_lead_defaults' => [
        'business_type' => 'buyer',
        'currency' => 'USD',
        'units' => 'kg',
        'payment_methods' => ['wire_transfer'],
    ],
];
