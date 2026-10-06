<?php

declare(strict_types=1);

namespace Tests\Feature\Employee;

use App\Models\User;
use App\Support\Enums\UserType;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SourcingTeamManagementTest extends TestCase
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

    public function test_admin_can_create_sourcing_login(): void
    {
        $this->actingAs($this->admin())
            ->post(route('verification.sourcing-team.store'), [
                'name' => 'Field Sourcer',
                'email' => 'sourcer@trade4deal.com',
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!',
            ])
            ->assertRedirect(route('verification.sourcing-team.index'))
            ->assertSessionHas('success');

        $user = User::query()->where('email', 'sourcer@trade4deal.com')->firstOrFail();
        $this->assertTrue($user->isSourcing());
        $this->assertTrue($user->hasRole('sourcing'));
    }

    public function test_employee_cannot_access_sourcing_team_management(): void
    {
        $employee = User::factory()->create(['user_type' => UserType::Employee]);
        $employee->assignRole('employee');

        $this->actingAs($employee)
            ->get(route('verification.sourcing-team.index'))
            ->assertForbidden();
    }

    public function test_admin_can_delete_sourcing_login(): void
    {
        $sourcer = User::factory()->create(['user_type' => UserType::Sourcing]);
        $sourcer->assignRole('sourcing');

        $this->actingAs($this->admin())
            ->delete(route('verification.sourcing-team.destroy', $sourcer))
            ->assertRedirect(route('verification.sourcing-team.index'))
            ->assertSessionHas('success');

        $this->assertSoftDeleted('users', ['id' => $sourcer->id]);
        $this->assertFalse($sourcer->fresh()->hasRole('sourcing'));
    }
}
