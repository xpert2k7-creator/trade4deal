<?php

declare(strict_types=1);

namespace Tests\Feature\Employee;

use App\Models\User;
use App\Support\Enums\ProductType;
use App\Support\Enums\UserPlan;
use App\Support\Enums\UserType;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeUserPlanTest extends TestCase
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

    private function admin(): User
    {
        $user = User::factory()->create([
            'user_type' => UserType::Admin,
        ]);
        $user->assignRole('admin');

        return $user;
    }

    public function test_employee_can_view_user_plan_management_page(): void
    {
        User::factory()->create([
            'name' => 'Market Buyer',
            'user_type' => UserType::Buyer,
            'plan' => UserPlan::Free,
        ]);

        $this->actingAs($this->employee())
            ->get(route('employee.users.index'))
            ->assertOk()
            ->assertSee('User Plans')
            ->assertSee('Market Buyer');
    }

    public function test_admin_can_edit_seller_details(): void
    {
        $seller = User::factory()->seller()->create([
            'name' => 'Original Seller',
            'email' => 'seller@example.com',
            'company_name' => 'Original Supplier Co',
            'country' => 'India',
            'industries' => [ProductType::Machinery->value],
        ]);
        $seller->assignRole('seller');

        $this->actingAs($this->admin())
            ->get(route('employee.users.sellers.edit', $seller))
            ->assertOk()
            ->assertSee('Original Supplier Co')
            ->assertSee('Seller snapshot');

        $this->actingAs($this->admin())
            ->put(route('employee.users.sellers.update', $seller), [
                'name' => 'Updated Seller',
                'email' => 'seller-updated@example.com',
                'company_name' => 'Updated Supplier Co',
                'country' => 'India',
                'city' => 'Surat',
                'tagline' => 'Trusted exporter',
                'about' => 'Updated supplier details for admin management.',
                'industries' => [ProductType::Textiles->value],
                'is_public' => true,
            ])
            ->assertRedirect(route('employee.users.sellers.edit', $seller));

        $this->assertDatabaseHas('users', [
            'id' => $seller->id,
            'name' => 'Updated Seller',
            'email' => 'seller-updated@example.com',
            'company_name' => 'Updated Supplier Co',
            'city' => 'Surat',
        ]);

        $this->assertSame([ProductType::Textiles->value], $seller->fresh()->industries);
    }

    public function test_employee_cannot_edit_seller_details(): void
    {
        $seller = User::factory()->seller()->create();
        $seller->assignRole('seller');

        $this->actingAs($this->employee())
            ->get(route('employee.users.sellers.edit', $seller))
            ->assertForbidden();
    }

    public function test_employee_can_upgrade_user_to_gold(): void
    {
        $buyer = User::factory()->create([
            'user_type' => UserType::Buyer,
            'plan' => UserPlan::Free,
        ]);

        $this->actingAs($this->employee())
            ->patch(route('employee.users.plan.update', $buyer), [
                'plan' => UserPlan::Gold->value,
            ])
            ->assertRedirect(route('employee.users.index'))
            ->assertSessionHas('success');

        $this->assertEquals(UserPlan::Gold, $buyer->fresh()->plan);
    }

    public function test_employee_can_downgrade_user_to_free(): void
    {
        $buyer = User::factory()->create([
            'user_type' => UserType::Buyer,
            'plan' => UserPlan::Gold,
        ]);

        $this->actingAs($this->employee())
            ->patch(route('employee.users.plan.update', $buyer), [
                'plan' => UserPlan::Free->value,
            ])
            ->assertRedirect(route('employee.users.index'))
            ->assertSessionHas('success');

        $this->assertEquals(UserPlan::Free, $buyer->fresh()->plan);
    }

    public function test_employee_cannot_change_plan_for_other_employees(): void
    {
        $staff = User::factory()->create([
            'user_type' => UserType::Employee,
            'plan' => UserPlan::Free,
        ]);

        $this->actingAs($this->employee())
            ->patch(route('employee.users.plan.update', $staff), [
                'plan' => UserPlan::Gold->value,
            ])
            ->assertForbidden();
    }

    public function test_buyer_cannot_access_user_plan_management(): void
    {
        $buyer = User::factory()->create([
            'user_type' => UserType::Buyer,
        ]);
        $buyer->assignRole('buyer');

        $this->actingAs($buyer)
            ->get(route('employee.users.index'))
            ->assertForbidden();
    }
}
