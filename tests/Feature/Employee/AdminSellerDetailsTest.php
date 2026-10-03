<?php

declare(strict_types=1);

namespace Tests\Feature\Employee;

use App\Models\User;
use App\Support\Enums\UserType;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSellerDetailsTest extends TestCase
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

    public function test_admin_can_view_and_update_seller_details(): void
    {
        $seller = User::factory()->create([
            'user_type' => UserType::Seller,
            'company_name' => 'Old Seller Co',
            'country' => 'India',
        ]);
        $seller->assignRole('seller');

        $this->actingAs($this->admin())
            ->get(route('employee.users.sellers.edit', $seller))
            ->assertOk()
            ->assertSee('Old Seller Co');

        $this->actingAs($this->admin())
            ->put(route('employee.users.sellers.update', $seller), [
                'name' => $seller->name,
                'email' => $seller->email,
                'company_name' => 'Updated Seller Co',
                'country' => 'India',
                'designation' => 'Director',
                'tagline' => 'Quality exports',
                'industries' => [],
                'is_public' => '1',
            ])
            ->assertRedirect(route('employee.users.sellers.edit', $seller))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $seller->id,
            'company_name' => 'Updated Seller Co',
            'designation' => 'Director',
        ]);
    }
}
