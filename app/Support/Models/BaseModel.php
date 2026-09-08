<?php

declare(strict_types=1);

namespace App\Support\Models;

use App\Support\Enums\RecordStatus;
use App\Support\Traits\Auditable;
use App\Support\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

abstract class BaseModel extends Model
{
    use Auditable;
    use HasFactory;
    use HasUuid;
    use SoftDeletes;

    protected $casts = [
        'status' => RecordStatus::class,
    ];

    public function scopeActive($query)
    {
        return $query->where('status', RecordStatus::Active);
    }
}
