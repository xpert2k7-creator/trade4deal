@extends('layouts.employee')

@section('title', 'Users')
@section('page-title', 'User Plans')

@section('content')
<div class="panel mb-3">
    <div class="panel-header">
        <h2>Marketplace users</h2>
        <form method="GET" action="{{ route('employee.users.index') }}" class="d-flex gap-2">
            <input type="search" name="q" value="{{ $search }}" class="form-control form-control-sm" placeholder="Search name, email, company…" style="min-width:220px;">
            <button type="submit" class="btn btn-sm btn-primary-t4d">Search</button>
        </form>
    </div>

    @if ($users->isEmpty())
        <div class="panel-body text-center text-muted py-5">
            No users found.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-emp align-middle mb-0">
                <thead>
                <tr>
                    <th>User</th>
                    <th>Type</th>
                    <th>Company</th>
                    <th>Current plan</th>
                    <th>Change plan</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $user->name }}</div>
                            <div class="small text-muted">{{ $user->email }}</div>
                        </td>
                        <td>{{ $user->user_type?->label() }}</td>
                        <td>{{ $user->company_name ?? '—' }}</td>
                        <td>
                            <span class="badge {{ $user->plan?->badgeClass() ?? 'bg-secondary' }}">
                                {{ $user->plan?->label() ?? 'Free' }}
                            </span>
                        </td>
                        <td>
                            <form method="POST"
                                  action="{{ route('employee.users.plan.update', $user) }}"
                                  class="d-flex gap-2 align-items-center plan-change-form"
                                  data-user-name="{{ $user->name }}"
                                  data-current-plan="{{ $user->plan?->value ?? 'free' }}">
                                @csrf
                                @method('PATCH')
                                @if ($search)
                                    <input type="hidden" name="q" value="{{ $search }}">
                                @endif
                                <select name="plan" class="form-select form-select-sm" style="min-width:120px;">
                                    @foreach (\App\Support\Enums\UserPlan::cases() as $plan)
                                        <option value="{{ $plan->value }}" @selected(($user->plan?->value ?? 'free') === $plan->value)>
                                            {{ $plan->label() }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary-t4d">Update</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="panel-body border-top">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.plan-change-form').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            const userName = form.dataset.userName;
            const currentPlan = form.dataset.currentPlan;
            const newPlan = form.querySelector('[name="plan"]').value;

            if (newPlan === currentPlan) {
                event.preventDefault();
                alert('Plan is unchanged for ' + userName + '.');
                return;
            }

            let message;
            if (newPlan === 'gold') {
                message = 'Are you sure you want to upgrade ' + userName + ' to the Gold plan?';
            } else {
                message = 'Are you sure you want to downgrade ' + userName + ' to the Free plan?';
            }

            if (! confirm(message)) {
                event.preventDefault();
            }
        });
    });
</script>
@endpush
