<?php

declare(strict_types=1);

namespace App\Domains\Product\Models;

use App\Support\Enums\RecordStatus;
use App\Support\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasUuid;

    protected $fillable = [
        'key',
        'name',
        'sort_order',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'status' => RecordStatus::class,
        ];
    }

    public function productFields(): HasMany
    {
        return $this->hasMany(ProductField::class)->orderBy('sort_order');
    }
}
