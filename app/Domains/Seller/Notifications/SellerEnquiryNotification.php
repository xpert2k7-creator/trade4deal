<?php

declare(strict_types=1);

namespace App\Domains\Seller\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SellerEnquiryNotification extends Notification
{
    use Queueable;

    /**
     * @param  array{name: string, email: string, company_name: string, country: string, phone?: string|null, message: string}  $inquiry
     */
    public function __construct(
        public readonly User $seller,
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
            ->subject('New enquiry for '.$this->seller->company_name.' on Trade4Deal')
            ->replyTo($this->inquiry['email'], $this->inquiry['name'])
            ->view('emails.sellers.enquiry', [
                'seller' => $this->seller,
                'inquiry' => $this->inquiry,
                'profileUrl' => route('sellers.show', $this->seller->slug),
            ]);
    }
}
