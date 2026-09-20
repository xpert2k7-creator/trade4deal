<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('designation', 120)->nullable()->after('phone');
            $table->string('secondary_phone', 30)->nullable()->after('designation');
            $table->string('gstin', 30)->nullable()->after('website');
            $table->string('cin', 30)->nullable()->after('gstin');
            $table->string('pan', 20)->nullable()->after('cin');
            $table->string('address_house_block', 120)->nullable()->after('address');
            $table->string('address_area_street', 180)->nullable()->after('address_house_block');
            $table->string('district', 120)->nullable()->after('city');
            $table->string('state', 120)->nullable()->after('district');
            $table->string('pin_code', 20)->nullable()->after('state');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'designation',
                'secondary_phone',
                'gstin',
                'cin',
                'pan',
                'address_house_block',
                'address_area_street',
                'district',
                'state',
                'pin_code',
            ]);
        });
    }
};
