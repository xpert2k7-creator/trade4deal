<?php

declare(strict_types=1);

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateManagedUserRequest;
use App\Http\Requests\UpdateSellerDetailsRequest;
use App\Http\Requests\UpdateUserPlanRequest;
use App\Models\User;
use App\Support\Enums\RecordStatus;
use App\Support\Enums\UserPlan;
use App\Support\Enums\UserType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

    public function edit(User $user): View|RedirectResponse
    {
        $this->authorize('updateManagedUser', $user);

        if ($user->isSeller()) {
            return redirect()->route('employee.users.sellers.edit', $user);
        }

        return view('employee.users.edit', [
            'managedUser' => $user,
        ]);
    }

    public function update(UpdateManagedUserRequest $request, User $user): RedirectResponse
    {
        $this->authorize('updateManagedUser', $user);

        if ($user->isSeller()) {
            return redirect()
                ->route('employee.users.sellers.edit', $user)
                ->with('error', 'Use the seller details form to update this account.');
        }

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
        $typeChanged = $user->user_type !== $newType;

        $user->fill($data);
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        $user->save();

        if ($typeChanged) {
            $user->syncRoles([$newType->value]);
        }

        if ($newType === UserType::Seller) {
            $user->ensureSellerSlug();

            return redirect()
                ->route('employee.users.sellers.edit', $user)
                ->with('success', 'User updated. Complete seller profile details below.');
        }

        return redirect()
            ->route('employee.users.edit', $user)
            ->with('success', 'User updated successfully.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        $this->authorize('delete', $user);

        $displayName = $user->company_name ?: $user->name;

        $this->removeSellerMedia($user);
        $user->products()->update(['status' => RecordStatus::Inactive]);
        $user->delete();

        return redirect()
            ->route('employee.users.index', $request->only('q'))
            ->with('success', "{$displayName} has been removed from the marketplace.");
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

        $data = $request->safe()->except(['logo', 'cover_image', 'remove_logo', 'remove_cover']);
        $data['industries'] = $request->input('industries', []);

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

            $path = $request->file('logo')->store(self::SELLER_LOGO_DIRECTORY, 'public');
            if ($path === false) {
                return back()
                    ->withInput()
                    ->withErrors(['logo' => 'Company logo could not be uploaded. Please try again.']);
            }

            $data['logo_path'] = $path;
        }

        if ($request->hasFile('cover_image')) {
            if ($user->cover_image_path) {
                Storage::disk('public')->delete($user->cover_image_path);
            }

            $path = $request->file('cover_image')->store(self::SELLER_COVER_DIRECTORY, 'public');
            if ($path === false) {
                return back()
                    ->withInput()
                    ->withErrors(['cover_image' => 'Cover image could not be uploaded. Please try again.']);
            }

            $data['cover_image_path'] = $path;
        }

        $user->fill($data);
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        $user->save();
        $user->ensureSellerSlug();

        return redirect()
            ->route('employee.users.sellers.edit', $user)
            ->with('success', 'Seller details updated successfully.');
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

    private function removeSellerMedia(User $user): void
    {
        if ($user->logo_path) {
            Storage::disk('public')->delete($user->logo_path);
        }

        if ($user->cover_image_path) {
            Storage::disk('public')->delete($user->cover_image_path);
        }
    }
}
