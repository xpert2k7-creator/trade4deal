<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Support\Enums\UserType;

class UserPolicy
{
    public function manage(User $actor): bool
    {
        return $actor->canModerateLeads();
    }

    public function updatePlan(User $actor, User $target): bool
    {
        return $this->canManageMarketplaceUser($actor, $target);
    }

    public function update(User $actor, User $target): bool
    {
        return $this->canManageMarketplaceUser($actor, $target);
    }

    public function delete(User $actor, User $target): bool
    {
        return $this->canManageMarketplaceUser($actor, $target);
    }

    public function updateSellerDetails(User $actor, User $target): bool
    {
        return $actor->isAdmin() && $target->user_type === UserType::Seller;
    }

    private function canManageMarketplaceUser(User $actor, User $target): bool
    {
        if (! $actor->canModerateLeads()) {
            return false;
        }

        return in_array($target->user_type, [UserType::Buyer, UserType::Seller], true);
    }
}
