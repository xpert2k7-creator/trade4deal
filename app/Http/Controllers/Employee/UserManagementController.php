<?php

declare(strict_types=1);

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserPlanRequest;
use App\Models\User;
use App\Support\Enums\UserPlan;
use App\Support\Enums\UserType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('manage', User::class);

        $search = $request->string('q')->toString() ?: null;

        $users = User::query()
            ->whereIn('user_type', [UserType::Buyer, UserType::Seller])
            ->when($search, function ($query) use ($search): void {
                $query->where(function ($inner) use ($search): void {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('company_name', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('employee.users.index', [
            'users' => $users,
            'search' => $search,
        ]);
    }

    public function updatePlan(UpdateUserPlanRequest $request, User $user): RedirectResponse
    {
        $this->authorize('updatePlan', $user);

        $plan = UserPlan::from($request->validated('plan'));

        if ($user->plan === $plan) {
            return redirect()
                ->route('employee.users.index', $request->only('q'))
                ->with('success', "{$user->name} is already on the {$plan->label()} plan.");
        }

        $user->forceFill(['plan' => $plan])->save();

        $action = $plan->isGold() ? 'upgraded to Gold' : 'downgraded to Free';

        return redirect()
            ->route('employee.users.index', $request->only('q'))
            ->with('success', "{$user->name} has been {$action}.");
    }
}
