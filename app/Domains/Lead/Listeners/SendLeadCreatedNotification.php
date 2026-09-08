<?php

declare(strict_types=1);

namespace App\Domains\Lead\Listeners;

use App\Domains\Lead\Events\LeadCreated;
use App\Domains\Lead\Jobs\ProcessLeadJob;
use App\Domains\Lead\Notifications\LeadCreatedNotification;
use Illuminate\Support\Facades\Notification;

class SendLeadCreatedNotification
{
    public function handle(LeadCreated $event): void
    {
        ProcessLeadJob::dispatch($event->lead);

        Notification::route('mail', $event->lead->email)
            ->notify(new LeadCreatedNotification($event->lead));
    }
}
