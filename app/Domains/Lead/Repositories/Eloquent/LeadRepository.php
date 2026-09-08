<?php

declare(strict_types=1);

namespace App\Domains\Lead\Repositories\Eloquent;

use App\Domains\Lead\Models\Lead;
use App\Domains\Lead\Repositories\Contracts\LeadRepositoryInterface;
use App\Models\User;
use App\Support\Enums\RecordStatus;
use App\Support\Enums\UserPlan;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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
            ->limit($limit)
            ->get();
    }

    public function paginateVisible(?User $viewer, int $perPage = 10): LengthAwarePaginator
    {
        return $this->visibleQuery($viewer)
            ->latest('published_at')
            ->paginate($perPage)
            ->withQueryString()
            ->fragment('leads');
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
            $delayHours = config('trade4deal.lead_visibility_delay_hours', 24);
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

        $delayHours = config('trade4deal.lead_visibility_delay_hours', 24);

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
                $delayHours = config('trade4deal.lead_visibility_delay_hours', 24);
                $query->where('published_at', '<=', now()->subHours($delayHours));
            }
        }

        return $query
            ->latest('published_at')
            ->limit($limit)
            ->get();
    }
}
