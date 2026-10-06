<?php

declare(strict_types=1);

namespace App\Support\Enums;

enum LeadSource: string
{
    case User = 'user';
    case Employee = 'employee';
    case Sourcing = 'sourcing';

    public function label(): string
    {
        return match ($this) {
            self::User => 'Public user',
            self::Employee => 'Employee',
            self::Sourcing => 'Sourcing',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::User => 'text-bg-light text-dark border',
            self::Employee => 'text-bg-primary',
            self::Sourcing => 'text-bg-info text-dark',
        };
    }
}
