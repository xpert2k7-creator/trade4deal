<?php

declare(strict_types=1);

namespace App\Http\Controllers\Employee;

use App\Domains\Lead\Actions\ApproveLeadAction;
use App\Domains\Lead\Actions\CreateLeadAction;
use App\Domains\Lead\Actions\RejectLeadAction;
use App\Domains\Lead\Actions\UpdateLeadAction;
use App\Domains\Lead\DTOs\CreateLeadDTO;
use App\Domains\Lead\DTOs\UpdateLeadDTO;
use App\Domains\Lead\Models\Lead;
use App\Domains\Lead\Requests\StoreLeadRequest;
use App\Domains\Lead\Requests\UpdateLeadRequest;
use App\Domains\Lead\Services\LeadService;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class LeadModerationController extends Controller
{
    public function dashboard(LeadService $leadService): View
    {
        $this->authorize('moderate', Lead::class);

        return view('employee.dashboard', [
            'counts' => $leadService->moderationCounts(),
            'pendingLeads' => $leadService->getRecentPending(8),
        ]);
    }

    public function index(Request $request, LeadService $leadService): View
    {
        $this->authorize('moderate', Lead::class);

        $status = $request->string('status')->toString() ?: 'pending';
        $search = $request->string('q')->toString() ?: null;
        $perPage = min(50, max(5, (int) $request->input('per_page', 10)));

        $filters = [
            'name' => $request->string('name')->toString() ?: null,
            'product_type' => $request->string('product_type')->toString() ?: null,
            'country' => $request->string('country')->toString() ?: null,
            'date_from' => $request->string('date_from')->toString() ?: null,
            'date_to' => $request->string('date_to')->toString() ?: null,
        ];

        return view('employee.leads.index', [
            'leads' => $leadService->listForModeration(
                statusFilter: $status === 'all' ? null : $status,
                search: $search,
                perPage: $perPage,
                filters: $filters,
            ),
            'counts' => $leadService->moderationCounts(),
            'countries' => $leadService->moderationCountries(),
            'status' => $status,
            'search' => $search,
            'filters' => $filters,
            'perPage' => $perPage,
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Lead::class);

        return view('employee.leads.create');
    }

    public function store(
        StoreLeadRequest $request,
        CreateLeadAction $createLeadAction,
    ): RedirectResponse {
        $this->authorize('create', Lead::class);

        $data = $request->validated();
        $data['user_id'] = auth()->id();

        if ($request->hasFile('product_image')) {
            $data['product_image_path'] = $request->file('product_image')
                ->store('leads', 'public');
        }

        unset($data['product_image']);

        $createLeadAction->execute(CreateLeadDTO::fromArray($data));

        return redirect()
            ->route('employee.leads.index', ['status' => 'pending'])
            ->with('success', 'Lead submitted successfully and added to the pending review queue.');
    }

    public function edit(Lead $lead): View
    {
        $this->authorize('update', $lead);

        return view('employee.leads.edit', compact('lead'));
    }

    public function update(
        UpdateLeadRequest $request,
        Lead $lead,
        UpdateLeadAction $updateLeadAction,
    ): RedirectResponse {
        $this->authorize('update', $lead);

        $data = $request->validated();

        if ($request->hasFile('product_image')) {
            if ($lead->product_image_path) {
                Storage::disk('public')->delete($lead->product_image_path);
            }
            $data['product_image_path'] = $request->file('product_image')
                ->store('leads', 'public');
        } else {
            $data['product_image_path'] = $lead->product_image_path;
        }

        unset($data['product_image']);

        $updateLeadAction->execute($lead, UpdateLeadDTO::fromArray($data));

        return redirect()
            ->route('employee.leads.edit', $lead)
            ->with('success', 'Lead details updated successfully.');
    }

    public function approve(
        Lead $lead,
        ApproveLeadAction $approveLeadAction,
    ): RedirectResponse {
        $this->authorize('update', $lead);

        $approveLeadAction->execute($lead);

        return redirect()
            ->route('employee.leads.index', ['status' => 'pending'])
            ->with('success', 'Lead approved and published. The submitter has been emailed.');
    }

    public function reject(
        Lead $lead,
        RejectLeadAction $rejectLeadAction,
    ): RedirectResponse {
        $this->authorize('update', $lead);

        $rejectLeadAction->execute($lead);

        return redirect()
            ->route('employee.leads.index', ['status' => 'pending'])
            ->with('success', 'Lead rejected. The submitter has been notified.');
    }
}
