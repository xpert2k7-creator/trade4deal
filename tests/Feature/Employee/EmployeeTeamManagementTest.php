<?php

declare(strict_types=1);

namespace Tests\Feature\Employee;

use App\Domains\Lead\Models\Lead;
use App\Models\User;
use App\Support\Enums\LeadCallStatus;
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
            'assigned_to' => $employee->id,
            'user_id' => $employee->id,
            'created_at' => now(),
            'call_status' => LeadCallStatus::LeadPostVerified,
        ]);

        Lead::factory()->pending()->create([
            'company_name' => 'Older Employee Lead',
            'source' => LeadSource::Employee,
            'created_by' => $employee->id,
            'assigned_to' => $employee->id,
            'user_id' => $employee->id,
            'created_at' => now()->subMonths(2),
        ]);

        Lead::factory()->pending()->create([
            'company_name' => 'Other Employee Lead',
            'source' => LeadSource::Employee,
            'created_by' => $otherEmployee->id,
            'assigned_to' => $otherEmployee->id,
            'user_id' => $otherEmployee->id,
            'created_at' => now(),
        ]);

        Lead::factory()->pending()->create([
            'company_name' => 'Public Should Not Count',
            'source' => LeadSource::User,
            'created_by' => $employee->id,
            'assigned_to' => null,
            'created_at' => now(),
        ]);

        $this->actingAs($this->admin())
            ->get(route('verification.employee-team.index', ['member' => $employee->id]))
            ->assertOk()
            ->assertSee('Verification Team')
            ->assertSee('Stats Employee')
            ->assertSee('Assigned: 2')
            ->assertSee('Verified: 1')
            ->assertSee('Unassigned pool')
            ->assertSee('Assign leads to')
            ->assertSee('Oldest first')
            ->assertSee('assign-submit', false)
            ->assertSee('Submitted vs Verified (last 30 days)')
            ->assertSee('Submitted')
            ->assertSee('Verified');
    }

    public function test_admin_sidebar_shows_verification_team_label(): void
    {
        $this->actingAs($this->admin())
            ->get(route('verification.employee-team.index'))
            ->assertOk()
            ->assertSee('Verification Team')
            ->assertDontSee('Verification Employee');
    }

    public function test_admin_chart_includes_submitted_and_verified_series(): void
    {
        $employee = $this->employee('Chart Employee', 'chart.employee@trade4deal.com');

        Lead::factory()->pending()->create([
            'company_name' => 'Submitted Today Co',
            'source' => LeadSource::Employee,
            'created_by' => $employee->id,
            'assigned_to' => $employee->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Lead::factory()->pending()->create([
            'company_name' => 'Verified Today Co',
            'source' => LeadSource::Employee,
            'created_by' => $employee->id,
            'assigned_to' => $employee->id,
            'call_status' => LeadCallStatus::LeadPostVerified,
            'created_at' => now()->subDays(2),
            'updated_at' => now(),
        ]);

        $chart = app(\App\Domains\Lead\Services\LeadService::class)
            ->employeeDailySubmittedAndVerifiedChart($employee, 30);

        $this->assertSame(30, count($chart['labels']));
        $this->assertSame(30, count($chart['submitted']));
        $this->assertSame(30, count($chart['verified']));
        $this->assertGreaterThanOrEqual(1, array_sum($chart['submitted']));
        $this->assertGreaterThanOrEqual(1, array_sum($chart['verified']));

        $this->actingAs($this->admin())
            ->get(route('verification.employee-team.index', ['member' => $employee->id]))
            ->assertOk()
            ->assertSee('Submitted vs Verified (last 30 days)');
    }

    public function test_admin_can_see_employee_call_status_and_follow_up_filters(): void
    {
        $employee = $this->employee('Filter Employee', 'filter.employee@trade4deal.com');
        $other = $this->employee('Other Filter Emp', 'other.filter@trade4deal.com');

        Lead::factory()->pending()->create([
            'company_name' => 'Admin Sees Verified Co',
            'source' => LeadSource::Employee,
            'created_by' => $employee->id,
            'assigned_to' => $employee->id,
            'call_status' => LeadCallStatus::LeadPostVerified,
        ]);

        Lead::factory()->pending()->create([
            'company_name' => 'Admin Sees Not Connected Co',
            'source' => LeadSource::Employee,
            'created_by' => $employee->id,
            'assigned_to' => $employee->id,
            'call_status' => LeadCallStatus::NotConnected,
            'call_follow_up_at' => now()->subDay(),
        ]);

        Lead::factory()->pending()->create([
            'company_name' => 'Other Emp Verified Hidden',
            'source' => LeadSource::Employee,
            'created_by' => $other->id,
            'assigned_to' => $other->id,
            'call_status' => LeadCallStatus::LeadPostVerified,
        ]);

        $this->actingAs($this->admin())
            ->get(route('verification.employee-team.index', [
                'member' => $employee->id,
                'follow_up' => 'all',
            ]))
            ->assertOk()
            ->assertSee('Verified')
            ->assertSee('Not connected')
            ->assertSee('Overdue')
            ->assertSee('Due in 7 days')
            ->assertSee('Admin Sees Verified Co')
            ->assertSee('Admin Sees Not Connected Co')
            ->assertDontSee('Other Emp Verified Hidden');

        $this->actingAs($this->admin())
            ->get(route('verification.employee-team.index', [
                'member' => $employee->id,
                'follow_up' => 'verified',
            ]))
            ->assertOk()
            ->assertSee('Verified leads')
            ->assertSee('Admin Sees Verified Co')
            ->assertDontSee('Admin Sees Not Connected Co');
    }
}
