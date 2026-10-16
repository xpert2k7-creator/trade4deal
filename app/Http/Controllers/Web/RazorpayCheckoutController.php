<?php

namespace App\Http\Controllers\Web;

use App\Domains\Billing\Models\RazorpayPayment;
use App\Domains\Billing\Services\RazorpayGateway;
use App\Domains\Billing\Services\RazorpayGoldCheckout;
use App\Http\Controllers\Controller;
use App\Support\Enums\UserPlan;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use RuntimeException;

class RazorpayCheckoutController extends Controller
{
    public function store(Request $request, RazorpayGoldCheckout $checkout, RazorpayGateway $gateway): RedirectResponse
    {
        if ($request->user()->userPlan()->isGold()) {
            return redirect()->route('plans.index')->with('status', 'Your Gold access is already active.');
        }
        abort_if($request->user()->plan_override === UserPlan::Free, 403, 'Please contact support before upgrading this account.');
        if (! $gateway->configured()) {
            return redirect()->route('plans.index')->with('error', 'Razorpay checkout is not configured yet.');
        }
        try {
            $purchase = $checkout->create($request->user());
        } catch (ConnectionException|RuntimeException $exception) {
            Log::warning('Razorpay order could not be created.', ['operation' => 'create_order']);

            return redirect()->route('plans.index')->with('error', 'Payment checkout is temporarily unavailable. Please try again or contact support.');
        }

        return redirect()->route('plans.razorpay.show', $purchase);
    }

    public function show(Request $request, RazorpayPayment $payment): View|RedirectResponse
    {
        abort_unless($payment->user_id === $request->user()->id, 404);
        if ($payment->status === 'paid') {
            return redirect()->route('plans.index')->with('status', 'Your Gold payment has been confirmed.');
        }
        abort_unless($payment->order_id && $payment->status === 'created', 404);

        return view('plans.razorpay', ['payment' => $payment,
            'keyId' => config('services.razorpay.key_id'),
            'testMode' => str_starts_with((string) config('services.razorpay.key_id'), 'rzp_test_')]);
    }

    public function verify(Request $request, RazorpayPayment $payment, RazorpayGoldCheckout $checkout): RedirectResponse
    {
        abort_unless($payment->user_id === $request->user()->id, 404);
        $data = $request->validate([
            'razorpay_order_id' => ['required', 'string', 'max:100'],
            'razorpay_payment_id' => ['required', 'string', 'regex:/^pay_[a-zA-Z0-9]+$/', 'max:100'],
            'razorpay_signature' => ['required', 'string', 'regex:/^[a-f0-9]{64}$/'],
        ]);
        abort_unless($payment->order_id === $data['razorpay_order_id'], 422, 'Order verification failed.');
        $checkout->verifySignature($payment, $data['razorpay_payment_id'], $data['razorpay_signature']);
        try {
            $payment = $checkout->reconcile($payment, $data['razorpay_payment_id']);
        } catch (ConnectionException|RuntimeException $exception) {
            Log::warning('Razorpay payment verification unavailable.', ['purchase_id' => $payment->id]);

            return redirect()->route('plans.index')->with('status', 'Your payment is awaiting verification. Please contact support with your payment ID if access does not activate.');
        }

        return redirect()->route('plans.index')->with('status', match ($payment->status) {
            'paid' => 'Payment verified. Your six-month Gold access is active.',
            'refunded' => 'This payment was refunded and no longer provides Gold access.',
            default => 'Payment is not captured yet. Gold access will activate after confirmation.',
        });
    }
}
