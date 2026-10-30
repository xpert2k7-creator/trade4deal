<?php

declare(strict_types=1);

namespace Tests\Feature\Employee;

use App\Domains\Lead\Models\Lead;
use App\Domains\Lead\Notifications\LeadApprovedNotification;
use App\Domains\Lead\Notifications\LeadRejectedNotification;
use App\Models\User;
use App\Support\Enums\LeadCallStatus;
use App\Support\Enums\RecordStatus;
use App\Support\Enums\UserPlan;
use App\Support\Enums\UserType;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class LeadModerationTest extends TestCase
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
            'email_verified_at' => now(),
            'status' => RecordStatus::Active,
        ]);
        $user->assignRole('employee');

        return $user;
    }

    private function buyer(): User
    {
        Role::findOrCreate('buyer', 'web');
        $user = User::factory()->create([
            'user_type' => UserType::Buyer,
            'email_verified_at' => now(),
            'status' => RecordStatus::Active,
        ]);
        $user->assignRole('buyer');

        return $user;
    }

    public function test_guest_cannot_access_employee_dashboard(): void
    {
        $this->get(route('verification.dashboard'))->assertRedirect(route('login'));
    }

    public function test_buyer_cannot_access_employee_dashboard(): void
    {
        $this->actingAs($this->buyer())
            ->get(route('verification.dashboard'))
            ->assertForbidden();
    }

    public function test_employee_can_access_dashboard(): void
    {
        $this->actingAs($this->employee())
            ->get(route('verification.dashboard'))
            ->assertOk()
            ->assertSee('My Leads Dashboard')
            ->assertSee('Follow-ups by date')
            ->assertDontSee('Moderation Overview');
    }

    public function test_admin_still_sees_moderation_overview_dashboard(): void
    {
        $admin = User::factory()->create([
            'user_type' => UserType::Admin,
            'email_verified_at' => now(),
            'status' => RecordStatus::Active,
        ]);
        $admin->assignRole('admin');

        $this->actingAs($admin)
            ->get(route('verification.dashboard'))
            ->assertOk()
            ->assertSee('Moderation Overview')
            ->assertSee('Pending review');
    }

    public function test_employee_leads_index_shows_only_own_leads_and_follow_up_filters(): void
    {
        $employee = $this->employee();
        $other = $this->employee();

        $mine = Lead::factory()->pending()->create([
            'company_name' => 'My Own Lead Co',
            'created_by' => $employee->id,
            'assigned_to' => $employee->id,
            'source' => \App\Support\Enums\LeadSource::Employee,
            'call_follow_up_at' => now()->addDays(3),
            'call_status' => LeadCallStatus::TalkLater7Days,
        ]);

        Lead::factory()->pending()->create([
            'company_name' => 'Someone Else Lead',
            'created_by' => $other->id,
            'assigned_to' => $other->id,
            'source' => \App\Support\Enums\LeadSource::Employee,
        ]);

        $this->actingAs($employee)
            ->get(route('verification.leads.index'))
            ->assertOk()
            ->assertSee('My Assigned Leads')
            ->assertSee('Verified')
            ->assertSee('Due in 7 days')
            ->assertSee('My Own Lead Co')
            ->assertDontSee('Someone Else Lead')
            ->assertSee('follow_up=', false)
            ->assertSee('Talk later in next 7 days');

        $this->assertSame($employee->id, $mine->created_by);
    }

    public function test_employee_can_filter_verified_leads(): void
    {
        $employee = $this->employee();

        Lead::factory()->pending()->create([
            'company_name' => 'Verified Lead Co',
            'created_by' => $employee->id,
            'assigned_to' => $employee->id,
            'source' => \App\Support\Enums\LeadSource::Employee,
            'call_status' => LeadCallStatus::LeadPostVerified,
            'call_follow_up_at' => null,
        ]);

        Lead::factory()->pending()->create([
            'company_name' => 'Not Connected Co',
            'created_by' => $employee->id,
            'assigned_to' => $employee->id,
            'source' => \App\Support\Enums\LeadSource::Employee,
            'call_status' => LeadCallStatus::NotConnected,
        ]);

        $this->actingAs($employee)
            ->get(route('verification.leads.index', ['follow_up' => 'all']))
            ->assertOk()
            ->assertDontSee('Verified Lead Co')
            ->assertSee('Not Connected Co');

        $this->actingAs($employee)
            ->get(route('verification.leads.index', ['follow_up' => 'verified']))
            ->assertOk()
            ->assertSee('Verified leads')
            ->assertSee('Verified Lead Co')
            ->assertDontSee('Not Connected Co');

        $dashboard = $this->actingAs($employee)
            ->get(route('verification.dashboard'))
            ->assertOk()
            ->assertSee('Verified');

        $this->assertStringContainsString(
            (string) 1,
            $dashboard->getContent(),
            'Verified count should remain visible on the employee dashboard'
        );
    }

    public function test_employee_can_approve_lead_and_it_appears_on_homepage(): void
    {
        Notification::fake();

        $employee = $this->employee();
        $lead = Lead::factory()->pending()->create([
            'company_name' => 'Publish Me Corp',
            'email' => 'publish@example.com',
            'product_interest' => 'Textiles',
            'assigned_to' => $employee->id,
        ]);

        $this->actingAs($employee)
            ->post(route('verification.leads.approve', $lead))
            ->assertRedirect();

        $this->assertDatabaseHas('leads', [
            'id' => $lead->id,
            'status' => RecordStatus::Active->value,
        ]);

        $this->assertNotNull($lead->fresh()->published_at);

        Notification::assertSentOnDemand(LeadApprovedNotification::class);

        $goldUser = User::factory()->create([
            'user_type' => UserType::Buyer,
            'plan' => UserPlan::Gold,
        ]);
        $goldUser->assignRole('buyer');

        $this->actingAs($goldUser)
            ->get(route('home'))
            ->assertOk()
            ->assertSee('Publish Me Corp');
    }

    public function test_employee_can_reject_lead_and_it_stays_hidden(): void
    {
        Notification::fake();

        $employee = $this->employee();
        $lead = Lead::factory()->pending()->create([
            'company_name' => 'Reject Me LLC',
            'email' => 'reject@example.com',
            'assigned_to' => $employee->id,
        ]);

        $this->actingAs($employee)
            ->post(route('verification.leads.reject', $lead))
            ->assertRedirect();

        $this->assertDatabaseHas('leads', [
            'id' => $lead->id,
            'status' => RecordStatus::Inactive->value,
        ]);

        Notification::assertSentOnDemand(LeadRejectedNotification::class);

        $this->get(route('home'))
            ->assertOk()
            ->assertDontSee('Reject Me LLC');
    }

    public function test_employee_can_update_lead_fields(): void
    {
        $employee = $this->employee();
        $lead = Lead::factory()->pending()->create([
            'company_name' => 'Old Name',
            'payment_methods' => ['wire_transfer'],
            'assigned_to' => $employee->id,
        ]);

        $this->actingAs($employee)
            ->put(route('verification.leads.update', $lead), [
                'company_name' => 'New Name Co',
                'contact_name' => 'Jane Doe',
                'email' => 'jane@example.com',
                'phone' => '+1 555 9999',
                'country' => 'India',
                'business_type' => 'seller',
                'product_interest' => 'Electronics',
                'product_type' => 'electronics',
                'currency' => 'USD',
                'units' => 'pieces',
                'required_quantity' => '12.5',
                'preferred_incoterm' => 'fob',
                'payment_terms' => 'tt',
                'packaging_size' => '25 kg bags',
                'message' => 'Updated message',
            ])
            ->assertRedirect(route('verification.leads.edit', $lead));

        $this->assertDatabaseHas('leads', [
            'id' => $lead->id,
            'company_name' => 'New Name Co',
            'product_interest' => 'Electronics',
            'business_type' => 'seller',
            'preferred_incoterm' => 'fob',
            'payment_terms' => 'tt',
        ]);

        $this->assertSame(['wire_transfer'], $lead->fresh()->payment_methods);
    }

    public function test_employee_edit_form_shows_trade_term_fields(): void
    {
        $employee = $this->employee();
        $lead = Lead::factory()->pending()->create([
            'company_name' => 'CVF Test',
            'currency' => 'INR',
            'units' => 'pieces',
            'payment_methods' => ['wire_transfer'],
            'assigned_to' => $employee->id,
        ]);

        $this->actingAs($employee)
            ->get(route('verification.leads.edit', $lead))
            ->assertOk()
            ->assertSee('Currency')
            ->assertSee('Units')
            ->assertDontSee('Payment Methods')
            ->assertSee('Preferred incoterm')
            ->assertSee('Payment terms')
            ->assertSee('Required quantity');
    }

    public function test_admin_can_access_lead_edit_form(): void
    {
        $admin = User::factory()->create([
            'user_type' => UserType::Admin,
            'email_verified_at' => now(),
            'status' => RecordStatus::Active,
        ]);
        $admin->assignRole('admin');

        $lead = Lead::factory()->pending()->create();

        $this->actingAs($admin)
            ->get(route('verification.leads.edit', $lead))
            ->assertOk()
            ->assertSee('Preferred incoterm');
    }


    public function test_employee_cannot_update_lead_assigned_to_someone_else(): void
    {
        $employee = $this->employee();
        $other = $this->employee();
        $lead = Lead::factory()->pending()->create([
            'assigned_to' => $other->id,
            'company_name' => 'Other Assignee Co',
        ]);

        $this->actingAs($employee)
            ->put(route('verification.leads.update', $lead), [
                'company_name' => 'Hacked Name',
                'contact_name' => 'Jane Doe',
                'email' => 'jane@example.com',
                'country' => 'India',
                'business_type' => 'seller',
                'product_interest' => 'Electronics',
                'product_type' => 'electronics',
                'currency' => 'USD',
                'units' => 'pieces',
                'payment_methods' => ['wire_transfer'],
                'required_quantity' => '10',
                'preferred_incoterm' => 'fob',
                'payment_terms' => 'tt',
            ])
            ->assertForbidden();
    }

    public function test_buyer_cannot_update_verification_lead(): void
    {
        $lead = Lead::factory()->pending()->create();

        $this->actingAs($this->buyer())
            ->put(route('verification.leads.update', $lead), [
                'company_name' => 'Hacked Name',
                'contact_name' => 'Jane Doe',
                'email' => 'jane@example.com',
                'country' => 'India',
                'business_type' => 'seller',
                'product_interest' => 'Electronics',
                'product_type' => 'electronics',
                'currency' => 'USD',
                'units' => 'pieces',
                'payment_methods' => ['wire_transfer'],
                'required_quantity' => '10',
                'preferred_incoterm' => 'fob',
                'payment_terms' => 'tt',
            ])
            ->assertForbidden();
    }

    public function test_employee_can_update_lead_call_status_and_sees_tag_with_dialer(): void
    {
        $employee = $this->employee();

        $lead = Lead::factory()->pending()->create([
            'phone' => '+91 98765 43210',
            'call_status' => null,
            'created_by' => $employee->id,
            'assigned_to' => $employee->id,
            'source' => \App\Support\Enums\LeadSource::Employee,
        ]);

        $this->actingAs($employee)
            ->get(route('verification.leads.index', ['follow_up' => 'all']))
            ->assertOk()
            ->assertSee('Call status')
            ->assertSee('tel:+919876543210', false)
            ->assertSee('Not set');

        $this->actingAs($employee)
            ->from(route('verification.leads.index', ['follow_up' => 'all']))
            ->patch(route('verification.leads.call-status', $lead), [
                'call_status' => LeadCallStatus::TalkLater7Days->value,
            ])
            ->assertRedirect();

        $lead->refresh();
        $this->assertSame(LeadCallStatus::TalkLater7Days, $lead->call_status);
        $this->assertNotNull($lead->call_follow_up_at);
        $this->assertTrue($lead->call_follow_up_at->isSameDay(now()->addDays(7)));

        $this->actingAs($employee)
            ->get(route('verification.leads.index', ['follow_up' => 'all']))
            ->assertOk()
            ->assertSee('Talk later in next 7 days');
    }

    public function test_employee_can_submit_lead_and_update_call_status_from_edit_page(): void
    {
        $employee = $this->employee();

        $this->actingAs($employee)
            ->post(route('verification.leads.store'), [
                'company_name' => 'Call Status Test Co',
                'contact_name' => 'Ravi Kumar',
                'email' => 'ravi.calltest@example.com',
                'phone' => '+91 90000 11111',
                'country' => 'India',
                'business_type' => 'buyer',
                'product_interest' => 'Cotton yarn',
                'product_type' => 'textiles',
                'currency' => 'INR',
                'units' => 'pieces',
                'required_quantity' => '100',
                'preferred_incoterm' => 'fob',
                'payment_terms' => 'tt',
                'message' => 'Need samples',
            ])
            ->assertRedirect(route('verification.leads.index', ['follow_up' => 'all']));

        $lead = Lead::query()->where('company_name', 'Call Status Test Co')->first();
        $this->assertNotNull($lead);
        $this->assertSame($employee->id, $lead->assigned_to);

        $editPage = $this->actingAs($employee)
            ->get(route('verification.leads.edit', $lead))
            ->assertOk()
            ->assertSee('Call tracking')
            ->assertSee('tel:+919000011111', false);

        // Call-status form must not be nested inside the update form.
        $html = $editPage->getContent();
        $callFormPos = strpos($html, 'verification/leads/'.$lead->id.'/call-status');
        $updateFormPos = strpos($html, 'action="'.e(route('verification.leads.update', $lead)).'"');
        $this->assertNotFalse($callFormPos);
        $this->assertNotFalse($updateFormPos);
        $this->assertLessThan($updateFormPos, $callFormPos, 'Call status form must appear before (outside) the update form');

        $this->actingAs($employee)
            ->from(route('verification.leads.edit', $lead))
            ->patch(route('verification.leads.call-status', $lead), [
                'call_status' => LeadCallStatus::NotConnected->value,
            ])
            ->assertRedirect(route('verification.leads.edit', $lead));

        $this->assertSame(LeadCallStatus::NotConnected, $lead->fresh()->call_status);

        $this->actingAs($employee)
            ->get(route('verification.leads.edit', $lead))
            ->assertOk()
            ->assertSee('Not connected');
    }

    public function test_buyer_cannot_update_lead_call_status(): void
    {
        $lead = Lead::factory()->pending()->create();

        $this->actingAs($this->buyer())
            ->patch(route('verification.leads.call-status', $lead), [
                'call_status' => LeadCallStatus::NotInterested->value,
            ])
            ->assertForbidden();
    }
}
