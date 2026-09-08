<?php

declare(strict_types=1);

namespace App\Domains\Lead\Events;

use App\Domains\Lead\Models\Lead;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LeadCreated
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly Lead $lead,
    ) {}
}
