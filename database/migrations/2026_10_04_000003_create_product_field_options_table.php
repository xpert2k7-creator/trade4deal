<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_field_options', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('product_field_id')->constrained('product_fields')->cascadeOnDelete();
            $table->string('label', 180);
            $table->string('value', 180);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['product_field_id', 'value']);
            $table->index(['product_field_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_field_options');
    }
};
