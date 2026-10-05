<?php

declare(strict_types=1);

use App\Models\User;
use App\Support\PhoneNumber;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_normalized', 20)->nullable()->after('phone');
        });

        User::query()->withTrashed()->orderBy('created_at')->each(function (User $user): void {
            $normalized = PhoneNumber::normalize($user->phone, $user->country);
            if ($normalized === null) {
                return;
            }

            $user->forceFill(['phone_normalized' => $normalized])->saveQuietly();
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

        Schema::table('users', function (Blueprint $table) {
            $table->unique('phone_normalized');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['phone_normalized']);
            $table->dropColumn('phone_normalized');
        });
    }
};
