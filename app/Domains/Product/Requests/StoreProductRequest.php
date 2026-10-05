<?php

declare(strict_types=1);

namespace App\Domains\Product\Requests;

use App\Domains\Product\Requests\Concerns\ValidatesProductImages;
use App\Domains\Product\Services\ProductDynamicFieldService;
use App\Support\Enums\Currency;
use App\Support\Enums\LeadUnit;
use App\Support\Enums\ProductType;
use App\Support\Enums\RecordStatus;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    use ValidatesProductImages;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $dynamic = $this->input('dynamic_fields');
        if (! is_array($dynamic)) {
            return;
        }

        foreach ($dynamic as $key => $value) {
            if ($value === '') {
                $dynamic[$key] = null;
            }
        }

        $this->merge(['dynamic_fields' => $dynamic]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = array_merge([
            'name' => ['required', 'string', 'max:180'],
            'description' => ['nullable', 'string', 'max:5000'],
            'product_type' => ['required', Rule::enum(ProductType::class)],
            'currency' => ['required', Rule::enum(Currency::class)],
            'units' => ['required', Rule::enum(LeadUnit::class)],
            'min_order_qty' => ['nullable', 'string', 'max:80'],
            'price_from' => ['nullable', 'numeric', 'min:0'],
            'price_to' => ['nullable', 'numeric', 'min:0', 'gte:price_from'],
            'status' => ['required', Rule::in([RecordStatus::Active->value, RecordStatus::Inactive->value])],
        ], $this->productImageRules());

        $productType = ProductType::tryFrom((string) $this->input('product_type'));
        if ($productType !== null) {
            $rules = array_merge(
                $rules,
                app(ProductDynamicFieldService::class)->validationRulesForProductType($productType),
            );
        }

        return $rules;
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $productType = ProductType::tryFrom((string) $this->input('product_type'));
            if ($productType === null) {
                return;
            }

            $allowedIds = app(ProductDynamicFieldService::class)
                ->activeFieldsForProductType($productType)
                ->pluck('id')
                ->map(fn (string $id): string => $id)
                ->all();

            $submitted = $this->input('dynamic_fields', []);
            if (! is_array($submitted)) {
                return;
            }

            foreach (array_keys($submitted) as $fieldId) {
                if (! in_array((string) $fieldId, $allowedIds, true)) {
                    $validator->errors()->add(
                        'dynamic_fields.'.$fieldId,
                        'This specification field is not valid for the selected category.',
                    );
                }
            }
        });
    }
}
