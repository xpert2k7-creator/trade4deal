<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Domains\Product\Models\Product;
use App\Models\User;
use App\Support\Enums\ProductType;
use App\Support\Enums\RecordStatus;
use App\Support\Enums\UserType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeCategoryProductsTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_shows_up_to_twelve_products_per_category(): void
    {
        $seller = User::factory()->seller()->create([
            'user_type' => UserType::Seller,
            'status' => RecordStatus::Active,
            'is_public' => true,
        ]);

        foreach (range(1, 12) as $i) {
            Product::factory()->create([
                'user_id' => $seller->id,
                'name' => "Machinery Product {$i}",
                'product_type' => ProductType::Machinery,
                'status' => RecordStatus::Active,
                'created_at' => now()->subDays(40)->addMinutes($i),
                'updated_at' => now()->subDays(40)->addMinutes($i),
            ]);
        }

        Product::factory()->create([
            'user_id' => $seller->id,
            'name' => 'Machinery Product Extra',
            'product_type' => ProductType::Machinery,
            'status' => RecordStatus::Active,
            'created_at' => now()->subDays(50),
            'updated_at' => now()->subDays(50),
        ]);

        // Flood newer products in another category so a global newest-72
        // query would starve machinery listings.
        foreach (range(1, 70) as $i) {
            Product::factory()->create([
                'user_id' => $seller->id,
                'name' => "Textile Product {$i}",
                'product_type' => ProductType::Textiles,
                'status' => RecordStatus::Active,
                'created_at' => now()->subMinutes($i),
                'updated_at' => now()->subMinutes($i),
            ]);
        }

        $response = $this->get(route('home'))->assertOk();

        foreach (range(1, 12) as $i) {
            $response->assertSee("Machinery Product {$i}");
            $response->assertSee("Textile Product {$i}");
        }

        $response->assertDontSee('Machinery Product Extra');
        $response->assertDontSee('Textile Product 13');
    }
}
