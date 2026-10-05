<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_field_values', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignUuid('product_field_id')->constrained('product_fields')->cascadeOnDelete();
            $table->text('value')->nullable();
            $table->string('unit', 80)->nullable();
            $table->timestamps();

            $table->unique(['product_id', 'product_field_id']);
            $table->index('product_id');
            $table->index('product_field_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_field_values');
    }
};
