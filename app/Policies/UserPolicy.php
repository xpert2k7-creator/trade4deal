<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Support\Enums\UserType;

class UserPolicy
{
    public function manage(User $actor): bool
    {
        return $actor->isAdmin();
    }

    public function updatePlan(User $actor, User $target): bool
    {
        if (! $actor->isAdmin()) {
            return false;
        }

        return in_array($target->user_type, [UserType::Buyer, UserType::Seller], true);
    }

    public function updateSellerDetails(User $actor, User $target): bool
    {
        return $actor->isAdmin() && $target->user_type === UserType::Seller;
    }

    public function deleteMarketplaceUser(User $actor, User $target): bool
    {
        if (! $actor->isAdmin()) {
            return false;
        }

        if ($actor->id === $target->id) {
            return false;
        }

        return in_array($target->user_type, [UserType::Buyer, UserType::Seller], true);
    }

    public function manageSourcingTeam(User $actor): bool
    {
        return $actor->isAdmin();
    }

    public function manageEmployeeTeam(User $actor): bool
    {
        return $actor->isAdmin();
    }

    public function manageSalesTeam(User $actor): bool
    {
        return $actor->isAdmin();
    }

    public function reviewSellerOnboarding(User $actor): bool
    {
        return $actor->canReviewSellerOnboarding();
    }

    public function updateSellerOnboardingDetails(User $actor, User $target): bool
    {
        if (! $actor->canReviewSellerOnboarding()) {
            return false;
        }

        return $target->user_type === UserType::Seller;
    }

    public function approveSellerOnboarding(User $actor, User $target): bool
    {
        if (! $actor->canReviewSellerOnboarding()) {
            return false;
        }

        return $target->user_type === UserType::Seller;
    }

    public function deleteStaffAccount(User $actor, User $target): bool
    {
        if (! $actor->isAdmin()) {
            return false;
        }

        if ($actor->id === $target->id) {
            return false;
        }

        return $target->isEmployee() || $target->isSourcing() || $target->isSales();
    }
}
