<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table): void {
            $table->string('location_city', 120)->nullable()->after('image_path');
            $table->string('location_state', 120)->nullable()->after('location_city');
            $table->string('location_country', 120)->nullable()->after('location_state');
            $table->string('location_id', 191)->nullable()->after('location_country');

            $table->index('location_id');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table): void {
            $table->dropIndex(['location_id']);
            $table->dropColumn([
                'location_city',
                'location_state',
                'location_country',
                'location_id',
            ]);
        });
    }
};
