<?php

declare(strict_types=1);

namespace Tests\Feature\Product;

use App\Domains\Product\Models\Category;
use App\Domains\Product\Models\ProductField;
use App\Domains\Product\Models\ProductFieldOption;
use Database\Seeders\ProductCategoryFieldSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCategoryFieldSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_creates_expected_categories_fields_and_idempotent_options(): void
    {
        $this->seed(ProductCategoryFieldSeeder::class);

        $this->assertDatabaseCount('categories', 9);
        $this->assertSame(121, ProductField::query()->count());
        $this->assertSame(0, ProductField::query()->whereHas('category', fn ($q) => $q->where('key', 'machinery'))->count());
        $this->assertTrue(Category::query()->where('key', 'machinery')->exists());
        $this->assertTrue(Category::query()->where('key', 'other')->exists());

        $optionsFirstRun = ProductFieldOption::query()->count();
        $this->assertGreaterThan(0, $optionsFirstRun);

        $this->seed(ProductCategoryFieldSeeder::class);

        $this->assertSame(121, ProductField::query()->count());
        $this->assertSame($optionsFirstRun, ProductFieldOption::query()->count());
    }
}
