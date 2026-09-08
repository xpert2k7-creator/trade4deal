<?php

declare(strict_types=1);

namespace App\Support\Enums;

enum EmployeesRange: string
{
    case Solo = '1';
    case Small = '2-10';
    case Medium = '11-50';
    case Large = '51-200';
    case Enterprise = '200+';

    public function label(): string
    {
        return match ($this) {
            self::Solo => '1 employee',
            self::Small => '2–10 employees',
            self::Medium => '11–50 employees',
            self::Large => '51–200 employees',
            self::Enterprise => '200+ employees',
        };
    }
}
