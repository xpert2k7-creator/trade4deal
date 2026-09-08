<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('product_type')->index();
            $table->string('image_path')->nullable();
            $table->string('currency', 3)->default('USD');
            $table->string('units', 40)->default('pieces');
            $table->string('min_order_qty', 80)->nullable();
            $table->decimal('price_from', 12, 2)->nullable();
            $table->decimal('price_to', 12, 2)->nullable();
            $table->unsignedTinyInteger('status')->default(1)->index();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['user_id', 'slug']);
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
