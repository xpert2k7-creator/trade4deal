@extends('layouts.sourcing')

@section('title', 'My submissions')
@section('page-title', 'My submissions')

@section('topbar-actions')
    <a href="{{ route('sourcing.leads.create') }}" class="btn btn-sm btn-primary-t4d">
        <i class="bi bi-plus-lg"></i> Add buy lead
    </a>
@endsection

@section('content')
<div class="panel mb-3">
    <ul class="nav status-tabs px-2">
        @foreach (['pending' => 'Pending review', 'active' => 'Live', 'rejected' => 'Rejected', 'all' => 'All'] as $tab => $label)
            <li class="nav-item">
                <a class="nav-link {{ $status === $tab ? 'active' : '' }}"
                   href="{{ route('sourcing.leads.index', ['status' => $tab, 'per_page' => $perPage !== 10 ? $perPage : null]) }}">
                    {{ $label }}
                </a>
            </li>
        @endforeach
    </ul>
</div>

<div class="panel">
    <div class="panel-header">
        <h2>Your buy leads</h2>
        <span class="small text-muted">{{ $leads->total() }} result{{ $leads->total() === 1 ? '' : 's' }}</span>
    </div>

    @if ($leads->isEmpty())
        <div class="panel-body text-center text-muted py-5">No submissions for this filter.</div>
    @else
        <div class="table-responsive">
            <table class="table table-emp align-middle mb-0">
                <thead>
                <tr>
                    <th>Company</th>
                    <th>Contact</th>
                    <th>Product</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Submitted</th>
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
                            <div class="small text-muted">{{ $lead->phone }} · {{ $lead->email }}</div>
                        </td>
                        <td>
                            <div>{{ $lead->product_type?->label() ?? '—' }}</div>
                            <div class="small text-muted">{{ $lead->product_interest }}</div>
                        </td>
                        <td class="small">
                            @if ($lead->city)
                                {{ $lead->city }}, {{ $lead->country }}
                            @else
                                {{ $lead->country }}
                            @endif
                        </td>
                        <td><span class="badge {{ $lead->status->badgeClass() }}">{{ $statusLabel }}</span></td>
                        <td class="small text-muted">{{ $lead->created_at?->format('M j, Y') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="panel-body border-top">
            {{ $leads->links() }}
        </div>
    @endif
</div>
@endsection
