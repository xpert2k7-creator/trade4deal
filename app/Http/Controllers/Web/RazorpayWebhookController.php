<?php

namespace App\Http\Controllers\Web;

use App\Domains\Billing\Models\RazorpayPayment;
use App\Domains\Billing\Services\RazorpayGoldCheckout;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RazorpayWebhookController extends Controller
{
    public function __invoke(Request $request, RazorpayGoldCheckout $checkout): Response
    {
        $secret = config('services.razorpay.webhook_secret');
        abort_unless(is_string($secret) && $secret !== '', 503);
        $signature = $request->header('X-Razorpay-Signature', '');
        abort_unless(hash_equals(hash_hmac('sha256', $request->getContent(), $secret), $signature), 403);
        $data = json_decode($request->getContent(), true);
        abort_unless(is_array($data), 400);
        if (! in_array($data['event'] ?? null, ['payment.captured', 'order.paid', 'payment.refunded', 'refund.processed'], true)) {
            return response('Ignored', 200);
        }
        $id = data_get($data, 'payload.payment.entity.id') ?? data_get($data, 'payload.refund.entity.payment_id');
        abort_unless(is_string($id) && preg_match('/^pay_[a-zA-Z0-9]+$/', $id), 400);
        $orderId = data_get($data, 'payload.payment.entity.order_id');
        $payment = RazorpayPayment::query()->when(is_string($orderId),
            fn ($query) => $query->where('order_id', $orderId),
            fn ($query) => $query->where('payment_id', $id))->first();
        if ($payment) {
            // Fetch current provider state: event payloads can be duplicated or arrive out of order.
            $checkout->reconcile($payment, $id);
        }

        return response('OK', 200);
    }
}
