<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Support\Enums\UserPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlanController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $currentPlan = $user?->userPlan() ?? UserPlan::Free;

        return view('plans.index', [
            'currentPlan' => $currentPlan,
        ]);
    }

    public function checkout(Request $request): RedirectResponse
    {
        $priceId = config('trade4deal.gold_stripe_price_id');

        if (! $priceId) {
            return back()->with('error', 'Gold plan is not configured yet. Please contact support.');
        }

        $planName = config('trade4deal.gold_plan_name', 'gold');

        return $request->user()
            ->newSubscription($planName, $priceId)
            ->checkout([
                'success_url' => route('plans.gold.success').'?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('plans.gold.cancel'),
            ])
            ->redirect();
    }

    public function success(Request $request): RedirectResponse
    {
        $request->user()->syncPlanFromSubscription();

        if ($request->user()->userPlan() !== UserPlan::Gold) {
            $request->user()->forceFill(['plan' => UserPlan::Gold])->save();
        }

        return redirect()
            ->route('dashboard')
            ->with('success', 'Welcome to Trade4Deal Gold! You now have instant access to new leads.');
    }

    public function cancel(): RedirectResponse
    {
        return redirect()
            ->route('plans.index')
            ->with('error', 'Gold checkout was cancelled. You can upgrade anytime.');
    }
}
