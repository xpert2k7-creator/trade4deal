<?php

declare(strict_types=1);

namespace App\Domains\Lead\Requests;

use App\Support\Enums\ProductType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSourcingLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isSourcing() ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'company_name' => ['required', 'string', 'max:255'],
            'contact_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255'],
            'product_type' => ['required', Rule::enum(ProductType::class)],
            'product_interest' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'company_name.required' => 'Please enter the company name.',
            'phone.required' => 'Please enter a contact number.',
            'email.required' => 'Please enter a valid email.',
            'country.required' => 'Please enter the country.',
            'product_type.required' => 'Please select a product category.',
        ];
    }
}
