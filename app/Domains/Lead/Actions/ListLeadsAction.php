<?php

declare(strict_types=1);

namespace App\Domains\Lead\Actions;

use App\Domains\Lead\Models\Lead;
use App\Domains\Lead\Services\LeadService;
use App\Models\User;
use Illuminate\Support\Collection;

class ListLeadsAction
{
    public function __construct(
        private readonly LeadService $leadService,
    ) {}

    /**
     * @return Collection<int, Lead>
     */
    public function execute(?User $viewer = null, int $limit = 12): Collection
    {
        return $this->leadService->getRecentLeadsForViewer($viewer, $limit);
    }
}
