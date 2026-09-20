<?php

declare(strict_types=1);

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateManagedUserRequest;
use App\Http\Requests\UpdateUserPlanRequest;
use App\Models\User;
use App\Support\Enums\UserPlan;
use App\Support\Enums\UserType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

    public function edit(Request $request, User $user): View|RedirectResponse
    {
        $this->authorize('update', $user);

        return view('employee.users.edit', [
            'user' => $user,
            'search' => $request->string('q')->toString() ?: null,
        ]);
    }

    public function update(UpdateManagedUserRequest $request, User $user): RedirectResponse
    {
        $this->authorize('update', $user);

        $data = $request->safe()->except([
            'password',
            'password_confirmation',
            'logo',
            'cover_image',
            'remove_logo',
            'remove_cover',
        ]);

        if ($request->filled('password')) {
            $data['password'] = $request->validated('password');
        }

        $newType = UserType::from($data['user_type']);

        if ($newType !== UserType::Seller) {
            unset($data['tagline'], $data['about'], $data['city'], $data['address'], $data['website']);
            unset($data['year_established'], $data['employees_range'], $data['industries'], $data['is_public']);
        } else {
            $this->applySellerMediaChanges($request, $user, $data);
        }

        $user->fill($data);
        $user->save();

        if ($user->wasChanged('user_type')) {
            $user->syncRoles([$newType->value]);
        }

        if ($user->isSeller()) {
            $user->ensureSellerSlug();
        }

        return redirect()
            ->route('employee.users.edit', array_filter([
                'user' => $user,
                'q' => $request->input('q'),
            ]))
            ->with('success', "{$user->name}'s profile has been updated.");
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        $this->authorize('delete', $user);

        $name = $user->name;
        $user->delete();

        return redirect()
            ->route('employee.users.index', $request->only('q'))
            ->with('success', "{$name} has been deleted.");
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function applySellerMediaChanges(UpdateManagedUserRequest $request, User $user, array &$data): void
    {
        if ($request->boolean('remove_logo') && $user->logo_path) {
            Storage::disk('public')->delete($user->logo_path);
            $data['logo_path'] = null;
        }

        if ($request->boolean('remove_cover') && $user->cover_image_path) {
            Storage::disk('public')->delete($user->cover_image_path);
            $data['cover_image_path'] = null;
        }

        if ($request->hasFile('logo')) {
            if ($user->logo_path) {
                Storage::disk('public')->delete($user->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('sellers/logos', 'public');
        }

        if ($request->hasFile('cover_image')) {
            if ($user->cover_image_path) {
                Storage::disk('public')->delete($user->cover_image_path);
            }
            $data['cover_image_path'] = $request->file('cover_image')->store('sellers/covers', 'public');
        }
    }
}
