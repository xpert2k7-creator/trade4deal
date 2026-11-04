<?php

declare(strict_types=1);

namespace App\Domains\Lead\Services;

use App\Domains\Lead\Models\Lead;
use App\Domains\Lead\Models\LeadView;
use App\Models\User;
use Carbon\CarbonInterface;

class LeadViewQuotaService
{
    public function canOpen(User $user, Lead $lead): bool
    {
        if ($user->canModerateLeads()) {
            return true;
        }

        if ($this->hasViewed($user, $lead)) {
            return true;
        }

        return $this->usedCount($user) < $this->limitFor($user);
    }

    /**
     * @return 'daily'|'lifetime'|null
     */
    public function denyReason(User $user, Lead $lead): ?string
    {
        if ($this->canOpen($user, $lead)) {
            return null;
        }

        return $user->userPlan()->isGold() ? 'daily' : 'lifetime';
    }

    public function record(User $user, Lead $lead): void
    {
        if ($user->canModerateLeads()) {
            return;
        }

        LeadView::query()->firstOrCreate(
            [
                'user_id' => $user->id,
                'lead_id' => $lead->id,
            ],
            [
                'viewed_at' => now(),
            ],
        );
    }

    public function hasViewed(User $user, Lead $lead): bool
    {
        return LeadView::query()
            ->where('user_id', $user->id)
            ->where('lead_id', $lead->id)
            ->exists();
    }

    public function usedCount(User $user): int
    {
        $query = LeadView::query()->where('user_id', $user->id);

        if ($user->userPlan()->isGold()) {
            $query->whereBetween('viewed_at', [
                now()->startOfDay(),
                now()->endOfDay(),
            ]);
        } else {
            $since = $this->freePeriodStartedAt($user);
            if ($since !== null) {
                $query->where('viewed_at', '>', $since);
            }
        }

        return $query->count();
    }

    public function remaining(User $user): int
    {
        if ($user->canModerateLeads()) {
            return PHP_INT_MAX;
        }

        return max(0, $this->limitFor($user) - $this->usedCount($user));
    }

    public function limitFor(User $user): int
    {
        $limits = config('trade4deal.lead_view_limits', []);

        if ($user->userPlan()->isGold()) {
            return (int) ($limits['gold_per_day'] ?? 2);
        }

        return (int) ($limits['free_lifetime'] ?? 2);
    }

    /**
     * After Gold expires, free quota restarts from that expiry moment.
     * Never-subscribed free users count all views.
     */
    private function freePeriodStartedAt(User $user): ?CarbonInterface
    {
        if ($user->gold_expires_at !== null && $user->gold_expires_at->isPast()) {
            return $user->gold_expires_at;
        }

        return null;
    }
}
