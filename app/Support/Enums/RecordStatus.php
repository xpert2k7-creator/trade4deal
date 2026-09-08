<?php

declare(strict_types=1);

namespace App\Support\Enums;

enum RecordStatus: int
{
    case Inactive = 0;
    case Active = 1;
    case Pending = 2;
    case Suspended = 3;

    public function label(): string
    {
        return match ($this) {
            self::Inactive => 'Inactive',
            self::Active => 'Active',
            self::Pending => 'Pending',
            self::Suspended => 'Suspended',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Inactive => 'bg-secondary',
            self::Active => 'bg-success',
            self::Pending => 'bg-warning text-dark',
            self::Suspended => 'bg-danger',
        };
    }
}
