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

    public function description(): string
    {
        return match ($this) {
            self::Machinery => 'Industrial machines and equipment from verified suppliers.',
            self::Textiles => 'Fabrics, garments, and apparel ready for wholesale trade.',
            self::Electronics => 'Consumer and industrial electronics from trusted sellers.',
            self::Agriculture => 'Farm produce, inputs, and agri supplies for global buyers.',
            self::Chemicals => 'Industrial and specialty chemicals from verified exporters.',
            self::Construction => 'Building materials and supplies for construction projects.',
            self::Medical => 'Medical devices and healthcare equipment for buyers.',
            self::Food => 'Packaged foods and beverages for domestic and export trade.',
            self::Other => 'Additional trade products across emerging categories.',
        };
    }
}
