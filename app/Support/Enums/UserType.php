<?php

declare(strict_types=1);

namespace App\Support\Enums;

enum UserType: string
{
    case Buyer = 'buyer';
    case Seller = 'seller';
    case Admin = 'admin';
    case Employee = 'employee';

    public function label(): string
    {
        return match ($this) {
            self::Buyer => 'Buyer',
            self::Seller => 'Seller',
            self::Admin => 'Administrator',
            self::Employee => 'Employee',
        };
    }
}
