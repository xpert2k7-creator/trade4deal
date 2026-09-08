<?php

declare(strict_types=1);

namespace App\Domains\Lead\Listeners;

use App\Domains\Lead\Events\LeadRejected;
use App\Domains\Lead\Notifications\LeadRejectedNotification;
use Illuminate\Support\Facades\Notification;

class SendLeadRejectedNotification
{
    public function handle(LeadRejected $event): void
    {
        Notification::route('mail', $event->lead->email)
            ->notify(new LeadRejectedNotification($event->lead));
    }
}
