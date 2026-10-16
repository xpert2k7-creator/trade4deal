<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Safe to rerun in production: never create or reset login credentials.
        $this->call(RolePermissionSeeder::class);
    }
}
