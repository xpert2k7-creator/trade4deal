<?php

declare(strict_types=1);

namespace App\Support\Enums;

enum ProductType: string
{
    case Machinery = 'machinery';
    case Textiles = 'textiles';
    case Electronics = 'electronics';
    case Agriculture = 'agriculture';
    case Chemicals = 'chemicals';
    case Construction = 'construction';
    case Medical = 'medical';
    case Food = 'food';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Machinery => 'Industrial Machinery',
            self::Textiles => 'Textiles & Apparel',
            self::Electronics => 'Electronics',
            self::Agriculture => 'Agriculture',
            self::Chemicals => 'Chemicals',
            self::Construction => 'Construction Materials',
            self::Medical => 'Medical Equipment',
            self::Food => 'Food & Beverage',
            self::Other => 'Other',
        };
    }
}
