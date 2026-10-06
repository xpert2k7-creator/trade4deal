<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Str;

class Location
{
    public static function id(?string $city, ?string $state, ?string $country): ?string
    {
        $city = self::clean($city);
        $state = self::clean($state);
        $country = self::clean($country);

        if ($city === null || $country === null) {
            return null;
        }

        return collect([
            'city' => $city,
            'state' => $state,
            'country' => $country,
        ])
            ->filter()
            ->map(fn (string $value, string $key): string => $key.':'.Str::slug(Str::ascii($value)))
            ->implode('|');
    }

    public static function isValidId(?string $id): bool
    {
        if ($id === null || $id === '' || strlen($id) > 191) {
            return false;
        }

        return preg_match('/^city:[a-z0-9]+(?:-[a-z0-9]+)*(?:\|state:[a-z0-9]+(?:-[a-z0-9]+)*)?\|country:[a-z0-9]+(?:-[a-z0-9]+)*$/', $id) === 1;
    }

    public static function label(?string $city, ?string $state, ?string $country): string
    {
        return collect([self::clean($city), self::clean($state), self::clean($country)])
            ->filter()
            ->implode(', ');
    }

    public static function cityLabel(?string $city): string
    {
        return self::clean($city) ?? 'Select location';
    }

    public static function clean(?string $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : Str::limit($value, 120, '');
    }
}
