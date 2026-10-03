<?php

declare(strict_types=1);

namespace App\Domains\Lead\Services;

use App\Domains\Lead\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LeadProductImageStorage
{
    private const DISK = 'public';

    private const DIRECTORY = 'leads';

    /**
     * @return array<string, mixed>
     */
    public function mergeUploadedImagesIntoLeadData(Request $request, array $data, ?Lead $lead = null): array
    {
        $paths = collect($lead?->productImagePathsList() ?? []);

        /** @var array<int, string> $removePaths */
        $removePaths = $request->input('remove_product_images', []);
        foreach ($removePaths as $path) {
            if (! is_string($path) || ! $paths->contains($path)) {
                continue;
            }

            Storage::disk(self::DISK)->delete($path);
            $paths = $paths->reject(fn (string $stored): bool => $stored === $path);
        }

        if ($request->hasFile('product_images')) {
            foreach ($request->file('product_images') as $file) {
                if ($file === null) {
                    continue;
                }

                $stored = $file->store(self::DIRECTORY, self::DISK);
                if ($stored !== false) {
                    $paths->push($stored);
                }
            }
        }

        if ($request->hasFile('product_image')) {
            $stored = $request->file('product_image')->store(self::DIRECTORY, self::DISK);
            if ($stored !== false) {
                $paths->push($stored);
            }
        }

        $paths = $paths->values()->all();

        $data['product_image_paths'] = $paths === [] ? null : $paths;
        $data['product_image_path'] = $paths[0] ?? null;

        unset($data['product_image'], $data['product_images'], $data['remove_product_images']);

        return $data;
    }
}
