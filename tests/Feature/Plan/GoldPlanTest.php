<?php

declare(strict_types=1);

namespace Tests\Feature\Plan;

use App\Models\User;
use App\Support\Enums\UserPlan;
use App\Support\Enums\UserType;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GoldPlanTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_plans_page_is_accessible(): void
    {
        $this->get(route('plans.index'))
            ->assertOk()
            ->assertSee('Choose your plan')
            ->assertSee('Gold')
            ->assertSee('$174')
            ->assertSee('half year')
            ->assertSee('Instant</strong> verified buyer details', false)
            ->assertSee('Verified supplier tag mark by Trade4Deal')
            ->assertSee('Weekly 14 verified buy leads')
            ->assertSee('Relationship manager support')
            ->assertSee('Export business exposure');
    }

    public function test_checkout_requires_authentication(): void
    {
        $this->post(route('plans.razorpay.checkout'))
            ->assertRedirect(route('login'));
    }

    public function test_removed_success_route_cannot_grant_gold(): void
    {
        $user = User::factory()->create([
            'user_type' => UserType::Buyer,
            'plan' => UserPlan::Free,
        ]);
        $user->assignRole('buyer');

        $this->actingAs($user)
            ->get('/plans/gold/success')
            ->assertNotFound();

        $this->assertEquals(UserPlan::Free, $user->fresh()->plan);
        $this->assertSame(UserPlan::Free, $user->fresh()->userPlan());
    }

    public function test_stripe_endpoints_are_removed_and_razorpay_is_the_only_checkout(): void
    {
        $this->postJson('/stripe/webhook', [])->assertNotFound();
        $user = User::factory()->create();
        $this->actingAs($user)->get(route('plans.index'))
            ->assertOk()->assertSee('Pay with Razorpay')->assertDontSee('Pay with Stripe');
        $this->post('/plans/gold/checkout')->assertNotFound();
        $this->get('/plans/gold/cancel')->assertNotFound();
    }
}
