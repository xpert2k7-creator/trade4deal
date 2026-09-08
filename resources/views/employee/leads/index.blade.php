@extends('layouts.employee')

@section('title', 'Leads')
@section('page-title', 'Lead Queue')

@section('topbar-actions')
    <a href="{{ route('employee.leads.create') }}" class="btn btn-sm btn-primary-t4d">
        <i class="bi bi-plus-lg"></i> Submit lead
    </a>
@endsection

@section('content')
@php
    $queryParams = array_filter([
        'name' => $filters['name'] ?? null,
        'product_type' => $filters['product_type'] ?? null,
        'country' => $filters['country'] ?? null,
        'date_from' => $filters['date_from'] ?? null,
        'date_to' => $filters['date_to'] ?? null,
        'per_page' => $perPage !== 10 ? $perPage : null,
        'q' => $search,
    ], fn ($value) => $value !== null && $value !== '');
@endphp

<div class="panel mb-3">
    <ul class="nav status-tabs px-2">
        @foreach (['pending' => 'Pending', 'active' => 'Live', 'rejected' => 'Rejected', 'all' => 'All'] as $tab => $label)
            <li class="nav-item">
                <a class="nav-link {{ $status === $tab ? 'active' : '' }}"
                   href="{{ route('employee.leads.index', array_merge(['status' => $tab], $queryParams)) }}">
                    {{ $label }}
                    @if ($tab !== 'all')
                        <span class="badge text-bg-{{ $tab === 'pending' ? 'warning text-dark' : ($tab === 'active' ? 'success' : 'secondary') }} ms-1">
                            {{ $counts[$tab] }}
                        </span>
                    @else
                        <span class="badge text-bg-light text-dark border ms-1">{{ $counts['total'] }}</span>
                    @endif
                </a>
            </li>
        @endforeach
    </ul>
</div>

<div class="panel mb-3">
    <div class="panel-header">
        <h2>Filters</h2>
        @if (! empty(array_filter($filters)) || $search)
            <a href="{{ route('employee.leads.index', ['status' => $status]) }}" class="small fw-semibold text-decoration-none" style="color: var(--t4d-primary);">
                Clear filters
            </a>
        @endif
    </div>
    <div class="panel-body">
        <form method="GET" action="{{ route('employee.leads.index') }}" class="row g-3 align-items-end">
            <input type="hidden" name="status" value="{{ $status }}">

            <div class="col-md-3">
                <label for="name" class="form-label">Company / contact</label>
                <input type="search" id="name" name="name" value="{{ $filters['name'] }}"
                       class="form-control form-control-sm" placeholder="Search by name…">
            </div>

            <div class="col-md-3">
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
                       class="form-control form-control-sm" placeholder="Country" list="country-options">
                <datalist id="country-options">
                    @foreach ($countries as $countryOption)
                        <option value="{{ $countryOption }}"></option>
                    @endforeach
                </datalist>
            </div>

            <div class="col-md-2">
                <label for="date_from" class="form-label">From date</label>
                <input type="date" id="date_from" name="date_from" value="{{ $filters['date_from'] }}"
                       class="form-control form-control-sm">
            </div>

            <div class="col-md-2">
                <label for="date_to" class="form-label">To date</label>
                <input type="date" id="date_to" name="date_to" value="{{ $filters['date_to'] }}"
                       class="form-control form-control-sm">
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
            @if ($status === 'pending') Pending leads
            @elseif ($status === 'active') Live leads
            @elseif ($status === 'rejected') Rejected leads
            @else All leads
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
                    <th>Business</th>
                    <th>Product</th>
                    <th>Trade</th>
                    <th>Status</th>
                    <th>Submitted</th>
                    @if ($status === 'active' || $status === 'all')
                        <th>Published</th>
                    @endif
                    <th class="text-end">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($leads as $lead)
                    @php
                        $statusLabel = $lead->status === \App\Support\Enums\RecordStatus::Inactive
                            ? 'Rejected'
                            : $lead->status->label();
                        $badge = $lead->status->badgeClass();
                    @endphp
                    <tr>
                        <td class="fw-semibold">{{ $lead->company_name }}</td>
                        <td>
                            <div>{{ $lead->contact_name }}</div>
                            <div class="small text-muted">{{ $lead->email }}</div>
                        </td>
                        <td class="small">{{ $lead->country }}</td>
                        <td>{{ $lead->business_type?->label() }}</td>
                        <td>
                            <div>{{ $lead->product_type?->label() ?? '—' }}</div>
                            <div class="small text-muted">{{ $lead->product_interest }}</div>
                        </td>
                        <td class="small">
                            @if ($lead->currency && $lead->units)
                                {{ $lead->currency->value }} / {{ $lead->units->label() }}
                            @else
                                —
                            @endif
                        </td>
                        <td><span class="badge {{ $badge }}">{{ $statusLabel }}</span></td>
                        <td class="small text-muted">{{ $lead->created_at?->format('M j, Y') }}</td>
                        @if ($status === 'active' || $status === 'all')
                            <td class="small text-muted">{{ $lead->published_at?->format('M j, Y g:i A') ?? '—' }}</td>
                        @endif
                        <td class="text-end text-nowrap">
                            <a href="{{ route('employee.leads.edit', $lead) }}" class="btn btn-sm btn-outline-secondary me-1">Edit</a>
                            @if ($lead->status === \App\Support\Enums\RecordStatus::Pending)
                                <form action="{{ route('employee.leads.approve', $lead) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-approve me-1">Approve</button>
                                </form>
                                <form action="{{ route('employee.leads.reject', $lead) }}" method="POST" class="d-inline" onsubmit="return confirm('Reject this lead?');">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-reject">Reject</button>
                                </form>
                            @elseif ($lead->status !== \App\Support\Enums\RecordStatus::Active)
                                <form action="{{ route('employee.leads.approve', $lead) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-approve">Publish</button>
                                </form>
                            @endif
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
