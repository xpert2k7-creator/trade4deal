<?php

declare(strict_types=1);

use App\Models\User;
use App\Support\PhoneNumber;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        User::query()->withTrashed()->orderBy('created_at')->each(function (User $user): void {
            $user->forceFill([
                'phone_normalized' => PhoneNumber::normalize($user->phone, $user->country),
            ])->saveQuietly();
        });

        $duplicateValues = DB::table('users')
            ->select('phone_normalized')
            ->whereNotNull('phone_normalized')
            ->groupBy('phone_normalized')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('phone_normalized');

        foreach ($duplicateValues as $normalized) {
            $ids = User::query()
                ->withTrashed()
                ->where('phone_normalized', $normalized)
                ->orderBy('created_at')
                ->pluck('id');

            foreach ($ids->slice(1) as $duplicateId) {
                User::query()->withTrashed()->whereKey($duplicateId)->update([
                    'phone_normalized' => null,
                ]);
            }
        }
    }

    public function down(): void
    {
        //
    }
};
