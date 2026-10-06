<?php

declare(strict_types=1);

namespace App\Domains\Lead\Actions;

use App\Domains\Lead\DTOs\CreateLeadDTO;
use App\Domains\Lead\Models\Lead;
use App\Models\User;
use App\Support\Enums\BusinessType;
use App\Support\Enums\Currency;
use App\Support\Enums\LeadSource;
use App\Support\Enums\LeadUnit;
use App\Support\Enums\ProductType;

class CreateSourcingLeadAction
{
    public function __construct(
        private readonly CreateLeadAction $createLeadAction,
    ) {}

    /**
     * @param  array<string, mixed>  $validated
     */
    public function execute(array $validated, User $sourcer): Lead
    {
        $defaults = config('trade4deal.sourcing_lead_defaults', []);

        $contactName = trim((string) ($validated['contact_name'] ?? ''));
        if ($contactName === '') {
            $contactName = '—';
        }

        $productInterest = trim((string) ($validated['product_interest'] ?? ''));
        if ($productInterest === '') {
            $productInterest = ProductType::from($validated['product_type'])->label();
        }

        return $this->createLeadAction->execute(CreateLeadDTO::fromArray([
            'company_name' => $validated['company_name'],
            'contact_name' => $contactName,
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'country' => $validated['country'],
            'city' => $validated['city'] ?? null,
            'business_type' => $defaults['business_type'] ?? BusinessType::Buyer->value,
            'product_interest' => $productInterest,
            'product_type' => $validated['product_type'],
            'product_image_path' => null,
            'currency' => $defaults['currency'] ?? Currency::USD->value,
            'units' => $defaults['units'] ?? LeadUnit::Kg->value,
            'payment_methods' => $defaults['payment_methods'] ?? ['wire_transfer'],
            'message' => null,
            'user_id' => $sourcer->id,
            'source' => LeadSource::Sourcing->value,
        ]));
    }
}
