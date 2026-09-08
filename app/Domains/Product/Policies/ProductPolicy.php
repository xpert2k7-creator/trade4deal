<?php

declare(strict_types=1);

namespace App\Domains\Product\Policies;

use App\Domains\Product\Models\Product;
use App\Models\User;
use App\Support\Enums\UserType;

class ProductPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isSeller() || $user->isAdmin();
    }

    public function view(?User $user, Product $product): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isSeller() || $user->isAdmin();
    }

    public function update(User $user, Product $product): bool
    {
        return $user->isAdmin() || ($user->isSeller() && $product->user_id === $user->id);
    }

    public function delete(User $user, Product $product): bool
    {
        return $this->update($user, $product);
    }

    public function manageProfile(User $user): bool
    {
        return $user->isSeller() || $user->isAdmin();
    }
}
