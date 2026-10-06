@extends('layouts.sourcing')

@section('title', 'Overview')
@section('page-title', 'Sourcing Overview')

@section('topbar-actions')
    <a href="{{ route('sourcing.leads.create') }}" class="btn btn-sm btn-primary-t4d">
        <i class="bi bi-plus-lg"></i> Add buy lead
    </a>
@endsection

@section('content')
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-4">
        <div class="kpi-card">
            <div class="label">Today</div>
            <div class="value">{{ $counts['today'] }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-4">
        <div class="kpi-card active">
            <div class="label">This month</div>
            <div class="value">{{ $counts['month'] }}</div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="kpi-card pending">
            <div class="label">Total submitted</div>
            <div class="value">{{ $counts['total'] }}</div>
        </div>
    </div>
</div>

<div class="mb-4">
    @include('partials.sourcing-submission-chart', [
        'chartId' => 'sourcing-user-chart',
        'labels' => $chartLabels,
        'values' => $chartValues,
    ])
</div>

<div class="panel">
    <div class="panel-header">
        <h2>Recent pending submissions</h2>
        <a href="{{ route('sourcing.leads.index', ['status' => 'pending']) }}" class="small fw-semibold text-decoration-none" style="color: var(--t4d-primary);">
            View all
        </a>
    </div>

    @if ($recentLeads->isEmpty())
        <div class="panel-body text-center text-muted py-5">
            No pending submissions yet.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-emp align-middle mb-0">
                <thead>
                <tr>
                    <th>Company</th>
                    <th>Contact</th>
                    <th>Product</th>
                    <th>Country</th>
                    <th>Submitted</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($recentLeads as $lead)
                    <tr>
                        <td class="fw-semibold">{{ $lead->company_name }}</td>
                        <td>
                            <div>{{ $lead->contact_name }}</div>
                            <div class="small text-muted">{{ $lead->email }}</div>
                        </td>
                        <td>
                            <div>{{ $lead->product_type?->label() ?? '—' }}</div>
                            <div class="small text-muted">{{ $lead->product_interest }}</div>
                        </td>
                        <td>{{ $lead->country }}</td>
                        <td class="small text-muted">{{ $lead->created_at?->diffForHumans() }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
