<?php

declare(strict_types=1);

namespace Tests\Unit\Lead;

use App\Domains\Lead\DTOs\CreateLeadDTO;
use App\Domains\Lead\Repositories\Contracts\LeadRepositoryInterface;
use App\Domains\Lead\Services\LeadService;
use App\Support\Enums\BusinessType;
use App\Support\Enums\Currency;
use App\Support\Enums\LeadUnit;
use App\Support\Enums\PaymentMethod;
use App\Support\Enums\ProductType;
use App\Support\Enums\RecordStatus;
use Mockery;
use Tests\TestCase;

class LeadServiceTest extends TestCase
{
    public function test_create_lead_sets_pending_status(): void
    {
        $dto = new CreateLeadDTO(
            companyName: 'Test Co',
            contactName: 'Jane Doe',
            email: 'jane@test.com',
            phone: null,
            country: 'India',
            businessType: BusinessType::Seller,
            productInterest: 'Textiles',
            productType: ProductType::Textiles,
            productImagePath: null,
            currency: Currency::USD,
            units: LeadUnit::MetricTon,
            paymentMethods: [PaymentMethod::WireTransfer->value],
            message: null,
        );

        $lead = new \App\Domains\Lead\Models\Lead([
            'company_name' => 'Test Co',
            'status' => RecordStatus::Pending,
        ]);

        $repository = Mockery::mock(LeadRepositoryInterface::class);
        $repository->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function (array $data): bool {
                return $data['status'] === RecordStatus::Pending
                    && $data['company_name'] === 'Test Co';
            }))
            ->andReturn($lead);

        $service = new LeadService($repository);
        $result = $service->createLead($dto);

        $this->assertSame('Test Co', $result->company_name);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
