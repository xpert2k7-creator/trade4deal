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

        if (! class_exists(PhoneNumberUtil::class)) {
            return self::normalizeWithoutLibPhoneNumber($phone, $countryHint);
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

    private static function normalizeWithoutLibPhoneNumber(string $phone, ?string $countryHint = null): ?string
    {
        $digits = preg_replace('/\D+/', '', $phone) ?? '';
        if ($digits === '') {
            return null;
        }

        $region = CountryRegion::toIso2($countryHint);
        $dialCode = match ($region) {
            'IN' => '91',
            'US', 'CA' => '1',
            'GB' => '44',
            'AE' => '971',
            'SA' => '966',
            'AU' => '61',
            'SG' => '65',
            'DE' => '49',
            'FR' => '33',
            'CN' => '86',
            'JP' => '81',
            'BD' => '880',
            'PK' => '92',
            'NP' => '977',
            'LK' => '94',
            'NG' => '234',
            'ZA' => '27',
            default => null,
        };

        if (str_starts_with($phone, '+')) {
            return strlen($digits) >= 8 && strlen($digits) <= 15 ? $digits : null;
        }

        if ($dialCode === null) {
            return null;
        }

        if (str_starts_with($digits, '0')) {
            $digits = ltrim($digits, '0');
        }

        if (str_starts_with($digits, $dialCode) && strlen($digits) > strlen($dialCode) + 6) {
            return strlen($digits) <= 15 ? $digits : null;
        }

        $normalized = $dialCode.$digits;

        return strlen($digits) >= 8 && strlen($normalized) <= 15 ? $normalized : null;
    }
}
