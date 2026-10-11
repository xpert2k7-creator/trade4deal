<?php

declare(strict_types=1);

namespace App\Domains\Lead\Requests;

use App\Domains\Lead\Requests\Concerns\ValidatesLeadTradeTerms;

class StoreEmployeeLeadRequest extends StoreLeadRequest
{
    use ValidatesLeadTradeTerms;

    public function authorize(): bool
    {
        return $this->user()?->canModerateLeads() ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), $this->leadTradeTermRules());
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return array_merge(parent::messages(), $this->leadTradeTermMessages());
    }
}
