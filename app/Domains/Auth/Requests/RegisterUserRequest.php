<?php

declare(strict_types=1);

namespace App\Domains\Auth\Requests;

use App\Models\User;
use App\Support\Enums\UserType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterUserRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Password::defaults()],
            'phone' => ['nullable', 'string', 'max:30'],
            'company_name' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:100'],
            'user_type' => ['required', Rule::in([UserType::Buyer->value, UserType::Seller->value])],
        ];
    }
}
