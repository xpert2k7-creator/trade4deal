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

    public function test_guest_is_redirected_to_login_when_viewing_lead(): void
    {
        $lead = Lead::factory()->create([
            'company_name' => 'Visible Dealer Co',
            'product_interest' => 'Copper Wire Lots',
            'phone' => '+1 555 SECRET',
            'published_at' => now()->subDays(8),
        ]);

        $this->get(route('leads.show', $lead))
            ->assertRedirect(route('login'))
            ->assertSessionHas('status', 'Please login or register to view this lead.');
    }

    public function test_guest_cannot_view_recently_published_lead_after_login_gate(): void
    {
        $lead = Lead::factory()->publishedRecently()->create([
            'company_name' => 'Hidden Fresh Co',
        ]);

        $user = User::factory()->create([
            'user_type' => UserType::Buyer,
            'plan' => UserPlan::Free,
        ]);
        $user->assignRole('buyer');

        $this->actingAs($user)
            ->get(route('leads.show', $lead))
            ->assertNotFound();
    }

    public function test_authenticated_user_can_view_lead_published_over_7_days_ago(): void
    {
        $user = User::factory()->create([
            'user_type' => UserType::Buyer,
            'plan' => UserPlan::Free,
        ]);
        $user->assignRole('buyer');

        $lead = Lead::factory()->create([
            'company_name' => 'Visible Dealer Co',
            'product_interest' => 'Copper Wire Lots',
            'phone' => '+1 555 SECRET',
            'published_at' => now()->subDays(8),
        ]);

        $this->actingAs($user)
            ->get(route('leads.show', $lead))
            ->assertOk()
            ->assertSee('Copper Wire Lots')
            ->assertSee('Connect with Buyer')
            ->assertSee('Contact buyer')
            ->assertSee('Identity protected')
            ->assertDontSee('Visible Dealer Co')
            ->assertDontSee('Contact with Supplier')
            ->assertDontSee('+1 555 SECRET');
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
            ->assertDontSee('Gold Instant Dealer');
    }

    public function test_guest_contact_is_redirected_to_login(): void
    {
        $lead = Lead::factory()->create([
            'email' => 'dealer@example.com',
            'published_at' => now()->subDays(8),
        ]);

        $this->post(route('leads.contact', $lead), [
            'name' => 'Interested Buyer',
            'email' => 'buyer@example.com',
            'company_name' => 'Buyer Imports LLC',
            'country' => 'United States',
            'phone' => '+1 555 1111',
            'message' => 'We are interested in sourcing textile yarn in bulk for Q4 delivery.',
        ])->assertRedirect(route('login'));
    }

    public function test_contact_form_sends_email_to_that_leads_buyer(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'user_type' => UserType::Buyer,
            'plan' => UserPlan::Free,
        ]);
        $user->assignRole('buyer');

        $buyerLead = Lead::factory()->create([
            'email' => 'buyer-lead@example.com',
            'contact_name' => 'Lead Buyer',
            'company_name' => 'Hidden Buyer Co',
            'product_interest' => 'Textile Yarn',
            'published_at' => now()->subDays(8),
        ]);

        Lead::factory()->create([
            'email' => 'other-buyer@example.com',
            'product_interest' => 'Other Product',
            'published_at' => now()->subDays(8),
        ]);

        $this->actingAs($user)
            ->post(route('leads.contact', $buyerLead), [
                'name' => 'Interested Supplier',
                'email' => 'supplier@example.com',
                'company_name' => 'Supplier Exports LLC',
                'country' => 'United States',
                'phone' => '+1 555 1111',
                'message' => 'We are interested in supplying textile yarn in bulk for Q4 delivery.',
            ])
            ->assertRedirect(route('leads.show', $buyerLead))
            ->assertSessionHas('success', 'Your message has been sent to the buyer. They can reply to you by email.');

        Notification::assertSentOnDemandTimes(LeadInquiryNotification::class, 1);

        Notification::assertSentOnDemand(
            LeadInquiryNotification::class,
            function (LeadInquiryNotification $notification, array $channels, object $notifiable) use ($buyerLead): bool {
                return ($notifiable->routes['mail'] ?? null) === 'buyer-lead@example.com'
                    && ($notifiable->routes['mail'] ?? null) !== 'other-buyer@example.com'
                    && $notification->lead->is($buyerLead)
                    && $notification->inquiry['email'] === 'supplier@example.com'
                    && $notification->inquiry['company_name'] === 'Supplier Exports LLC';
            },
        );
    }

    public function test_similar_leads_are_shown_on_detail_page(): void
    {
        $user = User::factory()->create([
            'user_type' => UserType::Buyer,
            'plan' => UserPlan::Free,
        ]);
        $user->assignRole('buyer');

        $lead = Lead::factory()->create([
            'product_type' => ProductType::Electronics,
            'product_interest' => 'Main Electronics Lead',
            'published_at' => now()->subDays(8),
        ]);

        Lead::factory()->create([
            'product_type' => ProductType::Electronics,
            'company_name' => 'Similar Electronics Corp',
            'product_interest' => 'Circuit Boards',
            'city' => 'Shenzhen',
            'country' => 'China',
            'published_at' => now()->subDays(8),
        ]);

        Lead::factory()->create([
            'product_type' => ProductType::Textiles,
            'company_name' => 'Unrelated Textile Corp',
            'product_interest' => 'Cotton Yarn Lots',
            'published_at' => now()->subDays(8),
        ]);

        $this->actingAs($user)
            ->get(route('leads.show', $lead))
            ->assertOk()
            ->assertSee('Similar leads')
            ->assertSee('Circuit Boards')
            ->assertSee('Shenzhen, China')
            ->assertDontSee('Similar Electronics Corp')
            ->assertDontSee('Cotton Yarn Lots')
            ->assertDontSee('Unrelated Textile Corp');
    }

    public function test_homepage_lead_cards_hide_buyer_info_and_link_to_detail(): void
    {
        $lead = Lead::factory()->create([
            'company_name' => 'Linked Dealer Inc',
            'product_interest' => 'Export Ready Widgets',
            'required_quantity' => 250,
            'city' => 'Mumbai',
            'country' => 'India',
            'published_at' => now()->subDays(8),
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Export Ready Widgets')
            ->assertSee('Login to view')
            ->assertSee(route('leads.show', $lead), false)
            ->assertDontSee('Linked Dealer Inc')
            ->assertDontSee('View & contact');
    }
}
