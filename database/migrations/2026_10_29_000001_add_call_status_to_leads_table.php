<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table): void {
            if (! Schema::hasColumn('leads', 'call_status')) {
                $table->string('call_status', 40)->nullable()->after('status')->index();
            }

            if (! Schema::hasColumn('leads', 'call_follow_up_at')) {
                $table->timestamp('call_follow_up_at')->nullable()->after('call_status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table): void {
            if (Schema::hasColumn('leads', 'call_follow_up_at')) {
                $table->dropColumn('call_follow_up_at');
            }

            if (Schema::hasColumn('leads', 'call_status')) {
                $table->dropColumn('call_status');
            }
        });
    }
};
