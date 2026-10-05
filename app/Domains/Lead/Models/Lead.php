<?php

declare(strict_types=1);

namespace App\Domains\Lead\Models;

use App\Models\User;
use App\Support\Enums\BusinessType;
use App\Support\Enums\Currency;
use App\Support\Enums\Incoterm;
use App\Support\Enums\LeadPaymentTerm;
use App\Support\Enums\LeadUnit;
use App\Support\Enums\PaymentMethod;
use App\Support\Enums\ProductType;
use App\Support\Enums\RecordStatus;
use App\Support\Models\BaseModel;
use Database\Factories\LeadFactory;
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
        'product_image_paths',
        'currency',
        'units',
        'payment_methods',
        'packaging_requirement',
        'required_quantity',
        'packaging_size',
        'target_price',
        'preferred_incoterm',
        'port_of_loading',
        'destination_port',
        'payment_terms',
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
        'product_image_paths' => 'array',
        'required_quantity' => 'decimal:3',
        'target_price' => 'decimal:2',
        'preferred_incoterm' => Incoterm::class,
        'payment_terms' => LeadPaymentTerm::class,
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

    /**
     * @return array<int, string>
     */
    public function productImagePathsList(): array
    {
        if (is_array($this->product_image_paths) && $this->product_image_paths !== []) {
            return array_values($this->product_image_paths);
        }

        if ($this->product_image_path !== null) {
            return [$this->product_image_path];
        }

        return [];
    }

    /**
     * @return array<int, string>
     */
    public function productImageUrls(): array
    {
        return array_map(
            fn (string $path): string => '/uploads/'.ltrim($path, '/'),
            $this->productImagePathsList(),
        );
    }

    public function productImageUrl(): ?string
    {
        return $this->productImageUrls()[0] ?? null;
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
