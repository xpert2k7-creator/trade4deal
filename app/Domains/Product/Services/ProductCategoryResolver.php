<?php

declare(strict_types=1);

namespace App\Domains\Product\Services;

use App\Support\Enums\ProductType;

final class ProductCategoryResolver
{
    /**
     * Category key used to load product_fields (machinery uses other — no machinery fields in DB).
     */
    public static function fieldCategoryKey(ProductType $productType): string
    {
        if ($productType === ProductType::Machinery) {
            return ProductType::Other->value;
        }

        return $productType->value;
    }
}
