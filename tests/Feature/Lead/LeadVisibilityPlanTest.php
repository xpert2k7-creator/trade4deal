<?php

declare(strict_types=1);

namespace Tests\Feature\Lead;

use App\Domains\Lead\Models\Lead;
use App\Models\User;
use App\Support\Enums\Currency;
use App\Support\Enums\LeadUnit;
use App\Support\Enums\PaymentMethod;
use App\Support\Enums\ProductType;
use App\Support\Enums\UserPlan;
use App\Support\Enums\UserType;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadVisibilityPlanTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_guest_cannot_see_recently_published_lead(): void
    {
        Lead::factory()->publishedRecently()->create([
            'company_name' => 'Fresh Lead Corp',
            'product_interest' => 'Copper Wire',
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertDontSee('Copper Wire')
            ->assertDontSee('Fresh Lead Corp');
    }

    public function test_guest_can_see_lead_published_over_7_days_ago(): void
    {
        Lead::factory()->create([
            'company_name' => 'Old Lead Corp',
            'product_interest' => 'Steel Beams',
            'city' => 'Dubai',
            'country' => 'UAE',
            'required_quantity' => 100,
            'published_at' => now()->subDays(8),
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Steel Beams')
            ->assertSee('Dubai, UAE')
            ->assertDontSee('Old Lead Corp');
    }

    public function test_gold_user_sees_recently_published_lead_immediately(): void
    {
        $user = User::factory()->create([
            'user_type' => UserType::Buyer,
            'plan' => UserPlan::Gold,
        ]);
        $user->assignRole('buyer');

        Lead::factory()->publishedRecently()->create([
            'company_name' => 'Gold Instant Corp',
            'product_interest' => 'Solar Panels',
        ]);

        $this->actingAs($user)
            ->get(route('home'))
            ->assertOk()
            ->assertSee('Solar Panels')
            ->assertDontSee('Gold Instant Corp');
    }

    public function test_free_user_cannot_see_recently_published_lead(): void
    {
        $user = User::factory()->create([
            'user_type' => UserType::Buyer,
            'plan' => UserPlan::Free,
        ]);
        $user->assignRole('buyer');

        Lead::factory()->publishedRecently()->create([
            'company_name' => 'Delayed For Free Corp',
            'product_interest' => 'Delayed Free Product',
        ]);

        $this->actingAs($user)
            ->get(route('home'))
            ->assertOk()
            ->assertDontSee('Delayed Free Product')
            ->assertDontSee('Delayed For Free Corp');
    }
}
