<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('leads', 'source')) {
            Schema::table('leads', function (Blueprint $table) {
                $table->string('source', 20)->default('user')->after('user_id');
            });
        }

        if (! Schema::hasColumn('leads', 'city')) {
            Schema::table('leads', function (Blueprint $table) {
                $table->string('city', 100)->nullable()->after('country');
            });
        }

        if (! Schema::hasIndex('leads', 'leads_source_index')) {
            Schema::table('leads', function (Blueprint $table) {
                $table->index('source');
            });
        }

        if (! Schema::hasIndex('leads', 'leads_created_by_created_at_index')) {
            Schema::table('leads', function (Blueprint $table) {
                $table->index(['created_by', 'created_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropIndex(['source']);
            $table->dropIndex(['created_by', 'created_at']);
            $table->dropColumn(['source', 'city']);
        });
    }
};
