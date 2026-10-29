@extends('layouts.employee')

@section('title', 'My Leads')
@section('page-title', 'My Leads Dashboard')

@section('topbar-actions')
    <a href="{{ route('verification.leads.create') }}" class="btn btn-sm btn-outline-secondary me-1">
        <i class="bi bi-plus-lg"></i> Submit lead
    </a>
    <a href="{{ route('verification.leads.index', ['follow_up' => 'all']) }}" class="btn btn-primary-t4d btn-sm">
        My leads
    </a>
@endsection

@section('content')
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="kpi-card">
            <div class="label">Submitted today</div>
            <div class="value">{{ $counts['today'] }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="kpi-card active">
            <div class="label">This month</div>
            <div class="value">{{ $counts['month'] }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="kpi-card pending">
            <div class="label">Assigned to me</div>
            <div class="value">{{ $counts['assigned'] ?? $counts['total'] }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="kpi-card rejected">
            <div class="label">Follow-ups due</div>
            <div class="value">{{ $followUpCounts['overdue'] + $followUpCounts['next_7_days'] }}</div>
        </div>
    </div>
</div>

<div class="row g-3 mb-2">
    <div class="col-12">
        <div class="small text-muted">You submitted {{ $counts['total'] }} lead{{ $counts['total'] === 1 ? '' : 's' }} yourself (auto-assigned to you).</div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <a href="{{ route('verification.leads.index', ['follow_up' => 'verified']) }}" class="text-decoration-none">
            <div class="panel h-100">
                <div class="panel-body">
                    <div class="small text-muted fw-semibold">Verified</div>
                    <div class="fs-3 fw-bold text-success">{{ $followUpCounts['verified'] }}</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-lg-3">
        <a href="{{ route('verification.leads.index', ['follow_up' => 'overdue']) }}" class="text-decoration-none">
            <div class="panel h-100">
                <div class="panel-body">
                    <div class="small text-muted fw-semibold">Overdue follow-ups</div>
                    <div class="fs-3 fw-bold text-danger">{{ $followUpCounts['overdue'] }}</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-lg-3">
        <a href="{{ route('verification.leads.index', ['follow_up' => 'next_7_days']) }}" class="text-decoration-none">
            <div class="panel h-100">
                <div class="panel-body">
                    <div class="small text-muted fw-semibold">Next 7 days</div>
                    <div class="fs-3 fw-bold text-warning">{{ $followUpCounts['next_7_days'] }}</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-lg-3">
        <a href="{{ route('verification.leads.index', ['follow_up' => 'next_month']) }}" class="text-decoration-none">
            <div class="panel h-100">
                <div class="panel-body">
                    <div class="small text-muted fw-semibold">Next month</div>
                    <div class="fs-3 fw-bold text-info">{{ $followUpCounts['next_month'] }}</div>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="panel mb-4">
    <div class="panel-header">
        <h2>Follow-ups by date</h2>
        <a href="{{ route('verification.leads.index', ['follow_up' => 'with_follow_up']) }}" class="small fw-semibold text-decoration-none" style="color: var(--t4d-primary);">
            View all
        </a>
    </div>

    @if ($followUps->isEmpty())
        <div class="panel-body text-center text-muted py-5">
            No follow-up dates set yet. Update call status on a lead to schedule one.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-emp align-middle mb-0">
                <thead>
                <tr>
                    <th>Follow-up date</th>
                    <th>Company</th>
                    <th>Contact</th>
                    <th>Call status</th>
                    <th class="text-end">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($followUps as $lead)
                    @php
                        $isOverdue = $lead->call_follow_up_at?->lt(now()->startOfDay());
                    @endphp
                    <tr>
                        <td class="fw-semibold {{ $isOverdue ? 'text-danger' : '' }}">
                            {{ $lead->call_follow_up_at?->format('M j, Y') }}
                            @if ($isOverdue)
                                <span class="badge text-bg-danger ms-1">Overdue</span>
                            @endif
                        </td>
                        <td>{{ $lead->company_name }}</td>
                        <td>
                            <div>{{ $lead->contact_name }}</div>
                            @if (filled($lead->phone))
                                <div class="d-flex align-items-center gap-2 mt-1">
                                    <span class="small text-muted">{{ $lead->phone }}</span>
                                    <a href="tel:{{ preg_replace('/[^\d+]/', '', $lead->phone) }}"
                                       class="btn btn-sm btn-outline-success py-0 px-1"
                                       title="Call {{ $lead->phone }}">
                                        <i class="bi bi-telephone-fill"></i>
                                    </a>
                                </div>
                            @endif
                        </td>
                        <td>
                            @if ($lead->call_status)
                                <span class="badge {{ $lead->call_status->badgeClass() }}">{{ $lead->call_status->label() }}</span>
                            @else
                                <span class="badge text-bg-light text-dark border">Not set</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('verification.leads.edit', $lead) }}" class="btn btn-sm btn-outline-secondary">Open</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<div class="panel">
    <div class="panel-header">
        <h2>My recent submissions</h2>
        <a href="{{ route('verification.leads.index', ['follow_up' => 'all']) }}" class="small fw-semibold text-decoration-none" style="color: var(--t4d-primary);">
            View all
        </a>
    </div>

    @if ($recentLeads->isEmpty())
        <div class="panel-body text-center text-muted py-5">
            You have not submitted any leads yet.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-emp align-middle mb-0">
                <thead>
                <tr>
                    <th>Company</th>
                    <th>Contact</th>
                    <th>Interest</th>
                    <th>Call status</th>
                    <th>Submitted</th>
                    <th class="text-end">Actions</th>
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
                        <td>{{ $lead->product_interest }}</td>
                        <td>
                            @if ($lead->call_status)
                                <span class="badge {{ $lead->call_status->badgeClass() }}">{{ $lead->call_status->label() }}</span>
                            @else
                                <span class="badge text-bg-light text-dark border">Not set</span>
                            @endif
                        </td>
                        <td class="small text-muted">{{ $lead->created_at?->diffForHumans() }}</td>
                        <td class="text-end">
                            <a href="{{ route('verification.leads.edit', $lead) }}" class="btn btn-sm btn-outline-secondary">Open</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
