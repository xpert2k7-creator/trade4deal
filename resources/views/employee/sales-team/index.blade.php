@extends('layouts.employee')

@section('title', 'Sales team')
@section('page-title', 'Sales team')

@section('topbar-actions')
    <a href="{{ route('verification.sales-team.create') }}" class="btn btn-sm btn-primary-t4d">
        <i class="bi bi-person-plus"></i> Create login
    </a>
    <a href="{{ route('sales.onboarding.index', ['status' => 'pending']) }}" class="btn btn-sm btn-outline-secondary">
        Open sales panel
    </a>
@endsection

@section('content')
<div class="panel">
    <div class="panel-header">
        <h2>Sales logins</h2>
    </div>
    @if ($members->isEmpty())
        <div class="panel-body text-muted text-center py-5">
            No sales logins yet. Create one to review new seller signups.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-emp align-middle mb-0">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($members as $member)
                    <tr>
                        <td class="fw-semibold">{{ $member->name }}</td>
                        <td>{{ $member->email }}</td>
                        <td>
                            <span class="badge {{ $member->status?->badgeClass() ?? 'bg-secondary' }}">
                                {{ $member->status?->label() ?? '—' }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('verification.sales-team.edit', $member) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
