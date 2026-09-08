<?php

declare(strict_types=1);

namespace App\Support\Traits;

use Symfony\Component\Uid\Uuid;

trait HasUuid
{
    public function getIncrementing(): bool
    {
        return false;
    }

    public function getKeyType(): string
    {
        return 'string';
    }

    protected static function bootHasUuid(): void
    {
        static::creating(function ($model): void {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = Uuid::v7()->toRfc4122();
            }
        });
    }
}
