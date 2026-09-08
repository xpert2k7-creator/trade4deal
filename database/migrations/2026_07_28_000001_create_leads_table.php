<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('company_name');
            $table->string('contact_name');
            $table->string('email');
            $table->string('phone', 30)->nullable();
            $table->string('country', 100);
            $table->string('business_type', 20);
            $table->string('product_interest');
            $table->text('message')->nullable();
            $table->uuid('user_id')->nullable();
            $table->unsignedTinyInteger('status')->default(1);
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->index('status');
            $table->index('business_type');
            $table->index('country');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
