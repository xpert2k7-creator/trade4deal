<?php

declare(strict_types=1);

namespace App\Domains\Product\Requests;

use App\Domains\Product\Models\Product;
use App\Domains\Product\Services\ProductImageStorage;
use Illuminate\Contracts\Validation\Validator;

class UpdateProductRequest extends StoreProductRequest
{
    public function withValidator(Validator $validator): void
    {
        parent::withValidator($validator);

        $validator->after(function (Validator $validator): void {
            $product = $this->route('product');
            if (! $product instanceof Product) {
                return;
            }

            $existing = $product->imagePathsList();
            /** @var array<int, string> $remove */
            $remove = $this->input('remove_product_images', []);
            $remove = is_array($remove) ? $remove : [];
            $remaining = count(array_diff($existing, $remove));

            $newUploads = $this->file('product_images');
            $newCount = is_array($newUploads) ? count(array_filter($newUploads)) : 0;
            if ($this->hasFile('image')) {
                $newCount++;
            }

            if ($remaining + $newCount > 10) {
                $validator->errors()->add(
                    'product_images',
                    'You may have at most 10 product images.',
                );
            }

            foreach ($remove as $path) {
                if (! is_string($path)) {
                    continue;
                }

                if (! in_array($path, $existing, true)) {
                    $validator->errors()->add(
                        'remove_product_images',
                        'One or more images to remove are invalid.',
                    );
                    break;
                }

                if (! ProductImageStorage::pathBelongsToMember($path, (string) $product->user_id)) {
                    $validator->errors()->add(
                        'remove_product_images',
                        'One or more images to remove are invalid.',
                    );
                    break;
                }
            }
        });
    }
}
