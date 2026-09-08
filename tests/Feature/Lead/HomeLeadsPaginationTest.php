<?php

declare(strict_types=1);

namespace Tests\Feature\Lead;

use App\Domains\Lead\Models\Lead;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeLeadsPaginationTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_live_leads_are_paginated(): void
    {
        Lead::factory()->count(12)->create([
            'published_at' => now()->subHours(30),
        ]);

        $response = $this->get(route('home', ['per_page' => 10]));

        $response->assertOk();
        $response->assertSee('Showing 1–10 of 12 leads');
        $response->assertSee('Live Business Leads');
    }

    public function test_homepage_second_page_shows_remaining_leads(): void
    {
        Lead::factory()->count(12)->create([
            'published_at' => now()->subHours(30),
        ]);

        $this->get(route('home', ['page' => 2, 'per_page' => 10]))
            ->assertOk()
            ->assertSee('Showing 11–12 of 12 leads');
    }
}
