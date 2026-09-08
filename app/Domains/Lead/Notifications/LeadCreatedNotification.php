<?php

declare(strict_types=1);

namespace App\Domains\Lead\Notifications;

use App\Domains\Lead\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeadCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly Lead $lead,
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Welcome to Trade4Deal — Your inquiry has been received')
            ->greeting('Hello '.$this->lead->contact_name.',')
            ->line('Thank you for registering your business interest on Trade4Deal.')
            ->line('Company: '.$this->lead->company_name)
            ->line('Product Interest: '.$this->lead->product_interest)
            ->line('Our team is reviewing your submission. You will receive another email once it is approved and published on the marketplace.')
            ->action('Explore Trade4Deal', url('/'))
            ->line('Thank you for choosing Trade4Deal for global B2B trade.');
    }
}
