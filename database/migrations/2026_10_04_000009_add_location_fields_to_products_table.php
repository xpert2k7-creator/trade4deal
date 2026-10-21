<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $columns = [
            'location_city' => 'image_path',
            'location_state' => 'location_city',
            'location_country' => 'location_state',
            'location_id' => 'location_country',
        ];

        foreach ($columns as $column => $after) {
            if (! Schema::hasColumn('products', $column)) {
                Schema::table('products', function (Blueprint $table) use ($column, $after): void {
                    $table->string($column, $column === 'location_id' ? 191 : 120)->nullable()->after($after);
                });
            }
        }

        if (! Schema::hasIndex('products', 'products_location_id_index')) {
            Schema::table('products', function (Blueprint $table): void {
                $table->index('location_id');
            });
        }
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
