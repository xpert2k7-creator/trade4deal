<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateEmployeeUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manageEmployeeTeam', User::class) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        /** @var User $employeeUser */
        $employeeUser = $this->route('employeeUser');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($employeeUser->id)],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ];
    }
}
