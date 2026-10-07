<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class LocationSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_location_search_returns_curated_city_matches(): void
    {
        $this->getJson(route('locations.search', ['q' => 'mumbai']))
            ->assertOk()
            ->assertJsonPath('locations.0.label', 'Mumbai')
            ->assertJsonPath('locations.0.country', 'India');
    }

    public function test_location_search_returns_empty_for_single_character_query(): void
    {
        $this->getJson(route('locations.search', ['q' => 'm']))
            ->assertOk()
            ->assertJson(['locations' => []]);
    }

    public function test_location_search_returns_default_suggestions_without_query(): void
    {
        $response = $this->getJson(route('locations.search'));

        $response->assertOk();
        $this->assertGreaterThanOrEqual(1, count($response->json('locations')));
    }

    public function test_reverse_geocode_returns_service_unavailable_when_provider_disabled(): void
    {
        config(['services.location_geocoder.provider' => 'none']);

        $this->postJson(route('locations.reverse'), [
            'lat' => 19.076,
            'lng' => 72.877,
        ])
            ->assertStatus(503)
            ->assertJsonPath('message', 'Reverse geocoding provider is not configured.');
    }

    public function test_reverse_geocode_resolves_city_when_nominatim_is_configured(): void
    {
        config(['services.location_geocoder.provider' => 'nominatim']);

        Http::fake([
            'nominatim.openstreetmap.org/reverse*' => Http::response([
                'address' => [
                    'city' => 'Mumbai',
                    'state' => 'Maharashtra',
                    'country' => 'India',
                ],
            ]),
        ]);

        $this->postJson(route('locations.reverse'), [
            'lat' => 19.076,
            'lng' => 72.877,
        ])
            ->assertOk()
            ->assertJsonPath('location.label', 'Mumbai')
            ->assertJsonPath('location.full_label', 'Mumbai, Maharashtra, India');
    }
}
