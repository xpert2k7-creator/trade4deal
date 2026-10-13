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

        $members = User::query()
            ->where('user_type', UserType::Employee)
            ->orderBy('name')
            ->get()
            ->map(function (User $user) use ($leadService): array {
                $counts = $leadService->employeeSubmissionCounts($user);
                $chart = $leadService->employeeDailyChart($user, 30);

                return [
                    'user' => $user,
                    'counts' => $counts,
                    'chartLabels' => $chart->pluck('day')->all(),
                    'chartValues' => $chart->pluck('total')->map(fn ($value) => (int) $value)->all(),
                ];
            });

        $selectedId = $request->string('member')->toString() ?: ($members->first()['user']->id ?? null);
        $selected = $members->firstWhere(fn (array $row) => $row['user']->id === $selectedId) ?? $members->first();

        return view('employee.employee-team.index', [
            'members' => $members,
            'selected' => $selected,
        ]);
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
