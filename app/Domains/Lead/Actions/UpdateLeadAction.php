<?php

declare(strict_types=1);

namespace App\Domains\Lead\Actions;

use App\Domains\Lead\DTOs\UpdateLeadDTO;
use App\Domains\Lead\Models\Lead;
use App\Domains\Lead\Services\LeadService;

class UpdateLeadAction
{
    public function __construct(
        private readonly LeadService $leadService,
    ) {}

    public function execute(Lead $lead, UpdateLeadDTO $dto): Lead
    {
        return $this->leadService->updateLead($lead, $dto);
    }
}
