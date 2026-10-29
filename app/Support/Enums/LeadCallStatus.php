<?php

declare(strict_types=1);

namespace App\Support\Enums;

enum LeadCallStatus: string
{
    case TalkLater7Days = 'talk_later_7_days';
    case LeadPostVerified = 'lead_post_verified';
    case NotConnected = 'not_connected';
    case NextMonth = 'next_month';
    case NotInterested = 'not_interested';
    case InvalidNumber = 'invalid_number';

    public function label(): string
    {
        return match ($this) {
            self::TalkLater7Days => 'Talk later in next 7 days',
            self::LeadPostVerified => 'Lead Post (Verified)',
            self::NotConnected => 'Not connected',
            self::NextMonth => 'Next month',
            self::NotInterested => 'Not interested',
            self::InvalidNumber => 'Invalid number',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::TalkLater7Days => 'text-bg-warning text-dark',
            self::LeadPostVerified => 'text-bg-success',
            self::NotConnected => 'text-bg-secondary',
            self::NextMonth => 'text-bg-info text-dark',
            self::NotInterested => 'text-bg-dark',
            self::InvalidNumber => 'text-bg-danger',
        };
    }

    public function followUpAt(): ?\Carbon\CarbonInterface
    {
        return match ($this) {
            self::TalkLater7Days => now()->addDays(7),
            self::NextMonth => now()->addMonth(),
            default => null,
        };
    }
}
