<?php

declare(strict_types=1);

namespace App\Domains\Lead\Models;

use App\Models\User;
use App\Support\Enums\BusinessType;
use App\Support\Enums\Currency;
use App\Support\Enums\LeadUnit;
use App\Support\Enums\PaymentMethod;
use App\Support\Enums\ProductType;
use App\Support\Enums\RecordStatus;
use App\Support\Models\BaseModel;
use Database\Factories\LeadFactory;
use Illuminate\Database\Eloquent\Casts\AsEnumCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lead extends BaseModel
{
    /** @use HasFactory<LeadFactory> */
    use HasFactory;

    protected $fillable = [
        'company_name',
        'contact_name',
        'email',
        'phone',
        'country',
        'business_type',
        'product_interest',
        'product_type',
        'product_image_path',
        'currency',
        'units',
        'payment_methods',
        'message',
        'user_id',
        'status',
        'published_at',
    ];

    protected $casts = [
        'business_type' => BusinessType::class,
        'product_type' => ProductType::class,
        'currency' => Currency::class,
        'units' => LeadUnit::class,
        'payment_methods' => 'array',
        'status' => RecordStatus::class,
        'published_at' => 'datetime',
    ];

    protected static function newFactory(): LeadFactory
    {
        return LeadFactory::new();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getRouteKeyName(): string
    {
        return 'id';
    }

    public function productImageUrl(): ?string
    {
        if ($this->product_image_path === null) {
            return null;
        }

        return '/uploads/'.ltrim($this->product_image_path, '/');
    }

    /**
     * @return array<int, PaymentMethod>
     */
    public function paymentMethodEnums(): array
    {
        return collect($this->payment_methods ?? [])
            ->map(fn (string $method) => PaymentMethod::from($method))
            ->all();
    }
}
