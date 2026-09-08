<?php

declare(strict_types=1);

namespace App\Domains\Lead\Notifications;

use App\Domains\Lead\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeadRejectedNotification extends Notification
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
            ->subject('Update on your Trade4Deal lead submission')
            ->view('emails.leads.rejected', [
                'lead' => $this->lead,
                'homeUrl' => url('/'),
            ]);
    }
}
