<?php

declare(strict_types=1);

namespace App\Support\Enums;

enum UserPlan: string
{
    case Free = 'free';
    case Gold = 'gold';

    public function label(): string
    {
        return match ($this) {
            self::Free => 'Free',
            self::Gold => 'Gold',
        };
    }

    public function isGold(): bool
    {
        return $this === self::Gold;
    }

    public function canViewLeadImmediately(): bool
    {
        return $this === self::Gold;
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Free => 'bg-secondary',
            self::Gold => 'bg-warning text-dark',
        };
    }
}
