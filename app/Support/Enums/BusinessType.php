<?php

declare(strict_types=1);

namespace App\Support\Enums;

enum BusinessType: string
{
    case Buyer = 'buyer';
    case Seller = 'seller';
    case Both = 'both';

    public function label(): string
    {
        return match ($this) {
            self::Buyer => 'Buyer',
            self::Seller => 'Seller',
            self::Both => 'Buyer & Seller',
        };
    }
}
