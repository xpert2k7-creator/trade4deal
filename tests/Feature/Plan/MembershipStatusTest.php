<?php

declare(strict_types=1);

namespace Tests\Feature\Plan;

use App\Models\User;
use App\Support\Enums\UserPlan;
use App\Support\Enums\UserType;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MembershipStatusTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_gold_member_sees_gold_status_on_dashboard_and_profile(): void
    {
        $user = User::factory()->create([
            'user_type' => UserType::Buyer,
            'plan' => UserPlan::Gold,
            'gold_expires_at' => now()->addYear(),
        ]);
        $user->assignRole('buyer');

        $this->actingAs($user)->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Gold Member')
            ->assertSee('Valid until');

        $this->actingAs($user)->get(route('profile.edit'))
            ->assertOk()
            ->assertSee('Gold Member')
            ->assertSee('Renew Gold');
    }

    public function test_free_member_sees_get_plan_on_dashboard_and_profile(): void
    {
        $user = User::factory()->create([
            'user_type' => UserType::Buyer,
            'plan' => UserPlan::Free,
        ]);
        $user->assignRole('buyer');

        $this->actingAs($user)->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Free Member')
            ->assertSee('Get Gold Plan');

        $this->actingAs($user)->get(route('profile.edit'))
            ->assertOk()
            ->assertSee('Get Gold Plan');
    }

    public function test_expired_gold_sees_get_plan(): void
    {
        $user = User::factory()->create([
            'user_type' => UserType::Buyer,
            'plan' => UserPlan::Gold,
            'gold_expires_at' => now()->subDay(),
        ]);
        $user->assignRole('buyer');

        $this->actingAs($user)->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Free Member')
            ->assertSee('Get Gold Plan');
    }
}
