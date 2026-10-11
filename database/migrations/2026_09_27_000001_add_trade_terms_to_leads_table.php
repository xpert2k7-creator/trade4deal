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
            if (! Schema::hasColumn('leads', 'packaging_requirement')) {
                $table->text('packaging_requirement')->nullable()->after('payment_methods');
            }
            if (! Schema::hasColumn('leads', 'required_quantity')) {
                $table->decimal('required_quantity', 15, 3)->nullable()->after('packaging_requirement');
            }
            if (! Schema::hasColumn('leads', 'packaging_size')) {
                $table->string('packaging_size', 120)->nullable()->after('required_quantity');
            }
            if (! Schema::hasColumn('leads', 'target_price')) {
                $table->decimal('target_price', 15, 2)->nullable()->after('packaging_size');
            }
            if (! Schema::hasColumn('leads', 'preferred_incoterm')) {
                $table->string('preferred_incoterm', 32)->nullable()->after('target_price');
            }
            if (! Schema::hasColumn('leads', 'port_of_loading')) {
                $table->string('port_of_loading', 120)->nullable()->after('preferred_incoterm');
            }
            if (! Schema::hasColumn('leads', 'destination_port')) {
                $table->string('destination_port', 120)->nullable()->after('port_of_loading');
            }
            if (! Schema::hasColumn('leads', 'payment_terms')) {
                $table->string('payment_terms', 32)->nullable()->after('destination_port');
            }
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $columns = [
                'packaging_requirement',
                'required_quantity',
                'packaging_size',
                'target_price',
                'preferred_incoterm',
                'port_of_loading',
                'destination_port',
                'payment_terms',
            ];

            $existing = array_values(array_filter(
                $columns,
                fn (string $column): bool => Schema::hasColumn('leads', $column),
            ));

            if ($existing !== []) {
                $table->dropColumn($existing);
            }
        });
    }
};
