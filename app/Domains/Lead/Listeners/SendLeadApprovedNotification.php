<?php

declare(strict_types=1);

namespace App\Domains\Lead\Listeners;

use App\Domains\Lead\Events\LeadApproved;
use App\Domains\Lead\Notifications\LeadApprovedNotification;
use Illuminate\Support\Facades\Notification;

class SendLeadApprovedNotification
{
    public function handle(LeadApproved $event): void
    {
        Notification::route('mail', $event->lead->email)
            ->notify(new LeadApprovedNotification($event->lead));
    }
}
