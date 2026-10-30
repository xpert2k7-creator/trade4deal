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

class LeadAssignmentTest extends TestCase
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

    private function employee(string $name = 'Assignee', string $email = 'assignee@trade4deal.com'): User
    {
        $user = User::factory()->create([
            'name' => $name,
            'email' => $email,
            'user_type' => UserType::Employee,
        ]);
        $user->assignRole('employee');

        return $user;
    }

    public function test_admin_assigns_oldest_unassigned_leads_of_any_source(): void
    {
        $admin = $this->admin();
        $employee = $this->employee('Worker A', 'worker.a@trade4deal.com');

        $oldest = Lead::factory()->pending()->create([
            'company_name' => 'Oldest User Lead',
            'source' => LeadSource::User,
            'assigned_to' => null,
            'created_at' => now()->subDays(10),
        ]);
        $middle = Lead::factory()->pending()->create([
            'company_name' => 'Middle Sourcing Lead',
            'source' => LeadSource::Sourcing,
            'assigned_to' => null,
            'created_at' => now()->subDays(5),
        ]);
        $newest = Lead::factory()->pending()->create([
            'company_name' => 'Newest Employee Lead',
            'source' => LeadSource::Employee,
            'assigned_to' => null,
            'created_at' => now()->subDay(),
        ]);

        $this->actingAs($admin)
            ->post(route('verification.employee-team.assign-leads'), [
                'assignee_id' => $employee->id,
                'count' => 2,
                'from_assignee_id' => '',
            ])
            ->assertRedirect(route('verification.employee-team.index', [
                'member' => $employee->id,
                'follow_up' => 'all',
            ]))
            ->assertSessionHas('success');

        $this->assertSame($employee->id, $oldest->fresh()->assigned_to);
        $this->assertSame($employee->id, $middle->fresh()->assigned_to);
        $this->assertNull($newest->fresh()->assigned_to);
        $this->assertSame($admin->id, $oldest->fresh()->assigned_by);
        $this->assertNotNull($oldest->fresh()->assigned_at);
    }

    public function test_admin_reassign_moves_lead_to_new_assignee_only(): void
    {
        $admin = $this->admin();
        $from = $this->employee('From Emp', 'from.emp@trade4deal.com');
        $to = $this->employee('To Emp', 'to.emp@trade4deal.com');

        $lead = Lead::factory()->pending()->create([
            'company_name' => 'Reassign Me Co',
            'source' => LeadSource::User,
            'assigned_to' => $from->id,
            'assigned_at' => now()->subDay(),
            'assigned_by' => $admin->id,
            'created_at' => now()->subDays(3),
        ]);

        $this->actingAs($admin)
            ->post(route('verification.employee-team.assign-leads'), [
                'assignee_id' => $to->id,
                'count' => 5,
                'from_assignee_id' => $from->id,
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $lead->refresh();
        $this->assertSame($to->id, $lead->assigned_to);
        $this->assertSame($admin->id, $lead->assigned_by);

        $this->actingAs($from)
            ->get(route('verification.leads.index', ['follow_up' => 'all']))
            ->assertOk()
            ->assertDontSee('Reassign Me Co');

        $this->actingAs($to)
            ->get(route('verification.leads.index', ['follow_up' => 'all']))
            ->assertOk()
            ->assertSee('Reassign Me Co');
    }

    public function test_employee_submit_auto_assigns_to_self(): void
    {
        $employee = $this->employee('Self Submit', 'self.submit@trade4deal.com');

        $this->actingAs($employee)
            ->post(route('verification.leads.store'), [
                'company_name' => 'Self Submitted Co',
                'contact_name' => 'Self User',
                'email' => 'self.lead@example.com',
                'phone' => '+91 91111 22222',
                'country' => 'India',
                'business_type' => 'buyer',
                'product_interest' => 'Rice',
                'product_type' => 'agriculture',
                'currency' => 'INR',
                'units' => 'pieces',
                'required_quantity' => '50',
                'preferred_incoterm' => 'fob',
                'payment_terms' => 'tt',
                'message' => 'Own lead',
            ])
            ->assertRedirect();

        $lead = Lead::query()->where('company_name', 'Self Submitted Co')->firstOrFail();
        $this->assertSame($employee->id, $lead->assigned_to);
        $this->assertSame($employee->id, $lead->assigned_by);
        $this->assertSame(LeadSource::Employee, $lead->source);

        $this->actingAs($employee)
            ->get(route('verification.leads.index', ['follow_up' => 'all']))
            ->assertOk()
            ->assertSee('Self Submitted Co');
    }

    public function test_employee_cannot_see_unassigned_or_others_assigned_leads(): void
    {
        $employee = $this->employee('Viewer', 'viewer@trade4deal.com');
        $other = $this->employee('Other Viewer', 'other.viewer@trade4deal.com');

        Lead::factory()->pending()->create([
            'company_name' => 'Unassigned Pool Co',
            'assigned_to' => null,
        ]);
        Lead::factory()->pending()->create([
            'company_name' => 'Other Assigned Co',
            'assigned_to' => $other->id,
        ]);
        Lead::factory()->pending()->create([
            'company_name' => 'My Assigned Co',
            'assigned_to' => $employee->id,
        ]);

        $this->actingAs($employee)
            ->get(route('verification.leads.index', ['follow_up' => 'all']))
            ->assertOk()
            ->assertSee('My Assigned Co')
            ->assertDontSee('Unassigned Pool Co')
            ->assertDontSee('Other Assigned Co');
    }

    public function test_full_assign_work_reassign_flow(): void
    {
        $admin = $this->admin();
        $empA = $this->employee('Flow Emp A', 'flow.a@trade4deal.com');
        $empB = $this->employee('Flow Emp B', 'flow.b@trade4deal.com');

        // 1) Unassigned pool of all lead types (oldest first).
        $userLead = Lead::factory()->pending()->create([
            'company_name' => 'Flow User Lead',
            'source' => LeadSource::User,
            'assigned_to' => null,
            'created_at' => now()->subDays(9),
            'phone' => '+91 98000 00001',
        ]);
        $sourcingLead = Lead::factory()->pending()->create([
            'company_name' => 'Flow Sourcing Lead',
            'source' => LeadSource::Sourcing,
            'assigned_to' => null,
            'created_at' => now()->subDays(4),
        ]);
        Lead::factory()->pending()->create([
            'company_name' => 'Flow Newer Unassigned',
            'source' => LeadSource::Employee,
            'assigned_to' => null,
            'created_at' => now()->subDay(),
        ]);

        // 2) Admin assigns 2 oldest to Emp A from unassigned pool.
        $this->actingAs($admin)
            ->get(route('verification.employee-team.index', ['member' => $empA->id]))
            ->assertOk()
            ->assertSee('Unassigned pool')
            ->assertSee('Assign leads to')
            ->assertSee('Oldest first');

        $this->actingAs($admin)
            ->post(route('verification.employee-team.assign-leads'), [
                'assignee_id' => $empA->id,
                'count' => 2,
                'from_assignee_id' => '',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertSame($empA->id, $userLead->fresh()->assigned_to);
        $this->assertSame($empA->id, $sourcingLead->fresh()->assigned_to);

        // 3) Emp A sees assigned leads; Emp B does not.
        $this->actingAs($empA)
            ->get(route('verification.leads.index', ['follow_up' => 'all']))
            ->assertOk()
            ->assertSee('Flow User Lead')
            ->assertSee('Flow Sourcing Lead')
            ->assertDontSee('Flow Newer Unassigned');

        $this->actingAs($empB)
            ->get(route('verification.leads.index', ['follow_up' => 'all']))
            ->assertOk()
            ->assertDontSee('Flow User Lead')
            ->assertDontSee('Flow Sourcing Lead');

        // 4) Emp A can work an assigned lead (call status).
        $this->actingAs($empA)
            ->patch(route('verification.leads.call-status', $userLead), [
                'call_status' => LeadCallStatus::NotConnected->value,
            ])
            ->assertRedirect();
        $this->assertSame(LeadCallStatus::NotConnected, $userLead->fresh()->call_status);

        // 5) Emp A submits own lead → auto-assigned to self.
        $this->actingAs($empA)
            ->post(route('verification.leads.store'), [
                'company_name' => 'Flow Self Lead',
                'contact_name' => 'Flow Contact',
                'email' => 'flow.self@example.com',
                'phone' => '+91 98000 00099',
                'country' => 'India',
                'business_type' => 'buyer',
                'product_interest' => 'Wheat',
                'product_type' => 'agriculture',
                'currency' => 'INR',
                'units' => 'pieces',
                'required_quantity' => '10',
                'preferred_incoterm' => 'fob',
                'payment_terms' => 'tt',
                'message' => 'Self submit flow',
            ])
            ->assertRedirect();

        $selfLead = Lead::query()->where('company_name', 'Flow Self Lead')->firstOrFail();
        $this->assertSame($empA->id, $selfLead->assigned_to);

        // 6) Admin reassigns Emp A's leads to Emp B (oldest first, count 2).
        $this->actingAs($admin)
            ->post(route('verification.employee-team.assign-leads'), [
                'assignee_id' => $empB->id,
                'count' => 2,
                'from_assignee_id' => $empA->id,
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertSame($empB->id, $userLead->fresh()->assigned_to);
        $this->assertSame($empB->id, $sourcingLead->fresh()->assigned_to);
        // Self-submitted is newer; may remain with A depending on created_at order.
        $remainingWithA = Lead::query()->where('assigned_to', $empA->id)->pluck('company_name')->all();
        $this->assertContains('Flow Self Lead', $remainingWithA);

        // 7) After reassignment: only Emp B sees the reassigned leads.
        $this->actingAs($empA)
            ->get(route('verification.leads.index', ['follow_up' => 'all']))
            ->assertOk()
            ->assertDontSee('Flow User Lead')
            ->assertDontSee('Flow Sourcing Lead')
            ->assertSee('Flow Self Lead');

        $this->actingAs($empB)
            ->get(route('verification.leads.index', ['follow_up' => 'all']))
            ->assertOk()
            ->assertSee('Flow User Lead')
            ->assertSee('Flow Sourcing Lead')
            ->assertDontSee('Flow Self Lead');

        // 8) Emp A can no longer update a reassigned lead; Emp B can.
        $this->actingAs($empA)
            ->patch(route('verification.leads.call-status', $userLead), [
                'call_status' => LeadCallStatus::LeadPostVerified->value,
            ])
            ->assertForbidden();

        $this->actingAs($empB)
            ->patch(route('verification.leads.call-status', $userLead), [
                'call_status' => LeadCallStatus::LeadPostVerified->value,
            ])
            ->assertRedirect();

        $this->assertSame(LeadCallStatus::LeadPostVerified, $userLead->fresh()->call_status);

        // 9) Verified lead leaves the active table but remains in Verified count/list.
        $this->actingAs($admin)
            ->get(route('verification.employee-team.index', ['member' => $empB->id, 'follow_up' => 'all']))
            ->assertOk()
            ->assertDontSee('Flow User Lead')
            ->assertSee('Assigned:')
            ->assertSee('Verified:');

        $this->actingAs($admin)
            ->get(route('verification.employee-team.index', ['member' => $empB->id, 'follow_up' => 'verified']))
            ->assertOk()
            ->assertSee('Flow User Lead');

        $this->actingAs($empB)
            ->get(route('verification.leads.index', ['follow_up' => 'all']))
            ->assertOk()
            ->assertDontSee('Flow User Lead');

        $this->actingAs($empB)
            ->get(route('verification.leads.index', ['follow_up' => 'verified']))
            ->assertOk()
            ->assertSee('Flow User Lead');
    }
}
