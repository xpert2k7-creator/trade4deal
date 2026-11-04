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
            'phone' => '+1 202 555 0104',
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
            'product_image' => new UploadedFile($this->tinyJpegPath(), 'product.jpg', 'image/jpeg', null, true),
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

    public function test_requirement_rejects_numeric_names_and_product_and_alphabetic_phone(): void
    {
        $this->from('/')->post(route('leads.store'), $this->validPayload([
            '_form' => 'requirement-modal',
            'company_name' => '28825',
            'contact_name' => '25558',
            'phone' => '98765abc210',
            'country' => 'India',
            'product_interest' => '12345',
        ]))->assertRedirect('/')
            ->assertSessionHasErrors(['company_name', 'contact_name', 'phone', 'product_interest']);

        $this->assertDatabaseCount('leads', 0);
        $this->get('/')->assertOk()
            ->assertSee('Enter a valid mobile number for your country.')
            ->assertSee('if (modalEl) new bootstrap.Modal(modalEl).show();', false);
    }

    public function test_requirement_rejects_short_or_missing_phone(): void
    {
        foreach (['80098534', ''] as $phone) {
            $this->post(route('leads.store'), $this->validPayload([
                'phone' => $phone,
                'country' => 'India',
            ]))->assertSessionHasErrors('phone');
        }

        $this->assertDatabaseCount('leads', 0);
    }

    public function test_requirement_allows_business_and_product_names_containing_digits(): void
    {
        $this->post(route('leads.store'), $this->validPayload([
            'company_name' => '3M Trading',
            'contact_name' => "John O'Connor",
            'product_interest' => 'SS 304 Steel',
        ]))->assertSessionHasNoErrors()->assertRedirect(route('home'));

        $this->assertDatabaseHas('leads', ['company_name' => '3M Trading']);
    }

    private function tinyJpegPath(): string
    {
        $path = tempnam(sys_get_temp_dir(), 'lead-image-').'.jpg';

        file_put_contents(
            $path,
            base64_decode('/9j/4AAQSkZJRgABAQAAAQABAAD/2wBDAP//////////////////////////////////////////////////////////////////////////////////////2wBDAf//////////////////////////////////////////////////////////////////////////////////////wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAX/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIQAxAAAAGgP//EABQQAQAAAAAAAAAAAAAAAAAAAAD/2gAIAQEAAQUCf//EABQRAQAAAAAAAAAAAAAAAAAAABD/2gAIAQMBAT8BP//EABQRAQAAAAAAAAAAAAAAAAAAABD/2gAIAQIBAT8BP//EABQQAQAAAAAAAAAAAAAAAAAAAAD/2gAIAQEABj8Cf//EABQQAQAAAAAAAAAAAAAAAAAAAAD/2gAIAQEAAT8hf//aAAwDAQACAAMAAAAQ8P/EABQRAQAAAAAAAAAAAAAAAAAAABD/2gAIAQMBAT8QP//EABQRAQAAAAAAAAAAAAAAAAAAABD/2gAIAQIBAT8QP//EABQQAQAAAAAAAAAAAAAAAAAAAAD/2gAIAQEAAT8QP//Z')
        );

        return $path;
    }
}
