<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Domains\Lead\Models\Lead;
use App\Support\Enums\ProductType;
use App\Support\Enums\RecordStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarketplaceLeadDirectoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_lead_directory_shows_visible_published_leads(): void
    {
        Lead::factory()->create([
            'product_interest' => 'Basmati Rice Buyers Needed',
            'company_name' => 'Rice Traders LLC',
            'country' => 'India',
            'product_type' => ProductType::Agriculture,
            'status' => RecordStatus::Active,
            'published_at' => now()->subHours(30),
        ]);

        Lead::factory()->pending()->create([
            'product_interest' => 'Hidden Pending Rice Lead',
            'company_name' => 'Pending Co',
        ]);

        $this->get(route('marketplace.page', ['page' => 'lead-directory']))
            ->assertOk()
            ->assertSee('Basmati Rice Buyers Needed')
            ->assertSee('Rice Traders LLC')
            ->assertDontSee('Hidden Pending Rice Lead');
    }

    public function test_lead_directory_filters_by_search_term(): void
    {
        Lead::factory()->create([
            'product_interest' => 'Apple Juice Concentrate',
            'company_name' => 'Fruit Buyers Inc',
            'country' => 'UAE',
            'status' => RecordStatus::Active,
            'published_at' => now()->subHours(30),
        ]);

        Lead::factory()->create([
            'product_interest' => 'Industrial Bearings',
            'company_name' => 'Machinery Hub',
            'country' => 'India',
            'status' => RecordStatus::Active,
            'published_at' => now()->subHours(30),
        ]);

        $this->get(route('marketplace.page', [
            'page' => 'lead-directory',
            'search' => 'apple',
        ]))
            ->assertOk()
            ->assertSee('Apple Juice Concentrate')
            ->assertSee('Fruit Buyers Inc')
            ->assertDontSee('Industrial Bearings');
    }

    public function test_lead_directory_filters_by_category(): void
    {
        Lead::factory()->create([
            'product_interest' => 'Cotton Fabric Requirement',
            'product_type' => ProductType::Textiles,
            'status' => RecordStatus::Active,
            'published_at' => now()->subHours(30),
        ]);

        Lead::factory()->create([
            'product_interest' => 'Circuit Board Inquiry',
            'product_type' => ProductType::Electronics,
            'status' => RecordStatus::Active,
            'published_at' => now()->subHours(30),
        ]);

        $this->get(route('marketplace.page', [
            'page' => 'lead-directory',
            'category' => ProductType::Textiles->value,
        ]))
            ->assertOk()
            ->assertSee('Cotton Fabric Requirement')
            ->assertDontSee('Circuit Board Inquiry');
    }
}
