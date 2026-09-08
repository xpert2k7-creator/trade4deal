<?php

declare(strict_types=1);

namespace App\Domains\Lead\DTOs;

use App\Support\Enums\BusinessType;
use App\Support\Enums\Currency;
use App\Support\Enums\LeadUnit;
use App\Support\Enums\ProductType;

readonly class UpdateLeadDTO
{
    /**
     * @param  array<int, string>  $paymentMethods
     */
    public function __construct(
        public string $companyName,
        public string $contactName,
        public string $email,
        public ?string $phone,
        public string $country,
        public BusinessType $businessType,
        public string $productInterest,
        public ProductType $productType,
        public ?string $productImagePath,
        public Currency $currency,
        public LeadUnit $units,
        public array $paymentMethods,
        public ?string $message,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            companyName: $data['company_name'],
            contactName: $data['contact_name'],
            email: $data['email'],
            phone: $data['phone'] ?? null,
            country: $data['country'],
            businessType: BusinessType::from($data['business_type']),
            productInterest: $data['product_interest'],
            productType: ProductType::from($data['product_type']),
            productImagePath: $data['product_image_path'] ?? null,
            currency: Currency::from($data['currency']),
            units: LeadUnit::from($data['units']),
            paymentMethods: $data['payment_methods'] ?? [],
            message: $data['message'] ?? null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'company_name' => $this->companyName,
            'contact_name' => $this->contactName,
            'email' => $this->email,
            'phone' => $this->phone,
            'country' => $this->country,
            'business_type' => $this->businessType->value,
            'product_interest' => $this->productInterest,
            'product_type' => $this->productType->value,
            'product_image_path' => $this->productImagePath,
            'currency' => $this->currency->value,
            'units' => $this->units->value,
            'payment_methods' => $this->paymentMethods,
            'message' => $this->message,
        ];
    }
}
