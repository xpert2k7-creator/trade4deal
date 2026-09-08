<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('login_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable();
            $table->string('email')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('device', 100)->nullable();
            $table->string('location', 150)->nullable();
            $table->boolean('successful')->default(true);
            $table->boolean('suspicious')->default(false);
            $table->unsignedTinyInteger('status')->default(1);
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->index('user_id');
            $table->index('ip_address');
            $table->index('successful');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('login_histories');
    }
};
