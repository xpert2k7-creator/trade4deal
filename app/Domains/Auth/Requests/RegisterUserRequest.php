<?php

declare(strict_types=1);

namespace App\Domains\Auth\Requests;

use App\Models\User;
use App\Support\Enums\UserType;
use App\Support\PhoneNumber;
use Illuminate\Contracts\Validation\Validator;
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
            'name' => ['required', 'string', 'max:255', 'regex:/^[\p{L}\p{M}\s.\x{0027}\x{2019}-]+$/u', 'regex:/\p{L}/u'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Password::defaults()],
            'phone' => ['nullable', 'string', 'max:30'],
            'company_name' => ['required', 'string', 'max:255', 'regex:/\p{L}/u'],
            'selling_products' => ['nullable', 'string', 'max:255', 'regex:/\p{L}/u'],
            'country' => ['required', 'string', 'max:100'],
            'user_type' => ['required', Rule::in([UserType::Buyer->value, UserType::Seller->value])],
        ];
    }

    public function messages(): array
    {
        return [
            'name.regex' => 'Enter a valid contact person name using letters, spaces, apostrophes, dots or hyphens.',
            'company_name.regex' => 'Your business name must contain at least one letter.',
            'selling_products.regex' => 'Selling products must contain at least one letter.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if (! $this->filled('phone')) {
                return;
            }

            $normalized = PhoneNumber::normalize(
                $this->input('phone'),
                $this->input('country'),
            );

            if ($normalized === null) {
                $validator->errors()->add(
                    'phone',
                    'Enter a valid mobile number for your country.',
                );

                return;
            }

            $exists = User::query()
                ->withTrashed()
                ->where('phone_normalized', $normalized)
                ->exists();

            if ($exists) {
                $validator->errors()->add(
                    'phone',
                    'This mobile number is already registered.',
                );
            }
        });
    }
}
