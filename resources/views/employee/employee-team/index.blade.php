@extends('layouts.employee')

@section('title', 'Employees')
@section('page-title', 'Employee logins')

@section('topbar-actions')
    <a href="{{ route('verification.employee-team.create') }}" class="btn btn-sm btn-primary-t4d">
        <i class="bi bi-person-plus"></i> Create login
    </a>
@endsection

@section('content')
<div class="panel">
    <div class="panel-header">
        <h2>Moderation employees</h2>
        <span class="small text-muted">{{ $members->count() }} account{{ $members->count() === 1 ? '' : 's' }}</span>
    </div>

    @if ($members->isEmpty())
        <div class="panel-body text-muted">No employee logins yet.</div>
    @else
        <div class="table-responsive">
            <table class="table table-emp align-middle mb-0">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Added</th>
                    <th class="text-end">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($members as $member)
                    <tr>
                        <td class="fw-semibold">{{ $member->name }}</td>
                        <td>{{ $member->email }}</td>
                        <td class="small text-muted">{{ $member->created_at?->format('M j, Y') }}</td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('verification.employee-team.edit', $member) }}" class="btn btn-sm btn-outline-secondary me-1">Edit</a>
                            @include('partials.delete-staff-account-form', [
                                'action' => route('verification.employee-team.destroy', $member),
                                'name' => $member->name,
                            ])
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
