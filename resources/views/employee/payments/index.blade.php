@extends('layouts.employee')

@section('title', 'Payments')
@section('page-title', 'Payment Dashboard')

@section('content')
<div class="row g-3 mb-3">
    <div class="col-md-3">
        <div class="panel h-100"><div class="panel-body">
            <div class="small text-muted fw-bold text-uppercase mb-1">Revenue (INR)</div>
            <div class="h4 fw-bold mb-0">₹{{ number_format($summary['revenue_inr'] / 100) }}</div>
            <div class="small text-muted">{{ $rangeLabel }}</div>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="panel h-100"><div class="panel-body">
            <div class="small text-muted fw-bold text-uppercase mb-1">Revenue (USD)</div>
            <div class="h4 fw-bold mb-0">${{ number_format($summary['revenue_usd'] / 100) }}</div>
            <div class="small text-muted">{{ $rangeLabel }}</div>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="panel h-100"><div class="panel-body">
            <div class="small text-muted fw-bold text-uppercase mb-1">Successful Payments</div>
            <div class="h4 fw-bold mb-0">{{ number_format($summary['paid_count']) }} <span class="small text-muted fw-normal">/ {{ number_format($summary['total_count']) }} total</span></div>
            <div class="small text-muted">In selected period</div>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="panel h-100"><div class="panel-body">
            <div class="small text-muted fw-bold text-uppercase mb-1">Active Gold</div>
            <div class="h4 fw-bold mb-0">{{ number_format($summary['active_gold']) }}</div>
            <div class="small text-muted">{{ number_format($summary['expiring_this_month']) }} expiring this month</div>
        </div></div>
    </div>
</div>

<div class="panel mb-3">
    <div class="panel-header">
        <h2>Payments</h2>
    </div>
    <div class="panel-body border-bottom">
        <form method="GET" action="{{ route('verification.payments.index') }}" class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label small fw-bold" for="period">Period</label>
                <select id="period" name="period" class="form-select form-select-sm">
                    <option value="month" @selected($period === 'month')>Current Month</option>
                    <option value="year" @selected($period === 'year')>Current Year</option>
                    <option value="custom" @selected($period === 'custom')>Custom Date</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold" for="date_from">From</label>
                <input id="date_from" type="date" name="date_from" value="{{ $dateFrom }}" class="form-control form-control-sm">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold" for="date_to">To</label>
                <input id="date_to" type="date" name="date_to" value="{{ $dateTo }}" class="form-control form-control-sm">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold" for="status">Status</label>
                <select id="status" name="status" class="form-select form-select-sm">
                    <option value="">All statuses</option>
                    <option value="paid" @selected($status === 'paid')>Paid</option>
                    <option value="created" @selected($status === 'created')>Created</option>
                    <option value="failed" @selected($status === 'failed')>Failed</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold" for="q">Search user</label>
                <input id="q" type="search" name="q" value="{{ $search }}" class="form-control form-control-sm" placeholder="Name, email, company…">
            </div>
            <div class="col-md-1 d-flex gap-1">
                <button type="submit" class="btn btn-sm btn-primary-t4d">Filter</button>
                <a href="{{ route('verification.payments.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
        </form>
        <div class="small text-muted mt-2">Showing payments from <strong>{{ $rangeLabel }}</strong>. Custom dates apply when Period is “Custom Date”.</div>
    </div>

    @if ($payments->isEmpty())
        <div class="panel-body text-center text-muted py-5">No payments found for this period.</div>
    @else
        <div class="table-responsive">
            <table class="table table-emp align-middle mb-0">
                <thead>
                <tr>
                    <th>Date</th>
                    <th>User</th>
                    <th>Subscription</th>
                    <th>Amount</th>
                    <th>Country</th>
                    <th>Method</th>
                    <th>Status</th>
                    <th>Razorpay IDs</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($payments as $payment)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ ($payment->paid_at ?? $payment->created_at)?->format('d M Y') }}</div>
                            <div class="small text-muted">{{ ($payment->paid_at ?? $payment->created_at)?->format('h:i A') }}</div>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $payment->user?->name ?? '—' }}</div>
                            <div class="small text-muted">{{ $payment->user?->email }}</div>
                            @if ($payment->user?->company_name)
                                <div class="small text-muted">{{ $payment->user->company_name }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-warning text-dark">Gold · 1 Year</span>
                            @if ($payment->subscription_starts_at && $payment->subscription_expires_at)
                                <div class="small text-muted mt-1">{{ $payment->subscription_starts_at->format('d M Y') }} → {{ $payment->subscription_expires_at->format('d M Y') }}</div>
                            @else
                                <div class="small text-muted mt-1">Not activated</div>
                            @endif
                        </td>
                        <td class="fw-semibold">{{ $payment->amountLabel() }}</td>
                        <td>{{ $payment->country_code ?? '—' }}</td>
                        <td>{{ $payment->method ? ucfirst($payment->method) : '—' }}</td>
                        <td>
                            @if ($payment->status === 'paid')
                                <span class="badge bg-success">Paid</span>
                            @elseif ($payment->status === 'failed')
                                <span class="badge bg-danger">Failed</span>
                            @else
                                <span class="badge bg-secondary">Created</span>
                            @endif
                        </td>
                        <td>
                            <div class="small"><span class="text-muted">Order:</span> {{ $payment->razorpay_order_id }}</div>
                            <div class="small"><span class="text-muted">Payment:</span> {{ $payment->razorpay_payment_id ?? '—' }}</div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="panel-body border-top d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
            <div class="small text-muted">Showing {{ $payments->firstItem() }}–{{ $payments->lastItem() }} of {{ $payments->total() }} payments</div>
            {{ $payments->links() }}
        </div>
    @endif
</div>
@endsection
