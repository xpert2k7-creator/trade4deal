<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table): void {
            if (! Schema::hasColumn('leads', 'assigned_to')) {
                $table->foreignUuid('assigned_to')->nullable()->after('created_by')->constrained('users')->nullOnDelete();
                $table->index('assigned_to');
            }

            if (! Schema::hasColumn('leads', 'assigned_at')) {
                $table->timestamp('assigned_at')->nullable()->after('assigned_to');
            }

            if (! Schema::hasColumn('leads', 'assigned_by')) {
                $table->foreignUuid('assigned_by')->nullable()->after('assigned_at')->constrained('users')->nullOnDelete();
            }
        });

        // Existing employee-submitted leads stay visible to that employee.
        // CURRENT_TIMESTAMP works on both MySQL and SQLite (tests).
        DB::table('leads')
            ->where('source', 'employee')
            ->whereNotNull('created_by')
            ->whereNull('assigned_to')
            ->update([
                'assigned_to' => DB::raw('created_by'),
                'assigned_at' => DB::raw('COALESCE(created_at, CURRENT_TIMESTAMP)'),
                'assigned_by' => DB::raw('created_by'),
            ]);
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table): void {
            if (Schema::hasColumn('leads', 'assigned_by')) {
                $table->dropConstrainedForeignId('assigned_by');
            }
            if (Schema::hasColumn('leads', 'assigned_at')) {
                $table->dropColumn('assigned_at');
            }
            if (Schema::hasColumn('leads', 'assigned_to')) {
                $table->dropConstrainedForeignId('assigned_to');
            }
        });
    }
};
