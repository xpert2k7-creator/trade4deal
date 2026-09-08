<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use App\Support\Enums\RecordStatus;
use App\Support\Enums\UserPlan;
use App\Support\Enums\UserType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'phone' => fake()->phoneNumber(),
            'company_name' => fake()->company(),
            'country' => fake()->country(),
            'user_type' => fake()->randomElement([UserType::Buyer, UserType::Seller]),
            'plan' => UserPlan::Free,
            'status' => RecordStatus::Active,
            'is_public' => true,
            'remember_token' => Str::random(10),
        ];
    }

    public function seller(): static
    {
        return $this->state(function (array $attributes): array {
            $company = $attributes['company_name'] ?? fake()->company();

            return [
                'user_type' => UserType::Seller,
                'company_name' => $company,
                'slug' => Str::slug($company).'-'.fake()->unique()->numerify('###'),
                'tagline' => fake()->sentence(6),
                'about' => fake()->paragraphs(2, true),
                'city' => fake()->city(),
                'is_public' => true,
            ];
        });
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
