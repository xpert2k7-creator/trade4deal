<?php

declare(strict_types=1);

namespace Tests\Feature\Employee;

use App\Models\User;
use App\Support\Enums\UserType;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
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

    public function test_admin_can_delete_marketplace_seller(): void
    {
        $seller = User::factory()->create([
            'user_type' => UserType::Seller,
            'company_name' => 'Delete Me Co',
        ]);
        $seller->assignRole('seller');

        $this->actingAs($this->admin())
            ->delete(route('employee.users.destroy', $seller))
            ->assertRedirect(route('employee.users.index'));

        $this->assertSoftDeleted('users', ['id' => $seller->id]);
    }

    public function test_admin_can_update_buyer_account(): void
    {
        $buyer = User::factory()->create([
            'user_type' => UserType::Buyer,
            'name' => 'Old Name',
        ]);
        $buyer->assignRole('buyer');

        $this->actingAs($this->admin())
            ->put(route('employee.users.update', $buyer), [
                'name' => 'New Name',
                'email' => $buyer->email,
                'phone' => '1234567890',
                'company_name' => $buyer->company_name,
                'country' => $buyer->country,
                'user_type' => UserType::Buyer->value,
                'plan' => 'free',
                'status' => 1,
            ])
            ->assertRedirect(route('employee.users.edit', $buyer));

        $this->assertDatabaseHas('users', [
            'id' => $buyer->id,
            'name' => 'New Name',
        ]);
    }
}
