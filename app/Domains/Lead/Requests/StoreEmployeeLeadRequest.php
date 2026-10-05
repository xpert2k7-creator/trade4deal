<?php

declare(strict_types=1);

namespace App\Domains\Lead\Requests;

use App\Domains\Lead\Requests\Concerns\ValidatesLeadTradeTerms;
use App\Support\Enums\PaymentMethod;
use Illuminate\Validation\Rule;

class StoreEmployeeLeadRequest extends StoreLeadRequest
{
    use ValidatesLeadTradeTerms;

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = parent::rules();
        unset($rules['payment_methods'], $rules['payment_methods.*']);

        return array_merge($rules, $this->leadTradeTermRules(), [
            'payment_methods' => ['nullable', 'array'],
            'payment_methods.*' => [Rule::enum(PaymentMethod::class)],
        ]);
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'payment_methods' => $this->input('payment_methods', []),
        ]);
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return array_merge(parent::messages(), $this->leadTradeTermMessages());
    }
}
