<?php

declare(strict_types=1);

namespace App\Domains\Lead\Repositories\Eloquent;

use App\Domains\Lead\Models\Lead;
use App\Domains\Lead\Repositories\Contracts\LeadRepositoryInterface;
use App\Models\User;
use App\Support\Enums\LeadCallStatus;
use App\Support\Enums\LeadSource;
use App\Support\Enums\RecordStatus;
use App\Support\Enums\UserPlan;
use BackedEnum;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class LeadRepository implements LeadRepositoryInterface
{
    public function create(array $data): Lead
    {
        return Lead::query()->create($data);
    }

    public function findById(string $id): ?Lead
    {
        return Lead::query()->find($id);
    }

    public function update(Lead $lead, array $data): Lead
    {
        $lead->update($data);

        return $lead->fresh();
    }

    public function getRecentActive(int $limit = 12): Collection
    {
        return Lead::query()
            ->active()
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getRecentVisible(?User $viewer, int $limit = 12): Collection
    {
        return $this->visibleQuery($viewer)
            ->latest('published_at')
            ->latest('updated_at')
            ->limit($limit)
            ->get();
    }

    public function getMatchingVisibleForSeller(User $seller, int $limit = 6): Collection
    {
        $categories = $this->matchingCategoriesForSeller($seller);

        if ($categories->isEmpty()) {
            return collect();
        }

        return $this->visibleQuery($seller)
            ->whereIn('product_type', $categories->all())
            ->latest('published_at')
            ->limit($limit)
            ->get();
    }

    public function matchingCategoriesForSeller(User $seller): Collection
    {
        $industryCategories = collect($seller->industries ?? []);
        $productCategories = $seller->products()->pluck('product_type');

        return $industryCategories
            ->merge($productCategories)
            ->map(fn ($category) => $category instanceof BackedEnum ? $category->value : (string) $category)
            ->filter()
            ->unique()
            ->values();
    }

    public function paginateVisible(
        ?User $viewer,
        int $perPage = 10,
        ?string $search = null,
        ?string $productType = null,
        ?string $country = null,
        ?string $fragment = 'leads',
    ): LengthAwarePaginator {
        $query = $this->visibleQuery($viewer);

        $search = $search !== null ? trim($search) : '';
        if ($search !== '') {
            $query->where(function ($q) use ($search): void {
                $q->where('company_name', 'like', '%'.$search.'%')
                    ->orWhere('contact_name', 'like', '%'.$search.'%')
                    ->orWhere('product_interest', 'like', '%'.$search.'%')
                    ->orWhere('country', 'like', '%'.$search.'%')
                    ->orWhere('message', 'like', '%'.$search.'%');
            });
        }

        $productType = $productType !== null ? trim($productType) : '';
        if ($productType !== '') {
            $query->where('product_type', $productType);
        }

        $country = $country !== null ? trim($country) : '';
        if ($country !== '') {
            $query->where('country', $country);
        }

        $paginator = $query
            ->latest('published_at')
            ->latest('updated_at')
            ->paginate($perPage)
            ->withQueryString();

        if ($fragment !== null && $fragment !== '') {
            $paginator->fragment($fragment);
        }

        return $paginator;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder<Lead>
     */
    private function visibleQuery(?User $viewer)
    {
        $query = Lead::query()
            ->where('status', RecordStatus::Active)
            ->whereNotNull('published_at');

        if ($viewer?->canModerateLeads()) {
            return $query;
        }

        $plan = $viewer?->userPlan() ?? UserPlan::Free;

        if (! $plan->canViewLeadImmediately()) {
            $delayHours = config('trade4deal.lead_visibility_delay_hours', 168);
            $query->where('published_at', '<=', now()->subHours($delayHours));
        }

        return $query;
    }

    public function paginateActive(int $perPage = 15): LengthAwarePaginator
    {
        return Lead::query()
            ->active()
            ->latest()
            ->paginate($perPage);
    }

    public function paginateForModeration(
        ?RecordStatus $status = null,
        ?string $search = null,
        int $perPage = 15,
        array $filters = [],
    ): LengthAwarePaginator {
        $query = Lead::query()->latest();

        if ($status !== null) {
            $query->where('status', $status);
        }

        $name = $filters['name'] ?? null;
        if ($name !== null && $name !== '') {
            $query->where(function ($q) use ($name): void {
                $q->where('company_name', 'like', '%'.$name.'%')
                    ->orWhere('contact_name', 'like', '%'.$name.'%');
            });
        }

        if ($search !== null && $search !== '') {
            $query->where(function ($q) use ($search): void {
                $q->where('company_name', 'like', '%'.$search.'%')
                    ->orWhere('contact_name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%')
                    ->orWhere('product_interest', 'like', '%'.$search.'%')
                    ->orWhere('country', 'like', '%'.$search.'%');
            });
        }

        $productType = $filters['product_type'] ?? null;
        if ($productType !== null && $productType !== '') {
            $query->where('product_type', $productType);
        }

        $country = $filters['country'] ?? null;
        if ($country !== null && $country !== '') {
            $query->where('country', 'like', '%'.$country.'%');
        }

        $dateFrom = $filters['date_from'] ?? null;
        if ($dateFrom !== null && $dateFrom !== '') {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        $dateTo = $filters['date_to'] ?? null;
        if ($dateTo !== null && $dateTo !== '') {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $source = $filters['source'] ?? null;
        if ($source !== null && $source !== '') {
            $query->where('source', $source);
        }

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * @return Collection<int, string>
     */
    public function distinctCountries(): Collection
    {
        return Lead::query()
            ->whereNotNull('country')
            ->where('country', '!=', '')
            ->distinct()
            ->orderBy('country')
            ->pluck('country');
    }

    public function getRecentPending(int $limit = 8): Collection
    {
        return Lead::query()
            ->where('status', RecordStatus::Pending)
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function countByStatus(RecordStatus $status): int
    {
        return Lead::query()->where('status', $status)->count();
    }

    public function isVisibleToViewer(Lead $lead, ?User $viewer): bool
    {
        if ($lead->status !== RecordStatus::Active || $lead->published_at === null) {
            return false;
        }

        if ($viewer?->canModerateLeads()) {
            return true;
        }

        $plan = $viewer?->userPlan() ?? UserPlan::Free;

        if ($plan->canViewLeadImmediately()) {
            return true;
        }

        $delayHours = config('trade4deal.lead_visibility_delay_hours', 168);

        return $lead->published_at->lte(now()->subHours($delayHours));
    }

    public function getSimilarVisible(Lead $lead, ?User $viewer, int $limit = 4): Collection
    {
        $query = Lead::query()
            ->where('status', RecordStatus::Active)
            ->whereNotNull('published_at')
            ->where('id', '!=', $lead->id);

        if ($lead->product_type !== null) {
            $query->where('product_type', $lead->product_type);
        }

        if (! $viewer?->canModerateLeads()) {
            $plan = $viewer?->userPlan() ?? UserPlan::Free;

            if (! $plan->canViewLeadImmediately()) {
                $delayHours = config('trade4deal.lead_visibility_delay_hours', 168);
                $query->where('published_at', '<=', now()->subHours($delayHours));
            }
        }

        return $query
            ->latest('published_at')
            ->limit($limit)
            ->get();
    }

    public function paginateForCreator(
        string $creatorId,
        ?RecordStatus $status = null,
        int $perPage = 15,
    ): LengthAwarePaginator {
        $query = Lead::query()
            ->where('created_by', $creatorId)
            ->where('source', LeadSource::Sourcing)
            ->latest();

        if ($status !== null) {
            $query->where('status', $status);
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function countForCreator(
        string $creatorId,
        ?Carbon $from = null,
        ?Carbon $to = null,
        LeadSource $source = LeadSource::Sourcing,
    ): int {
        $query = Lead::query()
            ->where('created_by', $creatorId)
            ->where('source', $source);

        if ($from !== null) {
            $query->where('created_at', '>=', $from);
        }

        if ($to !== null) {
            $query->where('created_at', '<=', $to);
        }

        return $query->count();
    }

    public function dailyCountsForCreator(
        string $creatorId,
        Carbon $from,
        Carbon $to,
        LeadSource $source = LeadSource::Sourcing,
    ): Collection {
        return Lead::query()
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->where('created_by', $creatorId)
            ->where('source', $source)
            ->whereBetween('created_at', [$from->copy()->startOfDay(), $to->copy()->endOfDay()])
            ->groupBy('day')
            ->orderBy('day')
            ->get();
    }

    public function dailyVerifiedCountsForCreator(
        string $creatorId,
        Carbon $from,
        Carbon $to,
    ): Collection {
        return Lead::query()
            ->selectRaw('DATE(updated_at) as day, COUNT(*) as total')
            ->where('assigned_to', $creatorId)
            ->where('call_status', LeadCallStatus::LeadPostVerified)
            ->whereBetween('updated_at', [$from->copy()->startOfDay(), $to->copy()->endOfDay()])
            ->groupBy('day')
            ->orderBy('day')
            ->get();
    }

    public function countBySource(LeadSource $source, ?Carbon $from = null, ?Carbon $to = null): int
    {
        $query = Lead::query()->where('source', $source);

        if ($from !== null) {
            $query->where('created_at', '>=', $from);
        }

        if ($to !== null) {
            $query->where('created_at', '<=', $to);
        }

        return $query->count();
    }

    public function followUpCountsForCreator(string $creatorId): array
    {
        // Employee work queue is scoped by assignment, not submission.
        $base = Lead::query()->where('assigned_to', $creatorId);
        $active = (clone $base)->where(function ($query): void {
            $query->whereNull('call_status')
                ->orWhere('call_status', '!=', LeadCallStatus::LeadPostVerified);
        });
        $today = now()->startOfDay();
        $in7Days = now()->copy()->addDays(7)->endOfDay();
        $monthStart = now()->copy()->addMonthNoOverflow()->startOfMonth();
        $monthEnd = now()->copy()->addMonthNoOverflow()->endOfMonth();

        return [
            'overdue' => (clone $active)
                ->whereNotNull('call_follow_up_at')
                ->where('call_follow_up_at', '<', $today)
                ->count(),
            'next_7_days' => (clone $active)
                ->whereNotNull('call_follow_up_at')
                ->whereBetween('call_follow_up_at', [$today, $in7Days])
                ->count(),
            'next_month' => (clone $active)
                ->where(function ($query) use ($monthStart, $monthEnd): void {
                    $query->where('call_status', LeadCallStatus::NextMonth)
                        ->orWhereBetween('call_follow_up_at', [$monthStart, $monthEnd]);
                })
                ->count(),
            'with_follow_up' => (clone $active)
                ->whereNotNull('call_follow_up_at')
                ->count(),
            // Verified stays countable for employee + admin dashboards.
            'verified' => (clone $base)
                ->where('call_status', LeadCallStatus::LeadPostVerified)
                ->count(),
            'not_connected' => (clone $base)
                ->where('call_status', LeadCallStatus::NotConnected)
                ->count(),
            'not_interested' => (clone $base)
                ->where('call_status', LeadCallStatus::NotInterested)
                ->count(),
            'invalid_number' => (clone $base)
                ->where('call_status', LeadCallStatus::InvalidNumber)
                ->count(),
            'talk_later_7_days' => (clone $base)
                ->where('call_status', LeadCallStatus::TalkLater7Days)
                ->count(),
        ];
    }

    public function getFollowUpsForCreator(string $creatorId, int $limit = 10): Collection
    {
        return Lead::query()
            ->where('assigned_to', $creatorId)
            ->where(function ($query): void {
                $query->whereNull('call_status')
                    ->orWhere('call_status', '!=', LeadCallStatus::LeadPostVerified);
            })
            ->whereNotNull('call_follow_up_at')
            ->orderBy('call_follow_up_at')
            ->limit($limit)
            ->get();
    }

    public function getRecentForCreator(string $creatorId, int $limit = 8): Collection
    {
        // Active work queue only — verified leads stay in counts, not recent lists.
        return Lead::query()
            ->where('assigned_to', $creatorId)
            ->where(function ($query): void {
                $query->whereNull('call_status')
                    ->orWhere('call_status', '!=', LeadCallStatus::LeadPostVerified);
            })
            ->latest('assigned_at')
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function paginateForEmployeeCreator(
        string $creatorId,
        ?string $followUpFilter = null,
        ?string $search = null,
        int $perPage = 15,
        array $filters = [],
    ): LengthAwarePaginator {
        $query = Lead::query()
            ->where('assigned_to', $creatorId)
            ->latest('assigned_at')
            ->latest();

        $today = now()->startOfDay();
        $in7Days = now()->copy()->addDays(7)->endOfDay();
        $monthStart = now()->copy()->addMonthNoOverflow()->startOfMonth();
        $monthEnd = now()->copy()->addMonthNoOverflow()->endOfMonth();

        match ($followUpFilter) {
            'overdue' => $query
                ->where(function ($inner): void {
                    $inner->whereNull('call_status')
                        ->orWhere('call_status', '!=', LeadCallStatus::LeadPostVerified);
                })
                ->whereNotNull('call_follow_up_at')
                ->where('call_follow_up_at', '<', $today),
            'next_7_days' => $query
                ->where(function ($inner): void {
                    $inner->whereNull('call_status')
                        ->orWhere('call_status', '!=', LeadCallStatus::LeadPostVerified);
                })
                ->whereNotNull('call_follow_up_at')
                ->whereBetween('call_follow_up_at', [$today, $in7Days]),
            'next_month' => $query->where(function ($inner) use ($monthStart, $monthEnd): void {
                $inner->where('call_status', LeadCallStatus::NextMonth)
                    ->orWhereBetween('call_follow_up_at', [$monthStart, $monthEnd]);
            })->where(function ($inner): void {
                $inner->whereNull('call_status')
                    ->orWhere('call_status', '!=', LeadCallStatus::LeadPostVerified);
            }),
            'with_follow_up' => $query
                ->where(function ($inner): void {
                    $inner->whereNull('call_status')
                        ->orWhere('call_status', '!=', LeadCallStatus::LeadPostVerified);
                })
                ->whereNotNull('call_follow_up_at'),
            'verified' => $query->where('call_status', LeadCallStatus::LeadPostVerified),
            'not_connected' => $query->where('call_status', LeadCallStatus::NotConnected),
            'not_interested' => $query->where('call_status', LeadCallStatus::NotInterested),
            'invalid_number' => $query->where('call_status', LeadCallStatus::InvalidNumber),
            'talk_later_7_days' => $query->where('call_status', LeadCallStatus::TalkLater7Days),
            // Default "All assigned" work queue hides verified leads (counts still tracked).
            default => $query->where(function ($inner): void {
                $inner->whereNull('call_status')
                    ->orWhere('call_status', '!=', LeadCallStatus::LeadPostVerified);
            }),
        };

        $callStatus = $filters['call_status'] ?? null;
        if ($callStatus !== null && $callStatus !== '') {
            $query->where('call_status', $callStatus);
        }

        $name = $filters['name'] ?? null;
        if ($name !== null && $name !== '') {
            $query->where(function ($q) use ($name): void {
                $q->where('company_name', 'like', '%'.$name.'%')
                    ->orWhere('contact_name', 'like', '%'.$name.'%');
            });
        }

        if ($search !== null && $search !== '') {
            $query->where(function ($q) use ($search): void {
                $q->where('company_name', 'like', '%'.$search.'%')
                    ->orWhere('contact_name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%')
                    ->orWhere('product_interest', 'like', '%'.$search.'%')
                    ->orWhere('country', 'like', '%'.$search.'%');
            });
        }

        $productType = $filters['product_type'] ?? null;
        if ($productType !== null && $productType !== '') {
            $query->where('product_type', $productType);
        }

        $country = $filters['country'] ?? null;
        if ($country !== null && $country !== '') {
            $query->where('country', 'like', '%'.$country.'%');
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function countAssignedTo(string $assigneeId): int
    {
        // Active assigned queue (excludes verified — those stay in verified counts / charts).
        return Lead::query()
            ->where('assigned_to', $assigneeId)
            ->where(function ($query): void {
                $query->whereNull('call_status')
                    ->orWhere('call_status', '!=', LeadCallStatus::LeadPostVerified);
            })
            ->count();
    }

    public function countUnassigned(): int
    {
        return Lead::query()->whereNull('assigned_to')->count();
    }

    public function assignOldestLeadsTo(
        string $assigneeId,
        string $assignedById,
        int $count,
        ?string $fromAssigneeId = null,
    ): int {
        if ($count < 1) {
            return 0;
        }

        $query = Lead::query()->orderBy('created_at')->orderBy('id');

        if ($fromAssigneeId !== null && $fromAssigneeId !== '') {
            $query->where('assigned_to', $fromAssigneeId)
                ->where('assigned_to', '!=', $assigneeId);
        } else {
            $query->whereNull('assigned_to');
        }

        $ids = $query->limit($count)->pluck('id');
        if ($ids->isEmpty()) {
            return 0;
        }

        return Lead::query()->whereIn('id', $ids)->update([
            'assigned_to' => $assigneeId,
            'assigned_at' => now(),
            'assigned_by' => $assignedById,
            'updated_at' => now(),
        ]);
    }
}
