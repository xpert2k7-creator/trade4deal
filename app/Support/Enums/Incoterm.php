<?php

declare(strict_types=1);

namespace App\Support\Enums;

enum Incoterm: string
{
    case Fob = 'fob';
    case ExFactory = 'ex_factory';
    case Cfr = 'cfr';
    case Cif = 'cif';

    public function label(): string
    {
        return match ($this) {
            self::Fob => 'FOB',
            self::ExFactory => 'EX Factory',
            self::Cfr => 'CFR',
            self::Cif => 'CIF',
        };
    }
}
