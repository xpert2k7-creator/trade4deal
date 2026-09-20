<?php

declare(strict_types=1);

namespace App\Models;

use App\Domains\Product\Models\Product;
use App\Support\Enums\EmployeesRange;
use App\Support\Enums\ProductType;
use App\Support\Enums\RecordStatus;
use App\Support\Enums\UserPlan;
use App\Support\Enums\UserType;
use App\Support\Traits\Auditable;
use App\Support\Traits\HasUuid;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Cashier\Billable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use Auditable;
    use Billable;
    use HasFactory;
    use HasRoles;
    use HasUuid;
    use Notifiable;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'designation',
        'secondary_phone',
        'company_name',
        'slug',
        'logo_path',
        'cover_image_path',
        'tagline',
        'about',
        'website',
        'gstin',
        'cin',
        'pan',
        'address',
        'address_house_block',
        'address_area_street',
        'city',
        'district',
        'state',
        'pin_code',
        'industries',
        'year_established',
        'employees_range',
        'is_public',
        'country',
        'user_type',
        'plan',
        'status',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'user_type' => UserType::class,
            'plan' => UserPlan::class,
            'status' => RecordStatus::class,
            'industries' => 'array',
            'is_public' => 'boolean',
            'year_established' => 'integer',
            'employees_range' => EmployeesRange::class,
        ];
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function liveProducts(): HasMany
    {
        return $this->products()->where('status', RecordStatus::Active)->latest();
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin') || $this->user_type === UserType::Admin;
    }

    public function isEmployee(): bool
    {
        return $this->hasRole('employee') || $this->user_type === UserType::Employee;
    }

    public function isSeller(): bool
    {
        return $this->user_type === UserType::Seller || $this->hasRole('seller');
    }

    public function isBuyer(): bool
    {
        return $this->user_type === UserType::Buyer || $this->hasRole('buyer');
    }

    public function canModerateLeads(): bool
    {
        return $this->isAdmin() || $this->isEmployee() || $this->hasAnyRole(['admin', 'employee']);
    }

    public function userPlan(): UserPlan
    {
        $planName = config('trade4deal.gold_plan_name', 'gold');

        if ($this->subscribed($planName)) {
            return UserPlan::Gold;
        }

        return $this->plan ?? UserPlan::Free;
    }

    public function syncPlanFromSubscription(): void
    {
        $planName = config('trade4deal.gold_plan_name', 'gold');
        $plan = $this->subscribed($planName) ? UserPlan::Gold : UserPlan::Free;

        if ($this->plan !== $plan) {
            $this->forceFill(['plan' => $plan])->save();
        }
    }

    public function ensureSellerSlug(): string
    {
        if (filled($this->slug)) {
            return $this->slug;
        }

        $base = Str::slug($this->company_name ?: $this->name) ?: 'seller';
        $slug = $base;
        $i = 1;

        while (static::query()->where('slug', $slug)->where('id', '!=', $this->id)->exists()) {
            $slug = $base.'-'.$i;
            $i++;
        }

        $this->forceFill(['slug' => $slug])->save();

        return $slug;
    }

    public function logoUrl(): ?string
    {
        return $this->logo_path ? '/uploads/'.ltrim($this->logo_path, '/') : null;
    }

    public function coverImageUrl(): ?string
    {
        return $this->cover_image_path ? '/uploads/'.ltrim($this->cover_image_path, '/') : null;
    }

    /**
     * @return array<int, ProductType>
     */
    public function industryEnums(): array
    {
        return collect($this->industries ?? [])
            ->map(fn (string $value) => ProductType::tryFrom($value))
            ->filter()
            ->values()
            ->all();
    }

    public function profileCompleteness(): int
    {
        $checks = [
            filled($this->company_name),
            filled($this->tagline),
            filled($this->about),
            filled($this->country),
            filled($this->city),
            filled($this->designation),
            filled($this->address_house_block) || filled($this->address),
            filled($this->logo_path),
            filled($this->website) || filled($this->phone),
            ! empty($this->industries),
        ];

        $done = count(array_filter($checks));

        return (int) round(($done / count($checks)) * 100);
    }

    public function hasPublicStorefront(): bool
    {
        return $this->isSeller()
            && $this->is_public
            && filled($this->slug)
            && $this->status === RecordStatus::Active;
    }
}
