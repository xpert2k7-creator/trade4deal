<?php

declare(strict_types=1);

namespace App\Support\Traits;

trait Auditable
{
    protected static function bootAuditable(): void
    {
        static::creating(function ($model): void {
            if (auth()->check()) {
                $model->created_by = auth()->id();
            }
        });

        static::updating(function ($model): void {
            if (auth()->check()) {
                $model->updated_by = auth()->id();
            }
        });

        static::deleting(function ($model): void {
            if (
                auth()->check()
                && method_exists($model, 'isForceDeleting')
                && ! $model->isForceDeleting()
            ) {
                $model->deleted_by = auth()->id();
                $model->saveQuietly();
            }
        });
    }
}
