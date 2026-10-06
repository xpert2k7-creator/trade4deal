<?php

declare(strict_types=1);

namespace App\Http\Controllers\Sourcing;

use App\Domains\Lead\Services\LeadService;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(LeadService $leadService): View
    {
        abort_unless(auth()->user()?->isSourcing(), 403);

        $user = auth()->user();
        $chart = $leadService->sourcingDailyChart($user, 30);

        return view('sourcing.dashboard', [
            'counts' => $leadService->sourcingSubmissionCounts($user),
            'chartLabels' => $chart->pluck('day')->all(),
            'chartValues' => $chart->pluck('total')->map(fn ($value) => (int) $value)->all(),
            'recentLeads' => $leadService->listForSourcingUser($user, 'pending', 8),
        ]);
    }
}
