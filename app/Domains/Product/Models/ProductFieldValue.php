<?php

declare(strict_types=1);

namespace App\Domains\Product\Models;

use App\Support\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductFieldValue extends Model
{
    use HasUuid;

    protected $fillable = [
        'product_id',
        'product_field_id',
        'value',
        'unit',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function productField(): BelongsTo
    {
        return $this->belongsTo(ProductField::class);
    }
}
