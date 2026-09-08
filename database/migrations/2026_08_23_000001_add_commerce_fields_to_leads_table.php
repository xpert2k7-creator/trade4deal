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
            $table->string('product_type')->nullable()->after('product_interest');
            $table->string('product_image_path')->nullable()->after('product_type');
            $table->string('currency', 3)->nullable()->after('product_image_path');
            $table->string('units')->nullable()->after('currency');
            $table->json('payment_methods')->nullable()->after('units');
            $table->timestamp('published_at')->nullable()->after('status');

            $table->index('product_type');
            $table->index('published_at');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropIndex(['product_type']);
            $table->dropIndex(['published_at']);
            $table->dropColumn([
                'product_type',
                'product_image_path',
                'currency',
                'units',
                'payment_methods',
                'published_at',
            ]);
        });
    }
};
