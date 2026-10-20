<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Support\Enums\UserPlan;
use App\Support\GeoPricing;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlanController extends Controller
{
    public function index(Request $request, GeoPricing $pricing): View
    {
        $user = auth()->user();
        $currentPlan = $user?->userPlan() ?? UserPlan::Free;

        return view('plans.index', [
            'currentPlan' => $currentPlan,
            'price' => $pricing->price($request),
            'goldExpiresAt' => $currentPlan->isGold() ? $user?->gold_expires_at : null,
        ]);
    }
}
