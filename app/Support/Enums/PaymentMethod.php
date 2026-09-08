<?php

declare(strict_types=1);

namespace App\Support\Enums;

enum PaymentMethod: string
{
    case WireTransfer = 'wire_transfer';
    case LetterOfCredit = 'lc';
    case Escrow = 'escrow';
    case PayPal = 'paypal';
    case Crypto = 'crypto';

    public function label(): string
    {
        return match ($this) {
            self::WireTransfer => 'Wire Transfer',
            self::LetterOfCredit => 'Letter of Credit (LC)',
            self::Escrow => 'Escrow',
            self::PayPal => 'PayPal',
            self::Crypto => 'Cryptocurrency',
        };
    }
}
