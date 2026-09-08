<?php

declare(strict_types=1);

return [
    'gold_stripe_price_id' => env('STRIPE_GOLD_PRICE_ID'),
    'gold_plan_name' => env('STRIPE_GOLD_PLAN_NAME', 'gold'),
    'lead_visibility_delay_hours' => (int) env('LEAD_VISIBILITY_DELAY_HOURS', 24),
];
