<?php

declare(strict_types=1);

namespace App\Domains\Lead\Notifications;

use App\Domains\Lead\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeadInquiryNotification extends Notification
{
    use Queueable;

    /**
     * @param  array{name: string, email: string, company_name: string, country: string, phone?: string|null, message: string}  $inquiry
     */
    public function __construct(
        public readonly Lead $lead,
        public readonly array $inquiry,
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
            ->subject('New inquiry about your Trade4Deal lead: '.$this->lead->product_interest)
            ->replyTo($this->inquiry['email'], $this->inquiry['name'])
            ->view('emails.leads.inquiry', [
                'lead' => $this->lead,
                'inquiry' => $this->inquiry,
                'leadUrl' => route('leads.show', $this->lead),
            ]);
    }
}
