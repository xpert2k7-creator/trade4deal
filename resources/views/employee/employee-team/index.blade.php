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
                        <a href="{{ route('verification.employee-team.index', ['member' => $row['user']->id, 'period' => $period ?? 'month']) }}"
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

            @php
                $activePeriod = $period ?? 'month';
                $periodLabel = $workProgress['periodLabel'] ?? 'This month';
                $granularity = $workProgress['granularity'] ?? 'day';
                $columnLabel = $workProgress['columnLabel'] ?? 'Date';
                $memberBaseQuery = ['member' => $selected['user']->id, 'period' => $activePeriod];
            @endphp

            <div class="work-period-filter mb-3">
                <div class="work-period-label">Work period</div>
                <div class="work-period-tabs">
                    @foreach ([
                        'day' => 'Day',
                        'month' => 'Month',
                        'year' => 'Year',
                    ] as $periodKey => $periodName)
                        <a href="{{ route('verification.employee-team.index', array_merge($memberBaseQuery, ['period' => $periodKey, 'follow_up' => $followUp ?? 'all'])) }}"
                           class="work-period-tab {{ $activePeriod === $periodKey ? 'active' : '' }}">
                            {{ $periodName }}
                        </a>
                    @endforeach
                </div>
            </div>

            @include('partials.sourcing-submission-chart', [
                'chartId' => 'admin-verification-team-chart',
                'labels' => $selected['chartLabels'] ?? [],
                'values' => $selected['chartSubmittedValues'] ?? [],
                'verifiedValues' => $selected['chartVerifiedValues'] ?? [],
                'title' => 'Submitted vs Verified — '.$periodLabel,
                'datasetLabel' => 'Submitted',
                'verifiedDatasetLabel' => 'Verified',
            ])

            @php
                $dailyLabels = $selected['chartLabels'] ?? [];
                $dailySubmitted = $selected['chartSubmittedValues'] ?? [];
                $dailyVerified = $selected['chartVerifiedValues'] ?? [];
                $dailyRows = [];
                $totalSubmitted = 0;
                $totalVerified = 0;

                foreach ($dailyLabels as $i => $day) {
                    $submitted = (int) ($dailySubmitted[$i] ?? 0);
                    $verified = (int) ($dailyVerified[$i] ?? 0);
                    $totalSubmitted += $submitted;
                    $totalVerified += $verified;
                    $dailyRows[] = [
                        'day' => $day,
                        'submitted' => $submitted,
                        'verified' => $verified,
                        'total' => $submitted + $verified,
                    ];
                }

                $dailyRows = array_reverse($dailyRows);
            @endphp

            <div class="panel mb-3 mt-3 daily-progress-panel">
                <div class="panel-header">
                    <h2>Work progress — {{ $selected['user']->name }}</h2>
                    <span class="small text-muted">{{ $periodLabel }} · newest first</span>
                </div>
                <div class="table-responsive daily-progress-scroll">
                    <table class="table table-emp align-middle mb-0 daily-progress-table">
                        <thead>
                            <tr>
                                <th>{{ $columnLabel }}</th>
                                <th class="text-end">Submitted</th>
                                <th class="text-end">Verified</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dailyRows as $row)
                                <tr class="{{ $row['total'] > 0 ? 'daily-progress-active' : 'daily-progress-empty' }}">
                                    <td class="fw-semibold text-nowrap">
                                        @if ($granularity === 'month')
                                            {{ \Illuminate\Support\Carbon::createFromFormat('Y-m', $row['day'])->format('M Y') }}
                                        @else
                                            {{ \Illuminate\Support\Carbon::parse($row['day'])->format('D, M j, Y') }}
                                        @endif
                                    </td>
                                    <td class="text-end tabular-nums">{{ $row['submitted'] }}</td>
                                    <td class="text-end tabular-nums">
                                        @if ($row['verified'] > 0)
                                            <span class="daily-verified-count">{{ $row['verified'] }}</span>
                                        @else
                                            {{ $row['verified'] }}
                                        @endif
                                    </td>
                                    <td class="text-end tabular-nums fw-semibold">{{ $row['total'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">No activity in this period.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if (count($dailyRows) > 0)
                            <tfoot>
                                <tr>
                                    <th>Total</th>
                                    <th class="text-end tabular-nums">{{ $totalSubmitted }}</th>
                                    <th class="text-end tabular-nums">{{ $totalVerified }}</th>
                                    <th class="text-end tabular-nums">{{ $totalSubmitted + $totalVerified }}</th>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>

            <style>
                .work-period-filter {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    gap: 0.75rem;
                    flex-wrap: wrap;
                }

                .work-period-label {
                    font-size: 0.72rem;
                    font-weight: 700;
                    letter-spacing: 0.05em;
                    text-transform: uppercase;
                    color: var(--t4d-muted);
                }

                .work-period-tabs {
                    display: inline-flex;
                    gap: 0.35rem;
                    padding: 0.25rem;
                    border: 1px solid var(--t4d-border);
                    border-radius: 999px;
                    background: #fff;
                }

                .work-period-tab {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    min-width: 4.5rem;
                    padding: 0.4rem 0.85rem;
                    border-radius: 999px;
                    font-size: 0.82rem;
                    font-weight: 700;
                    color: var(--t4d-muted);
                    text-decoration: none;
                    transition: background 0.15s ease, color 0.15s ease;
                }

                .work-period-tab:hover {
                    color: var(--t4d-primary);
                    background: rgba(11, 58, 110, 0.06);
                }

                .work-period-tab.active {
                    background: var(--t4d-primary);
                    color: #fff;
                }

                .daily-progress-scroll {
                    max-height: 360px;
                    overflow-y: auto;
                }

                .daily-progress-table thead th,
                .daily-progress-table tfoot th {
                    position: sticky;
                    background: #fff;
                    z-index: 1;
                }

                .daily-progress-table thead th {
                    top: 0;
                    box-shadow: inset 0 -1px 0 var(--t4d-border);
                }

                .daily-progress-table tfoot th {
                    bottom: 0;
                    box-shadow: inset 0 1px 0 var(--t4d-border);
                    background: #F8FAFC;
                    font-size: 0.78rem;
                    text-transform: uppercase;
                    letter-spacing: 0.04em;
                    color: var(--t4d-muted);
                }

                .daily-progress-table .tabular-nums {
                    font-variant-numeric: tabular-nums;
                }

                .daily-progress-empty td {
                    color: #94A3B8;
                }

                .daily-progress-active td {
                    color: var(--t4d-dark);
                }

                .daily-verified-count {
                    display: inline-block;
                    min-width: 1.75rem;
                    padding: 0.15rem 0.45rem;
                    border-radius: 999px;
                    background: rgba(25, 135, 84, 0.12);
                    color: #198754;
                    font-weight: 700;
                    text-align: center;
                }

                .lead-filter-header h2 {
                    font-size: 0.95rem;
                }

                .lead-filter-bar {
                    padding: 0.55rem 0.85rem;
                    border-bottom: 1px solid var(--t4d-border);
                    background: #F8FAFC;
                }

                .lead-filter-scroll {
                    display: flex;
                    flex-wrap: nowrap;
                    gap: 0.35rem;
                    overflow-x: auto;
                    scrollbar-width: thin;
                    padding-bottom: 0.1rem;
                }

                .lead-filter-chip {
                    display: inline-flex;
                    align-items: center;
                    gap: 0.3rem;
                    flex: 0 0 auto;
                    padding: 0.28rem 0.55rem;
                    border-radius: 999px;
                    border: 1px solid #E2E8F0;
                    background: #fff;
                    color: #475569;
                    text-decoration: none;
                    font-size: 0.75rem;
                    font-weight: 600;
                    line-height: 1.2;
                    white-space: nowrap;
                }

                .lead-filter-chip span {
                    min-width: 1.1rem;
                    padding: 0.05rem 0.3rem;
                    border-radius: 999px;
                    background: #F1F5F9;
                    color: #64748B;
                    font-size: 0.68rem;
                    font-weight: 700;
                    font-variant-numeric: tabular-nums;
                    text-align: center;
                }

                .lead-filter-chip:hover {
                    border-color: rgba(11, 58, 110, 0.3);
                    color: var(--t4d-primary);
                }

                .lead-filter-chip.is-zero {
                    color: #94A3B8;
                }

                .lead-filter-chip.is-zero span {
                    background: #F8FAFC;
                    color: #CBD5E1;
                }

                .lead-filter-chip.active {
                    background: var(--t4d-primary);
                    border-color: var(--t4d-primary);
                    color: #fff;
                }

                .lead-filter-chip.active span,
                .lead-filter-chip.active.is-zero span {
                    background: rgba(255, 255, 255, 0.22);
                    color: #fff;
                }
            </style>

            @php
                $fu = $selected['followUpCounts'];
                $memberQuery = $memberBaseQuery;
                $activeFollowUp = $followUp ?? 'all';
                $leadFilters = [
                    'all' => ['label' => 'All', 'count' => $selected['counts']['assigned'] ?? 0],
                    'verified' => ['label' => 'Verified', 'count' => $fu['verified']],
                    'talk_later_7_days' => ['label' => 'Talk later', 'count' => $fu['talk_later_7_days']],
                    'not_connected' => ['label' => 'Not connected', 'count' => $fu['not_connected']],
                    'next_month' => ['label' => 'Next month', 'count' => $fu['next_month']],
                    'not_interested' => ['label' => 'Not interested', 'count' => $fu['not_interested']],
                    'invalid_number' => ['label' => 'Invalid', 'count' => $fu['invalid_number']],
                    'overdue' => ['label' => 'Overdue', 'count' => $fu['overdue']],
                    'next_7_days' => ['label' => 'Due 7d', 'count' => $fu['next_7_days']],
                ];
                $leadFilterTitles = [
                    'all' => 'All assigned leads',
                    'verified' => 'Verified leads',
                    'talk_later_7_days' => 'Talk later',
                    'not_connected' => 'Not connected',
                    'not_interested' => 'Not interested',
                    'invalid_number' => 'Invalid number',
                    'overdue' => 'Overdue follow-ups',
                    'next_7_days' => 'Due in next 7 days',
                    'next_month' => 'Next month',
                ];
            @endphp

            <div class="panel mt-4 lead-filter-panel">
                <div class="panel-header lead-filter-header py-2 px-3">
                    <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap w-100">
                        <div class="d-flex align-items-baseline gap-2 flex-wrap">
                            <h2 class="mb-0">{{ $leadFilterTitles[$activeFollowUp] ?? 'Leads' }}</h2>
                            <span class="small text-muted">{{ $selected['user']->name }} · {{ $memberLeads?->total() ?? 0 }}</span>
                        </div>
                    </div>
                </div>

                <div class="lead-filter-bar">
                    <div class="lead-filter-scroll">
                        @foreach ($leadFilters as $tab => $meta)
                            <a href="{{ route('verification.employee-team.index', array_merge($memberQuery, ['follow_up' => $tab])) }}"
                               class="lead-filter-chip {{ $activeFollowUp === $tab ? 'active' : '' }} {{ (int) $meta['count'] === 0 && $activeFollowUp !== $tab ? 'is-zero' : '' }}">
                                {{ $meta['label'] }}
                                <span>{{ $meta['count'] }}</span>
                            </a>
                        @endforeach
                    </div>
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
