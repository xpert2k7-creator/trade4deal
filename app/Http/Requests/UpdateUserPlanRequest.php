<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Support\Enums\UserPlan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserPlanRequest extends FormRequest
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
            'plan' => ['required', Rule::enum(UserPlan::class)],
        ];
    }
}
