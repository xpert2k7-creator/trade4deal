<?php

declare(strict_types=1);

namespace Tests\Feature\Plan;

use App\Models\RazorpayPayment;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    private function admin(): User
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        return $user;
    }

    private function employee(): User
    {
        $user = User::factory()->create();
        $user->assignRole('employee');

        return $user;
    }

    public function test_admin_can_view_payment_dashboard(): void
    {
        $buyer = User::factory()->create(['name' => 'Paying Buyer', 'company_name' => 'Buyer Co']);
        RazorpayPayment::factory()->paid()->create([
            'user_id' => $buyer->id,
            'amount_minor' => 2500000,
            'currency' => 'INR',
        ]);

        $this->actingAs($this->admin())
            ->get(route('verification.payments.index'))
            ->assertOk()
            ->assertSee('Payment Dashboard')
            ->assertSee('Paying Buyer')
            ->assertSee('₹25,000');
    }

    public function test_employee_cannot_view_payment_dashboard(): void
    {
        $this->actingAs($this->employee())
            ->get(route('verification.payments.index'))
            ->assertForbidden();
    }

    public function test_custom_date_filter_excludes_outside_payments(): void
    {
        $old = User::factory()->create(['name' => 'Old Payer']);
        $new = User::factory()->create(['name' => 'New Payer']);

        RazorpayPayment::factory()->paid()->create(['user_id' => $old->id, 'created_at' => now()->subYear()]);
        RazorpayPayment::factory()->paid()->create(['user_id' => $new->id, 'created_at' => now()]);

        $this->actingAs($this->admin())
            ->get(route('verification.payments.index', [
                'period' => 'custom',
                'date_from' => now()->subDays(7)->toDateString(),
                'date_to' => now()->toDateString(),
            ]))
            ->assertOk()
            ->assertSee('New Payer')
            ->assertDontSee('Old Payer');
    }

    public function test_month_and_year_filters_render(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->get(route('verification.payments.index', ['period' => 'month']))->assertOk();
        $this->actingAs($admin)->get(route('verification.payments.index', ['period' => 'year']))->assertOk();
    }
}
