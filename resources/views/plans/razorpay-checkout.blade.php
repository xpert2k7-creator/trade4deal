@extends('layouts.marketplace')

@section('title', 'Complete Payment — Trade4Deal')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card card-t4d shadow">
                    <div class="card-body p-4 text-center">
                        <div class="text-warning small fw-bold text-uppercase mb-2">Gold — 1 Year</div>
                        <h1 class="h3 fw-bold mb-2">{{ $payment->amountLabel() }}</h1>
                        <p class="text-muted mb-4">Complete your payment securely with Razorpay to activate Gold for 1 year.</p>

                        <button id="payNowBtn" type="button" class="btn btn-primary-t4d text-white w-100 py-2 mb-2">
                            <i class="bi bi-lock-fill me-1"></i> Pay {{ $payment->amountLabel() }}
                        </button>
                        <a href="{{ route('plans.index') }}" class="btn btn-outline-t4d w-100">Cancel and return to plans</a>

                        <form id="verifyForm" method="POST" action="{{ route('plans.gold.razorpay.verify') }}" class="d-none">
                            @csrf
                            <input type="hidden" name="razorpay_order_id" id="rzp_order_id">
                            <input type="hidden" name="razorpay_payment_id" id="rzp_payment_id">
                            <input type="hidden" name="razorpay_signature" id="rzp_signature">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
(function () {
    const options = {
        key: @json($razorpayKey),
        amount: @json($payment->amount_minor),
        currency: @json($payment->currency),
        name: 'Trade4Deal',
        description: 'Gold Plan — 1 Year',
        order_id: @json($payment->razorpay_order_id),
        prefill: {
            name: @json($user->name),
            email: @json($user->email)
        },
        theme: { color: '#064475' },
        handler: function (response) {
            document.getElementById('rzp_order_id').value = response.razorpay_order_id;
            document.getElementById('rzp_payment_id').value = response.razorpay_payment_id;
            document.getElementById('rzp_signature').value = response.razorpay_signature;
            document.getElementById('verifyForm').submit();
        },
        modal: {
            ondismiss: function () {
                window.location.href = @json(route('plans.index'));
            }
        }
    };

    const rzp = new Razorpay(options);
    document.getElementById('payNowBtn')?.addEventListener('click', function () { rzp.open(); });
    rzp.open();
})();
</script>
@endpush
