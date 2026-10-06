<?php

declare(strict_types=1);

namespace App\Domains\Auth\Services;

use App\Domains\Auth\DTOs\RegisterUserDTO;
use App\Domains\Auth\Notifications\WelcomeUserNotification;
use App\Models\User;
use App\Support\Enums\RecordStatus;
use App\Support\Enums\UserPlan;
use Spatie\Permission\Models\Role;

class AuthService
{
    public function registerUser(RegisterUserDTO $dto): User
    {
        $user = User::query()->create([
            'name' => $dto->name,
            'email' => $dto->email,
            // Password is hashed by the User model cast — do not Hash::make here.
            'password' => $dto->password,
            'phone' => $dto->phone,
            'company_name' => $dto->companyName,
            'country' => $dto->country,
            'user_type' => $dto->userType,
            'plan' => UserPlan::Free,
            'status' => RecordStatus::Active,
            // Auto-verify so users can open the dashboard without SMTP setup.
            'email_verified_at' => now(),
        ]);

        Role::findOrCreate($dto->userType->value, 'web');
        $user->assignRole($dto->userType->value);

        if ($user->isSeller()) {
            $user->ensureSellerSlug();
        }

        try {
            $user->notify(new WelcomeUserNotification($user));
        } catch (\Throwable $e) {
            report($e);
        }

        return $user;
    }
}
