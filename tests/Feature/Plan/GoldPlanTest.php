<?php

declare(strict_types=1);

namespace Tests\Feature\Plan;

use App\Models\RazorpayPayment;
use App\Models\User;
use App\Support\Enums\UserPlan;
use App\Support\Enums\UserType;
use App\Support\RazorpayPayments;
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
        config([
            'services.razorpay.key' => 'rzp_test_key',
            'services.razorpay.secret' => 'test-secret',
            'services.razorpay.webhook_secret' => 'test-webhook-secret',
            'trade4deal.pricing_country_override' => 'IN',
        ]);
    }

    public function test_plans_page_shows_india_price(): void
    {
        $this->get(route('plans.index'))
            ->assertOk()
            ->assertSee('Choose your plan')
            ->assertSee('Gold')
            ->assertSee('₹25,000')
            ->assertSee('/ year');
    }

    public function test_plans_page_shows_international_price(): void
    {
        config(['trade4deal.pricing_country_override' => 'US']);

        $this->get(route('plans.index'))
            ->assertOk()
            ->assertSee('$265')
            ->assertSee('/ year');
    }

    public function test_checkout_requires_authentication(): void
    {
        $this->post(route('plans.gold.razorpay.checkout'))
            ->assertRedirect(route('login'));
    }

    public function test_inr_checkout_prefers_upi_qr_payment_option(): void
    {
        $user = User::factory()->create(['user_type' => UserType::Buyer, 'plan' => UserPlan::Free]);
        $user->assignRole('buyer');

        $payment = RazorpayPayment::factory()->make([
            'user_id' => $user->id,
            'amount_minor' => 2500000,
            'currency' => 'INR',
            'razorpay_order_id' => 'order_upi1',
        ]);

        $this->mock(RazorpayPayments::class, function ($mock) use ($payment): void {
            $mock->shouldReceive('isConfigured')->andReturnTrue();
            $mock->shouldReceive('createOrder')->andReturn($payment);
        });

        $this->actingAs($user)
            ->post(route('plans.gold.razorpay.checkout'))
            ->assertOk()
            ->assertSee('UPI / QR')
            ->assertSee('Pay using UPI / QR')
            ->assertSee('block.upi', false);
    }

    public function test_verify_grants_one_year_gold(): void
    {
        $user = User::factory()->create(['user_type' => UserType::Buyer, 'plan' => UserPlan::Free]);
        $user->assignRole('buyer');

        $payment = RazorpayPayment::factory()->create([
            'user_id' => $user->id,
            'razorpay_order_id' => 'order_test123',
        ]);

        $signature = hash_hmac('sha256', 'order_test123|pay_test123', 'test-secret');

        $this->actingAs($user)
            ->post(route('plans.gold.razorpay.verify'), [
                'razorpay_order_id' => 'order_test123',
                'razorpay_payment_id' => 'pay_test123',
                'razorpay_signature' => $signature,
            ])
            ->assertRedirect(route('dashboard'));

        $fresh = $user->fresh();
        $this->assertEquals(UserPlan::Gold, $fresh->plan);
        $this->assertTrue($fresh->userPlan()->isGold());
        $this->assertEqualsWithDelta(now()->addYear()->timestamp, $fresh->gold_expires_at->timestamp, 5);
        $this->assertSame('paid', $payment->fresh()->status);
    }

    public function test_verify_rejects_bad_signature(): void
    {
        $user = User::factory()->create(['user_type' => UserType::Buyer, 'plan' => UserPlan::Free]);
        $user->assignRole('buyer');

        RazorpayPayment::factory()->create([
            'user_id' => $user->id,
            'razorpay_order_id' => 'order_bad',
        ]);

        $this->actingAs($user)
            ->post(route('plans.gold.razorpay.verify'), [
                'razorpay_order_id' => 'order_bad',
                'razorpay_payment_id' => 'pay_bad',
                'razorpay_signature' => 'invalid',
            ])
            ->assertRedirect(route('plans.index'));

        $this->assertEquals(UserPlan::Free, $user->fresh()->userPlan());
    }

    public function test_renewal_stacks_one_year_on_active_gold(): void
    {
        $existingExpiry = now()->addMonths(6);
        $user = User::factory()->create([
            'user_type' => UserType::Buyer,
            'plan' => UserPlan::Gold,
            'gold_expires_at' => $existingExpiry,
        ]);
        $user->assignRole('buyer');

        $payment = RazorpayPayment::factory()->create(['user_id' => $user->id]);

        app(RazorpayPayments::class)->markPaid($payment, 'pay_renew1');

        $this->assertEqualsWithDelta(
            $existingExpiry->copy()->addYear()->timestamp,
            $user->fresh()->gold_expires_at->timestamp,
            5
        );
    }

    public function test_expired_gold_is_treated_as_free(): void
    {
        $user = User::factory()->create([
            'plan' => UserPlan::Gold,
            'gold_expires_at' => now()->subDay(),
        ]);

        $this->assertEquals(UserPlan::Free, $user->userPlan());
    }

    public function test_webhook_marks_payment_paid(): void
    {
        $user = User::factory()->create(['user_type' => UserType::Buyer, 'plan' => UserPlan::Free]);
        $user->assignRole('buyer');

        $payment = RazorpayPayment::factory()->create([
            'user_id' => $user->id,
            'razorpay_order_id' => 'order_hook1',
        ]);

        $payload = json_encode([
            'event' => 'payment.captured',
            'payload' => ['payment' => ['entity' => [
                'id' => 'pay_hook1',
                'order_id' => 'order_hook1',
                'method' => 'upi',
            ]]],
        ]);
        $signature = hash_hmac('sha256', $payload, 'test-webhook-secret');

        $this->call('POST', route('razorpay.webhook'), [], [], [], [
            'HTTP_X-Razorpay-Signature' => $signature,
            'CONTENT_TYPE' => 'application/json',
        ], $payload)->assertOk();

        $this->assertSame('paid', $payment->fresh()->status);
        $this->assertTrue($user->fresh()->userPlan()->isGold());
    }
}
