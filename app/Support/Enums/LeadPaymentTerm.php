<?php

declare(strict_types=1);

namespace App\Support\Enums;

enum LeadPaymentTerm: string
{
    case Advance = 'advance';
    case Lc = 'lc';
    case Tt = 'tt';
    case Wt = 'wt';

    public function label(): string
    {
        return match ($this) {
            self::Advance => 'Advance',
            self::Lc => 'LC',
            self::Tt => 'TT',
            self::Wt => 'WT',
        };
    }
}
