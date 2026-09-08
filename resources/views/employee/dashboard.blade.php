@extends('layouts.employee')

@section('title', 'Overview')
@section('page-title', 'Moderation Overview')

@section('topbar-actions')
    <a href="{{ route('employee.leads.create') }}" class="btn btn-sm btn-outline-secondary me-1">
        <i class="bi bi-plus-lg"></i> Submit lead
    </a>
    <a href="{{ route('employee.leads.index', ['status' => 'pending']) }}" class="btn btn-primary-t4d btn-sm">
        Review queue
    </a>
@endsection

@section('content')
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="kpi-card pending">
            <div class="label">Pending</div>
            <div class="value">{{ $counts['pending'] }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="kpi-card active">
            <div class="label">Live</div>
            <div class="value">{{ $counts['active'] }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="kpi-card rejected">
            <div class="label">Rejected</div>
            <div class="value">{{ $counts['rejected'] }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="kpi-card">
            <div class="label">Total</div>
            <div class="value">{{ $counts['total'] }}</div>
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel-header">
        <h2>Pending review</h2>
        <a href="{{ route('employee.leads.index', ['status' => 'pending']) }}" class="small fw-semibold text-decoration-none" style="color: var(--t4d-primary);">
            View all
        </a>
    </div>

    @if ($pendingLeads->isEmpty())
        <div class="panel-body text-center text-muted py-5">
            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
            No leads waiting for review.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-emp align-middle mb-0">
                <thead>
                <tr>
                    <th>Company</th>
                    <th>Contact</th>
                    <th>Interest</th>
                    <th>Country</th>
                    <th>Submitted</th>
                    <th class="text-end">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($pendingLeads as $lead)
                    <tr>
                        <td class="fw-semibold">{{ $lead->company_name }}</td>
                        <td>
                            <div>{{ $lead->contact_name }}</div>
                            <div class="small text-muted">{{ $lead->email }}</div>
                        </td>
                        <td>{{ $lead->product_interest }}</td>
                        <td>{{ $lead->country }}</td>
                        <td class="small text-muted">{{ $lead->created_at?->diffForHumans() }}</td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('employee.leads.edit', $lead) }}" class="btn btn-sm btn-outline-secondary me-1">Edit</a>
                            <form action="{{ route('employee.leads.approve', $lead) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-approve me-1">Approve</button>
                            </form>
                            <form action="{{ route('employee.leads.reject', $lead) }}" method="POST" class="d-inline" onsubmit="return confirm('Reject this lead and notify the submitter?');">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-reject">Reject</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
