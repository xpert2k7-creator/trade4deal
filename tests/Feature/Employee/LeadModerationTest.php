<?php

declare(strict_types=1);

namespace Tests\Feature\Employee;

use App\Domains\Lead\Models\Lead;
use App\Domains\Lead\Notifications\LeadApprovedNotification;
use App\Domains\Lead\Notifications\LeadRejectedNotification;
use App\Models\User;
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
        $this->get(route('employee.dashboard'))->assertRedirect(route('login'));
    }

    public function test_buyer_cannot_access_employee_dashboard(): void
    {
        $this->actingAs($this->buyer())
            ->get(route('employee.dashboard'))
            ->assertForbidden();
    }

    public function test_employee_can_access_dashboard(): void
    {
        $this->actingAs($this->employee())
            ->get(route('employee.dashboard'))
            ->assertOk()
            ->assertSee('Moderation Overview');
    }

    public function test_employee_can_approve_lead_and_it_appears_on_homepage(): void
    {
        Notification::fake();

        $lead = Lead::factory()->pending()->create([
            'company_name' => 'Publish Me Corp',
            'email' => 'publish@example.com',
            'product_interest' => 'Textiles',
        ]);

        $this->actingAs($this->employee())
            ->post(route('employee.leads.approve', $lead))
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

        $lead = Lead::factory()->pending()->create([
            'company_name' => 'Reject Me LLC',
            'email' => 'reject@example.com',
        ]);

        $this->actingAs($this->employee())
            ->post(route('employee.leads.reject', $lead))
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
        $lead = Lead::factory()->pending()->create([
            'company_name' => 'Old Name',
        ]);

        $this->actingAs($this->employee())
            ->put(route('employee.leads.update', $lead), [
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
                'payment_methods' => ['wire_transfer'],
                'message' => 'Updated message',
            ])
            ->assertRedirect(route('employee.leads.edit', $lead));

        $this->assertDatabaseHas('leads', [
            'id' => $lead->id,
            'company_name' => 'New Name Co',
            'product_interest' => 'Electronics',
            'business_type' => 'seller',
        ]);
    }
}
