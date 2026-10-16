<?php

namespace Tests\Feature\Plan;

use App\Domains\Billing\Models\RazorpayPayment;
use App\Models\User;
use App\Support\Enums\UserPlan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class RazorpayCheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Http::preventStrayRequests();
    }

    public function test_order_price_and_owner_are_server_controlled_and_secret_is_not_rendered(): void
    {
        $user = User::factory()->create();
        Http::fake(['api.razorpay.com/v1/orders' => Http::response([
            'id' => 'order_test123', 'amount' => 17400, 'currency' => 'USD',
        ])]);
        $this->actingAs($user)->post(route('plans.razorpay.checkout'), ['amount' => 1, 'currency' => 'INR'])
            ->assertRedirect();
        $purchase = RazorpayPayment::firstOrFail();
        $this->assertSame($user->id, $purchase->user_id);
        $this->assertSame(17400, $purchase->amount);
        $this->assertSame('USD', $purchase->currency);
        Http::assertSent(fn ($request) => $request['amount'] === 17400 && $request['currency'] === 'USD'
            && $request['receipt'] === $purchase->id && $request['partial_payment'] === false);
        $this->get(route('plans.razorpay.show', $purchase))->assertOk()->assertSee('USD 174.00')
            ->assertSee('Test checkout')->assertSee('rzp_test_example')
            ->assertDontSee(config('services.razorpay.key_secret'));
        $this->assertSame(UserPlan::Free, $user->fresh()->userPlan());
    }

    public function test_captured_payment_grants_exactly_six_months_once_and_expires(): void
    {
        $this->travelTo(now()->startOfSecond());
        $purchase = $this->purchase();
        Http::fake(['api.razorpay.com/v1/payments/pay_test123' => Http::response($this->remote($purchase))]);
        $this->actingAs($purchase->user)->post(route('plans.razorpay.verify', $purchase), $this->signature($purchase))
            ->assertRedirect(route('plans.index'));
        $expiry = now()->addMonthsNoOverflow(6);
        $this->assertSame('paid', $purchase->fresh()->status);
        $this->assertTrue($purchase->fresh()->expires_at->equalTo($expiry));
        $this->assertSame(UserPlan::Gold, $purchase->user->fresh()->userPlan());
        $this->post(route('plans.razorpay.verify', $purchase), $this->signature($purchase))->assertRedirect();
        $this->webhook('payment.captured', $purchase)->assertOk();
        $this->assertTrue($purchase->fresh()->expires_at->equalTo($expiry));
        $this->travelTo($expiry->addSecond());
        $this->assertSame(UserPlan::Free, $purchase->user->fresh()->userPlan());
    }

    public function test_forged_signature_cannot_grant_access_or_call_provider(): void
    {
        $purchase = $this->purchase();
        $this->actingAs($purchase->user)->post(route('plans.razorpay.verify', $purchase),
            [...$this->signature($purchase), 'razorpay_signature' => str_repeat('0', 64)])
            ->assertSessionHasErrors('payment');
        Http::assertNothingSent();
        $this->assertSame(UserPlan::Free, $purchase->user->fresh()->userPlan());
    }

    public function test_other_users_cannot_view_or_verify_an_order(): void
    {
        $purchase = $this->purchase();
        $this->actingAs(User::factory()->create())->get(route('plans.razorpay.show', $purchase))->assertNotFound();
        $this->post(route('plans.razorpay.verify', $purchase), $this->signature($purchase))->assertNotFound();
        Http::assertNothingSent();
    }

    public function test_wrong_amount_currency_or_order_and_authorized_payments_do_not_grant_gold(): void
    {
        $purchase = $this->purchase();
        $this->actingAs($purchase->user);
        $sequence = Http::fakeSequence('api.razorpay.com/v1/payments/*');
        foreach ([['amount' => 1], ['currency' => 'INR'], ['order_id' => 'order_someoneelse']] as $change) {
            $sequence->push([...$this->remote($purchase), ...$change]);
            $this->post(route('plans.razorpay.verify', $purchase), $this->signature($purchase))->assertSessionHasErrors('payment');
            $this->assertSame('created', $purchase->fresh()->status);
        }
        $sequence->push([
            ...$this->remote($purchase), 'status' => 'authorized', 'captured' => false,
        ]);
        $this->post(route('plans.razorpay.verify', $purchase), $this->signature($purchase))->assertRedirect()->assertSessionHasNoErrors();
        $this->assertSame(UserPlan::Free, $purchase->user->fresh()->userPlan());
    }

    public function test_signed_capture_works_without_browser_callback_and_full_refund_revokes_it(): void
    {
        $purchase = $this->purchase();
        $sequence = Http::fakeSequence('api.razorpay.com/v1/payments/*')->push($this->remote($purchase));
        $this->webhook('payment.captured', $purchase)->assertOk();
        $this->assertSame(UserPlan::Gold, $purchase->user->fresh()->userPlan());
        $sequence->push([
            ...$this->remote($purchase), 'status' => 'refunded', 'amount_refunded' => 17400,
        ]);
        $this->webhook('payment.refunded', $purchase)->assertOk();
        $this->assertSame('refunded', $purchase->fresh()->status);
        $this->assertSame(UserPlan::Free, $purchase->user->fresh()->userPlan());
        // Even a late captured callback cannot reactivate a refunded local purchase.
        $sequence->push($this->remote($purchase));
        $this->actingAs($purchase->user)->post(route('plans.razorpay.verify', $purchase), $this->signature($purchase));
        $this->assertSame(UserPlan::Free, $purchase->user->fresh()->userPlan());
    }

    public function test_invalid_and_unconfigured_webhooks_fail_closed(): void
    {
        $this->postJson('/razorpay/webhook', ['event' => 'payment.captured'])->assertForbidden();
        config(['services.razorpay.webhook_secret' => null]);
        $this->postJson('/razorpay/webhook', [])->assertStatus(503);
        Http::assertNothingSent();
    }

    public function test_provider_failure_does_not_expose_error_or_grant_access(): void
    {
        Http::fake(['api.razorpay.com/v1/orders' => Http::response(['error' => ['description' => 'provider internal error']], 400)]);
        $user = User::factory()->create();
        $this->actingAs($user)->post(route('plans.razorpay.checkout'))->assertRedirect(route('plans.index'))
            ->assertSessionHas('error');
        $this->get(route('plans.index'))->assertDontSee('provider internal error');
        $this->assertSame(UserPlan::Free, $user->fresh()->userPlan());
    }

    public function test_checkout_requires_verified_login_and_respects_admin_restrictions(): void
    {
        $this->post(route('plans.razorpay.checkout'))->assertRedirect(route('login'));
        $user = User::factory()->unverified()->create();
        $this->actingAs($user)->post(route('plans.razorpay.checkout'))->assertRedirect(route('verification.notice'));
        $user->forceFill(['email_verified_at' => now(), 'plan_override' => UserPlan::Free])->save();
        $this->actingAs($user->fresh())->post(route('plans.razorpay.checkout'))->assertForbidden();
        Http::assertNothingSent();
    }

    private function purchase(): RazorpayPayment
    {
        return RazorpayPayment::create(['user_id' => User::factory()->create()->id,
            'order_id' => 'order_test123', 'amount' => 17400, 'currency' => 'USD', 'duration_months' => 6]);
    }

    private function remote(RazorpayPayment $purchase): array
    {
        return ['id' => 'pay_test123', 'order_id' => $purchase->order_id, 'amount' => $purchase->amount,
            'currency' => $purchase->currency, 'status' => 'captured', 'captured' => true, 'amount_refunded' => 0];
    }

    private function signature(RazorpayPayment $purchase): array
    {
        return ['razorpay_order_id' => $purchase->order_id, 'razorpay_payment_id' => 'pay_test123',
            'razorpay_signature' => hash_hmac('sha256', $purchase->order_id.'|pay_test123', config('services.razorpay.key_secret'))];
    }

    private function webhook(string $event, RazorpayPayment $purchase): TestResponse
    {
        $body = json_encode(['event' => $event, 'payload' => ['payment' => ['entity' => $this->remote($purchase)]]]);

        return $this->call('POST', '/razorpay/webhook', [], [], [], ['CONTENT_TYPE' => 'application/json',
            'HTTP_X_RAZORPAY_SIGNATURE' => hash_hmac('sha256', $body, config('services.razorpay.webhook_secret'))], $body);
    }
}
