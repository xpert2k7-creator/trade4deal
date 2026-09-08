<?php

declare(strict_types=1);

namespace App\Domains\Product\Requests;

use App\Support\Enums\Currency;
use App\Support\Enums\LeadUnit;
use App\Support\Enums\ProductType;
use App\Support\Enums\RecordStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:180'],
            'description' => ['nullable', 'string', 'max:5000'],
            'product_type' => ['required', Rule::enum(ProductType::class)],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'currency' => ['required', Rule::enum(Currency::class)],
            'units' => ['required', Rule::enum(LeadUnit::class)],
            'min_order_qty' => ['nullable', 'string', 'max:80'],
            'price_from' => ['nullable', 'numeric', 'min:0'],
            'price_to' => ['nullable', 'numeric', 'min:0', 'gte:price_from'],
            'status' => ['required', Rule::in([RecordStatus::Active->value, RecordStatus::Inactive->value])],
        ];
    }
}
