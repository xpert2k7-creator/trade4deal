<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Support\Enums\UserPlan;
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

}
