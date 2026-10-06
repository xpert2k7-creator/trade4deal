<?php

declare(strict_types=1);

namespace Tests\Feature\Sourcing;

use App\Domains\Lead\Models\Lead;
use App\Models\User;
use App\Support\Enums\LeadSource;
use App\Support\Enums\ProductType;
use App\Support\Enums\RecordStatus;
use App\Support\Enums\UserType;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SourcingLeadFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    private function sourcingUser(): User
    {
        $user = User::factory()->create([
            'user_type' => UserType::Sourcing,
        ]);
        $user->assignRole('sourcing');

        return $user;
    }

    private function employee(): User
    {
        $user = User::factory()->create([
            'user_type' => UserType::Employee,
        ]);
        $user->assignRole('employee');

        return $user;
    }

    private function validPayload(): array
    {
        return [
            'company_name' => 'Sourced Trade Co',
            'contact_name' => 'Asha Patel',
            'phone' => '+91 90000 11122',
            'email' => 'buyer@sourced.example',
            'product_type' => ProductType::Textiles->value,
            'product_interest' => 'Cotton yarn',
            'city' => 'Surat',
            'country' => 'India',
        ];
    }

    public function test_sourcing_user_is_redirected_to_sourcing_dashboard(): void
    {
        $this->actingAs($this->sourcingUser())
            ->get(route('dashboard'))
            ->assertRedirect(route('sourcing.dashboard'));
    }

    public function test_sourcing_user_cannot_access_employee_panel(): void
    {
        $this->actingAs($this->sourcingUser())
            ->get(route('verification.dashboard'))
            ->assertForbidden();
    }

    public function test_sourcing_user_can_submit_buy_lead_to_pending_queue(): void
    {
        $sourcer = $this->sourcingUser();

        $this->actingAs($sourcer)
            ->post(route('sourcing.leads.store'), $this->validPayload())
            ->assertRedirect(route('sourcing.leads.index', ['status' => 'pending']))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('leads', [
            'company_name' => 'Sourced Trade Co',
            'email' => 'buyer@sourced.example',
            'city' => 'Surat',
            'source' => LeadSource::Sourcing->value,
            'status' => RecordStatus::Pending->value,
            'created_by' => $sourcer->id,
        ]);
    }

    public function test_employee_can_approve_sourcing_lead(): void
    {
        $sourcer = $this->sourcingUser();

        $this->actingAs($sourcer)
            ->post(route('sourcing.leads.store'), $this->validPayload());

        $lead = Lead::query()->where('email', 'buyer@sourced.example')->firstOrFail();

        $this->actingAs($this->employee())
            ->post(route('verification.leads.approve', $lead))
            ->assertRedirect(route('verification.leads.index', ['status' => 'pending']));

        $lead->refresh();
        $this->assertSame(RecordStatus::Active, $lead->status);
        $this->assertNotNull($lead->published_at);
    }
}
