<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\RazorpayPayment;
use App\Support\GeoPricing;
use App\Support\RazorpayPayments;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RazorpayController extends Controller
{
    public function checkout(Request $request, GeoPricing $pricing, RazorpayPayments $razorpay): View|RedirectResponse
    {
        if (! $razorpay->isConfigured()) {
            return redirect()->route('plans.index')->with('error', 'Gold plan is not configured yet. Please contact support.');
        }

        try {
            $payment = $razorpay->createOrder($request->user(), $pricing->price($request));
        } catch (\Throwable) {
            return redirect()->route('plans.index')->with('error', 'Could not start payment. Please try again.');
        }

        return view('plans.razorpay-checkout', [
            'payment' => $payment,
            'razorpayKey' => (string) config('services.razorpay.key'),
            'user' => $request->user(),
        ]);
    }

    public function verify(Request $request, RazorpayPayments $razorpay): RedirectResponse
    {
        $data = $request->validate([
            'razorpay_order_id' => ['required', 'string'],
            'razorpay_payment_id' => ['required', 'string'],
            'razorpay_signature' => ['required', 'string'],
        ]);

        $payment = RazorpayPayment::query()
            ->where('user_id', $request->user()->id)
            ->where('razorpay_order_id', $data['razorpay_order_id'])
            ->first();

        if ($payment === null || ! $razorpay->verifySignature($data['razorpay_order_id'], $data['razorpay_payment_id'], $data['razorpay_signature'])) {
            return redirect()->route('plans.index')->with('error', 'Payment verification failed. If money was deducted, please contact support.');
        }

        $razorpay->markPaid($payment, $data['razorpay_payment_id']);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Welcome to Trade4Deal Gold! Your 1-year Gold subscription is now active.');
    }

    public function webhook(Request $request, RazorpayPayments $razorpay): JsonResponse
    {
        $payload = $request->getContent();
        $signature = (string) $request->header('X-Razorpay-Signature', '');

        if (! $razorpay->verifyWebhookSignature($payload, $signature)) {
            return response()->json(['message' => 'Invalid signature'], 400);
        }

        $event = json_decode($payload, true);
        if (($event['event'] ?? null) === 'payment.captured') {
            $entity = $event['payload']['payment']['entity'] ?? [];
            $orderId = $entity['order_id'] ?? null;
            $paymentId = $entity['id'] ?? null;

            if ($orderId && $paymentId) {
                $payment = RazorpayPayment::query()->where('razorpay_order_id', $orderId)->first();
                if ($payment !== null) {
                    $razorpay->markPaid($payment, (string) $paymentId, isset($entity['method']) ? (string) $entity['method'] : null);
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }
}
