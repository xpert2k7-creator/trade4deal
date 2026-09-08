<?php

declare(strict_types=1);

namespace App\Domains\Auth\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeUserNotification extends Notification
{
    use Queueable;

    public function __construct(
        public readonly User $user,
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
            ->subject('Welcome to Trade4Deal')
            ->greeting('Hello '.$this->user->name.',')
            ->line('Your Trade4Deal account has been created successfully.')
            ->line('Company: '.($this->user->company_name ?? 'N/A'))
            ->line('You can now access your dashboard and start connecting with global buyers and sellers.')
            ->action('Go to Dashboard', route('dashboard'))
            ->line('Thank you for joining Trade4Deal.');
    }
}
