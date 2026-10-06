<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domains\Lead\Actions\CreateLeadAction;
use App\Domains\Lead\DTOs\CreateLeadDTO;
use App\Domains\Lead\Models\Lead;
use App\Domains\Lead\Notifications\LeadInquiryNotification;
use App\Domains\Lead\Requests\ContactLeadRequest;
use App\Domains\Lead\Requests\StoreLeadRequest;
use App\Domains\Lead\Services\LeadService;
use App\Http\Controllers\Controller;
use App\Support\Enums\LeadSource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class LeadController extends Controller
{
    public function show(Lead $lead, LeadService $leadService): View
    {
        $this->authorize('view', $lead);

        $viewer = auth()->user();

        abort_unless($leadService->isVisibleToViewer($lead, $viewer), 404);

        return view('marketplace.leads.show', [
            'lead' => $lead,
            'similarLeads' => $leadService->getSimilarLeadsForViewer($lead, $viewer, 4),
            'viewerPlan' => $leadService->viewerPlan($viewer),
        ]);
    }

    public function contact(
        ContactLeadRequest $request,
        Lead $lead,
        LeadService $leadService,
    ): RedirectResponse {
        $this->authorize('view', $lead);

        abort_unless($leadService->isVisibleToViewer($lead, auth()->user()), 404);

        $inquiry = $request->validated();

        Notification::route('mail', $lead->email)
            ->notify(new LeadInquiryNotification($lead, $inquiry));

        return redirect()
            ->route('leads.show', $lead)
            ->with('success', 'Your message has been sent to the supplier. They can reply to you by email.');
    }

    public function store(
        StoreLeadRequest $request,
        CreateLeadAction $createLeadAction,
    ): RedirectResponse {
        $this->authorize('create', Lead::class);

        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['source'] = LeadSource::User->value;

        if ($request->hasFile('product_image')) {
            $file = $request->file('product_image');
            $path = $file->store('leads', 'public');
            $path = is_string($path) ? $path : $file->hashName('leads');

            Storage::disk('public')->put($path, $file->get());

            $data['product_image_path'] = $path;
        }

        unset($data['product_image']);

        $createLeadAction->execute(CreateLeadDTO::fromArray($data));

        return redirect()
            ->route('home')
            ->with('success', 'Your business lead has been submitted successfully. Our team will review it shortly before it goes live on Trade4Deal.');
    }
}
