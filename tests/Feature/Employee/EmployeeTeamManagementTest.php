<?php

declare(strict_types=1);

namespace Tests\Feature\Employee;

use App\Domains\Lead\Models\Lead;
use App\Models\User;
use App\Support\Enums\LeadSource;
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

    private function employee(string $name = 'Lead Moderator', string $email = 'moderator@trade4deal.com'): User
    {
        $user = User::factory()->create([
            'name' => $name,
            'email' => $email,
            'user_type' => UserType::Employee,
        ]);
        $user->assignRole('employee');

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
        $employee = $this->employee();

        $this->actingAs($this->admin())
            ->delete(route('verification.employee-team.destroy', $employee))
            ->assertRedirect(route('verification.employee-team.index'))
            ->assertSessionHas('success');

        $this->assertSoftDeleted('users', ['id' => $employee->id]);
        $this->assertFalse($employee->fresh()->hasRole('employee'));
    }

    public function test_employee_cannot_manage_employee_team(): void
    {
        $employee = $this->employee();

        $this->actingAs($employee)
            ->get(route('verification.employee-team.index'))
            ->assertForbidden();
    }

    public function test_admin_can_view_verification_team_stats_for_employee(): void
    {
        $employee = $this->employee('Stats Employee', 'stats.employee@trade4deal.com');
        $otherEmployee = $this->employee('Other Employee', 'other.employee@trade4deal.com');

        Lead::factory()->pending()->create([
            'company_name' => 'Employee Lead Co',
            'source' => LeadSource::Employee,
            'created_by' => $employee->id,
            'user_id' => $employee->id,
            'created_at' => now(),
        ]);

        Lead::factory()->pending()->create([
            'company_name' => 'Older Employee Lead',
            'source' => LeadSource::Employee,
            'created_by' => $employee->id,
            'user_id' => $employee->id,
            'created_at' => now()->subMonths(2),
        ]);

        Lead::factory()->pending()->create([
            'company_name' => 'Other Employee Lead',
            'source' => LeadSource::Employee,
            'created_by' => $otherEmployee->id,
            'user_id' => $otherEmployee->id,
            'created_at' => now(),
        ]);

        Lead::factory()->pending()->create([
            'company_name' => 'Public Should Not Count',
            'source' => LeadSource::User,
            'created_by' => $employee->id,
            'created_at' => now(),
        ]);

        $this->actingAs($this->admin())
            ->get(route('verification.employee-team.index', ['member' => $employee->id]))
            ->assertOk()
            ->assertSee('Verification Team')
            ->assertSee('Stats Employee')
            ->assertSee('Total leads: 2')
            ->assertSee('Buy leads added (last 30 days)');
    }

    public function test_admin_sidebar_shows_verification_team_label(): void
    {
        $this->actingAs($this->admin())
            ->get(route('verification.employee-team.index'))
            ->assertOk()
            ->assertSee('Verification Team')
            ->assertDontSee('Verification Employee');
    }
}
