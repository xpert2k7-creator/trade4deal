<?php

declare(strict_types=1);

namespace Tests\Feature\Employee;

use App\Domains\Lead\Models\Lead;
use App\Models\User;
use App\Support\Enums\ProductType;
use App\Support\Enums\UserType;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeLeadFiltersTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    private function employee(): User
    {
        $user = User::factory()->create([
            'user_type' => UserType::Employee,
        ]);
        $user->assignRole('employee');

        return $user;
    }

    public function test_employee_sees_all_live_leads_on_homepage_without_plan_delay(): void
    {
        Lead::factory()->publishedRecently()->create([
            'company_name' => 'Staff Visible Corp',
        ]);

        $this->actingAs($this->employee())
            ->get(route('home'))
            ->assertOk()
            ->assertSee('Staff Visible Corp')
            ->assertSee('Staff view');
    }

    public function test_employee_can_filter_leads_by_category(): void
    {
        Lead::factory()->pending()->create([
            'company_name' => 'Machinery Co',
            'product_type' => ProductType::Machinery,
        ]);

        Lead::factory()->pending()->create([
            'company_name' => 'Textile Co',
            'product_type' => ProductType::Textiles,
        ]);

        $this->actingAs($this->employee())
            ->get(route('employee.leads.index', [
                'status' => 'pending',
                'product_type' => ProductType::Machinery->value,
            ]))
            ->assertOk()
            ->assertSee('Machinery Co')
            ->assertDontSee('Textile Co');
    }

    public function test_employee_can_filter_leads_by_name_and_country(): void
    {
        Lead::factory()->pending()->create([
            'company_name' => 'Alpha Traders',
            'country' => 'India',
        ]);

        Lead::factory()->pending()->create([
            'company_name' => 'Beta Exports',
            'country' => 'Germany',
        ]);

        $this->actingAs($this->employee())
            ->get(route('employee.leads.index', [
                'status' => 'pending',
                'name' => 'Alpha',
                'country' => 'India',
            ]))
            ->assertOk()
            ->assertSee('Alpha Traders')
            ->assertDontSee('Beta Exports');
    }

    public function test_employee_leads_index_is_paginated(): void
    {
        Lead::factory()->count(12)->pending()->create();

        $response = $this->actingAs($this->employee())
            ->get(route('employee.leads.index', [
                'status' => 'pending',
                'per_page' => 10,
            ]));

        $response->assertOk();
        $response->assertSee('Showing 1–10 of 12 leads');
        $response->assertSee('pagination', false); // bootstrap nav class
    }
}
