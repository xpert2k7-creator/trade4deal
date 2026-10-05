<?php

declare(strict_types=1);

namespace App\Support;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

final class PhoneNumber
{
    /**
     * E.164 digits (no leading +) for global uniqueness checks.
     *
     * @param  string|null  $countryHint  Registration country name or ISO2 (e.g. "India", "IN")
     */
    public static function normalize(?string $phone, ?string $countryHint = null): ?string
    {
        if ($phone === null) {
            return null;
        }

        $phone = trim($phone);
        if ($phone === '') {
            return null;
        }

        $util = PhoneNumberUtil::getInstance();
        $region = CountryRegion::toIso2($countryHint);

        if ($region === null && ! str_starts_with($phone, '+')) {
            return null;
        }

        try {
            $parsed = $util->parse($phone, $region ?? PhoneNumberUtil::UNKNOWN_REGION);
        } catch (NumberParseException) {
            return null;
        }

        if (! $util->isValidNumber($parsed)) {
            return null;
        }

        $e164 = $util->format($parsed, PhoneNumberFormat::E164);

        return ltrim($e164, '+');
    }
}
