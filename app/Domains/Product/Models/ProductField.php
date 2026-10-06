<?php

declare(strict_types=1);

namespace App\Domains\Product\Models;

use App\Support\Enums\ProductFieldType;
use App\Support\Enums\RecordStatus;
use App\Support\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductField extends Model
{
    use HasUuid;

    protected $fillable = [
        'category_id',
        'section',
        'field_name',
        'field_key',
        'field_type',
        'unit',
        'is_required',
        'sort_order',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'field_type' => ProductFieldType::class,
            'is_required' => 'boolean',
            'sort_order' => 'integer',
            'status' => RecordStatus::class,
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(ProductFieldOption::class)->orderBy('sort_order');
    }

    public function values(): HasMany
    {
        return $this->hasMany(ProductFieldValue::class);
    }
}
