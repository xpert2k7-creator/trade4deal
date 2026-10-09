<?php

declare(strict_types=1);

namespace App\Support;

use libphonenumber\PhoneNumberUtil;

final class CountryRegion
{
    /** @var array<string, string> lowercase alias => ISO 3166-1 alpha-2 */
    private const ALIASES = [
        'india' => 'IN',
        'united states' => 'US',
        'united states of america' => 'US',
        'usa' => 'US',
        'u.s.a.' => 'US',
        'u.s.' => 'US',
        'us' => 'US',
        'united kingdom' => 'GB',
        'uk' => 'GB',
        'u.k.' => 'GB',
        'great britain' => 'GB',
        'england' => 'GB',
        'canada' => 'CA',
        'australia' => 'AU',
        'germany' => 'DE',
        'france' => 'FR',
        'italy' => 'IT',
        'spain' => 'ES',
        'netherlands' => 'NL',
        'belgium' => 'BE',
        'switzerland' => 'CH',
        'sweden' => 'SE',
        'norway' => 'NO',
        'denmark' => 'DK',
        'finland' => 'FI',
        'poland' => 'PL',
        'turkey' => 'TR',
        'türkiye' => 'TR',
        'turkiye' => 'TR',
        'china' => 'CN',
        'japan' => 'JP',
        'south korea' => 'KR',
        'korea' => 'KR',
        'singapore' => 'SG',
        'malaysia' => 'MY',
        'indonesia' => 'ID',
        'thailand' => 'TH',
        'vietnam' => 'VN',
        'viet nam' => 'VN',
        'philippines' => 'PH',
        'united arab emirates' => 'AE',
        'uae' => 'AE',
        'saudi arabia' => 'SA',
        'qatar' => 'QA',
        'kuwait' => 'KW',
        'bahrain' => 'BH',
        'oman' => 'OM',
        'pakistan' => 'PK',
        'bangladesh' => 'BD',
        'sri lanka' => 'LK',
        'nepal' => 'NP',
        'south africa' => 'ZA',
        'nigeria' => 'NG',
        'kenya' => 'KE',
        'egypt' => 'EG',
        'brazil' => 'BR',
        'mexico' => 'MX',
        'argentina' => 'AR',
        'chile' => 'CL',
        'colombia' => 'CO',
        'new zealand' => 'NZ',
        'ireland' => 'IE',
        'portugal' => 'PT',
        'austria' => 'AT',
        'czech republic' => 'CZ',
        'czechia' => 'CZ',
        'hungary' => 'HU',
        'romania' => 'RO',
        'israel' => 'IL',
        'hong kong' => 'HK',
        'taiwan' => 'TW',
        'russia' => 'RU',
        'ukraine' => 'UA',
    ];

    public static function toIso2(?string $country): ?string
    {
        if ($country === null) {
            return null;
        }

        $country = trim($country);
        if ($country === '') {
            return null;
        }

        $supported = class_exists(PhoneNumberUtil::class)
            ? PhoneNumberUtil::getInstance()->getSupportedRegions()
            : array_values(self::ALIASES);

        if (strlen($country) === 2 && ctype_alpha($country)) {
            $iso = strtoupper($country);

            return in_array($iso, $supported, true) ? $iso : null;
        }

        $alias = self::ALIASES[strtolower($country)] ?? null;
        if ($alias !== null) {
            return $alias;
        }

        return null;
    }
}
