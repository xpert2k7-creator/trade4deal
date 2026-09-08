<?php

declare(strict_types=1);

namespace App\Domains\Seller\Requests;

use App\Support\Enums\EmployeesRange;
use App\Support\Enums\ProductType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSellerProfileRequest extends FormRequest
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
            'company_name' => ['required', 'string', 'max:180'],
            'tagline' => ['nullable', 'string', 'max:200'],
            'about' => ['nullable', 'string', 'max:8000'],
            'phone' => ['nullable', 'string', 'max:40'],
            'country' => ['required', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:120'],
            'address' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'year_established' => ['nullable', 'integer', 'min:1800', 'max:'.(int) date('Y')],
            'employees_range' => ['nullable', Rule::enum(EmployeesRange::class)],
            'industries' => ['nullable', 'array'],
            'industries.*' => [Rule::enum(ProductType::class)],
            'is_public' => ['sometimes', 'boolean'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'remove_logo' => ['sometimes', 'boolean'],
            'remove_cover' => ['sometimes', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_public' => $this->boolean('is_public'),
            'remove_logo' => $this->boolean('remove_logo'),
            'remove_cover' => $this->boolean('remove_cover'),
        ]);
    }
}
