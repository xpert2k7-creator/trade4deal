<?php

namespace App\Support\Storage;

use Closure;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Throwable;

class PublicFileTransaction
{
    private array $created = [];

    private array $obsolete = [];

    public function run(Closure $callback): mixed
    {
        $this->created = $this->obsolete = [];
        try {
            return DB::transaction(function () use ($callback) {
                $result = $callback();
                $obsolete = $this->obsolete;
                DB::afterCommit(fn () => $this->deleteSafely($obsolete));

                return $result;
            });
        } catch (Throwable $exception) {
            $this->deleteSafely($this->created);
            throw $exception;
        } finally {
            $this->created = $this->obsolete = [];
        }
    }

    public function store(UploadedFile $file, string $directory, string $field): string
    {
        $path = $file->store($directory, 'public');
        if (! is_string($path) || $path === '') {
            throw ValidationException::withMessages([$field => 'The file could not be uploaded. Please try again.']);
        }
        $this->created[] = $path;

        return $path;
    }

    public function removeAfterCommit(?string $path): void
    {
        if (filled($path)) {
            $this->obsolete[] = $path;
        }
    }

    private function deleteSafely(array $paths): void
    {
        foreach (array_unique($paths) as $path) {
            try {
                if (! Storage::disk('public')->delete($path)) {
                    report(new \RuntimeException('Could not delete obsolete public file: '.$path));
                }
            } catch (Throwable $exception) {
                report($exception);
            }
        }
    }
}
