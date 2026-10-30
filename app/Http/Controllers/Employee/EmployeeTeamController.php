<?php

declare(strict_types=1);

namespace App\Http\Controllers\Employee;

use App\Domains\Lead\Services\LeadService;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeUserRequest;
use App\Http\Requests\UpdateEmployeeUserRequest;
use App\Models\User;
use App\Support\Enums\RecordStatus;
use App\Support\Enums\UserType;
use App\Support\Permissions\EmployeeRole;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class EmployeeTeamController extends Controller
{
    public function index(Request $request, LeadService $leadService): View
    {
        $this->authorize('manageEmployeeTeam', User::class);

        $period = $request->string('period')->toString() ?: 'month';
        if (! in_array($period, ['day', 'month', 'year'], true)) {
            $period = 'month';
        }

        $members = User::query()
            ->where('user_type', UserType::Employee)
            ->orderBy('name')
            ->get()
            ->map(function (User $user) use ($leadService): array {
                $counts = $leadService->employeeSubmissionCounts($user);
                $counts['assigned'] = $leadService->assignedLeadCount($user);
                $followUpCounts = $leadService->employeeFollowUpCounts($user);

                return [
                    'user' => $user,
                    'counts' => $counts,
                    'followUpCounts' => $followUpCounts,
                ];
            });

        $selectedId = $request->string('member')->toString() ?: ($members->first()['user']->id ?? null);
        $selected = $members->firstWhere(fn (array $row) => $row['user']->id === $selectedId) ?? $members->first();

        $followUp = $request->string('follow_up')->toString() ?: 'all';
        $memberLeads = null;
        $workProgress = null;

        if ($selected !== null) {
            $workProgress = $leadService->employeeWorkProgressChart($selected['user'], $period);
            $selected['chartLabels'] = $workProgress['labels'];
            $selected['chartSubmittedValues'] = $workProgress['submitted'];
            $selected['chartVerifiedValues'] = $workProgress['verified'];

            $memberLeads = $leadService->listForEmployeeCreator(
                user: $selected['user'],
                followUpFilter: $followUp === 'all' ? null : $followUp,
                perPage: 10,
            );
        }

        return view('employee.employee-team.index', [
            'members' => $members,
            'selected' => $selected,
            'followUp' => $followUp,
            'period' => $period,
            'workProgress' => $workProgress,
            'memberLeads' => $memberLeads,
            'unassignedCount' => $leadService->unassignedLeadCount(),
        ]);
    }

    public function assignLeads(Request $request, LeadService $leadService): RedirectResponse
    {
        $this->authorize('manageEmployeeTeam', User::class);

        $admin = $request->user();
        assert($admin !== null);

        $data = $request->validate([
            'assignee_id' => ['required', 'uuid', 'exists:users,id'],
            'count' => ['required', 'integer', 'min:1', 'max:500'],
            'from_assignee_id' => ['nullable', 'uuid', 'exists:users,id', 'different:assignee_id'],
        ]);

        $assignee = User::query()->findOrFail($data['assignee_id']);
        abort_unless($assignee->isEmployee() && ! $assignee->isAdmin(), 422, 'Assignee must be a verification employee.');

        if (! empty($data['from_assignee_id'])) {
            $from = User::query()->findOrFail($data['from_assignee_id']);
            abort_unless($from->isEmployee() && ! $from->isAdmin(), 422, 'Source must be a verification employee.');
        }

        $assigned = $leadService->assignLeadsToEmployee(
            assignee: $assignee,
            count: (int) $data['count'],
            assignedBy: $admin,
            fromAssigneeId: $data['from_assignee_id'] ?? null,
        );

        $sourceLabel = empty($data['from_assignee_id'])
            ? 'unassigned pool'
            : 'another employee';

        return redirect()
            ->route('verification.employee-team.index', ['member' => $assignee->id, 'follow_up' => 'all'])
            ->with(
                $assigned > 0 ? 'success' : 'error',
                $assigned > 0
                    ? "Assigned {$assigned} oldest lead(s) to {$assignee->name} from the {$sourceLabel}."
                    : 'No matching leads were available to assign.'
            );
    }

    public function create(): View
    {
        $this->authorize('manageEmployeeTeam', User::class);

        return view('employee.employee-team.create');
    }

    public function store(StoreEmployeeUserRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $user = User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'user_type' => UserType::Employee,
            'status' => RecordStatus::Active,
            'email_verified_at' => now(),
        ]);

        EmployeeRole::ensureExists();
        $user->assignRole('employee');

        return redirect()
            ->route('verification.employee-team.index')
            ->with('success', 'Employee login created successfully.');
    }

    public function edit(User $employeeUser): View
    {
        $this->authorize('manageEmployeeTeam', User::class);
        abort_unless($employeeUser->isEmployee() && ! $employeeUser->isAdmin(), 404);

        return view('employee.employee-team.edit', [
            'member' => $employeeUser,
        ]);
    }

    public function update(UpdateEmployeeUserRequest $request, User $employeeUser): RedirectResponse
    {
        abort_unless($employeeUser->isEmployee() && ! $employeeUser->isAdmin(), 404);

        $data = $request->validated();

        $employeeUser->fill([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        if ($employeeUser->isDirty('email')) {
            $employeeUser->email_verified_at = now();
        }

        if (! empty($data['password'])) {
            $employeeUser->password = Hash::make($data['password']);
        }

        $employeeUser->save();

        return redirect()
            ->route('verification.employee-team.index', ['member' => $employeeUser->id])
            ->with('success', 'Employee account updated successfully.');
    }

    public function destroy(User $employeeUser): RedirectResponse
    {
        $this->authorize('deleteStaffAccount', $employeeUser);
        abort_unless($employeeUser->isEmployee() && ! $employeeUser->isAdmin(), 404);

        $employeeUser->syncRoles([]);
        $employeeUser->delete();

        return redirect()
            ->route('verification.employee-team.index')
            ->with('success', 'Employee account removed successfully.');
    }
}
