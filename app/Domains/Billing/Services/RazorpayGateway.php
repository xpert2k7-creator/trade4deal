<?php

namespace App\Domains\Billing\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class RazorpayGateway
{
    public function configured(): bool
    {
        return filled(config('services.razorpay.key_id')) && filled(config('services.razorpay.key_secret'));
    }

    public function createOrder(array $data): array
    {
        $response = $this->client()->post('orders', $data);
        // Do not expose provider error payloads, headers, or credentials to the browser/logs.
        if (! $response->successful() || ! is_array($response->json())) {
            throw new RuntimeException('Razorpay order creation failed.');
        }

        return $response->json();
    }

    public function fetchPayment(string $id): array
    {
        if (! preg_match('/^pay_[a-zA-Z0-9]+$/', $id)) {
            throw new RuntimeException('Invalid payment identifier.');
        }
        $response = $this->client()->get('payments/'.$id);
        if (! $response->successful() || ! is_array($response->json())) {
            throw new RuntimeException('Razorpay payment verification is temporarily unavailable.');
        }

        return $response->json();
    }

    private function client(): PendingRequest
    {
        if (! $this->configured()) {
            throw new RuntimeException('Razorpay is not configured.');
        }

        return Http::baseUrl('https://api.razorpay.com/v1/')
            ->withBasicAuth(config('services.razorpay.key_id'), config('services.razorpay.key_secret'))
            ->acceptJson()->asJson()->connectTimeout(5)->timeout(15);
    }
}
