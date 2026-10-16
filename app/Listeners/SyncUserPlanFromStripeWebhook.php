<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Models\User;
use Laravel\Cashier\Events\WebhookHandled;

class SyncUserPlanFromStripeWebhook
{
    public function handle(WebhookHandled $event): void
    {
        $type = $event->payload['type'] ?? null;

        if (! in_array($type, [
            'customer.subscription.created',
            'customer.subscription.updated',
            'customer.subscription.deleted',
            'checkout.session.completed',
        ], true)) {
            return;
        }

        $customerId = data_get($event->payload, 'data.object.customer');

        if (! $customerId) {
            return;
        }

        $user = User::query()->where('stripe_id', $customerId)->first();

        if (! $user) {
            return;
        }

        $user->syncPlanFromSubscription();
    }
}
