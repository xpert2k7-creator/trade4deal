<?php

declare(strict_types=1);

namespace Tests\Feature\Lead;

use App\Domains\Lead\Models\Lead;
use App\Domains\Lead\Notifications\LeadInquiryNotification;
use App\Models\User;
use App\Support\Enums\ProductType;
use App\Support\Enums\UserPlan;
use App\Support\Enums\UserType;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class LeadShowAndContactTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_guest_can_view_lead_published_over_24_hours_ago(): void
    {
        $lead = Lead::factory()->create([
            'company_name' => 'Visible Dealer Co',
            'product_interest' => 'Copper Wire Lots',
            'phone' => '+1 555 SECRET',
            'published_at' => now()->subHours(25),
        ]);

        $this->get(route('leads.show', $lead))
            ->assertOk()
            ->assertSee('Copper Wire Lots')
            ->assertSee('Visible Dealer Co')
            ->assertSee('Contact the dealer')
            ->assertDontSee('+1 555 SECRET');
    }

    public function test_guest_cannot_view_recently_published_lead(): void
    {
        $lead = Lead::factory()->publishedRecently()->create([
            'company_name' => 'Hidden Fresh Co',
        ]);

        $this->get(route('leads.show', $lead))->assertNotFound();
    }

    public function test_gold_user_can_view_recently_published_lead(): void
    {
        $user = User::factory()->create([
            'user_type' => UserType::Buyer,
            'plan' => UserPlan::Gold,
        ]);
        $user->assignRole('buyer');

        $lead = Lead::factory()->publishedRecently()->create([
            'company_name' => 'Gold Instant Dealer',
            'product_interest' => 'Solar Inverters',
        ]);

        $this->actingAs($user)
            ->get(route('leads.show', $lead))
            ->assertOk()
            ->assertSee('Solar Inverters')
            ->assertSee('Gold Instant Dealer');
    }

    public function test_contact_form_sends_email_to_lead_poster(): void
    {
        Notification::fake();

        $lead = Lead::factory()->create([
            'email' => 'dealer@example.com',
            'contact_name' => 'Dealer Owner',
            'product_interest' => 'Textile Yarn',
            'published_at' => now()->subHours(25),
        ]);

        $this->post(route('leads.contact', $lead), [
            'name' => 'Interested Buyer',
            'email' => 'buyer@example.com',
            'company_name' => 'Buyer Imports LLC',
            'country' => 'United States',
            'phone' => '+1 555 1111',
            'message' => 'We are interested in sourcing textile yarn in bulk for Q4 delivery.',
        ])
            ->assertRedirect(route('leads.show', $lead))
            ->assertSessionHas('success');

        Notification::assertSentOnDemand(LeadInquiryNotification::class);
    }

    public function test_similar_leads_are_shown_on_detail_page(): void
    {
        $lead = Lead::factory()->create([
            'product_type' => ProductType::Electronics,
            'product_interest' => 'Main Electronics Lead',
            'published_at' => now()->subHours(30),
        ]);

        Lead::factory()->create([
            'product_type' => ProductType::Electronics,
            'company_name' => 'Similar Electronics Corp',
            'product_interest' => 'Circuit Boards',
            'published_at' => now()->subHours(28),
        ]);

        Lead::factory()->create([
            'product_type' => ProductType::Textiles,
            'company_name' => 'Unrelated Textile Corp',
            'published_at' => now()->subHours(28),
        ]);

        $this->get(route('leads.show', $lead))
            ->assertOk()
            ->assertSee('Similar leads')
            ->assertSee('Similar Electronics Corp')
            ->assertDontSee('Unrelated Textile Corp');
    }

    public function test_homepage_links_to_lead_detail_page(): void
    {
        $lead = Lead::factory()->create([
            'company_name' => 'Linked Dealer Inc',
            'published_at' => now()->subHours(30),
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee(route('leads.show', $lead), false)
            ->assertSee('View & contact');
    }
}
