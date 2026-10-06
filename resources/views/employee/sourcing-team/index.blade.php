@extends('layouts.employee')

@section('title', 'Sourcing team')
@section('page-title', 'Sourcing team')

@section('topbar-actions')
    <a href="{{ route('verification.sourcing-team.create') }}" class="btn btn-sm btn-primary-t4d">
        <i class="bi bi-person-plus"></i> Create login
    </a>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="panel">
            <div class="panel-header">
                <h2>Team members</h2>
            </div>
            @if ($members->isEmpty())
                <div class="panel-body text-muted">No sourcing logins yet.</div>
            @else
                <div class="list-group list-group-flush">
                    @foreach ($members as $row)
                        <a href="{{ route('verification.sourcing-team.index', ['member' => $row['user']->id]) }}"
                           class="list-group-item list-group-item-action {{ ($selected['user']->id ?? null) === $row['user']->id ? 'active' : '' }}">
                            <div class="fw-semibold">{{ $row['user']->name }}</div>
                            <div class="small opacity-75">{{ $row['user']->email }}</div>
                            <div class="small mt-1">Total leads: {{ $row['counts']['total'] }}</div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="col-lg-8">
        @if ($selected)
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="kpi-card">
                        <div class="label">Today</div>
                        <div class="value">{{ $selected['counts']['today'] }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="kpi-card active">
                        <div class="label">This month</div>
                        <div class="value">{{ $selected['counts']['month'] }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="kpi-card pending">
                        <div class="label">Total</div>
                        <div class="value">{{ $selected['counts']['total'] }}</div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mb-3">
                <a href="{{ route('verification.sourcing-team.edit', $selected['user']) }}" class="btn btn-sm btn-outline-secondary">
                    Edit account
                </a>
                @include('partials.delete-staff-account-form', [
                    'action' => route('verification.sourcing-team.destroy', $selected['user']),
                    'name' => $selected['user']->name,
                ])
            </div>

            @include('partials.sourcing-submission-chart', [
                'chartId' => 'admin-sourcing-chart',
                'labels' => $selected['chartLabels'],
                'values' => $selected['chartValues'],
            ])
        @else
            <div class="panel">
                <div class="panel-body text-muted">Create a sourcing login to start tracking submissions.</div>
            </div>
        @endif
    </div>
</div>
@endsection
