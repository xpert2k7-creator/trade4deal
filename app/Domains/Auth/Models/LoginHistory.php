<?php

declare(strict_types=1);

namespace App\Domains\Auth\Models;

use App\Support\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoginHistory extends BaseModel
{
    protected $table = 'login_histories';

    protected $fillable = [
        'user_id',
        'email',
        'ip_address',
        'user_agent',
        'device',
        'location',
        'successful',
        'suspicious',
        'status',
    ];

    protected $casts = [
        'successful' => 'boolean',
        'suspicious' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
