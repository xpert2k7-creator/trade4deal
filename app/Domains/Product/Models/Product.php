<?php

declare(strict_types=1);

namespace App\Domains\Product\Models;

use App\Models\User;
use App\Support\Enums\Currency;
use App\Support\Enums\LeadUnit;
use App\Support\Enums\ProductType;
use App\Support\Enums\RecordStatus;
use App\Support\Models\BaseModel;
use App\Support\Storage\PublicUploads;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends BaseModel
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'product_type',
        'image_path',
        'image_paths',
        'currency',
        'units',
        'min_order_qty',
        'price_from',
        'price_to',
        'status',
    ];

    protected $casts = [
        'product_type' => ProductType::class,
        'currency' => Currency::class,
        'units' => LeadUnit::class,
        'price_from' => 'decimal:2',
        'price_to' => 'decimal:2',
        'status' => RecordStatus::class,
        'image_paths' => 'array',
    ];

    protected static function newFactory(): ProductFactory
    {
        return ProductFactory::new();
    }

    protected static function booted(): void
    {
        static::creating(function (Product $product): void {
            if (blank($product->slug)) {
                $product->slug = static::uniqueSlugForUser(
                    (string) $product->user_id,
                    $product->name,
                );
            }
        });
    }

    public static function uniqueSlugForUser(string $userId, string $name, ?string $ignoreId = null): string
    {
        $base = Str::slug(Str::limit($name, 60, '')) ?: 'product';
        $slug = $base;
        $i = 1;

        while (
            static::query()
                ->where('user_id', $userId)
                ->where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base.'-'.$i;
            $i++;
        }

        return $slug;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function fieldValues(): HasMany
    {
        return $this->hasMany(ProductFieldValue::class);
    }

    public function getRouteKeyName(): string
    {
        return 'id';
    }

    /**
     * @return array<int, string>
     */
    public function imagePathsList(): array
    {
        if (is_array($this->image_paths) && $this->image_paths !== []) {
            return array_values($this->image_paths);
        }

        if ($this->image_path !== null) {
            return [$this->image_path];
        }

        return [];
    }

    /**
     * @return array<int, string>
     */
    public function imageUrls(): array
    {
        return PublicUploads::urlsForPaths($this->imagePathsList());
    }

    public function imageUrl(): ?string
    {
        $paths = $this->imagePathsList();

        if ($paths === []) {
            return null;
        }

        return PublicUploads::url($paths[0]);
    }

    public function isLive(): bool
    {
        return $this->status === RecordStatus::Active;
    }

    public function priceLabel(): string
    {
        $symbol = $this->currency?->symbol() ?? '';

        if ($this->price_from === null && $this->price_to === null) {
            return 'Price on request';
        }

        if ($this->price_from !== null && $this->price_to !== null && (float) $this->price_from !== (float) $this->price_to) {
            return $symbol.number_format((float) $this->price_from, 2).' – '.$symbol.number_format((float) $this->price_to, 2);
        }

        $amount = $this->price_from ?? $this->price_to;

        return $symbol.number_format((float) $amount, 2);
    }
}
