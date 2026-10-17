<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactInquiryNotification extends Notification
{
    use Queueable;

    /**
     * @param  array{name: string, email: string, company?: string|null, phone?: string|null, message: string}  $inquiry
     */
    public function __construct(
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
            ->subject('New contact message from '.$this->inquiry['name'].' — Trade4Deal')
            ->replyTo($this->inquiry['email'], $this->inquiry['name'])
            ->view('emails.contact.inquiry', [
                'inquiry' => $this->inquiry,
            ]);
    }
}
