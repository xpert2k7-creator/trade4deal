@extends('layouts.marketplace')

@section('title', 'Gold checkout | Trade4Deal')

@section('content')
<section class="py-5">
    <div class="container" style="max-width: 640px">
        <div class="card card-t4d">
            <div class="card-body p-4">
                <h1 class="h3">Upgrade to Gold</h1>
                <p class="text-muted">One payment for six months of Gold access. No automatic renewal.</p>
                <p class="h2 mb-4">{{ $payment->currency }} {{ number_format($payment->amount / 100, 2) }}</p>
                @if ($testMode)
                    <div class="alert alert-warning">Test checkout: no real money will be charged.</div>
                @endif
                <p id="payment-status" class="text-muted" role="status" aria-live="polite">Complete your payment in the secure Razorpay window.</p>
                @if ($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif
                <button id="razorpay-pay" type="button" class="btn btn-primary-t4d text-white w-100 mb-3">Pay with Razorpay</button>
                <a href="{{ route('plans.index') }}">Back to plans</a>
                <form id="razorpay-verify" method="POST" action="{{ route('plans.razorpay.verify', $payment) }}">
                    @csrf
                    <input type="hidden" name="razorpay_order_id">
                    <input type="hidden" name="razorpay_payment_id">
                    <input type="hidden" name="razorpay_signature">
                </form>
                <noscript><p class="alert alert-info mt-3">Enable JavaScript to open secure payment checkout.</p></noscript>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
(() => {
    const button = document.getElementById('razorpay-pay');
    const status = document.getElementById('payment-status');
    const form = document.getElementById('razorpay-verify');
    const options = {
        key: @json($keyId),
        amount: @json($payment->amount),
        currency: @json($payment->currency),
        order_id: @json($payment->order_id),
        name: 'Trade4Deal',
        description: 'Gold access for six months',
        prefill: @json(['name' => auth()->user()->name, 'email' => auth()->user()->email]),
        theme: { color: '#0B3A6E' },
        handler(response) {
            status.textContent = 'Payment received. Verifying securely…';
            button.disabled = true;
            for (const key of ['razorpay_order_id', 'razorpay_payment_id', 'razorpay_signature']) {
                form.elements[key].value = response[key] || '';
            }
            form.submit();
        },
        modal: { ondismiss() {
            button.disabled = false;
            status.textContent = 'Checkout closed. You can retry when ready.';
        }}
    };
    button.addEventListener('click', () => {
        if (typeof window.Razorpay !== 'function') {
            status.textContent = 'Secure checkout could not load. Please refresh and try again.';
            return;
        }
        button.disabled = true;
        const checkout = new window.Razorpay(options);
        checkout.on('payment.failed', () => {
            status.textContent = 'Payment did not complete. Retry in the payment window or close it and try again.';
            button.disabled = false;
        });
        checkout.open();
    });
})();
</script>
@endpush
