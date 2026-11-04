<?php

declare(strict_types=1);

namespace App\Domains\Lead\Requests;

use App\Support\Enums\BusinessType;
use App\Support\Enums\Currency;
use App\Support\Enums\LeadUnit;
use App\Support\Enums\PaymentMethod;
use App\Support\Enums\ProductType;
use App\Support\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLeadRequest extends FormRequest
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
            'company_name' => ['required', 'string', 'max:255', 'regex:/\p{L}/u'],
            'contact_name' => ['required', 'string', 'max:255', 'regex:/^[\p{L}\p{M}\s.\x{0027}\x{2019}-]+$/u', 'regex:/\p{L}/u'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:30', function (string $attribute, mixed $value, \Closure $fail): void {
                if (is_string($value) && PhoneNumber::normalize($value, $this->input('country')) === null) {
                    $fail('Enter a valid mobile number for your country.');
                }
            }],
            'country' => ['required', 'string', 'max:100'],
            'business_type' => ['required', Rule::enum(BusinessType::class)],
            'product_interest' => ['required', 'string', 'max:255', 'regex:/\p{L}/u'],
            'product_type' => ['required', Rule::enum(ProductType::class)],
            'product_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'currency' => ['required', Rule::enum(Currency::class)],
            'units' => ['required', Rule::enum(LeadUnit::class)],
            'payment_methods' => ['required', 'array', 'min:1'],
            'payment_methods.*' => [Rule::enum(PaymentMethod::class)],
            'message' => ['nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'company_name.required' => 'Please enter your company name.',
            'company_name.regex' => 'Your business name must contain at least one letter.',
            'contact_name.regex' => 'Enter a valid owner name using letters, spaces, apostrophes, dots or hyphens.',
            'phone.required' => 'Please enter your mobile number.',
            'product_interest.regex' => 'Your product description must contain at least one letter.',
            'contact_name.required' => 'Please enter the contact person name.',
            'email.required' => 'Please enter a valid business email.',
            'country.required' => 'Please select your country.',
            'business_type.required' => 'Please select whether you are a buyer or seller.',
            'product_interest.required' => 'Please describe the product or service you are interested in.',
            'product_type.required' => 'Please select a product type.',
            'currency.required' => 'Please select a currency.',
            'units.required' => 'Please select units.',
            'payment_methods.required' => 'Please select at least one payment method.',
            'payment_methods.min' => 'Please select at least one payment method.',
        ];
    }
}
