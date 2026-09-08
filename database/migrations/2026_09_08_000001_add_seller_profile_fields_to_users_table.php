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
            $table->string('slug')->nullable()->unique()->after('company_name');
            $table->string('logo_path')->nullable()->after('slug');
            $table->string('cover_image_path')->nullable()->after('logo_path');
            $table->string('tagline', 200)->nullable()->after('cover_image_path');
            $table->text('about')->nullable()->after('tagline');
            $table->string('website')->nullable()->after('about');
            $table->string('address')->nullable()->after('website');
            $table->string('city', 120)->nullable()->after('address');
            $table->json('industries')->nullable()->after('city');
            $table->unsignedSmallInteger('year_established')->nullable()->after('industries');
            $table->string('employees_range', 40)->nullable()->after('year_established');
            $table->boolean('is_public')->default(true)->after('employees_range');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'slug',
                'logo_path',
                'cover_image_path',
                'tagline',
                'about',
                'website',
                'address',
                'city',
                'industries',
                'year_established',
                'employees_range',
                'is_public',
            ]);
        });
    }
};
