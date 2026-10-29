@extends('layouts.employee')

@section('title', 'Verification Team')
@section('page-title', 'Verification Team')

@section('topbar-actions')
    <a href="{{ route('verification.employee-team.create') }}" class="btn btn-sm btn-primary-t4d">
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
                <div class="panel-body text-muted">No verification employee logins yet.</div>
            @else
                <div class="list-group list-group-flush">
                    @foreach ($members as $row)
                        <a href="{{ route('verification.employee-team.index', ['member' => $row['user']->id]) }}"
                           class="list-group-item list-group-item-action {{ ($selected['user']->id ?? null) === $row['user']->id ? 'active' : '' }}">
                            <div class="fw-semibold">{{ $row['user']->name }}</div>
                            <div class="small opacity-75">{{ $row['user']->email }}</div>
                            <div class="small mt-1">
                                Assigned: {{ $row['counts']['assigned'] ?? 0 }}
                                · Verified: {{ $row['followUpCounts']['verified'] }}
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="col-lg-8">
        @if ($selected)
            <div class="row g-3 mb-3">
                <div class="col-6 col-lg-3">
                    <div class="kpi-card">
                        <div class="label">Unassigned pool</div>
                        <div class="value">{{ $unassignedCount }}</div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="kpi-card pending">
                        <div class="label">Assigned to {{ $selected['user']->name }}</div>
                        <div class="value">{{ $selected['counts']['assigned'] ?? 0 }}</div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="kpi-card active">
                        <div class="label">Submitted by them</div>
                        <div class="value">{{ $selected['counts']['total'] }}</div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="kpi-card" style="border-color: #198754;">
                        <div class="label">Verified</div>
                        <div class="value text-success">{{ $selected['followUpCounts']['verified'] }}</div>
                    </div>
                </div>
            </div>

            <div class="panel mb-3 assign-leads-panel">
                <div class="panel-header">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <h2 class="mb-0">Assign leads to {{ $selected['user']->name }}</h2>
                        <span class="assign-meta-pill">
                            <i class="bi bi-sort-down"></i> Oldest first
                        </span>
                    </div>
                    <span class="assign-pool-pill">
                        <i class="bi bi-inbox"></i>
                        Pool {{ $unassignedCount }}
                    </span>
                </div>
                <div class="panel-body assign-leads-body">
                    <form method="POST" action="{{ route('verification.employee-team.assign-leads') }}" class="assign-leads-bar">
                        @csrf
                        <input type="hidden" name="assignee_id" value="{{ $selected['user']->id }}">

                        <div class="assign-field assign-field-count">
                            <label for="count" class="assign-label">Quantity</label>
                            <input
                                type="number"
                                id="count"
                                name="count"
                                class="form-control assign-input"
                                min="1"
                                max="500"
                                value="{{ old('count', 20) }}"
                                required
                                aria-describedby="assign-count-hint"
                            >
                        </div>

                        <div class="assign-field assign-field-source">
                            <label for="from_assignee_id" class="assign-label">Source</label>
                            <select id="from_assignee_id" name="from_assignee_id" class="form-select assign-input">
                                <option value="">Unassigned pool ({{ $unassignedCount }})</option>
                                @foreach ($members as $row)
                                    @if ($row['user']->id !== $selected['user']->id)
                                        <option value="{{ $row['user']->id }}" @selected(old('from_assignee_id') === $row['user']->id)>
                                            {{ $row['user']->name }} — {{ $row['counts']['assigned'] ?? 0 }} assigned
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="assign-field assign-field-action">
                            <span class="assign-label assign-label-spacer" aria-hidden="true">&nbsp;</span>
                            <button type="submit" class="btn btn-primary-t4d assign-submit">
                                <i class="bi bi-person-check"></i>
                                Assign
                            </button>
                        </div>
                    </form>
                    <p id="assign-count-hint" class="assign-hint mb-0">
                        Assigns the oldest matching leads to <strong>{{ $selected['user']->name }}</strong>. Choose another teammate under Source to reassign.
                    </p>
                </div>
            </div>

            <style>
                .assign-leads-panel .panel-header {
                    background: linear-gradient(180deg, #F8FAFC 0%, #FFFFFF 100%);
                }

                .assign-meta-pill,
                .assign-pool-pill {
                    display: inline-flex;
                    align-items: center;
                    gap: 0.35rem;
                    font-size: 0.72rem;
                    font-weight: 700;
                    letter-spacing: 0.02em;
                    padding: 0.28rem 0.65rem;
                    border-radius: 999px;
                    border: 1px solid var(--t4d-border);
                    background: #fff;
                    color: var(--t4d-muted);
                    white-space: nowrap;
                }

                .assign-pool-pill {
                    color: var(--t4d-primary);
                    border-color: rgba(11, 58, 110, 0.18);
                    background: rgba(11, 58, 110, 0.05);
                }

                .assign-leads-body {
                    padding-top: 1rem;
                    padding-bottom: 1rem;
                }

                .assign-leads-bar {
                    display: grid;
                    grid-template-columns: 120px minmax(0, 1fr) auto;
                    gap: 0.85rem;
                    align-items: end;
                }

                .assign-field {
                    display: flex;
                    flex-direction: column;
                    gap: 0.4rem;
                    min-width: 0;
                }

                .assign-label {
                    margin: 0;
                    font-size: 0.72rem;
                    font-weight: 700;
                    letter-spacing: 0.05em;
                    text-transform: uppercase;
                    color: var(--t4d-muted);
                    line-height: 1;
                    height: 0.85rem;
                }

                .assign-label-spacer {
                    visibility: hidden;
                }

                .assign-input {
                    height: 42px;
                    border-radius: 10px;
                    border-color: var(--t4d-border);
                    font-size: 0.92rem;
                    font-weight: 600;
                    color: var(--t4d-dark);
                    box-shadow: none;
                }

                .assign-input:focus {
                    border-color: rgba(11, 58, 110, 0.45);
                    box-shadow: 0 0 0 3px rgba(11, 58, 110, 0.12);
                }

                .assign-field-count .assign-input {
                    text-align: center;
                    font-variant-numeric: tabular-nums;
                    font-size: 1rem;
                }

                .assign-submit {
                    height: 42px;
                    min-width: 132px;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    gap: 0.45rem;
                    padding: 0 1.15rem;
                    border-radius: 10px;
                    white-space: nowrap;
                }

                .assign-hint {
                    margin-top: 0.75rem;
                    font-size: 0.8rem;
                    color: var(--t4d-muted);
                    line-height: 1.45;
                }

                @media (max-width: 767.98px) {
                    .assign-leads-bar {
                        grid-template-columns: 1fr;
                        align-items: stretch;
                    }

                    .assign-field-action {
                        margin-top: 0.15rem;
                    }

                    .assign-submit {
                        width: 100%;
                    }
                }
            </style>

            <div class="d-flex justify-content-end gap-2 mb-3">
                <a href="{{ route('verification.employee-team.edit', $selected['user']) }}" class="btn btn-sm btn-outline-secondary">
                    Edit account
                </a>
                @include('partials.delete-staff-account-form', [
                    'action' => route('verification.employee-team.destroy', $selected['user']),
                    'name' => $selected['user']->name,
                ])
            </div>

            @include('partials.sourcing-submission-chart', [
                'chartId' => 'admin-verification-team-chart',
                'labels' => $selected['chartLabels'],
                'values' => $selected['chartSubmittedValues'],
                'verifiedValues' => $selected['chartVerifiedValues'],
                'title' => 'Submitted vs Verified (last 30 days)',
                'datasetLabel' => 'Submitted',
                'verifiedDatasetLabel' => 'Verified',
            ])

            @php
                $fu = $selected['followUpCounts'];
                $memberQuery = ['member' => $selected['user']->id];
            @endphp

            <div class="panel mb-3 mt-4">
                <ul class="nav status-tabs px-2 flex-wrap">
                    @foreach ([
                        'all' => ['label' => 'All assigned', 'count' => $selected['counts']['assigned'] ?? 0, 'badge' => 'light text-dark border'],
                        'verified' => ['label' => 'Verified', 'count' => $fu['verified'], 'badge' => 'success'],
                        'talk_later_7_days' => ['label' => 'Talk later', 'count' => $fu['talk_later_7_days'], 'badge' => 'warning text-dark'],
                        'not_connected' => ['label' => 'Not connected', 'count' => $fu['not_connected'], 'badge' => 'secondary'],
                        'next_month' => ['label' => 'Next month', 'count' => $fu['next_month'], 'badge' => 'info text-dark'],
                        'not_interested' => ['label' => 'Not interested', 'count' => $fu['not_interested'], 'badge' => 'dark'],
                        'invalid_number' => ['label' => 'Invalid number', 'count' => $fu['invalid_number'], 'badge' => 'danger'],
                        'overdue' => ['label' => 'Overdue', 'count' => $fu['overdue'], 'badge' => 'danger'],
                        'next_7_days' => ['label' => 'Due in 7 days', 'count' => $fu['next_7_days'], 'badge' => 'warning text-dark'],
                    ] as $tab => $meta)
                        <li class="nav-item">
                            <a class="nav-link {{ ($followUp ?? 'all') === $tab ? 'active' : '' }}"
                               href="{{ route('verification.employee-team.index', array_merge($memberQuery, ['follow_up' => $tab])) }}">
                                {{ $meta['label'] }}
                                <span class="badge text-bg-{{ $meta['badge'] }} ms-1">{{ $meta['count'] }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <h2>
                        @if (($followUp ?? 'all') === 'verified') Verified leads
                        @elseif (($followUp ?? 'all') === 'talk_later_7_days') Talk later
                        @elseif (($followUp ?? 'all') === 'not_connected') Not connected
                        @elseif (($followUp ?? 'all') === 'not_interested') Not interested
                        @elseif (($followUp ?? 'all') === 'invalid_number') Invalid number
                        @elseif (($followUp ?? 'all') === 'overdue') Overdue follow-ups
                        @elseif (($followUp ?? 'all') === 'next_7_days') Due in next 7 days
                        @elseif (($followUp ?? 'all') === 'next_month') Next month
                        @else All submitted leads
                        @endif
                        <span class="small text-muted fw-normal">— {{ $selected['user']->name }}</span>
                    </h2>
                    <span class="small text-muted">{{ $memberLeads?->total() ?? 0 }} result{{ ($memberLeads?->total() ?? 0) === 1 ? '' : 's' }}</span>
                </div>

                @if ($memberLeads === null || $memberLeads->isEmpty())
                    <div class="panel-body text-center text-muted py-4">
                        No leads for this filter.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-emp align-middle mb-0">
                            <thead>
                            <tr>
                                <th>Company</th>
                                <th>Contact</th>
                                <th>Call status</th>
                                <th>Follow-up</th>
                                <th>Submitted</th>
                                <th class="text-end">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($memberLeads as $lead)
                                <tr>
                                    <td class="fw-semibold">{{ $lead->company_name }}</td>
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
                                    <td class="small {{ $lead->call_follow_up_at?->lt(now()->startOfDay()) ? 'text-danger fw-semibold' : 'text-muted' }}">
                                        {{ $lead->call_follow_up_at?->format('M j, Y') ?? '—' }}
                                    </td>
                                    <td class="small text-muted">{{ $lead->created_at?->format('M j, Y') }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('verification.leads.edit', $lead) }}" class="btn btn-sm btn-outline-secondary">Open</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="panel-body border-top">
                        {{ $memberLeads->links() }}
                    </div>
                @endif
            </div>
        @else
            <div class="panel">
                <div class="panel-body text-muted">Create a verification employee login to start tracking submissions.</div>
            </div>
        @endif
    </div>
</div>
@endsection
