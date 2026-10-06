<?php

declare(strict_types=1);

namespace App\Http\Controllers\Sourcing;

use App\Domains\Lead\Actions\CreateSourcingLeadAction;
use App\Domains\Lead\Requests\StoreSourcingLeadRequest;
use App\Domains\Lead\Services\LeadService;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeadController extends Controller
{
    public function create(): View
    {
        abort_unless(auth()->user()?->isSourcing(), 403);

        return view('sourcing.leads.create');
    }

    public function store(
        StoreSourcingLeadRequest $request,
        CreateSourcingLeadAction $createSourcingLeadAction,
    ): RedirectResponse {
        $createSourcingLeadAction->execute($request->validated(), $request->user());

        return redirect()
            ->route('sourcing.leads.index', ['status' => 'pending'])
            ->with('success', 'Buy lead submitted successfully. It will appear in the employee review queue.');
    }

    public function index(Request $request, LeadService $leadService): View
    {
        abort_unless(auth()->user()?->isSourcing(), 403);

        $status = $request->string('status')->toString() ?: 'pending';
        $perPage = min(50, max(5, (int) $request->input('per_page', 10)));

        return view('sourcing.leads.index', [
            'leads' => $leadService->listForSourcingUser(auth()->user(), $status === 'all' ? null : $status, $perPage),
            'counts' => $leadService->sourcingSubmissionCounts(auth()->user()),
            'status' => $status,
            'perPage' => $perPage,
        ]);
    }
}
