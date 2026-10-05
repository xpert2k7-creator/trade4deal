<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domains\Product\Models\Category;
use App\Domains\Product\Models\ProductField;
use App\Domains\Product\Models\ProductFieldOption;
use App\Support\Enums\RecordStatus;
use Database\Seeders\Support\ProductCategoryFieldDefinitions;
use Illuminate\Database\Seeder;

class ProductCategoryFieldSeeder extends Seeder
{
    public function run(): void
    {
        foreach (ProductCategoryFieldDefinitions::categories() as $categoryRow) {
            Category::query()->updateOrCreate(
                ['key' => $categoryRow['key']],
                [
                    'name' => $categoryRow['name'],
                    'sort_order' => $categoryRow['sort_order'],
                    'status' => RecordStatus::Active,
                ],
            );
        }

        $categoriesByKey = Category::query()
            ->whereIn('key', array_column(ProductCategoryFieldDefinitions::categories(), 'key'))
            ->get()
            ->keyBy('key');

        foreach (ProductCategoryFieldDefinitions::fieldsByCategoryKey() as $categoryKey => $fields) {
            $category = $categoriesByKey->get($categoryKey);
            if ($category === null) {
                continue;
            }

            foreach ($fields as $sortOrder => $fieldRow) {
                $field = ProductField::query()->updateOrCreate(
                    [
                        'category_id' => $category->id,
                        'field_key' => $fieldRow['field_key'],
                    ],
                    [
                        'section' => ProductCategoryFieldDefinitions::SECTION,
                        'field_name' => $fieldRow['field_name'],
                        'field_type' => $fieldRow['field_type'],
                        'unit' => $fieldRow['unit'],
                        'is_required' => false,
                        'sort_order' => $sortOrder + 1,
                        'status' => RecordStatus::Active,
                    ],
                );

                if (! isset($fieldRow['options'])) {
                    continue;
                }

                foreach ($fieldRow['options'] as $optionIndex => $label) {
                    $value = ProductCategoryFieldDefinitions::optionValue($label);
                    if ($value === '') {
                        $value = 'option_'.($optionIndex + 1);
                    }

                    ProductFieldOption::query()->updateOrCreate(
                        [
                            'product_field_id' => $field->id,
                            'value' => $value,
                        ],
                        [
                            'label' => $label,
                            'sort_order' => $optionIndex + 1,
                        ],
                    );
                }
            }
        }
    }
}
