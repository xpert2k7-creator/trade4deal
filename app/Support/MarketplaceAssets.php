<?php

declare(strict_types=1);

namespace App\Support;

final class MarketplaceAssets
{
    public static function url(string $path, bool $withVersion = true): string
    {
        $url = asset($path);

        if (! $withVersion) {
            return $url;
        }

        $version = config('marketplace_assets.version');

        return $version !== null && $version !== ''
            ? $url.'?v='.rawurlencode((string) $version)
            : $url;
    }

    public static function path(string $key): string
    {
        $value = config('marketplace_assets.'.$key);

        if (! is_string($value) || $value === '') {
            throw new \InvalidArgumentException("Unknown marketplace asset key: {$key}");
        }

        return $value;
    }
}
