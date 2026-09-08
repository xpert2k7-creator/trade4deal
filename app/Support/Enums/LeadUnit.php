<?php

declare(strict_types=1);

namespace App\Support\Enums;

enum LeadUnit: string
{
    case Pieces = 'pieces';
    case Kg = 'kg';
    case MetricTon = 'mt';
    case Containers = 'containers';
    case Liters = 'liters';
    case Meters = 'meters';
    case Sets = 'sets';

    public function label(): string
    {
        return match ($this) {
            self::Pieces => 'Pieces',
            self::Kg => 'Kilograms (kg)',
            self::MetricTon => 'Metric Tons (MT)',
            self::Containers => 'Containers',
            self::Liters => 'Liters',
            self::Meters => 'Meters',
            self::Sets => 'Sets',
        };
    }
}
