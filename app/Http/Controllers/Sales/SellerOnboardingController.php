<?php

declare(strict_types=1);

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSellerOnboardingRequest;
use App\Models\User;
use App\Support\Enums\RecordStatus;
use App\Support\Enums\UserType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SellerOnboardingController extends Controller
{
    private const SELLER_LOGO_DIRECTORY = 'sellers/logos';

    private const SELLER_COVER_DIRECTORY = 'sellers/covers';

    public function index(Request $request): View
    {
        $this->authorize('reviewSellerOnboarding', User::class);

        $statusFilter = $request->string('status')->toString() ?: 'pending';
        if (! in_array($statusFilter, ['pending', 'inactive', 'active', 'all'], true)) {
            $statusFilter = 'pending';
        }

        $search = $request->string('q')->toString() ?: null;

        $sellers = User::query()
            ->where('user_type', UserType::Seller)
            ->when($statusFilter !== 'all', function ($query) use ($statusFilter): void {
                $status = match ($statusFilter) {
                    'pending' => RecordStatus::Pending,
                    'inactive' => RecordStatus::Inactive,
                    'active' => RecordStatus::Active,
                    default => RecordStatus::Pending,
                };
                $query->where('status', $status);
            })
            ->when($search, function ($query) use ($search): void {
                $query->where(function ($inner) use ($search): void {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('company_name', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        $counts = [
            'pending' => User::query()->where('user_type', UserType::Seller)->where('status', RecordStatus::Pending)->count(),
            'inactive' => User::query()->where('user_type', UserType::Seller)->where('status', RecordStatus::Inactive)->count(),
            'active' => User::query()->where('user_type', UserType::Seller)->where('status', RecordStatus::Active)->count(),
            'all' => User::query()->where('user_type', UserType::Seller)->count(),
        ];

        return view('sales.onboarding.index', [
            'sellers' => $sellers,
            'statusFilter' => $statusFilter,
            'search' => $search,
            'counts' => $counts,
        ]);
    }

    public function edit(User $seller): View
    {
        $this->authorize('updateSellerOnboardingDetails', $seller);
        abort_unless($seller->user_type === UserType::Seller, 404);

        return view('sales.onboarding.edit', [
            'seller' => $seller,
        ]);
    }

    public function update(UpdateSellerOnboardingRequest $request, User $seller): RedirectResponse
    {
        abort_unless($seller->user_type === UserType::Seller, 404);

        $data = $request->safe()->except(['logo', 'cover_image', 'remove_logo', 'remove_cover']);

        if ($request->boolean('remove_logo') && $seller->logo_path) {
            Storage::disk('public')->delete($seller->logo_path);
            $data['logo_path'] = null;
        }

        if ($request->boolean('remove_cover') && $seller->cover_image_path) {
            Storage::disk('public')->delete($seller->cover_image_path);
            $data['cover_image_path'] = null;
        }

        if ($request->hasFile('logo')) {
            if ($seller->logo_path) {
                Storage::disk('public')->delete($seller->logo_path);
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
            if ($seller->cover_image_path) {
                Storage::disk('public')->delete($seller->cover_image_path);
            }

            $path = $request->file('cover_image')->store(self::SELLER_COVER_DIRECTORY, 'public');
            if ($path === false) {
                return back()
                    ->withInput()
                    ->withErrors(['cover_image' => 'Cover image could not be uploaded. Please try again.']);
            }

            $data['cover_image_path'] = $path;
        }

        // Plan is intentionally never updated from this panel.
        unset($data['plan'], $data['gold_expires_at'], $data['status'], $data['user_type']);

        $seller->fill($data);
        if ($seller->isDirty('email')) {
            $seller->email_verified_at = null;
        }
        $seller->save();
        $seller->ensureSellerSlug();

        return redirect()
            ->route('sales.onboarding.edit', $seller)
            ->with('success', 'Seller details updated successfully.');
    }

    public function approve(User $seller): RedirectResponse
    {
        $this->authorize('approveSellerOnboarding', $seller);
        abort_unless($seller->user_type === UserType::Seller, 404);

        $seller->forceFill(['status' => RecordStatus::Active])->save();

        return back()->with('success', "{$seller->name} has been approved and is now live.");
    }

    public function disapprove(User $seller): RedirectResponse
    {
        $this->authorize('approveSellerOnboarding', $seller);
        abort_unless($seller->user_type === UserType::Seller, 404);

        // Soft deactivate — do not delete the account.
        $seller->forceFill(['status' => RecordStatus::Inactive])->save();

        return back()->with('success', "{$seller->name} has been declined (account kept inactive).");
    }
}
