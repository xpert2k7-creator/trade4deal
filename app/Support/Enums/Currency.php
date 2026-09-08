<?php

declare(strict_types=1);

namespace App\Support\Enums;

enum Currency: string
{
    case USD = 'USD';
    case EUR = 'EUR';
    case GBP = 'GBP';
    case INR = 'INR';
    case AED = 'AED';

    public function label(): string
    {
        return match ($this) {
            self::USD => 'USD — US Dollar',
            self::EUR => 'EUR — Euro',
            self::GBP => 'GBP — British Pound',
            self::INR => 'INR — Indian Rupee',
            self::AED => 'AED — UAE Dirham',
        };
    }

    public function symbol(): string
    {
        return match ($this) {
            self::USD => '$',
            self::EUR => '€',
            self::GBP => '£',
            self::INR => '₹',
            self::AED => 'AED',
        };
    }
}
