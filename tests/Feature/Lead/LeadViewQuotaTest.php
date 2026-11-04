<?php

declare(strict_types=1);

namespace Tests\Feature\Lead;

use App\Domains\Lead\Models\Lead;
use App\Domains\Lead\Models\LeadView;
use App\Models\User;
use App\Support\Enums\UserPlan;
use App\Support\Enums\UserType;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadViewQuotaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    private function freeUser(): User
    {
        $user = User::factory()->create([
            'user_type' => UserType::Seller,
            'plan' => UserPlan::Free,
            'gold_expires_at' => null,
        ]);
        $user->assignRole('seller');

        return $user;
    }

    private function goldUser(): User
    {
        $user = User::factory()->create([
            'user_type' => UserType::Seller,
            'plan' => UserPlan::Gold,
            'gold_expires_at' => now()->addYear(),
        ]);
        $user->assignRole('seller');

        return $user;
    }

    private function delayedLead(array $overrides = []): Lead
    {
        return Lead::factory()->create(array_merge([
            'published_at' => now()->subDays(8),
        ], $overrides));
    }

    private function freshLead(array $overrides = []): Lead
    {
        return Lead::factory()->publishedRecently()->create($overrides);
    }

    public function test_free_user_can_open_two_delayed_leads_then_is_blocked(): void
    {
        $user = $this->freeUser();
        $lead1 = $this->delayedLead(['product_interest' => 'Free Lead One']);
        $lead2 = $this->delayedLead(['product_interest' => 'Free Lead Two']);
        $lead3 = $this->delayedLead(['product_interest' => 'Free Lead Three']);

        $this->actingAs($user)->get(route('leads.show', $lead1))->assertOk()->assertSee('Free Lead One');
        $this->actingAs($user)->get(route('leads.show', $lead2))->assertOk()->assertSee('Free Lead Two');

        $this->actingAs($user)
            ->get(route('leads.show', $lead3))
            ->assertOk()
            ->assertSee('Free lead views used')
            ->assertSee('Purchase Gold plan')
            ->assertDontSee('Connect with Buyer');

        $this->assertDatabaseCount('lead_views', 2);
    }

    public function test_free_user_can_reopen_same_lead_without_extra_credit(): void
    {
        $user = $this->freeUser();
        $lead1 = $this->delayedLead(['product_interest' => 'Repeat Lead']);
        $lead2 = $this->delayedLead(['product_interest' => 'Second Lead']);

        $this->actingAs($user)->get(route('leads.show', $lead1))->assertOk();
        $this->actingAs($user)->get(route('leads.show', $lead2))->assertOk();
        $this->actingAs($user)->get(route('leads.show', $lead1))->assertOk()->assertSee('Repeat Lead');

        $this->assertDatabaseCount('lead_views', 2);
    }

    public function test_free_user_still_cannot_open_recent_lead_before_delay(): void
    {
        $user = $this->freeUser();
        $fresh = $this->freshLead(['product_interest' => 'Too Fresh']);

        $this->actingAs($user)
            ->get(route('leads.show', $fresh))
            ->assertNotFound();
    }

    public function test_gold_user_limited_to_two_leads_per_day(): void
    {
        $user = $this->goldUser();
        $lead1 = $this->freshLead(['product_interest' => 'Gold Lead One']);
        $lead2 = $this->freshLead(['product_interest' => 'Gold Lead Two']);
        $lead3 = $this->freshLead(['product_interest' => 'Gold Lead Three']);

        $this->actingAs($user)->get(route('leads.show', $lead1))->assertOk()->assertSee('Gold Lead One');
        $this->actingAs($user)->get(route('leads.show', $lead2))->assertOk()->assertSee('Gold Lead Two');

        $this->actingAs($user)
            ->get(route('leads.show', $lead3))
            ->assertOk()
            ->assertSee('Limit is reached for today')
            ->assertDontSee('Connect with Buyer');

        $this->assertSame(2, LeadView::query()->where('user_id', $user->id)->count());
    }

    public function test_gold_daily_limit_resets_next_day(): void
    {
        $user = $this->goldUser();
        $lead1 = $this->freshLead();
        $lead2 = $this->freshLead();
        $lead3 = $this->freshLead(['product_interest' => 'Next Day Lead']);

        $this->actingAs($user)->get(route('leads.show', $lead1))->assertOk();
        $this->actingAs($user)->get(route('leads.show', $lead2))->assertOk();

        LeadView::query()->where('user_id', $user->id)->update([
            'viewed_at' => now()->subDay(),
        ]);

        $this->actingAs($user)
            ->get(route('leads.show', $lead3))
            ->assertOk()
            ->assertSee('Next Day Lead');
    }

    public function test_after_gold_expires_free_quota_works_again(): void
    {
        $user = User::factory()->create([
            'user_type' => UserType::Seller,
            'plan' => UserPlan::Gold,
            'gold_expires_at' => now()->subDay(),
        ]);
        $user->assignRole('seller');
        $this->assertFalse($user->userPlan()->isGold());

        // Old views during Gold period should not block the new free period.
        $old1 = $this->delayedLead();
        $old2 = $this->delayedLead();
        LeadView::query()->create([
            'user_id' => $user->id,
            'lead_id' => $old1->id,
            'viewed_at' => now()->subDays(10),
        ]);
        LeadView::query()->create([
            'user_id' => $user->id,
            'lead_id' => $old2->id,
            'viewed_at' => now()->subDays(9),
        ]);

        $lead1 = $this->delayedLead(['product_interest' => 'After Expiry One']);
        $lead2 = $this->delayedLead(['product_interest' => 'After Expiry Two']);
        $lead3 = $this->delayedLead(['product_interest' => 'After Expiry Three']);

        $this->actingAs($user)->get(route('leads.show', $lead1))->assertOk()->assertSee('After Expiry One');
        $this->actingAs($user)->get(route('leads.show', $lead2))->assertOk()->assertSee('After Expiry Two');
        $this->actingAs($user)
            ->get(route('leads.show', $lead3))
            ->assertOk()
            ->assertSee('Purchase Gold plan');
    }

    public function test_staff_is_not_limited(): void
    {
        $admin = User::factory()->create([
            'user_type' => UserType::Admin,
            'plan' => UserPlan::Free,
        ]);
        $admin->assignRole('admin');

        $leads = collect([
            $this->freshLead(['product_interest' => 'Staff Lead A']),
            $this->freshLead(['product_interest' => 'Staff Lead B']),
            $this->freshLead(['product_interest' => 'Staff Lead C']),
        ]);

        foreach ($leads as $lead) {
            $this->actingAs($admin)
                ->get(route('leads.show', $lead))
                ->assertOk();
        }

        $this->assertDatabaseCount('lead_views', 0);
    }
}
