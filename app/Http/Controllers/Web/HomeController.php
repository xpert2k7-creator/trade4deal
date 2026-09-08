<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domains\Lead\Services\LeadService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(Request $request, LeadService $leadService): View
    {
        $viewer = auth()->user();
        $perPage = min(50, max(5, (int) $request->input('per_page', 10)));

        $leads = $leadService->paginateLeadsForViewer($viewer, $perPage);
        $viewerPlan = $leadService->viewerPlan($viewer);
        $isStaffViewer = $viewer?->canModerateLeads() ?? false;

        return view('marketplace.home', compact('leads', 'viewerPlan', 'isStaffViewer', 'perPage'));
    }
}
