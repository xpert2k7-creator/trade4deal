<?php

declare(strict_types=1);

namespace App\Domains\Lead\Policies;

use App\Domains\Lead\Models\Lead;
use App\Models\User;

class LeadPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Lead $lead): bool
    {
        return true;
    }

    public function create(?User $user): bool
    {
        return true;
    }

    public function update(User $user, Lead $lead): bool
    {
        return $user->canModerateLeads();
    }

    public function delete(User $user, Lead $lead): bool
    {
        return $user->canModerateLeads();
    }

    public function moderate(User $user): bool
    {
        return $user->canModerateLeads();
    }
}
