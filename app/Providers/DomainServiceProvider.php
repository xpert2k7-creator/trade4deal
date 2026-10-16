<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domains\Auth\Listeners\RecordLoginHistory;
use App\Domains\Lead\Events\LeadApproved;
use App\Domains\Lead\Events\LeadCreated;
use App\Domains\Lead\Events\LeadRejected;
use App\Domains\Lead\Listeners\SendLeadApprovedNotification;
use App\Domains\Lead\Listeners\SendLeadCreatedNotification;
use App\Domains\Lead\Listeners\SendLeadRejectedNotification;
use App\Domains\Lead\Models\Lead;
use App\Domains\Lead\Policies\LeadPolicy;
use App\Domains\Lead\Repositories\Contracts\LeadRepositoryInterface;
use App\Domains\Lead\Repositories\Eloquent\LeadRepository;
use App\Domains\Product\Models\Product;
use App\Domains\Product\Policies\ProductPolicy;
use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class DomainServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, class-string>
     */
    public array $bindings = [
        LeadRepositoryInterface::class => LeadRepository::class,
    ];

    public function boot(): void
    {
        Gate::policy(Lead::class, LeadPolicy::class);
        Gate::policy(Product::class, ProductPolicy::class);
        Gate::policy(User::class, UserPolicy::class);

        Event::listen(
            LeadCreated::class,
            SendLeadCreatedNotification::class,
        );

        Event::listen(
            LeadApproved::class,
            SendLeadApprovedNotification::class,
        );

        Event::listen(
            LeadRejected::class,
            SendLeadRejectedNotification::class,
        );

        Event::listen(
            Login::class,
            RecordLoginHistory::class,
        );

        // App/Listeners is discovered automatically by Laravel.
    }
}
