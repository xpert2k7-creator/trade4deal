<?php

declare(strict_types=1);

namespace App\Domains\Lead\Jobs;

use App\Domains\Lead\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessLeadJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly Lead $lead,
    ) {}

    public function handle(): void
    {
        Log::info('Trade4Deal lead processed', [
            'lead_id' => $this->lead->id,
            'company' => $this->lead->company_name,
            'email' => $this->lead->email,
        ]);
    }
}
