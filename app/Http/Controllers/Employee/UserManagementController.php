<?php

declare(strict_types=1);

namespace App\Http\Controllers\Employee;

use App\Domains\Seller\Services\UpdateSellerProfile;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSellerDetailsRequest;
use App\Http\Requests\UpdateUserPlanRequest;
use App\Models\User;
use App\Support\Enums\UserPlan;
use App\Support\Enums\UserType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    private const SELLER_LOGO_DIRECTORY = 'sellers/logos';

    private const SELLER_COVER_DIRECTORY = 'sellers/covers';

    public function index(Request $request): View
    {
        $this->authorize('manage', User::class);

        $search = $request->string('q')->toString() ?: null;

        $users = User::query()
            ->with(['activeRazorpayPayments'])
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

    public function editSeller(User $user): View
    {
        $this->authorize('updateSellerDetails', $user);

        return view('employee.users.seller-edit', [
            'seller' => $user,
        ]);
    }

    public function updateSeller(UpdateSellerDetailsRequest $request, User $user): RedirectResponse
    {
        $this->authorize('updateSellerDetails', $user);

        app(UpdateSellerProfile::class)->execute($user, $request);

        return redirect()
            ->route('verification.users.sellers.edit', $user)
            ->with('success', 'Seller details updated successfully.');
    }

    public function updatePlan(UpdateUserPlanRequest $request, User $user): RedirectResponse
    {
        $this->authorize('updatePlan', $user);

        $plan = UserPlan::from($request->validated('plan'));

        if ($user->plan_override === $plan) {
            return redirect()
                ->route('verification.users.index', $request->only('q'))
                ->with('success', "{$user->name} is already on the {$plan->label()} plan.");
        }

        $user->forceFill(['plan' => $plan, 'plan_override' => $plan])->save();

        $action = $plan->isGold() ? 'upgraded to Gold' : 'downgraded to Free';

        return redirect()
            ->route('verification.users.index', $request->only('q'))
            ->with('success', "{$user->name} has been {$action}.");
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        $this->authorize('deleteMarketplaceUser', $user);

        $name = $user->name;
        $user->syncRoles([]);
        $user->delete();

        return redirect()
            ->route('verification.users.index', $request->only('q'))
            ->with('success', "{$name} has been removed from the system.");
    }
}
