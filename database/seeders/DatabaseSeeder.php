<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use App\Support\Enums\RecordStatus;
use App\Support\Enums\UserType;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
        ]);

        $admin = User::query()->create([
            'name' => 'Trade4Deal Admin',
            'email' => 'admin@trade4deal.com',
            'password' => 'password',
            'company_name' => 'Trade4Deal Global',
            'country' => 'United States',
            'user_type' => UserType::Admin,
            'status' => RecordStatus::Active,
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');

        $employee = User::query()->create([
            'name' => 'Trade4Deal Employee',
            'email' => 'employee@trade4deal.com',
            'password' => 'password',
            'company_name' => 'Trade4Deal Global',
            'country' => 'United States',
            'user_type' => UserType::Employee,
            'status' => RecordStatus::Active,
            'email_verified_at' => now(),
        ]);
        $employee->assignRole('employee');

        User::query()->updateOrCreate(
            ['email' => 'rajeev@gmail.com'],
            [
                'name' => 'Rajeev',
                'password' => 'rajeev@123',
                'company_name' => 'Trade4Deal Global',
                'country' => 'India',
                'user_type' => UserType::Employee,
                'status' => RecordStatus::Active,
                'email_verified_at' => now(),
            ],
        )->assignRole('employee');

        $this->call([
            LeadSeeder::class,
        ]);
    }
}
