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
            $table->text('packaging_requirement')->nullable()->after('payment_methods');
            $table->decimal('required_quantity', 15, 3)->nullable()->after('packaging_requirement');
            $table->string('packaging_size', 120)->nullable()->after('required_quantity');
            $table->decimal('target_price', 15, 2)->nullable()->after('packaging_size');
            $table->string('preferred_incoterm', 32)->nullable()->after('target_price');
            $table->string('port_of_loading', 120)->nullable()->after('preferred_incoterm');
            $table->string('destination_port', 120)->nullable()->after('port_of_loading');
            $table->string('payment_terms', 32)->nullable()->after('destination_port');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn([
                'packaging_requirement',
                'required_quantity',
                'packaging_size',
                'target_price',
                'preferred_incoterm',
                'port_of_loading',
                'destination_port',
                'payment_terms',
            ]);
        });
    }
};
