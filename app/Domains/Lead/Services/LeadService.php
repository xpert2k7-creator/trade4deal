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
use App\Support\Enums\LeadSource;
use App\Support\Enums\RecordStatus;
use App\Support\Enums\UserPlan;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class LeadService
{
    public function __construct(
        private readonly LeadRepositoryInterface $leadRepository,
    ) {}

    public function createLead(CreateLeadDTO $dto): Lead
    {
        $data = [
            ...$dto->toArray(),
            'status' => RecordStatus::Pending,
        ];

        // Employee-submitted leads are auto-assigned to that employee.
        if ($dto->source === LeadSource::Employee && auth()->id()) {
            $data['assigned_to'] = auth()->id();
            $data['assigned_at'] = now();
            $data['assigned_by'] = auth()->id();
        }

        return $this->leadRepository->create($data);
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

    public function paginateLeadsForViewer(
        ?User $viewer,
        int $perPage = 10,
        ?string $search = null,
        ?string $productType = null,
        ?string $country = null,
        ?string $fragment = 'leads',
    ): LengthAwarePaginator {
        return $this->leadRepository->paginateVisible(
            $viewer,
            $perPage,
            $search,
            $productType,
            $country,
            $fragment,
        );
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

    public function listForSourcingUser(User $user, ?string $statusFilter, int $perPage = 15): LengthAwarePaginator
    {
        $status = match ($statusFilter) {
            'pending' => RecordStatus::Pending,
            'active' => RecordStatus::Active,
            'rejected' => RecordStatus::Inactive,
            default => null,
        };

        return $this->leadRepository->paginateForCreator($user->id, $status, $perPage);
    }

    /**
     * @return array{today: int, month: int, total: int}
     */
    public function sourcingSubmissionCounts(User $user): array
    {
        $todayStart = now()->startOfDay();
        $monthStart = now()->startOfMonth();

        return [
            'today' => $this->leadRepository->countForCreator($user->id, $todayStart),
            'month' => $this->leadRepository->countForCreator($user->id, $monthStart),
            'total' => $this->leadRepository->countForCreator($user->id),
        ];
    }

    /**
     * @return Collection<int, object{day: string, total: int}>
     */
    public function sourcingDailyChart(User $user, int $days = 30): Collection
    {
        $from = now()->subDays($days - 1)->startOfDay();
        $to = now()->endOfDay();

        return $this->leadRepository->dailyCountsForCreator($user->id, $from, $to);
    }

    /**
     * @return array{today: int, month: int, total: int}
     */
    public function employeeSubmissionCounts(User $user): array
    {
        $todayStart = now()->startOfDay();
        $monthStart = now()->startOfMonth();

        return [
            'today' => $this->leadRepository->countForCreator($user->id, $todayStart, null, LeadSource::Employee),
            'month' => $this->leadRepository->countForCreator($user->id, $monthStart, null, LeadSource::Employee),
            'total' => $this->leadRepository->countForCreator($user->id, null, null, LeadSource::Employee),
        ];
    }

    /**
     * @return Collection<int, object{day: string, total: int}>
     */
    public function employeeDailyChart(User $user, int $days = 30): Collection
    {
        $from = now()->subDays($days - 1)->startOfDay();
        $to = now()->endOfDay();

        return $this->leadRepository->dailyCountsForCreator($user->id, $from, $to, LeadSource::Employee);
    }

    /**
     * Full-range daily chart: submitted + verified for the last N days.
     *
     * @return array{labels: list<string>, submitted: list<int>, verified: list<int>}
     */
    public function employeeDailySubmittedAndVerifiedChart(User $user, int $days = 30): array
    {
        $from = now()->subDays($days - 1)->startOfDay();
        $to = now()->endOfDay();

        return $this->buildEmployeeSubmittedVerifiedSeries($user, $from, $to, 'day');
    }

    /**
     * Work progress chart/table for admin filters: day | month | year.
     *
     * @return array{
     *     labels: list<string>,
     *     submitted: list<int>,
     *     verified: list<int>,
     *     period: string,
     *     periodLabel: string,
     *     granularity: string,
     *     columnLabel: string
     * }
     */
    public function employeeWorkProgressChart(User $user, string $period = 'month'): array
    {
        $period = in_array($period, ['day', 'month', 'year'], true) ? $period : 'month';
        $to = now()->endOfDay();

        [$from, $granularity, $periodLabel, $columnLabel] = match ($period) {
            'day' => [
                now()->startOfDay(),
                'day',
                'Today',
                'Date',
            ],
            'year' => [
                now()->startOfYear(),
                'month',
                'This year ('.now()->format('Y').')',
                'Month',
            ],
            default => [
                now()->startOfMonth(),
                'day',
                'This month ('.now()->format('M Y').')',
                'Date',
            ],
        };

        $series = $this->buildEmployeeSubmittedVerifiedSeries($user, $from, $to, $granularity);

        return [
            ...$series,
            'period' => $period,
            'periodLabel' => $periodLabel,
            'granularity' => $granularity,
            'columnLabel' => $columnLabel,
        ];
    }

    /**
     * @return array{labels: list<string>, submitted: list<int>, verified: list<int>}
     */
    private function buildEmployeeSubmittedVerifiedSeries(
        User $user,
        Carbon $from,
        Carbon $to,
        string $granularity,
    ): array {
        $submittedByDay = $this->leadRepository
            ->dailyCountsForCreator($user->id, $from, $to, LeadSource::Employee)
            ->keyBy(fn ($row) => (string) $row->day);

        $verifiedByDay = $this->leadRepository
            ->dailyVerifiedCountsForCreator($user->id, $from, $to)
            ->keyBy(fn ($row) => (string) $row->day);

        $labels = [];
        $submitted = [];
        $verified = [];

        if ($granularity === 'month') {
            for ($month = $from->copy()->startOfMonth(); $month->lte($to); $month->addMonth()) {
                $key = $month->format('Y-m');
                $monthStart = $month->copy()->startOfMonth();
                $monthEnd = $month->copy()->endOfMonth();
                if ($monthEnd->gt($to)) {
                    $monthEnd = $to->copy();
                }

                $submittedTotal = 0;
                $verifiedTotal = 0;
                for ($day = $monthStart->copy(); $day->lte($monthEnd); $day->addDay()) {
                    $dayKey = $day->toDateString();
                    $submittedTotal += (int) ($submittedByDay->get($dayKey)?->total ?? 0);
                    $verifiedTotal += (int) ($verifiedByDay->get($dayKey)?->total ?? 0);
                }

                $labels[] = $key;
                $submitted[] = $submittedTotal;
                $verified[] = $verifiedTotal;
            }

            return [
                'labels' => $labels,
                'submitted' => $submitted,
                'verified' => $verified,
            ];
        }

        for ($day = $from->copy()->startOfDay(); $day->lte($to); $day->addDay()) {
            $key = $day->toDateString();
            $labels[] = $key;
            $submitted[] = (int) ($submittedByDay->get($key)?->total ?? 0);
            $verified[] = (int) ($verifiedByDay->get($key)?->total ?? 0);
        }

        return [
            'labels' => $labels,
            'submitted' => $submitted,
            'verified' => $verified,
        ];
    }

    /**
     * @return array{overdue: int, next_7_days: int, next_month: int, with_follow_up: int, verified: int, not_connected: int, not_interested: int, invalid_number: int, talk_later_7_days: int}
     */
    public function employeeFollowUpCounts(User $user): array
    {
        return $this->leadRepository->followUpCountsForCreator($user->id);
    }

    /**
     * @return Collection<int, Lead>
     */
    public function employeeFollowUps(User $user, int $limit = 10): Collection
    {
        return $this->leadRepository->getFollowUpsForCreator($user->id, $limit);
    }

    /**
     * @return Collection<int, Lead>
     */
    public function employeeRecentLeads(User $user, int $limit = 8): Collection
    {
        return $this->leadRepository->getRecentForCreator($user->id, $limit);
    }

    public function listForEmployeeCreator(
        User $user,
        ?string $followUpFilter = null,
        ?string $search = null,
        int $perPage = 15,
        array $filters = [],
    ): LengthAwarePaginator {
        return $this->leadRepository->paginateForEmployeeCreator(
            $user->id,
            $followUpFilter,
            $search,
            $perPage,
            $filters,
        );
    }

    public function assignedLeadCount(User $user): int
    {
        return $this->leadRepository->countAssignedTo($user->id);
    }

    public function unassignedLeadCount(): int
    {
        return $this->leadRepository->countUnassigned();
    }

    /**
     * Assign oldest leads to a verification employee.
     * - fromAssigneeId null => unassigned pool
     * - fromAssigneeId set => reassign from that employee
     */
    public function assignLeadsToEmployee(
        User $assignee,
        int $count,
        User $assignedBy,
        ?string $fromAssigneeId = null,
    ): int {
        return $this->leadRepository->assignOldestLeadsTo(
            $assignee->id,
            $assignedBy->id,
            $count,
            $fromAssigneeId,
        );
    }
}
