<?php

declare(strict_types=1);

namespace Tests\Feature\Plan;

use App\Models\User;
use App\Support\Enums\UserPlan;
use App\Support\Enums\UserType;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Cashier\Events\WebhookReceived;
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
            ->assertSee('Gold');
    }

    public function test_checkout_requires_authentication(): void
    {
        $this->post(route('plans.gold.checkout'))
            ->assertRedirect(route('login'));
    }

    public function test_success_route_upgrades_user_to_gold(): void
    {
        $user = User::factory()->create([
            'user_type' => UserType::Buyer,
            'plan' => UserPlan::Free,
        ]);
        $user->assignRole('buyer');

        $this->actingAs($user)
            ->get(route('plans.gold.success'))
            ->assertRedirect(route('dashboard'));

        $this->assertEquals(UserPlan::Gold, $user->fresh()->plan);
    }

    public function test_subscription_deleted_webhook_reverts_user_to_free(): void
    {
        $user = User::factory()->create([
            'stripe_id' => 'cus_test123',
            'plan' => UserPlan::Gold,
        ]);

        WebhookReceived::dispatch([
            'type' => 'customer.subscription.deleted',
            'data' => [
                'object' => [
                    'customer' => 'cus_test123',
                ],
            ],
        ]);

        $this->assertEquals(UserPlan::Free, $user->fresh()->plan);
    }
}
