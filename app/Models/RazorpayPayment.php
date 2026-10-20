<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RazorpayPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount_minor',
        'currency',
        'country_code',
        'status',
        'razorpay_order_id',
        'razorpay_payment_id',
        'method',
        'subscription_starts_at',
        'subscription_expires_at',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'amount_minor' => 'integer',
            'subscription_starts_at' => 'datetime',
            'subscription_expires_at' => 'datetime',
            'paid_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function amountLabel(): string
    {
        $major = $this->amount_minor / 100;

        return $this->currency === 'INR'
            ? '₹'.number_format($major, $major == (int) $major ? 0 : 2)
            : '$'.number_format($major, $major == (int) $major ? 0 : 2);
    }
}
