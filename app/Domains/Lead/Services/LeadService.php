<?php

declare(strict_types=1);

namespace App\Domains\Lead\Services;

use App\Domains\Lead\DTOs\CreateLeadDTO;
use App\Domains\Lead\DTOs\UpdateLeadDTO;
use App\Domains\Lead\Events\LeadApproved;
use App\Domains\Lead\Events\LeadRejected;
use App\Domains\Lead\Models\Lead;
use App\Domains\Lead\Repositories\Contracts\LeadRepositoryInterface;
use App\Models\User;
use App\Support\Enums\RecordStatus;
use App\Support\Enums\UserPlan;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class LeadService
{
    public function __construct(
        private readonly LeadRepositoryInterface $leadRepository,
    ) {}

    public function createLead(CreateLeadDTO $dto): Lead
    {
        return $this->leadRepository->create([
            ...$dto->toArray(),
            'status' => RecordStatus::Pending,
        ]);
    }

    public function updateLead(Lead $lead, UpdateLeadDTO $dto): Lead
    {
        return $this->leadRepository->update($lead, $dto->toArray());
    }

    public function approveLead(Lead $lead): Lead
    {
        $lead = $this->leadRepository->update($lead, [
            'status' => RecordStatus::Active,
            'published_at' => now(),
        ]);

        event(new LeadApproved($lead));

        return $lead;
    }

    public function rejectLead(Lead $lead): Lead
    {
        $lead = $this->leadRepository->update($lead, [
            'status' => RecordStatus::Inactive,
        ]);

        event(new LeadRejected($lead));

        return $lead;
    }

    /**
     * @return Collection<int, Lead>
     */
    public function getRecentLeadsForViewer(?User $viewer, int $limit = 12): Collection
    {
        return $this->leadRepository->getRecentVisible($viewer, $limit);
    }

    /**
     * @return Collection<int, Lead>
     */
    public function getMatchingLeadsForSeller(User $seller, int $limit = 6): Collection
    {
        return $this->leadRepository->getMatchingVisibleForSeller($seller, $limit);
    }

    /**
     * @return Collection<int, string>
     */
    public function matchingCategoriesForSeller(User $seller): Collection
    {
        return $this->leadRepository->matchingCategoriesForSeller($seller);
    }

    public function paginateLeadsForViewer(?User $viewer, int $perPage = 10): LengthAwarePaginator
    {
        return $this->leadRepository->paginateVisible($viewer, $perPage);
    }

    /**
     * @return Collection<int, Lead>
     */
    public function getRecentLeads(int $limit = 12): Collection
    {
        return $this->leadRepository->getRecentActive($limit);
    }

    public function paginateLeads(int $perPage = 15): LengthAwarePaginator
    {
        return $this->leadRepository->paginateActive($perPage);
    }

    public function listForModeration(
        ?string $statusFilter = null,
        ?string $search = null,
        int $perPage = 15,
        array $filters = [],
    ): LengthAwarePaginator {
        $status = match ($statusFilter) {
            'pending' => RecordStatus::Pending,
            'active' => RecordStatus::Active,
            'rejected' => RecordStatus::Inactive,
            default => null,
        };

        return $this->leadRepository->paginateForModeration($status, $search, $perPage, $filters);
    }

    /**
     * @return Collection<int, string>
     */
    public function moderationCountries(): Collection
    {
        return $this->leadRepository->distinctCountries();
    }

    /**
     * @return Collection<int, Lead>
     */
    public function getRecentPending(int $limit = 8): Collection
    {
        return $this->leadRepository->getRecentPending($limit);
    }

    /**
     * @return array{pending: int, active: int, rejected: int, total: int}
     */
    public function moderationCounts(): array
    {
        $pending = $this->leadRepository->countByStatus(RecordStatus::Pending);
        $active = $this->leadRepository->countByStatus(RecordStatus::Active);
        $rejected = $this->leadRepository->countByStatus(RecordStatus::Inactive);

        return [
            'pending' => $pending,
            'active' => $active,
            'rejected' => $rejected,
            'total' => $pending + $active + $rejected,
        ];
    }

    public function viewerPlan(?User $user): UserPlan
    {
        return $user?->userPlan() ?? UserPlan::Free;
    }

    public function isVisibleToViewer(Lead $lead, ?User $viewer): bool
    {
        return $this->leadRepository->isVisibleToViewer($lead, $viewer);
    }

    /**
     * @return Collection<int, Lead>
     */
    public function getSimilarLeadsForViewer(Lead $lead, ?User $viewer, int $limit = 4): Collection
    {
        return $this->leadRepository->getSimilarVisible($lead, $viewer, $limit);
    }
}
