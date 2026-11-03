@extends('layouts.sales')

@section('title', 'Seller onboarding')
@section('page-title', 'Seller onboarding')

@section('content')
<div class="panel mb-3">
    <div class="panel-header flex-wrap gap-2">
        <h2>Sellers awaiting review</h2>
        <form method="GET" action="{{ route('sales.onboarding.index') }}" class="d-flex gap-2">
            <input type="hidden" name="status" value="{{ $statusFilter }}">
            <input type="search" name="q" value="{{ $search }}" class="form-control form-control-sm" placeholder="Search name, email, company…" style="min-width:220px;">
            <button type="submit" class="btn btn-sm btn-primary-t4d">Search</button>
        </form>
    </div>

    <div class="panel-body border-bottom py-2">
        <ul class="nav nav-pills status-tabs gap-1 flex-wrap">
            @foreach ([
                'pending' => 'Pending',
                'inactive' => 'Declined',
                'active' => 'Approved',
                'all' => 'All',
            ] as $key => $label)
                <li class="nav-item">
                    <a class="nav-link {{ $statusFilter === $key ? 'active' : '' }}"
                       href="{{ route('sales.onboarding.index', array_filter(['status' => $key, 'q' => $search])) }}">
                        {{ $label }}
                        <span class="badge bg-light text-dark ms-1">{{ $counts[$key] ?? 0 }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    @if ($sellers->isEmpty())
        <div class="panel-body text-center text-muted py-5">
            No sellers found for this filter.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-emp align-middle mb-0">
                <thead>
                <tr>
                    <th>User</th>
                    <th>Type</th>
                    <th>Company</th>
                    <th>Plan</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($sellers as $seller)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $seller->name }}</div>
                            <div class="small text-muted">{{ $seller->email }}</div>
                        </td>
                        <td>{{ $seller->user_type?->label() }}</td>
                        <td>{{ $seller->company_name ?? '—' }}</td>
                        <td>
                            <span class="badge {{ $seller->plan?->badgeClass() ?? 'bg-secondary' }}">
                                {{ $seller->plan?->label() ?? 'Free' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $seller->status?->badgeClass() ?? 'bg-secondary' }}">
                                {{ $seller->status?->label() ?? '—' }}
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="d-flex flex-wrap gap-2 justify-content-end align-items-center">
                                <a href="{{ route('sales.onboarding.edit', $seller) }}" class="btn btn-sm btn-outline-secondary">Edit</a>

                                @if ($seller->status !== \App\Support\Enums\RecordStatus::Active)
                                    <form method="POST" action="{{ route('sales.onboarding.approve', $seller) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-approve">Approve</button>
                                    </form>
                                @endif

                                @if ($seller->status !== \App\Support\Enums\RecordStatus::Inactive)
                                    <form method="POST" action="{{ route('sales.onboarding.disapprove', $seller) }}" class="d-inline" onsubmit="return confirm('Decline {{ $seller->name }}? The account will stay in the system as inactive.');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Decline</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="panel-body border-top">
            {{ $sellers->links() }}
        </div>
    @endif
</div>
@endsection
