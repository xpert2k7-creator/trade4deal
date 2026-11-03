<?php

declare(strict_types=1);

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSalesUserRequest;
use App\Http\Requests\UpdateSalesUserRequest;
use App\Models\User;
use App\Support\Enums\RecordStatus;
use App\Support\Enums\UserType;
use App\Support\Permissions\SalesRole;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class SalesTeamController extends Controller
{
    public function index(): View
    {
        $this->authorize('manageSalesTeam', User::class);

        $members = User::query()
            ->where('user_type', UserType::Sales)
            ->orderBy('name')
            ->get();

        return view('employee.sales-team.index', [
            'members' => $members,
        ]);
    }

    public function create(): View
    {
        $this->authorize('manageSalesTeam', User::class);

        return view('employee.sales-team.create');
    }

    public function store(StoreSalesUserRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $user = User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'user_type' => UserType::Sales,
            'status' => RecordStatus::Active,
            'email_verified_at' => now(),
        ]);

        SalesRole::ensureExists();
        $user->assignRole('sales');

        return redirect()
            ->route('verification.sales-team.index')
            ->with('success', 'Sales login created successfully.');
    }

    public function edit(User $salesUser): View
    {
        $this->authorize('manageSalesTeam', User::class);
        abort_unless($salesUser->isSales() && ! $salesUser->isAdmin(), 404);

        return view('employee.sales-team.edit', [
            'member' => $salesUser,
        ]);
    }

    public function update(UpdateSalesUserRequest $request, User $salesUser): RedirectResponse
    {
        abort_unless($salesUser->isSales() && ! $salesUser->isAdmin(), 404);

        $data = $request->validated();

        $salesUser->fill([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        if ($salesUser->isDirty('email')) {
            $salesUser->email_verified_at = now();
        }

        if (! empty($data['password'])) {
            $salesUser->password = Hash::make($data['password']);
        }

        $salesUser->save();

        return redirect()
            ->route('verification.sales-team.index')
            ->with('success', 'Sales account updated successfully.');
    }

    public function destroy(User $salesUser): RedirectResponse
    {
        $this->authorize('deleteStaffAccount', $salesUser);
        abort_unless($salesUser->isSales() && ! $salesUser->isAdmin(), 404);

        $salesUser->syncRoles([]);
        $salesUser->delete();

        return redirect()
            ->route('verification.sales-team.index')
            ->with('success', 'Sales account removed successfully.');
    }
}
