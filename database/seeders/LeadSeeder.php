<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domains\Lead\Models\Lead;
use Illuminate\Database\Seeder;

class LeadSeeder extends Seeder
{
    public function run(): void
    {
        Lead::factory()->count(8)->create();
    }
}
