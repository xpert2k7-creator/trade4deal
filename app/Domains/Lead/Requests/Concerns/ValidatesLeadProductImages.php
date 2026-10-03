<?php

declare(strict_types=1);

namespace App\Domains\Lead\Requests\Concerns;

trait ValidatesLeadProductImages
{
    /**
     * @return array<string, mixed>
     */
    protected function leadProductImageRules(): array
    {
        return [
            'product_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'product_images' => ['nullable', 'array', 'max:10'],
            'product_images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_product_images' => ['nullable', 'array'],
            'remove_product_images.*' => ['string', 'max:255'],
        ];
    }
}
