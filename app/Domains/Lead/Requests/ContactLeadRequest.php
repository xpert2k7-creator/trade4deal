<?php

declare(strict_types=1);

namespace App\Domains\Lead\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactLeadRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'company_name' => ['required', 'string', 'max:180'],
            'country' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:40'],
            'message' => ['required', 'string', 'min:20', 'max:2000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'message.min' => 'Please include at least 20 characters so the supplier understands your inquiry.',
        ];
    }
}
