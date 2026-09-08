<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domains\Product\Models\Product;
use App\Models\User;
use App\Support\Enums\Currency;
use App\Support\Enums\LeadUnit;
use App\Support\Enums\ProductType;
use App\Support\Enums\RecordStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->words(3, true);

        return [
            'user_id' => User::factory(),
            'name' => ucfirst($name),
            'slug' => str($name)->slug()->toString(),
            'description' => fake()->paragraph(),
            'product_type' => fake()->randomElement(ProductType::cases()),
            'image_path' => null,
            'currency' => Currency::USD,
            'units' => LeadUnit::Pieces,
            'min_order_qty' => (string) fake()->numberBetween(10, 500),
            'price_from' => fake()->randomFloat(2, 10, 500),
            'price_to' => fake()->randomFloat(2, 500, 2000),
            'status' => RecordStatus::Active,
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (): array => [
            'status' => RecordStatus::Inactive,
        ]);
    }
}
