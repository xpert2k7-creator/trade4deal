<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Domains\Product\Models\Product;
use App\Models\User;
use App\Support\Enums\ProductType;
use App\Support\Enums\RecordStatus;
use App\Support\Enums\UserType;
use App\Support\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarketplaceProductDirectoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_directory_shows_active_public_seller_products(): void
    {
        $publicSeller = User::factory()->seller()->create([
            'company_name' => 'Surat Textile House',
            'city' => 'Surat',
            'country' => 'India',
            'user_type' => UserType::Seller,
            'status' => RecordStatus::Active,
            'is_public' => true,
        ]);

        $privateSeller = User::factory()->seller()->create([
            'company_name' => 'Hidden Supplier',
            'city' => 'Mumbai',
            'country' => 'India',
            'is_public' => false,
        ]);

        Product::factory()->create([
            'user_id' => $publicSeller->id,
            'name' => 'Surat Cotton Listing',
            'product_type' => ProductType::Textiles,
            'status' => RecordStatus::Active,
        ]);

        Product::factory()->create([
            'user_id' => $privateSeller->id,
            'name' => 'Hidden Mumbai Listing',
            'product_type' => ProductType::Textiles,
            'status' => RecordStatus::Active,
        ]);

        Product::factory()->draft()->create([
            'user_id' => $publicSeller->id,
            'name' => 'Draft Surat Listing',
            'product_type' => ProductType::Textiles,
        ]);

        $this->get(route('marketplace.page', ['page' => 'product-directory']))
            ->assertOk()
            ->assertSee('Surat Cotton Listing')
            ->assertSee('Surat Textile House')
            ->assertDontSee('Hidden Mumbai Listing')
            ->assertDontSee('Draft Surat Listing');
    }

    public function test_product_directory_filters_products_by_listing_location(): void
    {
        $suratSeller = User::factory()->seller()->create([
            'company_name' => 'Surat Textile House',
            'city' => 'Ahmedabad',
            'country' => 'India',
            'is_public' => true,
        ]);

        $mumbaiSeller = User::factory()->seller()->create([
            'company_name' => 'Mumbai Electronics Hub',
            'city' => 'Mumbai',
            'country' => 'India',
            'is_public' => true,
        ]);

        Product::factory()->create([
            'user_id' => $suratSeller->id,
            'name' => 'Surat Cotton Listing',
            'product_type' => ProductType::Textiles,
            'status' => RecordStatus::Active,
            'location_city' => 'Surat',
            'location_state' => 'Gujarat',
            'location_country' => 'India',
            'location_id' => Location::id('Surat', 'Gujarat', 'India'),
        ]);

        Product::factory()->create([
            'user_id' => $mumbaiSeller->id,
            'name' => 'Mumbai Circuit Listing',
            'product_type' => ProductType::Electronics,
            'status' => RecordStatus::Active,
            'location_city' => 'Mumbai',
            'location_state' => 'Maharashtra',
            'location_country' => 'India',
            'location_id' => Location::id('Mumbai', 'Maharashtra', 'India'),
        ]);

        $this->get(route('marketplace.page', [
            'page' => 'product-directory',
            'location_id' => Location::id('Surat', 'Gujarat', 'India'),
            'location_label' => 'Surat, Gujarat, India',
        ]))
            ->assertOk()
            ->assertSee('Surat Cotton Listing')
            ->assertSee('in Surat, Gujarat, India')
            ->assertDontSee('Mumbai Circuit Listing');
    }

    public function test_location_filter_includes_products_without_listing_location(): void
    {
        $seller = User::factory()->seller()->create([
            'company_name' => 'Public Seller',
            'is_public' => true,
        ]);

        Product::factory()->create([
            'user_id' => $seller->id,
            'name' => 'Delhi Industrial Pump',
            'product_type' => ProductType::Machinery,
            'location_city' => 'Delhi',
            'location_state' => 'Delhi',
            'location_country' => 'India',
            'location_id' => Location::id('Delhi', 'Delhi', 'India'),
        ]);

        Product::factory()->create([
            'user_id' => $seller->id,
            'name' => 'Noida Industrial Pump',
            'product_type' => ProductType::Machinery,
            'location_city' => 'Noida',
            'location_state' => 'Uttar Pradesh',
            'location_country' => 'India',
            'location_id' => Location::id('Noida', 'Uttar Pradesh', 'India'),
        ]);

        Product::factory()->create([
            'user_id' => $seller->id,
            'name' => 'Unknown Industrial Pump',
            'product_type' => ProductType::Machinery,
        ]);

        $this->get(route('marketplace.page', [
            'page' => 'product-directory',
            'search' => 'Industrial Pump',
            'location_id' => Location::id('Delhi', 'Delhi', 'India'),
            'location_label' => 'Delhi, Delhi, India',
        ]))
            ->assertOk()
            ->assertSee('Delhi Industrial Pump')
            ->assertSee('Unknown Industrial Pump')
            ->assertDontSee('Noida Industrial Pump');
    }

    public function test_duplicate_city_names_are_distinguished_by_canonical_location_id(): void
    {
        $seller = User::factory()->seller()->create([
            'company_name' => 'Public Seller',
            'is_public' => true,
        ]);

        Product::factory()->create([
            'user_id' => $seller->id,
            'name' => 'Springfield Illinois Listing',
            'location_city' => 'Springfield',
            'location_state' => 'Illinois',
            'location_country' => 'United States',
            'location_id' => Location::id('Springfield', 'Illinois', 'United States'),
        ]);

        Product::factory()->create([
            'user_id' => $seller->id,
            'name' => 'Springfield Missouri Listing',
            'location_city' => 'Springfield',
            'location_state' => 'Missouri',
            'location_country' => 'United States',
            'location_id' => Location::id('Springfield', 'Missouri', 'United States'),
        ]);

        $this->get(route('marketplace.page', [
            'page' => 'product-directory',
            'location_id' => Location::id('Springfield', 'Missouri', 'United States'),
            'location_label' => 'Springfield, Missouri, United States',
        ]))
            ->assertOk()
            ->assertSee('Springfield Missouri Listing')
            ->assertDontSee('Springfield Illinois Listing');
    }

    public function test_location_search_returns_common_city_suggestions_without_product_data(): void
    {
        $this->getJson(route('locations.search', ['q' => 'Delhi']))
            ->assertOk()
            ->assertJsonFragment([
                'id' => Location::id('Delhi', 'Delhi', 'India'),
                'label' => 'Delhi',
                'full_label' => 'Delhi, Delhi, India',
            ]);
    }

    public function test_location_search_returns_international_city_suggestions(): void
    {
        $this->getJson(route('locations.search', ['q' => 'London']))
            ->assertOk()
            ->assertJsonFragment([
                'id' => Location::id('London', 'England', 'United Kingdom'),
                'label' => 'London',
                'full_label' => 'London, England, United Kingdom',
            ]);
    }

    public function test_location_search_matches_country_aliases(): void
    {
        $this->getJson(route('locations.search', ['q' => 'UAE']))
            ->assertOk()
            ->assertJsonFragment([
                'id' => Location::id('Dubai', 'Dubai', 'United Arab Emirates'),
                'label' => 'Dubai',
                'full_label' => 'Dubai, Dubai, United Arab Emirates',
            ]);
    }
}
