<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\RazorpayPayment;
use App\Models\User;
use App\Support\Enums\UserPlan;
use Illuminate\Support\Facades\DB;
use Razorpay\Api\Api;

/**
 * Simple one-time Razorpay payments for 1-year Gold. No subscriptions.
 */
class RazorpayPayments
{
    public function api(): Api
    {
        return new Api(
            (string) config('services.razorpay.key'),
            (string) config('services.razorpay.secret'),
        );
    }

    public function isConfigured(): bool
    {
        return filled(config('services.razorpay.key')) && filled(config('services.razorpay.secret'));
    }

    /**
     * @param  array{amount_minor: int, currency: string, country_code: string|null}  $price
     */
    public function createOrder(User $user, array $price): RazorpayPayment
    {
        $order = $this->api()->order->create([
            'receipt' => 'gold_'.$user->id.'_'.now()->timestamp,
            'amount' => $price['amount_minor'],
            'currency' => $price['currency'],
            'notes' => [
                'user_id' => (string) $user->id,
                'plan' => 'gold',
                'duration' => '1 year',
            ],
        ]);

        return RazorpayPayment::create([
            'user_id' => $user->id,
            'amount_minor' => $price['amount_minor'],
            'currency' => $price['currency'],
            'country_code' => $price['country_code'],
            'status' => 'created',
            'razorpay_order_id' => $order['id'],
        ]);
    }

    public function verifySignature(string $orderId, string $paymentId, string $signature): bool
    {
        $expected = hash_hmac('sha256', $orderId.'|'.$paymentId, (string) config('services.razorpay.secret'));

        return hash_equals($expected, $signature);
    }

    public function verifyWebhookSignature(string $payload, string $signature): bool
    {
        $secret = (string) config('services.razorpay.webhook_secret');
        if ($secret === '') {
            return false;
        }

        return hash_equals(hash_hmac('sha256', $payload, $secret), $signature);
    }

    /**
     * Idempotently mark a payment paid and grant/extend Gold by 1 year.
     * Renewing early stacks: the new year starts at the current expiry.
     */
    public function markPaid(RazorpayPayment $payment, string $razorpayPaymentId, ?string $method = null): RazorpayPayment
    {
        return DB::transaction(function () use ($payment, $razorpayPaymentId, $method): RazorpayPayment {
            $payment = RazorpayPayment::query()->lockForUpdate()->findOrFail($payment->id);

            if ($method !== null && $method !== '') {
                $payment->method = $method;
            }

            if ($payment->isPaid()) {
                $payment->save();

                return $payment;
            }

            /** @var User $user */
            $user = User::query()->lockForUpdate()->findOrFail($payment->user_id);

            $now = now();
            $currentlyGold = $user->userPlan()->isGold();
            $startsAt = $currentlyGold && $user->gold_expires_at !== null && $user->gold_expires_at->isFuture()
                ? $user->gold_expires_at->copy()
                : $now->copy();
            $expiresAt = $startsAt->copy()->addYear();

            $user->forceFill([
                'plan' => UserPlan::Gold,
                'gold_expires_at' => $expiresAt,
            ])->save();

            $payment->forceFill([
                'status' => 'paid',
                'razorpay_payment_id' => $razorpayPaymentId,
                'subscription_starts_at' => $startsAt,
                'subscription_expires_at' => $expiresAt,
                'paid_at' => $now,
            ])->save();

            return $payment;
        });
    }
}
