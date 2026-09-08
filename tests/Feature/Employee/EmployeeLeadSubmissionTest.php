<?php

declare(strict_types=1);

namespace Tests\Feature\Employee;

use App\Domains\Lead\Models\Lead;
use App\Models\User;
use App\Support\Enums\Currency;
use App\Support\Enums\LeadUnit;
use App\Support\Enums\PaymentMethod;
use App\Support\Enums\ProductType;
use App\Support\Enums\RecordStatus;
use App\Support\Enums\UserPlan;
use App\Support\Enums\UserType;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EmployeeLeadSubmissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    private function employee(): User
    {
        $user = User::factory()->create([
            'user_type' => UserType::Employee,
        ]);
        $user->assignRole('employee');

        return $user;
    }

    private function validPayload(): array
    {
        return [
            'company_name' => 'Staff Submitted Co',
            'contact_name' => 'Rajeev Kumar',
            'email' => 'client@example.com',
            'phone' => '+91 98765 43210',
            'country' => 'India',
            'business_type' => 'buyer',
            'product_interest' => 'Textiles',
            'product_type' => ProductType::Textiles->value,
            'currency' => Currency::INR->value,
            'units' => LeadUnit::Kg->value,
            'payment_methods' => [PaymentMethod::WireTransfer->value],
            'message' => 'Submitted by employee.',
        ];
    }

    public function test_employee_can_view_submit_lead_form(): void
    {
        $this->actingAs($this->employee())
            ->get(route('employee.leads.create'))
            ->assertOk()
            ->assertSee('Submit Lead')
            ->assertSee('New business lead');
    }

    public function test_employee_can_submit_lead_from_dashboard(): void
    {
        Storage::fake('public');

        $this->actingAs($this->employee())
            ->post(route('employee.leads.store'), array_merge($this->validPayload(), [
                'product_image' => UploadedFile::fake()->image('product.jpg'),
            ]))
            ->assertRedirect(route('employee.leads.index', ['status' => 'pending']))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('leads', [
            'company_name' => 'Staff Submitted Co',
            'email' => 'client@example.com',
            'status' => RecordStatus::Pending->value,
        ]);
    }
}
