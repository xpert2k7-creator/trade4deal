<?php
namespace Tests\Feature;
use App\Models\User;
use App\Support\Enums\RecordStatus;
use App\Support\Enums\UserType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role;

class DashboardSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_verified_user_can_see_dashboard_without_vite_error(): void
    {
        Role::create(["name" => "buyer", "guard_name" => "web"]);
        $user = User::factory()->create([
            "email_verified_at" => now(),
            "user_type" => UserType::Buyer,
            "status" => RecordStatus::Active,
        ]);
        $user->assignRole("buyer");

        $response = $this->actingAs($user)->get("/dashboard");
        $response->assertOk();
        $response->assertSee("Welcome back");
        $response->assertDontSee("Vite manifest not found");
    }
}
