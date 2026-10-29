<?php

declare(strict_types=1);

namespace App\Domains\Lead\Repositories\Contracts;

use App\Domains\Lead\Models\Lead;
use App\Models\User;
use App\Support\Enums\LeadSource;
use App\Support\Enums\RecordStatus;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

interface LeadRepositoryInterface
{
    public function create(array $data): Lead;

    public function findById(string $id): ?Lead;

    public function update(Lead $lead, array $data): Lead;

    /**
     * @return Collection<int, Lead>
     */
    public function getRecentActive(int $limit = 12): Collection;

    /**
     * @return Collection<int, Lead>
     */
    public function getRecentVisible(?User $viewer, int $limit = 12): Collection;

    /**
     * @return Collection<int, Lead>
     */
    public function getMatchingVisibleForSeller(User $seller, int $limit = 6): Collection;

    /**
     * @return Collection<int, string>
     */
    public function matchingCategoriesForSeller(User $seller): Collection;

    public function paginateVisible(
        ?User $viewer,
        int $perPage = 10,
        ?string $search = null,
        ?string $productType = null,
        ?string $country = null,
        ?string $fragment = 'leads',
    ): LengthAwarePaginator;

    public function paginateActive(int $perPage = 15): LengthAwarePaginator;

    public function paginateForModeration(
        ?RecordStatus $status = null,
        ?string $search = null,
        int $perPage = 15,
        array $filters = [],
    ): LengthAwarePaginator;

    /**
     * @return Collection<int, Lead>
     */
    public function getRecentPending(int $limit = 8): Collection;

    public function countByStatus(RecordStatus $status): int;

    /**
     * @return Collection<int, string>
     */
    public function distinctCountries(): Collection;

    public function isVisibleToViewer(Lead $lead, ?User $viewer): bool;

    /**
     * @return Collection<int, Lead>
     */
    public function getSimilarVisible(Lead $lead, ?User $viewer, int $limit = 4): Collection;

    public function paginateForCreator(
        string $creatorId,
        ?RecordStatus $status = null,
        int $perPage = 15,
    ): LengthAwarePaginator;

    public function countForCreator(
        string $creatorId,
        ?Carbon $from = null,
        ?Carbon $to = null,
        LeadSource $source = LeadSource::Sourcing,
    ): int;

    /**
     * @return Collection<int, object{day: string, total: int}>
     */
    public function dailyCountsForCreator(
        string $creatorId,
        Carbon $from,
        Carbon $to,
        LeadSource $source = LeadSource::Sourcing,
    ): Collection;

    /**
     * Daily counts of leads currently marked verified, by the day they were last updated.
     *
     * @return Collection<int, object{day: string, total: int}>
     */
    public function dailyVerifiedCountsForCreator(
        string $creatorId,
        Carbon $from,
        Carbon $to,
    ): Collection;

    public function countBySource(LeadSource $source, ?Carbon $from = null, ?Carbon $to = null): int;

    /**
     * @return array{overdue: int, next_7_days: int, next_month: int, with_follow_up: int, verified: int, not_connected: int, not_interested: int, invalid_number: int, talk_later_7_days: int}
     */
    public function followUpCountsForCreator(string $creatorId): array;

    /**
     * @return Collection<int, Lead>
     */
    public function getFollowUpsForCreator(string $creatorId, int $limit = 10): Collection;

    /**
     * @return Collection<int, Lead>
     */
    public function getRecentForCreator(string $creatorId, int $limit = 8): Collection;

    public function paginateForEmployeeCreator(
        string $creatorId,
        ?string $followUpFilter = null,
        ?string $search = null,
        int $perPage = 15,
        array $filters = [],
    ): LengthAwarePaginator;

    public function countAssignedTo(string $assigneeId): int;

    public function countUnassigned(): int;

    /**
     * Assign the oldest matching leads to an employee. Returns how many were assigned.
     */
    public function assignOldestLeadsTo(
        string $assigneeId,
        string $assignedById,
        int $count,
        ?string $fromAssigneeId = null,
    ): int;
}
