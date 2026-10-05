<?php

namespace Tests\Feature\Auth;

use App\Domains\Auth\Notifications\WelcomeUserNotification;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
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
