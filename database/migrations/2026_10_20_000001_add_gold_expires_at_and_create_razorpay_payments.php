<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            if (! Schema::hasColumn('users', 'gold_expires_at')) {
                $table->timestamp('gold_expires_at')->nullable()->after('plan');
            }
        });

        // Grandfather existing Gold users with 1 year from release.
        DB::table('users')
            ->where('plan', 'gold')
            ->whereNull('gold_expires_at')
            ->update(['gold_expires_at' => now()->addYear()]);

        if (! Schema::hasTable('razorpay_payments')) {
            Schema::create('razorpay_payments', function (Blueprint $table): void {
                $table->id();
                $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
                $table->unsignedBigInteger('amount_minor');
                $table->string('currency', 3);
                $table->string('country_code', 2)->nullable();
                $table->string('status', 20)->default('created')->index();
                $table->string('razorpay_order_id')->unique();
                $table->string('razorpay_payment_id')->nullable()->unique();
                $table->string('method')->nullable();
                $table->timestamp('subscription_starts_at')->nullable();
                $table->timestamp('subscription_expires_at')->nullable();
                $table->timestamp('paid_at')->nullable()->index();
                $table->timestamps();

                $table->index(['user_id', 'created_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('razorpay_payments');

        Schema::table('users', function (Blueprint $table): void {
            if (Schema::hasColumn('users', 'gold_expires_at')) {
                $table->dropColumn('gold_expires_at');
            }
        });
    }
};
