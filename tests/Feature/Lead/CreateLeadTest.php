<?php

declare(strict_types=1);

namespace Tests\Feature\Lead;

use App\Domains\Lead\Models\Lead;
use App\Support\Enums\Currency;
use App\Support\Enums\LeadUnit;
use App\Support\Enums\PaymentMethod;
use App\Support\Enums\ProductType;
use App\Support\Enums\RecordStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CreateLeadTest extends TestCase
{
    use RefreshDatabase;

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'company_name' => 'Zephyr Trading Partners',
            'contact_name' => 'John Smith',
            'email' => 'john@zephyrtrading.test',
            'phone' => '+1 555 0100',
            'country' => 'United States',
            'business_type' => 'buyer',
            'product_interest' => 'Industrial Machinery',
            'product_type' => ProductType::Machinery->value,
            'currency' => Currency::USD->value,
            'units' => LeadUnit::MetricTon->value,
            'payment_methods' => [PaymentMethod::WireTransfer->value, PaymentMethod::LetterOfCredit->value],
            'message' => 'Looking for verified suppliers.',
        ], $overrides);
    }

    public function test_guest_can_submit_lead_as_pending_and_it_is_hidden_from_homepage(): void
    {
        Storage::fake('public');

        $response = $this->post(route('leads.store'), array_merge($this->validPayload(), [
            'product_image' => UploadedFile::fake()->image('product.jpg'),
        ]));

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('leads', [
            'company_name' => 'Zephyr Trading Partners',
            'email' => 'john@zephyrtrading.test',
            'status' => RecordStatus::Pending->value,
            'product_type' => ProductType::Machinery->value,
            'currency' => Currency::USD->value,
        ]);

        $lead = Lead::query()->where('email', 'john@zephyrtrading.test')->first();
        $this->assertNotNull($lead->product_image_path);
        Storage::disk('public')->assertExists($lead->product_image_path);

        $home = $this->get(route('home'));
        $home->assertOk();
        $home->assertDontSee('Zephyr Trading Partners');
    }

    public function test_lead_submission_requires_valid_data(): void
    {
        $response = $this->post(route('leads.store'), []);

        $response->assertSessionHasErrors([
            'company_name',
            'contact_name',
            'email',
            'country',
            'business_type',
            'product_interest',
            'product_type',
            'currency',
            'units',
            'payment_methods',
        ]);

        $this->assertDatabaseCount('leads', 0);
    }
}
