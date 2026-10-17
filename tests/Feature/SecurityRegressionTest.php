<?php

namespace Tests\Feature;

use App\Domains\Product\Models\Product;
use App\Models\User;
use App\Support\Enums\RecordStatus;
use App\Support\Enums\UserPlan;
use App\Support\Enums\UserType;
use App\Support\Storage\PublicFileTransaction;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class SecurityRegressionTest extends TestCase
{
    use RefreshDatabase;

    public function test_inactive_accounts_cannot_log_in_or_keep_using_a_session(): void
    {
        $user = User::factory()->create(['status' => RecordStatus::Inactive]);
        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
        $this->actingAs($user)->get('/profile')->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_legacy_gold_flag_does_not_prove_payment_and_admin_grants_are_separate(): void
    {
        $user = User::factory()->create(['plan' => UserPlan::Gold]);
        $this->assertSame(UserPlan::Free, $user->userPlan());
        $user->forceFill(['plan_override' => UserPlan::Gold])->save();
        $user->syncPlanFromPayments();
        $this->assertSame(UserPlan::Gold, $user->fresh()->userPlan());
    }

    public function test_unverified_account_cannot_checkout_or_publish_a_product(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $user = User::factory()->seller()->unverified()->create();
        $user->assignRole('seller');
        $this->actingAs($user)->post('/plans/gold/razorpay/checkout')->assertRedirect(route('verification.notice'));
        $this->post('/seller/products', [])->assertRedirect(route('verification.notice'));
    }

    public function test_profile_normalizes_and_rejects_duplicate_phone_numbers(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $user = User::factory()->seller()->create(['country' => 'India', 'phone' => '9876543210']);
        $user->assignRole('seller');
        $data = ['name' => $user->name, 'email' => $user->email, 'company_name' => $user->company_name,
            'country' => 'India', 'phone' => '9123456789'];
        $this->actingAs($user)->put('/seller/profile', $data)->assertSessionHasNoErrors();
        $this->assertSame('919123456789', $user->fresh()->phone_normalized);
        $other = User::factory()->create(['country' => 'India', 'phone' => '9876543210']);
        $this->put('/seller/profile', [...$data, 'phone' => '+91 9876543210'])->assertSessionHasErrors('phone');
        $this->assertSame('919876543210', $other->phone_normalized);
    }

    public function test_geocoder_outage_preserves_local_results_and_returns_controlled_reverse_failure(): void
    {
        config(['services.location_geocoder.provider' => 'nominatim']);
        Http::fake(fn () => throw new ConnectionException('Simulated outage'));
        $this->getJson('/locations/search?q=Mumbai')->assertOk()->assertJsonFragment(['city' => 'Mumbai']);
        $this->postJson('/locations/reverse', ['lat' => 19, 'lng' => 72])->assertStatus(422);
    }

    public function test_private_draft_locations_are_excluded_from_public_suggestions(): void
    {
        $seller = User::factory()->seller()->create(['is_public' => false]);
        Product::factory()->draft()->create(['user_id' => $seller->id, 'location_id' => 'private-location',
            'location_city' => 'AuditPrivateCity', 'location_country' => 'India']);
        $this->getJson('/locations/search?q=AuditPrivateCity')->assertOk()->assertExactJson(['locations' => []]);
        $this->get('/pages/product-directory')->assertOk()->assertDontSee('AuditPrivateCity');
    }

    public function test_default_seeding_never_provisions_or_resets_credentials(): void
    {
        $this->seed(DatabaseSeeder::class);
        $this->seed(DatabaseSeeder::class);
        $this->assertDatabaseCount('users', 0);
    }

    public function test_failed_upload_keeps_old_file_and_cleans_up_staged_files(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('old.txt', 'original');
        $files = app(PublicFileTransaction::class);
        $staged = null;
        try {
            $files->run(function () use ($files, &$staged): void {
                $staged = $files->store(UploadedFile::fake()->create('new.pdf', 1, 'application/pdf'), 'specs', 'file');
                $files->removeAfterCommit('old.txt');
                User::factory()->create(['email' => 'rollback@example.com']);
                throw ValidationException::withMessages(['file' => 'Simulated failure']);
            });
            $this->fail('Expected a failed transaction');
        } catch (ValidationException) {
            Storage::disk('public')->assertExists('old.txt');
            Storage::disk('public')->assertMissing($staged);
            $this->assertDatabaseMissing('users', ['email' => 'rollback@example.com']);
        }
    }

    public function test_old_file_is_deleted_only_after_successful_commit(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('old.txt', 'original');
        $files = app(PublicFileTransaction::class);
        $files->run(function () use ($files): void {
            $files->removeAfterCommit('old.txt');
            Storage::disk('public')->assertExists('old.txt');
        });
        Storage::disk('public')->assertMissing('old.txt');
    }

    public function test_disk_write_failure_keeps_the_previous_file(): void
    {
        Storage::fake('public');
        $disk = Storage::disk('public');
        $disk->put('old.txt', 'original');
        $brokenFile = \Mockery::mock(UploadedFile::class);
        $brokenFile->shouldReceive('store')->once()->with('specs', 'public')->andReturn(false);
        $files = app(PublicFileTransaction::class);
        try {
            $files->run(function () use ($files, $brokenFile): void {
                $files->removeAfterCommit('old.txt');
                $files->store($brokenFile, 'specs', 'file');
            });
            $this->fail('Expected upload validation failure');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('file', $exception->errors());
            $disk->assertExists('old.txt');
        }
    }

    public function test_requirement_form_collects_the_actual_business_identity(): void
    {
        $this->get('/')->assertOk()->assertSee('id="modal_email"', false)
            ->assertSee('id="modal_country"', false)->assertSee('id="modal_product_type"', false)
            ->assertDontSee('requirements@trade4deal.com');
    }

    public function test_production_preflight_fails_for_unsafe_settings(): void
    {
        config(['app.debug' => true, 'session.secure' => false, 'mail.default' => 'log']);
        $this->artisan('app:check-production')->assertExitCode(1);
    }

    public function test_team_members_are_paginated(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $admin = User::factory()->create(['user_type' => UserType::Admin]);
        $admin->assignRole('admin');
        User::factory()->count(21)->create(['user_type' => UserType::Employee]);
        $response = $this->actingAs($admin)->get('/verification/employee-team');
        $response->assertOk()->assertViewHas('members', fn ($members) => $members->count() === 20 && $members->total() === 21);
    }

}
