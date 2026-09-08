<?php

declare(strict_types=1);

namespace App\Domains\Lead\Actions;

use App\Domains\Lead\DTOs\CreateLeadDTO;
use App\Domains\Lead\Events\LeadCreated;
use App\Domains\Lead\Models\Lead;
use App\Domains\Lead\Services\LeadService;

class CreateLeadAction
{
    public function __construct(
        private readonly LeadService $leadService,
    ) {}

    public function execute(CreateLeadDTO $dto): Lead
    {
        $lead = $this->leadService->createLead($dto);

        LeadCreated::dispatch($lead);

        return $lead;
    }
}
