<?php

namespace App\Rules;

use App\Models\User;
use App\Support\PhoneNumber;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniquePhoneNumber implements ValidationRule
{
    public function __construct(private ?string $country, private ?string $ignoreId = null) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $normalized = is_string($value) ? PhoneNumber::normalize($value, $this->country) : null;
        if ($normalized === null) {
            $fail('Enter a valid mobile number for your country.');

            return;
        }

        if (User::withTrashed()->where('phone_normalized', $normalized)
            ->when($this->ignoreId, fn ($query) => $query->whereKeyNot($this->ignoreId))->exists()) {
            $fail('This mobile number is already registered.');
        }
    }
}
