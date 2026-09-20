<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\User;
use App\Support\Enums\EmployeesRange;
use App\Support\Enums\ProductType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSellerDetailsRequest extends FormRequest
{
    public function authorize(): bool
    {
        $seller = $this->route('user');

        return $seller instanceof User
            && $this->user()?->can('updateSellerDetails', $seller);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $seller = $this->route('user');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($seller?->id),
            ],
            'designation' => ['nullable', 'string', 'max:120'],
            'company_name' => ['required', 'string', 'max:180'],
            'tagline' => ['nullable', 'string', 'max:200'],
            'about' => ['nullable', 'string', 'max:8000'],
            'phone' => ['nullable', 'string', 'max:40'],
            'secondary_phone' => ['nullable', 'string', 'max:40'],
            'country' => ['required', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:120'],
            'address' => ['nullable', 'string', 'max:255'],
            'address_house_block' => ['nullable', 'string', 'max:120'],
            'address_area_street' => ['nullable', 'string', 'max:180'],
            'district' => ['nullable', 'string', 'max:120'],
            'state' => ['nullable', 'string', 'max:120'],
            'pin_code' => ['nullable', 'string', 'max:20'],
            'website' => ['nullable', 'url', 'max:255'],
            'gstin' => ['nullable', 'string', 'max:30'],
            'cin' => ['nullable', 'string', 'max:30'],
            'pan' => ['nullable', 'string', 'max:20'],
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
            'gstin' => $this->filled('gstin') ? strtoupper((string) $this->input('gstin')) : null,
            'cin' => $this->filled('cin') ? strtoupper((string) $this->input('cin')) : null,
            'pan' => $this->filled('pan') ? strtoupper((string) $this->input('pan')) : null,
        ]);
    }
}
