<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Notifications\ContactInquiryNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_page_renders(): void
    {
        $this->get(route('contact'))
            ->assertOk()
            ->assertSee('Tell us what you need')
            ->assertSee(route('contact.store'), false);
    }

    public function test_contact_form_sends_notification(): void
    {
        Notification::fake();

        $this->from(route('contact'))
            ->post(route('contact.store'), [
                'name' => 'Asha Verma',
                'company' => 'Verma Trading',
                'email' => 'asha@example.com',
                'phone' => '+91 98765 43210',
                'message' => 'We need help connecting with textile suppliers in India.',
            ])
            ->assertRedirect(route('contact'))
            ->assertSessionHas('success');

        Notification::assertSentOnDemand(ContactInquiryNotification::class, function (ContactInquiryNotification $notification): bool {
            return $notification->inquiry['email'] === 'asha@example.com'
                && $notification->inquiry['name'] === 'Asha Verma';
        });
    }

    public function test_contact_form_requires_valid_fields(): void
    {
        Notification::fake();

        $this->from(route('contact'))
            ->post(route('contact.store'), [
                'name' => '',
                'email' => 'not-an-email',
                'message' => 'short',
            ])
            ->assertRedirect(route('contact'))
            ->assertSessionHasErrors(['name', 'email', 'message']);

        Notification::assertNothingSent();
    }
}
