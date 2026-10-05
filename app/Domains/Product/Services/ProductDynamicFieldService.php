<?php

declare(strict_types=1);

namespace App\Domains\Product\Services;

use App\Domains\Product\Models\Category;
use App\Domains\Product\Models\Product;
use App\Domains\Product\Models\ProductField;
use App\Domains\Product\Models\ProductFieldValue;
use App\Support\Enums\ProductFieldType;
use App\Support\Enums\ProductType;
use App\Support\Enums\RecordStatus;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductDynamicFieldService
{
    private const FILE_DIRECTORY = 'products/specifications';

    /**
     * @return Collection<int, ProductField>
     */
    public function activeFieldsForProductType(ProductType $productType): Collection
    {
        $categoryKey = ProductCategoryResolver::fieldCategoryKey($productType);

        $category = Category::query()
            ->where('key', $categoryKey)
            ->where('status', RecordStatus::Active)
            ->first();

        if ($category === null) {
            return collect();
        }

        return ProductField::query()
            ->where('category_id', $category->id)
            ->where('status', RecordStatus::Active)
            ->with(['options' => fn ($q) => $q->orderBy('sort_order')])
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * @return array<string, mixed>
     */
    public function validationRulesForProductType(ProductType $productType): array
    {
        $rules = [
            'dynamic_fields' => ['nullable', 'array'],
        ];

        foreach ($this->activeFieldsForProductType($productType) as $field) {
            $key = 'dynamic_fields.'.$field->id;
            $rules[$key] = $this->rulesForField($field);
        }

        return $rules;
    }

    /**
     * @return list<string|Rule>
     */
    private function rulesForField(ProductField $field): array
    {
        $rules = [];

        if ($field->is_required) {
            $rules[] = 'required';
        } else {
            $rules[] = 'nullable';
        }

        return array_merge($rules, match ($field->field_type) {
            ProductFieldType::Text, ProductFieldType::Textarea => ['string', 'max:8000'],
            ProductFieldType::Number => ['numeric'],
            ProductFieldType::Date => ['date'],
            ProductFieldType::Boolean => ['in:0,1'],
            ProductFieldType::Select => [
                Rule::in($field->options->pluck('value')->all()),
            ],
            ProductFieldType::File => ['file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
        });
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function serializeFieldsForProductType(ProductType $productType): array
    {
        return $this->activeFieldsForProductType($productType)
            ->map(fn (ProductField $field): array => [
                'id' => $field->id,
                'field_name' => $field->field_name,
                'field_key' => $field->field_key,
                'field_type' => $field->field_type->value,
                'unit' => $field->unit,
                'is_required' => $field->is_required,
                'options' => $field->options->map(fn ($opt) => [
                    'label' => $opt->label,
                    'value' => $opt->value,
                ])->values()->all(),
            ])
            ->values()
            ->all();
    }

    /**
     * @param  array<string, mixed>  $dynamicFieldsInput
     */
    public function syncValues(Product $product, ProductType $productType, Request $request, array $dynamicFieldsInput): void
    {
        $allowedFields = $this->activeFieldsForProductType($productType)->keyBy('id');
        $allowedIds = $allowedFields->keys()->all();

        ProductFieldValue::query()
            ->where('product_id', $product->id)
            ->whereNotIn('product_field_id', $allowedIds)
            ->with('productField')
            ->get()
            ->each(function (ProductFieldValue $row): void {
                $this->deleteValueRow($row, $row->productField);
                $row->delete();
            });

        foreach ($allowedFields as $fieldId => $field) {
            $fieldId = (string) $fieldId;
            $file = $request->file('dynamic_fields.'.$fieldId);

            if ($field->field_type === ProductFieldType::File) {
                $this->syncFileField($product, $field, $file, $dynamicFieldsInput[$fieldId] ?? null);

                continue;
            }

            $raw = $dynamicFieldsInput[$fieldId] ?? null;
            $normalized = $this->normalizeSubmittedValue($field, $raw);

            if ($this->isEmptyValue($normalized)) {
                $existing = ProductFieldValue::query()
                    ->where('product_id', $product->id)
                    ->where('product_field_id', $field->id)
                    ->first();

                if ($existing !== null) {
                    $this->deleteValueRow($existing, $field);
                    $existing->delete();
                }

                continue;
            }

            ProductFieldValue::query()->updateOrCreate(
                [
                    'product_id' => $product->id,
                    'product_field_id' => $field->id,
                ],
                [
                    'value' => $normalized,
                    'unit' => $this->valueUnitForField($field),
                ],
            );
        }
    }

    private function syncFileField(
        Product $product,
        ProductField $field,
        ?UploadedFile $file,
        mixed $submittedNonFile,
    ): void {
        $existing = ProductFieldValue::query()
            ->where('product_id', $product->id)
            ->where('product_field_id', $field->id)
            ->first();

        if ($file === null) {
            return;
        }

        if ($existing?->value) {
            Storage::disk('public')->delete($existing->value);
        }

        $path = $file->store(self::FILE_DIRECTORY, 'public');
        if ($path === false) {
            return;
        }

        ProductFieldValue::query()->updateOrCreate(
            [
                'product_id' => $product->id,
                'product_field_id' => $field->id,
            ],
            [
                'value' => $path,
                'unit' => null,
            ],
        );
    }

    private function normalizeSubmittedValue(ProductField $field, mixed $raw): ?string
    {
        if ($raw === null || $raw === '') {
            return null;
        }

        if ($field->field_type === ProductFieldType::Boolean) {
            return in_array($raw, [true, 1, '1', 'true', 'yes', 'Yes'], true) ? '1' : '0';
        }

        return is_scalar($raw) ? trim((string) $raw) : null;
    }

    private function isEmptyValue(?string $value): bool
    {
        return $value === null || $value === '';
    }

    private function valueUnitForField(ProductField $field): ?string
    {
        if ($field->field_type !== ProductFieldType::Number) {
            return null;
        }

        return filled($field->unit) ? $field->unit : null;
    }

    private function deleteValueRow(ProductFieldValue $row, ?ProductField $field): void
    {
        if ($field?->field_type === ProductFieldType::File && filled($row->value)) {
            Storage::disk('public')->delete($row->value);
        }
    }

    /**
     * @return array<string, string>
     */
    public function valuesMapForProduct(Product $product, ProductType $productType): array
    {
        $allowedIds = $this->activeFieldsForProductType($productType)->pluck('id')->all();

        return ProductFieldValue::query()
            ->where('product_id', $product->id)
            ->whereIn('product_field_id', $allowedIds)
            ->get()
            ->mapWithKeys(fn (ProductFieldValue $row) => [(string) $row->product_field_id => (string) $row->value])
            ->all();
    }

    public function publicUrlForStoredPath(?string $path): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }

        return '/uploads/'.ltrim($path, '/');
    }
}
