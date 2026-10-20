<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\RazorpayPayment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RazorpayPayment>
 */
class RazorpayPaymentFactory extends Factory
{
    protected $model = RazorpayPayment::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'amount_minor' => 2500000,
            'currency' => 'INR',
            'country_code' => 'IN',
            'status' => 'created',
            'razorpay_order_id' => 'order_'.fake()->unique()->lexify('??????????????'),
            'razorpay_payment_id' => null,
            'method' => null,
            'subscription_starts_at' => null,
            'subscription_expires_at' => null,
            'paid_at' => null,
        ];
    }

    public function paid(): static
    {
        return $this->state(fn (): array => [
            'status' => 'paid',
            'razorpay_payment_id' => 'pay_'.fake()->unique()->lexify('??????????????'),
            'method' => 'upi',
            'subscription_starts_at' => now(),
            'subscription_expires_at' => now()->addYear(),
            'paid_at' => now(),
        ]);
    }
}
