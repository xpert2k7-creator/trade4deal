<?php

declare(strict_types=1);

namespace App\Domains\Lead\Actions;

use App\Domains\Lead\Models\Lead;
use App\Domains\Lead\Services\LeadService;

class RejectLeadAction
{
    public function __construct(
        private readonly LeadService $leadService,
    ) {}

    public function execute(Lead $lead): Lead
    {
        return $this->leadService->rejectLead($lead);
    }
}
