<?php

declare(strict_types=1);

namespace App\Http\Controllers\Employee;

use App\Domains\Lead\Services\LeadService;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSourcingUserRequest;
use App\Http\Requests\UpdateSourcingUserRequest;
use App\Models\User;
use App\Support\Enums\RecordStatus;
use App\Support\Enums\UserType;
use App\Support\Permissions\SourcingRole;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class SourcingTeamController extends Controller
{
    public function index(Request $request, LeadService $leadService): View
    {
        $this->authorize('manageSourcingTeam', User::class);

        $members = User::query()
            ->where('user_type', UserType::Sourcing)
            ->orderBy('name')
            ->get()
            ->map(function (User $user) use ($leadService): array {
                $counts = $leadService->sourcingSubmissionCounts($user);
                $chart = $leadService->sourcingDailyChart($user, 30);

                return [
                    'user' => $user,
                    'counts' => $counts,
                    'chartLabels' => $chart->pluck('day')->all(),
                    'chartValues' => $chart->pluck('total')->map(fn ($value) => (int) $value)->all(),
                ];
            });

        $selectedId = $request->string('member')->toString() ?: ($members->first()['user']->id ?? null);
        $selected = $members->firstWhere(fn (array $row) => $row['user']->id === $selectedId) ?? $members->first();

        return view('employee.sourcing-team.index', [
            'members' => $members,
            'selected' => $selected,
        ]);
    }

    public function create(): View
    {
        $this->authorize('manageSourcingTeam', User::class);

        return view('employee.sourcing-team.create');
    }

    public function store(StoreSourcingUserRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $user = User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'user_type' => UserType::Sourcing,
            'status' => RecordStatus::Active,
            'email_verified_at' => now(),
        ]);

        SourcingRole::ensureExists();
        $user->assignRole('sourcing');

        return redirect()
            ->route('verification.sourcing-team.index')
            ->with('success', 'Sourcing login created successfully.');
    }

    public function edit(User $sourcingUser): View
    {
        $this->authorize('manageSourcingTeam', User::class);
        abort_unless($sourcingUser->isSourcing(), 404);

        return view('employee.sourcing-team.edit', [
            'member' => $sourcingUser,
        ]);
    }

    public function update(UpdateSourcingUserRequest $request, User $sourcingUser): RedirectResponse
    {
        abort_unless($sourcingUser->isSourcing(), 404);

        $data = $request->validated();

        $sourcingUser->fill([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        if ($sourcingUser->isDirty('email')) {
            $sourcingUser->email_verified_at = now();
        }

        if (! empty($data['password'])) {
            $sourcingUser->password = Hash::make($data['password']);
        }

        $sourcingUser->save();

        return redirect()
            ->route('verification.sourcing-team.index', ['member' => $sourcingUser->id])
            ->with('success', 'Sourcing account updated successfully.');
    }

    public function destroy(User $sourcingUser): RedirectResponse
    {
        $this->authorize('deleteStaffAccount', $sourcingUser);
        abort_unless($sourcingUser->isSourcing(), 404);

        $sourcingUser->syncRoles([]);
        $sourcingUser->delete();

        return redirect()
            ->route('verification.sourcing-team.index')
            ->with('success', 'Sourcing account removed successfully.');
    }
}
