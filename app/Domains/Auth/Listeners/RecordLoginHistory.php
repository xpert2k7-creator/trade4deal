<?php

declare(strict_types=1);

namespace App\Domains\Auth\Listeners;

use App\Domains\Auth\Models\LoginHistory;
use App\Support\Enums\RecordStatus;
use Illuminate\Auth\Events\Login;

class RecordLoginHistory
{
    public function handle(Login $event): void
    {
        LoginHistory::query()->create([
            'user_id' => $event->user->getAuthIdentifier(),
            'email' => $event->user->email,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'device' => $this->detectDevice(request()->userAgent() ?? ''),
            'successful' => true,
            'suspicious' => false,
            'status' => RecordStatus::Active,
        ]);
    }

    private function detectDevice(string $userAgent): string
    {
        if (str_contains($userAgent, 'Mobile')) {
            return 'Mobile';
        }

        if (str_contains($userAgent, 'Tablet')) {
            return 'Tablet';
        }

        return 'Desktop';
    }
}
