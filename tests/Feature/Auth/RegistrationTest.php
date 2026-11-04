<?php

namespace Tests\Feature\Auth;

use App\Domains\Auth\Notifications\WelcomeUserNotification;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_invalid_registration_stays_on_registration_without_opening_requirement_modal(): void
    {
        $this->from('/register')->post('/register', [
            'name' => '25558',
            'email' => 'invalid@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'company_name' => '28825',
            'country' => 'India',
            'user_type' => 'buyer',
            'phone' => '98765abc210',
        ])->assertRedirect('/register')->assertSessionHasErrors(['name', 'company_name', 'phone']);

        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['email' => 'invalid@example.com']);
        $this->get('/register')
            ->assertOk()
            ->assertSee('Your business name must contain at least one letter.')
            ->assertDontSee('if (modalEl) new bootstrap.Modal(modalEl).show();', false);
    }

    public function test_requirement_modal_reopens_for_its_own_validation_errors(): void
    {
        $this->withSession([
            '_old_input' => ['_form' => 'requirement-modal'],
            'errors' => (new ViewErrorBag)->put('default', new MessageBag([
                'company_name' => 'Please enter your company name.',
            ])),
        ])->get('/')->assertOk()
            ->assertSee('if (modalEl) new bootstrap.Modal(modalEl).show();', false);
    }

    public function test_account_popup_rejects_numeric_selling_products(): void
    {
        $this->post('/register', [
            'name' => 'Test Seller',
            'email' => 'popup@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'company_name' => '3M Trading',
            'selling_products' => '12345',
            'country' => 'India',
            'user_type' => 'seller',
            'phone' => '+919876543210',
        ])->assertSessionHasErrors('selling_products');

        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['email' => 'popup@example.com']);
    }

    public function test_new_users_can_register_and_reach_dashboard(): void
    {
        Notification::fake();

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'company_name' => 'Acme Trading Co',
            'country' => 'India',
            'user_type' => 'buyer',
            'phone' => '+919876543210',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));

        $user = User::query()->where('email', 'test@example.com')->first();
        $this->assertNotNull($user);
        $this->assertNotNull($user->email_verified_at);
        $this->assertTrue(password_verify('password', $user->password));

        Notification::assertSentTo($user, WelcomeUserNotification::class);

        $this->get('/dashboard')->assertOk();
    }

    public function test_registration_rejects_duplicate_phone_after_normalization(): void
    {
        Notification::fake();

        $payload = [
            'name' => 'First User',
            'email' => 'first@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'company_name' => 'First Co',
            'country' => 'India',
            'user_type' => 'buyer',
            'phone' => '9876543210',
        ];

        $this->post('/register', $payload)->assertRedirect(route('dashboard', absolute: false));
        Auth::logout();

        $this->post('/register', [
            ...$payload,
            'name' => 'Second User',
            'email' => 'second@example.com',
            'phone' => '+91 9876543210',
        ])
            ->assertSessionHasErrors('phone');

        $this->assertGuest();
        $this->assertDatabaseCount('users', 1);
    }

    public function test_registration_allows_multiple_users_without_phone(): void
    {
        Notification::fake();

        $base = [
            'password' => 'password',
            'password_confirmation' => 'password',
            'company_name' => 'No Phone Co',
            'country' => 'India',
            'user_type' => 'seller',
        ];

        $this->post('/register', [
            ...$base,
            'name' => 'User A',
            'email' => 'a@example.com',
        ])->assertRedirect(route('dashboard', absolute: false));
        Auth::logout();

        $this->post('/register', [
            ...$base,
            'name' => 'User B',
            'email' => 'b@example.com',
        ])->assertRedirect(route('dashboard', absolute: false));

        $this->assertDatabaseCount('users', 2);
    }
}
