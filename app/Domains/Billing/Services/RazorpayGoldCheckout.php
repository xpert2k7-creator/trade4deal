<?php

namespace App\Domains\Billing\Services;

use App\Domains\Billing\Models\RazorpayPayment;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class RazorpayGoldCheckout
{
    public function __construct(private RazorpayGateway $gateway) {}

    public function create(User $user): RazorpayPayment
    {
        $amount = (int) config('services.razorpay.gold_amount', 17400);
        $currency = (string) config('services.razorpay.gold_currency', 'USD');
        if ($amount < 100 || ! preg_match('/^[A-Z]{3}$/', $currency)) {
            throw new RuntimeException('Gold pricing is not configured correctly.');
        }
        $payment = RazorpayPayment::create([
            'user_id' => $user->id, 'amount' => $amount, 'currency' => $currency, 'duration_months' => 6,
        ]);
        $order = $this->gateway->createOrder([
            'amount' => $payment->amount, 'currency' => $payment->currency,
            'receipt' => $payment->id, 'partial_payment' => false,
            'notes' => ['purchase_id' => $payment->id, 'plan' => 'gold'],
        ]);
        if (! is_string($order['id'] ?? null) || ! preg_match('/^order_[a-zA-Z0-9]+$/', $order['id'])
            || ($order['amount'] ?? null) !== $payment->amount || ($order['currency'] ?? null) !== $payment->currency) {
            throw new RuntimeException('Razorpay returned an unexpected order.');
        }
        $payment->update(['order_id' => $order['id']]);

        return $payment;
    }

    public function verifySignature(RazorpayPayment $payment, string $paymentId, string $signature): void
    {
        $secret = config('services.razorpay.key_secret');
        if (! is_string($secret) || $secret === '' || ! $payment->order_id ||
            ! hash_equals(hash_hmac('sha256', $payment->order_id.'|'.$paymentId, $secret), $signature)) {
            throw ValidationException::withMessages(['payment' => 'Payment signature verification failed.']);
        }
    }

    public function reconcile(RazorpayPayment $purchase, string $paymentId): RazorpayPayment
    {
        $remote = $this->gateway->fetchPayment($paymentId);
        if (($remote['id'] ?? null) !== $paymentId || ($remote['order_id'] ?? null) !== $purchase->order_id
            || ($remote['amount'] ?? null) !== $purchase->amount || ($remote['currency'] ?? null) !== $purchase->currency) {
            throw ValidationException::withMessages(['payment' => 'The payment does not match this Gold order.']);
        }

        return DB::transaction(function () use ($purchase, $paymentId, $remote): RazorpayPayment {
            // All payment deliveries for an account serialize here before changing access dates.
            $user = User::withTrashed()->whereKey($purchase->user_id)->lockForUpdate()->firstOrFail();
            $purchase = RazorpayPayment::whereKey($purchase->id)->lockForUpdate()->firstOrFail();
            if ($purchase->payment_id && $purchase->payment_id !== $paymentId) {
                throw ValidationException::withMessages(['payment' => 'This order already has a different payment.']);
            }
            if (($remote['status'] ?? null) === 'refunded' ||
                (int) ($remote['amount_refunded'] ?? 0) >= $purchase->amount) {
                $purchase->update(['payment_id' => $paymentId, 'status' => 'refunded']);
            } elseif ($purchase->status !== 'refunded' && ($remote['status'] ?? null) === 'captured'
                && ($remote['captured'] ?? false) === true && $purchase->paid_at === null) {
                $latestExpiry = RazorpayPayment::where('user_id', $user->id)->where('status', 'paid')
                    ->where('expires_at', '>', now())->max('expires_at');
                $start = $latestExpiry ? Carbon::parse($latestExpiry) : now();
                $purchase->update(['payment_id' => $paymentId, 'status' => 'paid', 'paid_at' => now(),
                    'starts_at' => $start, 'expires_at' => $start->copy()->addMonthsNoOverflow($purchase->duration_months)]);
            }
            // Duplicate/late callbacks do not re-grant refunded access or extend an existing grant.
            $user->syncPlanFromPayments();

            return $purchase->fresh();
        });
    }
}
