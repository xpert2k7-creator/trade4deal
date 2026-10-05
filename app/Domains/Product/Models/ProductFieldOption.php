<?php

declare(strict_types=1);

namespace App\Domains\Product\Models;

use App\Support\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductFieldOption extends Model
{
    use HasUuid;

    protected $fillable = [
        'product_field_id',
        'label',
        'value',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function productField(): BelongsTo
    {
        return $this->belongsTo(ProductField::class);
    }
}
