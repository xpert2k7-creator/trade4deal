<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domains\Lead\Models\Lead;
use App\Support\Enums\BusinessType;
use App\Support\Enums\Currency;
use App\Support\Enums\LeadUnit;
use App\Support\Enums\PaymentMethod;
use App\Support\Enums\ProductType;
use App\Support\Enums\RecordStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Lead>
 */
class LeadFactory extends Factory
{
    protected $model = Lead::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_name' => fake()->company(),
            'contact_name' => fake()->name(),
            'email' => fake()->unique()->companyEmail(),
            'phone' => fake()->phoneNumber(),
            'country' => fake()->country(),
            'business_type' => fake()->randomElement(BusinessType::cases()),
            'product_interest' => fake()->randomElement([
                'Industrial Machinery',
                'Textiles & Apparel',
                'Electronics Components',
                'Agricultural Products',
                'Construction Materials',
                'Medical Equipment',
            ]),
            'product_type' => fake()->randomElement(ProductType::cases()),
            'product_image_path' => null,
            'currency' => fake()->randomElement(Currency::cases())->value,
            'units' => fake()->randomElement(LeadUnit::cases())->value,
            'payment_methods' => [PaymentMethod::WireTransfer->value, PaymentMethod::LetterOfCredit->value],
            'message' => fake()->optional()->sentence(),
            'status' => RecordStatus::Active,
            'published_at' => now()->subDays(2),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (): array => [
            'status' => RecordStatus::Pending,
            'published_at' => null,
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (): array => [
            'status' => RecordStatus::Inactive,
            'published_at' => null,
        ]);
    }

    public function publishedRecently(): static
    {
        return $this->state(fn (): array => [
            'status' => RecordStatus::Active,
            'published_at' => now()->subHour(),
        ]);
    }
}
