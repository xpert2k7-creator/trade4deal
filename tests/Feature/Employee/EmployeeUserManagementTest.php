<?php

declare(strict_types=1);

namespace Tests\Feature\Employee;

use App\Models\User;
use App\Support\Enums\RecordStatus;
use App\Support\Enums\UserPlan;
use App\Support\Enums\UserType;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeUserManagementTest extends TestCase
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

    public function test_employee_can_view_user_edit_page(): void
    {
        $buyer = User::factory()->create([
            'name' => 'Edit Me Buyer',
            'user_type' => UserType::Buyer,
        ]);

        $this->actingAs($this->employee())
            ->get(route('employee.users.edit', $buyer))
            ->assertOk()
            ->assertSee('Edit Me Buyer')
            ->assertSee('Save changes');
    }

    public function test_employee_can_update_user_profile(): void
    {
        $buyer = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
            'user_type' => UserType::Buyer,
            'plan' => UserPlan::Free,
            'status' => RecordStatus::Active,
        ]);

        $this->actingAs($this->employee())
            ->put(route('employee.users.update', $buyer), [
                'name' => 'New Name',
                'email' => 'new@example.com',
                'phone' => '+1 555 0100',
                'company_name' => 'Acme Imports',
                'country' => 'United States',
                'user_type' => UserType::Buyer->value,
                'plan' => UserPlan::Gold->value,
                'status' => RecordStatus::Suspended->value,
            ])
            ->assertRedirect(route('employee.users.edit', $buyer))
            ->assertSessionHas('success');

        $buyer->refresh();

        $this->assertSame('New Name', $buyer->name);
        $this->assertSame('new@example.com', $buyer->email);
        $this->assertEquals(UserPlan::Gold, $buyer->plan);
        $this->assertEquals(RecordStatus::Suspended, $buyer->status);
    }

    public function test_employee_can_delete_marketplace_user(): void
    {
        $buyer = User::factory()->create([
            'user_type' => UserType::Buyer,
        ]);

        $this->actingAs($this->employee())
            ->delete(route('employee.users.destroy', $buyer))
            ->assertRedirect(route('employee.users.index'))
            ->assertSessionHas('success');

        $this->assertSoftDeleted($buyer);
    }

    public function test_employee_cannot_edit_staff_users(): void
    {
        $staff = User::factory()->create([
            'user_type' => UserType::Employee,
        ]);

        $this->actingAs($this->employee())
            ->get(route('employee.users.edit', $staff))
            ->assertForbidden();
    }
}
