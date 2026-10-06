<?php

declare(strict_types=1);

namespace Tests\Feature\Employee;

use App\Models\User;
use App\Support\Enums\UserType;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeTeamManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    private function admin(): User
    {
        $user = User::factory()->create([
            'user_type' => UserType::Admin,
        ]);
        $user->assignRole('admin');

        return $user;
    }

    public function test_admin_can_create_employee_login(): void
    {
        $this->actingAs($this->admin())
            ->post(route('verification.employee-team.store'), [
                'name' => 'Lead Moderator',
                'email' => 'moderator@trade4deal.com',
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!',
            ])
            ->assertRedirect(route('verification.employee-team.index'))
            ->assertSessionHas('success');

        $user = User::query()->where('email', 'moderator@trade4deal.com')->firstOrFail();
        $this->assertTrue($user->isEmployee());
        $this->assertTrue($user->hasRole('employee'));
    }

    public function test_admin_can_delete_employee_login(): void
    {
        $employee = User::factory()->create(['user_type' => UserType::Employee]);
        $employee->assignRole('employee');

        $this->actingAs($this->admin())
            ->delete(route('verification.employee-team.destroy', $employee))
            ->assertRedirect(route('verification.employee-team.index'))
            ->assertSessionHas('success');

        $this->assertSoftDeleted('users', ['id' => $employee->id]);
        $this->assertFalse($employee->fresh()->hasRole('employee'));
    }

    public function test_employee_cannot_manage_employee_team(): void
    {
        $employee = User::factory()->create(['user_type' => UserType::Employee]);
        $employee->assignRole('employee');

        $this->actingAs($employee)
            ->get(route('verification.employee-team.index'))
            ->assertForbidden();
    }
}
