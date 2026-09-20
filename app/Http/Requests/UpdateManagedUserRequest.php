<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\User;
use App\Support\Enums\EmployeesRange;
use App\Support\Enums\ProductType;
use App\Support\Enums\RecordStatus;
use App\Support\Enums\UserPlan;
use App\Support\Enums\UserType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateManagedUserRequest extends FormRequest
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
        /** @var User $user */
        $user = $this->route('user');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:40'],
            'company_name' => ['required', 'string', 'max:180'],
            'country' => ['required', 'string', 'max:100'],
            'user_type' => ['required', Rule::in([UserType::Buyer->value, UserType::Seller->value])],
            'plan' => ['required', Rule::enum(UserPlan::class)],
            'status' => ['required', Rule::enum(RecordStatus::class)],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'tagline' => ['nullable', 'string', 'max:200'],
            'about' => ['nullable', 'string', 'max:8000'],
            'city' => ['nullable', 'string', 'max:120'],
            'address' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'year_established' => ['nullable', 'integer', 'min:1800', 'max:'.(int) date('Y')],
            'employees_range' => ['nullable', Rule::enum(EmployeesRange::class)],
            'industries' => ['nullable', 'array'],
            'industries.*' => [Rule::enum(ProductType::class)],
            'is_public' => ['sometimes', 'boolean'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'remove_logo' => ['sometimes', 'boolean'],
            'remove_cover' => ['sometimes', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_public' => $this->boolean('is_public'),
            'remove_logo' => $this->boolean('remove_logo'),
            'remove_cover' => $this->boolean('remove_cover'),
        ]);
    }
}
