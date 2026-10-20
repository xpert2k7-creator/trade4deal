<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * Resolves the Gold price from the visitor IP country.
 * India -> INR constant, everywhere else -> USD constant.
 * The server always decides the price; nothing is trusted from the browser.
 */
class GeoPricing
{
    public function countryCode(?Request $request = null): ?string
    {
        $request ??= request();

        $override = strtoupper(trim((string) config('trade4deal.pricing_country_override', '')));
        if (preg_match('/^[A-Z]{2}$/', $override)) {
            return $override;
        }

        $cfCountry = strtoupper(trim((string) $request->header('CF-IPCountry', '')));
        if (preg_match('/^[A-Z]{2}$/', $cfCountry) && $cfCountry !== 'XX') {
            return $cfCountry;
        }

        $ip = (string) $request->ip();
        if ($ip === '' || $this->isPrivateOrReserved($ip)) {
            return null;
        }

        return Cache::remember('geo-pricing.country.'.sha1($ip), now()->addDay(), function () use ($ip): ?string {
            try {
                $response = Http::timeout(2)->acceptJson()->get('https://ipapi.co/'.$ip.'/country/');
                if (! $response->ok()) {
                    return null;
                }
                $code = strtoupper(trim($response->body()));

                return preg_match('/^[A-Z]{2}$/', $code) ? $code : null;
            } catch (\Throwable) {
                return null;
            }
        });
    }

    /**
     * @return array{amount: int, amount_minor: int, currency: string, label: string, country_code: string|null}
     */
    public function price(?Request $request = null): array
    {
        $country = $this->countryCode($request);

        if ($country === 'IN') {
            $amount = (int) config('trade4deal.gold_price_inr', 25000);

            return [
                'amount' => $amount,
                'amount_minor' => $amount * 100,
                'currency' => 'INR',
                'label' => '₹'.number_format($amount),
                'country_code' => $country,
            ];
        }

        $amount = (int) config('trade4deal.gold_price_usd', 265);

        return [
            'amount' => $amount,
            'amount_minor' => $amount * 100,
            'currency' => 'USD',
            'label' => '$'.number_format($amount),
            'country_code' => $country,
        ];
    }

    private function isPrivateOrReserved(string $ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false;
    }
}
