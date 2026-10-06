<?php

declare(strict_types=1);

namespace App\Domains\Product\Services;

use App\Domains\Product\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;

class ProductImageStorage
{
    private const DISK = 'public';

    public static function memberProductDirectory(string $memberId): string
    {
        return 'members/'.$memberId.'/products';
    }

    public static function pathBelongsToMember(string $path, string $memberId): bool
    {
        $normalized = ltrim($path, '/');
        $expectedPrefix = self::memberProductDirectory($memberId).'/';

        if (str_starts_with($normalized, $expectedPrefix)) {
            return true;
        }

        // Legacy flat storage (pre member folders).
        return str_starts_with($normalized, 'products/');
    }

    /**
     * @return array<string, mixed>
     */
    public function mergeUploadedImagesIntoProductData(
        Request $request,
        array $data,
        string $memberId,
        ?Product $product = null,
    ): array {
        if ($memberId === '') {
            throw new InvalidArgumentException('Member id is required to store product images.');
        }

        $paths = collect($product?->imagePathsList() ?? []);

        /** @var array<int, string> $removePaths */
        $removePaths = $request->input('remove_product_images', []);
        foreach ($removePaths as $path) {
            if (! is_string($path) || ! $paths->contains($path)) {
                continue;
            }

            if (! self::pathBelongsToMember($path, $memberId)) {
                continue;
            }

            Storage::disk(self::DISK)->delete($path);
            $paths = $paths->reject(fn (string $stored): bool => $stored === $path);
            Log::info('product_image.removed', [
                'member_id' => $memberId,
                'product_id' => $product?->id,
                'path' => $path,
            ]);
        }

        $directory = self::memberProductDirectory($memberId);
        $uploadAttempted = false;

        if ($request->hasFile('product_images')) {
            foreach ($request->file('product_images') as $file) {
                $uploadAttempted = true;
                $stored = $this->storeUploadedFile($file, $directory, $memberId);
                if ($stored !== null) {
                    $paths->push($stored);
                }
            }
        }

        if ($request->hasFile('image')) {
            $uploadAttempted = true;
            $stored = $this->storeUploadedFile($request->file('image'), $directory, $memberId);
            if ($stored !== null) {
                $paths->push($stored);
            }
        }

        $paths = $paths->values()->all();

        $data['image_paths'] = $paths === [] ? null : $paths;
        $data['image_path'] = $paths[0] ?? null;

        unset($data['image'], $data['product_images'], $data['remove_product_images']);

        Log::info('product_image.merge_complete', [
            'member_id' => $memberId,
            'product_id' => $product?->id,
            'product_name' => $data['name'] ?? $product?->name,
            'upload_attempted' => $uploadAttempted,
            'image_count' => count($paths),
            'primary_path' => $data['image_path'],
        ]);

        return $data;
    }

    public function deleteAllImages(Product $product): void
    {
        foreach ($product->imagePathsList() as $path) {
            Storage::disk(self::DISK)->delete($path);
        }
    }

    private function storeUploadedFile(?UploadedFile $file, string $directory, string $memberId): ?string
    {
        if ($file === null) {
            return null;
        }

        if (! $file->isValid()) {
            Log::warning('product_image.upload.invalid', [
                'member_id' => $memberId,
                'directory' => $directory,
                'client_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
                'error_code' => $file->getError(),
                'error_message' => $file->getErrorMessage(),
            ]);

            return null;
        }

        $stored = $file->store($directory, self::DISK);

        if ($stored === false) {
            Log::error('product_image.upload.store_failed', [
                'member_id' => $memberId,
                'directory' => $directory,
                'client_name' => $file->getClientOriginalName(),
            ]);

            return null;
        }

        Log::info('product_image.upload.stored', [
            'member_id' => $memberId,
            'path' => $stored,
            'client_name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
        ]);

        return $stored;
    }
}
