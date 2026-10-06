<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->string('source', 20)->default('user')->after('user_id');
            $table->string('city', 100)->nullable()->after('country');

            $table->index('source');
            $table->index(['created_by', 'created_at']);
        });
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
