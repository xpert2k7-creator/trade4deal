<?php

declare(strict_types=1);

namespace App\Support\Storage;

final class PublicUploads
{
    public static function url(?string $relativePath): ?string
    {
        if ($relativePath === null || $relativePath === '') {
            return null;
        }

        return route('uploads.public', ['path' => ltrim($relativePath, '/')]);
    }

    /**
     * @return array<int, string>
     */
    public static function urlsForPaths(array $paths): array
    {
        return array_values(array_filter(array_map(
            fn (string $path): ?string => self::url($path),
            $paths,
        )));
    }
}
