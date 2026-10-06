<?php

declare(strict_types=1);

namespace App\Domains\Lead\Requests;

use App\Support\Enums\BusinessType;
use App\Support\Enums\Currency;
use App\Support\Enums\LeadUnit;
use App\Support\Enums\PaymentMethod;
use App\Support\Enums\ProductType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canModerateLeads() ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'company_name' => ['required', 'string', 'max:255'],
            'contact_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'country' => ['required', 'string', 'max:100'],
            'business_type' => ['required', Rule::enum(BusinessType::class)],
            'product_interest' => ['required', 'string', 'max:255'],
            'product_type' => ['required', Rule::enum(ProductType::class)],
            'product_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'currency' => ['required', Rule::enum(Currency::class)],
            'units' => ['required', Rule::enum(LeadUnit::class)],
            'payment_methods' => ['required', 'array', 'min:1'],
            'payment_methods.*' => [Rule::enum(PaymentMethod::class)],
            'message' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
