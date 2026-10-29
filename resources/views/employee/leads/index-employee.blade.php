@extends('layouts.employee')

@section('title', 'My Leads')
@section('page-title', 'My Assigned Leads')

@section('topbar-actions')
    <a href="{{ route('verification.leads.create') }}" class="btn btn-sm btn-primary-t4d">
        <i class="bi bi-plus-lg"></i> Submit lead
    </a>
@endsection

@section('content')
@php
    $queryParams = array_filter([
        'name' => $filters['name'] ?? null,
        'product_type' => $filters['product_type'] ?? null,
        'country' => $filters['country'] ?? null,
        'call_status' => $filters['call_status'] ?? null,
        'per_page' => $perPage !== 10 ? $perPage : null,
        'q' => $search,
    ], fn ($value) => $value !== null && $value !== '');
@endphp

<div class="panel mb-3">
    <ul class="nav status-tabs px-2 flex-wrap">
        @foreach ([
            'all' => ['label' => 'All assigned', 'count' => $submissionCounts['assigned'] ?? $submissionCounts['total'], 'badge' => 'light text-dark border'],
            'verified' => ['label' => 'Verified', 'count' => $followUpCounts['verified'], 'badge' => 'success'],
            'talk_later_7_days' => ['label' => 'Talk later', 'count' => $followUpCounts['talk_later_7_days'], 'badge' => 'warning text-dark'],
            'not_connected' => ['label' => 'Not connected', 'count' => $followUpCounts['not_connected'], 'badge' => 'secondary'],
            'next_month' => ['label' => 'Next month', 'count' => $followUpCounts['next_month'], 'badge' => 'info text-dark'],
            'not_interested' => ['label' => 'Not interested', 'count' => $followUpCounts['not_interested'], 'badge' => 'dark'],
            'invalid_number' => ['label' => 'Invalid number', 'count' => $followUpCounts['invalid_number'], 'badge' => 'danger'],
            'overdue' => ['label' => 'Overdue', 'count' => $followUpCounts['overdue'], 'badge' => 'danger'],
            'next_7_days' => ['label' => 'Due in 7 days', 'count' => $followUpCounts['next_7_days'], 'badge' => 'warning text-dark'],
        ] as $tab => $meta)
            <li class="nav-item">
                <a class="nav-link {{ $followUp === $tab ? 'active' : '' }}"
                   href="{{ route('verification.leads.index', array_merge(['follow_up' => $tab], $queryParams)) }}">
                    {{ $meta['label'] }}
                    <span class="badge text-bg-{{ $meta['badge'] }} ms-1">{{ $meta['count'] }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</div>

<div class="panel mb-3">
    <div class="panel-header">
        <h2>Filters</h2>
        @if (! empty(array_filter($filters)) || $search)
            <a href="{{ route('verification.leads.index', ['follow_up' => $followUp]) }}" class="small fw-semibold text-decoration-none" style="color: var(--t4d-primary);">
                Clear filters
            </a>
        @endif
    </div>
    <div class="panel-body">
        <form method="GET" action="{{ route('verification.leads.index') }}" class="row g-3 align-items-end">
            <input type="hidden" name="follow_up" value="{{ $followUp }}">

            <div class="col-md-3">
                <label for="name" class="form-label">Company / contact</label>
                <input type="search" id="name" name="name" value="{{ $filters['name'] }}"
                       class="form-control form-control-sm" placeholder="Search by name…">
            </div>

            <div class="col-md-3">
                <label for="call_status" class="form-label">Call status</label>
                <select id="call_status" name="call_status" class="form-select form-select-sm">
                    <option value="">All call statuses</option>
                    @foreach (\App\Support\Enums\LeadCallStatus::cases() as $callStatus)
                        <option value="{{ $callStatus->value }}" @selected(($filters['call_status'] ?? '') === $callStatus->value)>
                            {{ $callStatus->label() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label for="product_type" class="form-label">Category</label>
                <select id="product_type" name="product_type" class="form-select form-select-sm">
                    <option value="">All categories</option>
                    @foreach (\App\Support\Enums\ProductType::cases() as $type)
                        <option value="{{ $type->value }}" @selected(($filters['product_type'] ?? '') === $type->value)>
                            {{ $type->label() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label for="country" class="form-label">Country</label>
                <input type="text" id="country" name="country" value="{{ $filters['country'] }}"
                       class="form-control form-control-sm" placeholder="Country">
            </div>

            <div class="col-md-2">
                <label for="per_page" class="form-label">Per page</label>
                <select id="per_page" name="per_page" class="form-select form-select-sm">
                    @foreach ([10, 15, 25, 50] as $size)
                        <option value="{{ $size }}" @selected($perPage === $size)>{{ $size }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary-t4d">Apply filters</button>
            </div>
        </form>
    </div>
</div>

<div class="panel">
    <div class="panel-header">
        <h2>
            @if ($followUp === 'verified') Verified leads
            @elseif ($followUp === 'talk_later_7_days') Talk later (next 7 days)
            @elseif ($followUp === 'not_connected') Not connected
            @elseif ($followUp === 'not_interested') Not interested
            @elseif ($followUp === 'invalid_number') Invalid number
            @elseif ($followUp === 'overdue') Overdue follow-ups
            @elseif ($followUp === 'next_7_days') Due in next 7 days
            @elseif ($followUp === 'next_month') Next month
            @else All assigned leads
            @endif
        </h2>
        <span class="small text-muted">{{ $leads->total() }} result{{ $leads->total() === 1 ? '' : 's' }}</span>
    </div>

    @if ($leads->isEmpty())
        <div class="panel-body text-center text-muted py-5">
            No leads found for this filter.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-emp align-middle mb-0">
                <thead>
                <tr>
                    <th>Company</th>
                    <th>Contact</th>
                    <th>Country</th>
                    <th>Product</th>
                    <th>Moderation</th>
                    <th>Call status</th>
                    <th>Follow-up</th>
                    <th>Submitted</th>
                    <th class="text-end">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($leads as $lead)
                    @php
                        $statusLabel = $lead->status === \App\Support\Enums\RecordStatus::Inactive
                            ? 'Rejected'
                            : $lead->status->label();
                    @endphp
                    <tr>
                        <td class="fw-semibold">{{ $lead->company_name }}</td>
                        <td>
                            <div>{{ $lead->contact_name }}</div>
                            <div class="small text-muted">{{ $lead->email }}</div>
                            @if (filled($lead->phone))
                                <div class="d-flex align-items-center gap-2 mt-1">
                                    <span class="small">{{ $lead->phone }}</span>
                                    <a href="tel:{{ preg_replace('/[^\d+]/', '', $lead->phone) }}"
                                       class="btn btn-sm btn-outline-success py-0 px-1"
                                       title="Call {{ $lead->phone }}">
                                        <i class="bi bi-telephone-fill"></i>
                                    </a>
                                </div>
                            @endif
                        </td>
                        <td class="small">{{ $lead->country }}</td>
                        <td>
                            <div>{{ $lead->product_type?->label() ?? '—' }}</div>
                            <div class="small text-muted">{{ $lead->product_interest }}</div>
                        </td>
                        <td><span class="badge {{ $lead->status->badgeClass() }}">{{ $statusLabel }}</span></td>
                        <td style="min-width: 210px;">
                            @if ($lead->call_status)
                                <span class="badge {{ $lead->call_status->badgeClass() }} mb-1">{{ $lead->call_status->label() }}</span>
                            @else
                                <span class="badge text-bg-light text-dark border mb-1">Not set</span>
                            @endif
                            <form method="POST" action="{{ route('verification.leads.call-status', $lead) }}" class="d-flex gap-1 align-items-center">
                                @csrf
                                @method('PATCH')
                                <select name="call_status" class="form-select form-select-sm" style="min-width: 160px;" onchange="this.form.submit()">
                                    <option value="">Not set</option>
                                    @foreach (\App\Support\Enums\LeadCallStatus::cases() as $callStatus)
                                        <option value="{{ $callStatus->value }}" @selected($lead->call_status === $callStatus)>
                                            {{ $callStatus->label() }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                        <td class="small {{ $lead->call_follow_up_at?->lt(now()->startOfDay()) ? 'text-danger fw-semibold' : 'text-muted' }}">
                            {{ $lead->call_follow_up_at?->format('M j, Y') ?? '—' }}
                        </td>
                        <td class="small text-muted">{{ $lead->created_at?->format('M j, Y') }}</td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('verification.leads.edit', $lead) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="panel-body border-top d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="small text-muted">
                Showing {{ $leads->firstItem() }}–{{ $leads->lastItem() }} of {{ $leads->total() }} leads
            </div>
            {{ $leads->links() }}
        </div>
    @endif
</div>
@endsection
