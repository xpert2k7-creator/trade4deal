<?php

declare(strict_types=1);

namespace Tests\Feature\Sales;

use App\Models\User;
use App\Support\Enums\RecordStatus;
use App\Support\Enums\UserPlan;
use App\Support\Enums\UserType;
use App\Support\Permissions\SalesRole;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SellerOnboardingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    private function admin(): User
    {
        $user = User::factory()->create([
            'user_type' => UserType::Admin,
            'status' => RecordStatus::Active,
        ]);
        $user->assignRole('admin');

        return $user;
    }

    private function salesUser(): User
    {
        SalesRole::ensureExists();
        $user = User::factory()->create([
            'user_type' => UserType::Sales,
            'status' => RecordStatus::Active,
        ]);
        $user->assignRole('sales');

        return $user;
    }

    private function seller(array $overrides = []): User
    {
        $user = User::factory()->seller()->create(array_merge([
            'user_type' => UserType::Seller,
            'status' => RecordStatus::Active,
        ], $overrides));
        $user->assignRole('seller');

        return $user;
    }

    public function test_admin_can_create_sales_login(): void
    {
        $this->actingAs($this->admin())
            ->post(route('verification.sales-team.store'), [
                'name' => 'Sales Rep',
                'email' => 'sales@trade4deal.com',
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!',
            ])
            ->assertRedirect(route('verification.sales-team.index'))
            ->assertSessionHas('success');

        $user = User::query()->where('email', 'sales@trade4deal.com')->firstOrFail();
        $this->assertTrue($user->isSales());
        $this->assertTrue($user->hasRole('sales'));
        $this->assertSame(RecordStatus::Active, $user->status);
    }

    public function test_new_seller_registers_as_pending_existing_active_unchanged(): void
    {
        Notification::fake();

        $existing = $this->seller([
            'email' => 'existing.seller@example.com',
            'status' => RecordStatus::Active,
        ]);

        $this->post('/register', [
            'name' => 'New Seller',
            'email' => 'new.seller@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'company_name' => 'New Seller Co',
            'country' => 'India',
            'user_type' => 'seller',
            'phone' => '+919811122233',
        ])->assertRedirect(route('dashboard', absolute: false));

        $newSeller = User::query()->where('email', 'new.seller@example.com')->firstOrFail();
        $this->assertSame(RecordStatus::Pending, $newSeller->status);
        $this->assertSame(RecordStatus::Active, $existing->fresh()->status);
    }

    public function test_buyer_registration_stays_active(): void
    {
        Notification::fake();

        $this->post('/register', [
            'name' => 'New Buyer',
            'email' => 'new.buyer@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'company_name' => 'Buyer Co',
            'country' => 'India',
            'user_type' => 'buyer',
            'phone' => '+919822233344',
        ])->assertRedirect(route('dashboard', absolute: false));

        $buyer = User::query()->where('email', 'new.buyer@example.com')->firstOrFail();
        $this->assertSame(RecordStatus::Active, $buyer->status);
    }

    public function test_pending_seller_sees_account_not_approved_and_cannot_open_seller_dashboard(): void
    {
        $seller = $this->seller(['status' => RecordStatus::Pending]);

        $this->actingAs($seller)
            ->get(route('dashboard'))
            ->assertRedirect(route('account.pending'));

        $this->actingAs($seller)
            ->get(route('account.pending'))
            ->assertOk()
            ->assertSee('Under review')
            ->assertSee('We are reviewing your seller profile')
            ->assertSee('Welcome aboard')
            ->assertSee('Contact support')
            ->assertSee(config('trade4deal.contact_email', 'info@trade4deal.com'));

        $this->actingAs($seller)
            ->get(route('seller.dashboard'))
            ->assertRedirect(route('account.pending'));
    }

    public function test_declined_seller_sees_minimal_declined_messaging(): void
    {
        $seller = $this->seller(['status' => RecordStatus::Inactive]);

        $this->actingAs($seller)
            ->get(route('account.pending'))
            ->assertOk()
            ->assertSee('Declined')
            ->assertSee('Your seller application was declined')
            ->assertSee('Contact support')
            ->assertDontSee('Welcome aboard')
            ->assertDontSee('What happens next')
            ->assertDontSee('finish a few details');
    }

    public function test_sales_can_approve_and_reapprove_seller(): void
    {
        $sales = $this->salesUser();
        $seller = $this->seller([
            'name' => 'Pending Co Contact',
            'status' => RecordStatus::Pending,
        ]);

        $this->actingAs($sales)
            ->get(route('sales.onboarding.index', ['status' => 'pending']))
            ->assertOk()
            ->assertSee('Pending Co Contact');

        $this->actingAs($sales)
            ->post(route('sales.onboarding.approve', $seller))
            ->assertRedirect();

        $this->assertSame(RecordStatus::Active, $seller->fresh()->status);

        $this->actingAs($sales)
            ->post(route('sales.onboarding.disapprove', $seller))
            ->assertRedirect();

        $this->assertSame(RecordStatus::Inactive, $seller->fresh()->status);
        $this->assertNotSoftDeleted('users', ['id' => $seller->id]);

        $this->actingAs($sales)
            ->post(route('sales.onboarding.approve', $seller))
            ->assertRedirect();

        $this->assertSame(RecordStatus::Active, $seller->fresh()->status);

        $this->actingAs($seller->fresh())
            ->get(route('seller.dashboard'))
            ->assertOk();
    }

    public function test_sales_can_edit_seller_details_but_not_plan(): void
    {
        $sales = $this->salesUser();
        $seller = $this->seller([
            'company_name' => 'Old Co',
            'status' => RecordStatus::Pending,
        ]);

        $this->actingAs($sales)
            ->put(route('sales.onboarding.update', $seller), [
                'name' => $seller->name,
                'email' => $seller->email,
                'company_name' => 'Updated Co',
                'country' => 'India',
                'is_public' => true,
            ])
            ->assertRedirect(route('sales.onboarding.edit', $seller));

        $this->assertDatabaseHas('users', [
            'id' => $seller->id,
            'company_name' => 'Updated Co',
        ]);
    }

    public function test_employee_cannot_access_sales_panel(): void
    {
        $employee = User::factory()->create(['user_type' => UserType::Employee]);
        $employee->assignRole('employee');

        $this->actingAs($employee)
            ->get(route('sales.onboarding.index'))
            ->assertForbidden();
    }

    public function test_admin_can_access_sales_panel(): void
    {
        $this->actingAs($this->admin())
            ->get(route('sales.onboarding.index'))
            ->assertOk()
            ->assertSee('Seller onboarding');
    }

    public function test_sales_panel_lists_sellers_only(): void
    {
        $sales = $this->salesUser();
        $this->seller([
            'name' => 'Seller Visible',
            'status' => RecordStatus::Pending,
        ]);
        User::factory()->create([
            'name' => 'Buyer Hidden',
            'user_type' => UserType::Buyer,
            'status' => RecordStatus::Pending,
        ]);

        $this->actingAs($sales)
            ->get(route('sales.onboarding.index', ['status' => 'pending']))
            ->assertOk()
            ->assertSee('Seller Visible')
            ->assertDontSee('Buyer Hidden');
    }

    public function test_full_seller_onboarding_scenario_end_to_end(): void
    {
        Notification::fake();

        $existingSeller = $this->seller([
            'email' => 'already.live@example.com',
            'company_name' => 'Already Live Co',
            'status' => RecordStatus::Active,
            'plan' => UserPlan::Free,
        ]);

        // 1) Admin creates a sales login
        $admin = $this->admin();
        $this->actingAs($admin)
            ->post(route('verification.sales-team.store'), [
                'name' => 'Onboarding Sales',
                'email' => 'onboarding.sales@trade4deal.com',
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!',
            ])
            ->assertRedirect(route('verification.sales-team.index'));

        $sales = User::query()->where('email', 'onboarding.sales@trade4deal.com')->firstOrFail();
        $this->assertTrue($sales->isSales());
        Auth::logout();

        // 2) New seller signs up → Pending; existing Active seller untouched
        $this->post('/register', [
            'name' => 'Fresh Seller',
            'email' => 'fresh.seller@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'company_name' => 'Fresh Seller Co',
            'country' => 'India',
            'user_type' => 'seller',
            'phone' => '+919900112233',
        ])->assertRedirect(route('dashboard', absolute: false));

        $fresh = User::query()->where('email', 'fresh.seller@example.com')->firstOrFail();
        $this->assertSame(RecordStatus::Pending, $fresh->status);
        $this->assertSame(RecordStatus::Active, $existingSeller->fresh()->status);

        // 3) Pending seller sees review page and cannot use seller app
        $this->actingAs($fresh)
            ->get(route('dashboard'))
            ->assertRedirect(route('account.pending'));

        $this->actingAs($fresh)
            ->get(route('account.pending'))
            ->assertOk()
            ->assertSee('Under review')
            ->assertSee('Welcome aboard')
            ->assertSee('Contact support')
            ->assertDontSee('Your seller application was declined');

        $this->actingAs($fresh)
            ->get(route('seller.dashboard'))
            ->assertRedirect(route('account.pending'));

        $this->actingAs($fresh)
            ->get(route('seller.products.index'))
            ->assertRedirect(route('account.pending'));
        Auth::logout();

        // 4) Sales edits details (plan stays Free)
        $this->actingAs($sales)
            ->get(route('sales.onboarding.index', ['status' => 'pending']))
            ->assertOk()
            ->assertSee('Fresh Seller')
            ->assertDontSee('Already Live Co');

        $this->actingAs($sales)
            ->put(route('sales.onboarding.update', $fresh), [
                'name' => 'Fresh Seller',
                'email' => 'fresh.seller@example.com',
                'company_name' => 'Fresh Seller Global',
                'country' => 'India',
                'city' => 'Surat',
                'is_public' => true,
            ])
            ->assertRedirect(route('sales.onboarding.edit', $fresh));

        $fresh->refresh();
        $this->assertSame('Fresh Seller Global', $fresh->company_name);
        $this->assertSame(UserPlan::Free, $fresh->plan);
        $this->assertSame(RecordStatus::Pending, $fresh->status);

        // 5) Sales declines → Inactive, account kept, declined messaging
        $this->actingAs($sales)
            ->post(route('sales.onboarding.disapprove', $fresh))
            ->assertRedirect()
            ->assertSessionHas('success');

        $fresh->refresh();
        $this->assertSame(RecordStatus::Inactive, $fresh->status);
        $this->assertNotSoftDeleted('users', ['id' => $fresh->id]);

        $this->actingAs($fresh)
            ->get(route('account.pending'))
            ->assertOk()
            ->assertSee('Declined')
            ->assertSee('Your seller application was declined')
            ->assertSee('Contact support')
            ->assertDontSee('Welcome aboard')
            ->assertDontSee('What happens next');

        $this->actingAs($fresh)
            ->get(route('seller.dashboard'))
            ->assertRedirect(route('account.pending'));
        Auth::logout();

        // 6) Sales re-approves → Active and seller app works
        $this->actingAs($sales)
            ->get(route('sales.onboarding.index', ['status' => 'inactive']))
            ->assertOk()
            ->assertSee('Fresh Seller');

        $this->actingAs($sales)
            ->post(route('sales.onboarding.approve', $fresh))
            ->assertRedirect()
            ->assertSessionHas('success');

        $fresh->refresh();
        $this->assertSame(RecordStatus::Active, $fresh->status);

        $this->actingAs($fresh)
            ->get(route('dashboard'))
            ->assertRedirect(route('seller.dashboard'));

        $this->actingAs($fresh)
            ->get(route('seller.dashboard'))
            ->assertOk()
            ->assertSee('Seller Overview');

        $this->actingAs($fresh)
            ->get(route('account.pending'))
            ->assertRedirect(route('seller.dashboard'));

        // 7) Pre-existing seller still live; admin can also use sales panel
        $this->assertSame(RecordStatus::Active, $existingSeller->fresh()->status);
        $this->actingAs($existingSeller)
            ->get(route('seller.dashboard'))
            ->assertOk();

        $this->actingAs($admin)
            ->get(route('sales.onboarding.index'))
            ->assertOk()
            ->assertSee('Seller onboarding');
    }
}
