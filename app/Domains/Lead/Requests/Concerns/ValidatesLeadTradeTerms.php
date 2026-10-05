<?php

declare(strict_types=1);

namespace App\Domains\Lead\Requests\Concerns;

use App\Support\Enums\Incoterm;
use App\Support\Enums\LeadPaymentTerm;
use Illuminate\Validation\Rule;

trait ValidatesLeadTradeTerms
{
    /**
     * @return array<string, mixed>
     */
    protected function leadTradeTermRules(): array
    {
        return [
            'packaging_requirement' => ['nullable', 'string', 'max:2000'],
            'required_quantity' => ['required', 'numeric', 'gt:0'],
            'packaging_size' => ['nullable', 'string', 'max:120'],
            'target_price' => ['nullable', 'numeric', 'min:0'],
            'preferred_incoterm' => ['required', Rule::enum(Incoterm::class)],
            'port_of_loading' => ['nullable', 'string', 'max:120'],
            'destination_port' => ['nullable', 'string', 'max:120'],
            'payment_terms' => ['required', Rule::enum(LeadPaymentTerm::class)],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function leadTradeTermMessages(): array
    {
        return [
            'required_quantity.required' => 'Please enter the required quantity.',
            'required_quantity.gt' => 'Required quantity must be greater than zero.',
            'preferred_incoterm.required' => 'Please select a preferred incoterm.',
            'payment_terms.required' => 'Please select payment terms.',
        ];
    }
}
