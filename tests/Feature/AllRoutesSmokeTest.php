<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Domains\Lead\Models\Lead;
use App\Domains\Product\Models\Product;
use App\Models\User;
use App\Support\Enums\RecordStatus;
use App\Support\Enums\UserType;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Illuminate\Testing\TestResponse;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AllRoutesSmokeTest extends TestCase
{
    use RefreshDatabase;

    /** @var list<string> */
    private const MARKETPLACE_PAGES = [
        'about-us',
        'help',
        'feedback',
        'customer-care',
        'live-leads',
        'sell-on-trade4deal',
        'latest-trade-leads',
        'product-directory',
        'lead-board',
        'submit-requirement',
        'search-products',
        'payment-safety',
        'seller-verification',
        'global-buyers',
        'verified-suppliers',
        'marketplace-leads',
        'categories',
        'enquiries',
        'terms-of-use',
        'privacy-policy',
    ];

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    private function assertNotServerError(TestResponse $response, string $label): void
    {
        $status = $response->status();
        $snippet = $status >= 500
            ? mb_substr(strip_tags((string) $response->getContent()), 0, 280)
            : '';

        $this->assertLessThan(
            500,
            $status,
            "{$label} returned HTTP {$status}. {$snippet}"
        );
    }

    public function test_home_renders_without_server_error(): void
    {
        $response = $this->get(route('home'));
        $this->assertNotServerError($response, 'home');
        $response->assertOk();
        $response->assertDontSee('Vite manifest not found', false);
    }

    public function test_contact_and_plans_render(): void
    {
        foreach (['contact', 'plans.index'] as $routeName) {
            $response = $this->get(route($routeName));
            $this->assertNotServerError($response, $routeName);
            $response->assertOk();
        }
    }

    public function test_all_marketplace_pages_render(): void
    {
        foreach (self::MARKETPLACE_PAGES as $page) {
            $response = $this->get(route('marketplace.page', ['page' => $page]));
            $this->assertNotServerError($response, "marketplace.page:{$page}");
            $response->assertOk();
        }
    }

    public function test_unknown_marketplace_page_returns_404_not_server_error(): void
    {
        $response = $this->get(route('marketplace.page', ['page' => 'not-a-real-page']));
        $this->assertNotServerError($response, 'marketplace.page:invalid');
        $response->assertNotFound();
    }

    public function test_guest_auth_pages_render(): void
    {
        foreach (['login', 'register', 'password.request'] as $routeName) {
            $response = $this->get(route($routeName));
            $this->assertNotServerError($response, $routeName);
            $response->assertOk();
        }
    }

    public function test_location_search_does_not_server_error(): void
    {
        $response = $this->getJson(route('locations.search', ['q' => '']));
        $this->assertNotServerError($response, 'locations.search');

        $response = $this->getJson(route('locations.search', ['q' => 'mum']));
        $this->assertNotServerError($response, 'locations.search:mum');
    }

    public function test_public_lead_and_seller_pages(): void
    {
        $lead = Lead::factory()->create([
            'published_at' => now()->subHours(30),
        ]);
        $seller = User::factory()->seller()->create([
            'status' => RecordStatus::Active,
            'is_public' => true,
        ]);
        $seller->assignRole('seller');

        $leadResponse = $this->get(route('leads.show', $lead));
        $this->assertNotServerError($leadResponse, 'leads.show');
        $leadResponse->assertOk();

        $sellerResponse = $this->get(route('sellers.show', $seller->slug));
        $this->assertNotServerError($sellerResponse, 'sellers.show');
        $sellerResponse->assertOk();
    }

    public function test_configured_static_assets_exist_on_disk(): void
    {
        $paths = array_filter([
            config('marketplace_assets.logo'),
            config('marketplace_assets.pages.electronics_logistics_bg'),
            ...array_values(config('marketplace_assets.hero', [])),
            ...array_values(config('marketplace_assets.favicons', [])),
        ]);

        foreach (config('marketplace_assets.clients', []) as $client) {
            $paths[] = $client['src'];
        }

        foreach (config('marketplace_assets.category_banners.machinery', []) as $banner) {
            $paths[] = $banner['src'];
        }

        foreach (array_unique($paths) as $path) {
            $this->assertFileExists(
                public_path($path),
                "Missing public asset: {$path}"
            );
        }
    }

    public function test_guest_protected_get_routes_redirect_without_server_error(): void
    {
        $routes = [
            'dashboard',
            'profile.edit',
            'plans.gold.success',
            'plans.gold.cancel',
            'seller.dashboard',
            'seller.products.index',
            'seller.products.create',
            'seller.profile.edit',
            'verification.dashboard',
            'verification.leads.index',
            'verification.leads.create',
            'verification.users.index',
            'sourcing.dashboard',
            'sourcing.leads.index',
            'sourcing.leads.create',
        ];

        foreach ($routes as $routeName) {
            $response = $this->get(route($routeName));
            $this->assertNotServerError($response, "guest:{$routeName}");
            $response->assertRedirect();
        }
    }

    public function test_buyer_dashboard_and_profile_do_not_server_error(): void
    {
        $buyer = $this->buyer();

        $dashboard = $this->actingAs($buyer)->get(route('dashboard'));
        $this->assertNotServerError($dashboard, 'buyer:dashboard');
        $dashboard->assertOk();

        $profile = $this->actingAs($buyer)->get(route('profile.edit'));
        $this->assertNotServerError($profile, 'buyer:profile.edit');
        $profile->assertOk();
    }

    public function test_seller_get_routes_do_not_server_error(): void
    {
        $seller = $this->seller();
        $product = Product::factory()->create(['user_id' => $seller->id]);

        $routes = [
            fn () => route('seller.dashboard'),
            fn () => route('seller.products.index'),
            fn () => route('seller.products.create'),
            fn () => route('seller.products.category-fields'),
            fn () => route('seller.products.edit', $product),
            fn () => route('seller.profile.edit'),
        ];

        foreach ($routes as $urlResolver) {
            $url = $urlResolver();
            $response = $this->actingAs($seller)->get($url);
            $this->assertNotServerError($response, "seller:{$url}");
            $response->assertOk();
        }
    }

    public function test_employee_verification_routes_do_not_server_error(): void
    {
        $employee = $this->employee();
        $lead = Lead::factory()->create();

        $routes = [
            fn () => route('verification.dashboard'),
            fn () => route('verification.leads.index'),
            fn () => route('verification.leads.create'),
            fn () => route('verification.leads.edit', $lead),
            fn () => route('verification.users.index'),
        ];

        foreach ($routes as $urlResolver) {
            $url = $urlResolver();
            $response = $this->actingAs($employee)->get($url);
            $this->assertNotServerError($response, "employee:{$url}");
            $response->assertOk();
        }
    }

    public function test_admin_team_routes_do_not_server_error(): void
    {
        $admin = $this->admin();
        $employeeUser = $this->employee();
        $sourcingUser = $this->sourcingUser();

        $routes = [
            fn () => route('verification.employee-team.index'),
            fn () => route('verification.employee-team.create'),
            fn () => route('verification.employee-team.edit', $employeeUser),
            fn () => route('verification.sourcing-team.index'),
            fn () => route('verification.sourcing-team.create'),
            fn () => route('verification.sourcing-team.edit', $sourcingUser),
        ];

        foreach ($routes as $urlResolver) {
            $url = $urlResolver();
            $response = $this->actingAs($admin)->get($url);
            $this->assertNotServerError($response, "admin:{$url}");
            $response->assertOk();
        }
    }

    public function test_sourcing_routes_do_not_server_error(): void
    {
        $sourcing = $this->sourcingUser();

        foreach (['sourcing.dashboard', 'sourcing.leads.index', 'sourcing.leads.create'] as $routeName) {
            $response = $this->actingAs($sourcing)->get(route($routeName));
            $this->assertNotServerError($response, "sourcing:{$routeName}");
            $response->assertOk();
        }
    }

    public function test_parameterless_get_routes_do_not_server_error_for_guest(): void
    {
        $skipUriPatterns = [
            '/^livewire/',
            '/^storage\//',
            '/^stripe\/payment\//',
            '/^verify-email\//',
            '/^reset-password\//',
            '/^up$/',
            '/^vendor\//',
        ];

        foreach (Route::getRoutes() as $route) {
            $methods = $route->methods();
            if (! in_array('GET', $methods, true) && ! in_array('HEAD', $methods, true)) {
                continue;
            }

            $uri = $route->uri();
            if (str_contains($uri, '{')) {
                continue;
            }

            foreach ($skipUriPatterns as $pattern) {
                if (preg_match($pattern, $uri)) {
                    continue 2;
                }
            }

            $response = $this->get('/'.$uri);
            $this->assertNotServerError($response, "GET /{$uri}");
        }
    }

    private function buyer(): User
    {
        Role::findOrCreate('buyer', 'web');
        $user = User::factory()->create([
            'user_type' => UserType::Buyer,
            'email_verified_at' => now(),
            'status' => RecordStatus::Active,
        ]);
        $user->assignRole('buyer');

        return $user;
    }

    private function seller(): User
    {
        $user = User::factory()->seller()->create([
            'user_type' => UserType::Seller,
            'email_verified_at' => now(),
            'status' => RecordStatus::Active,
        ]);
        $user->assignRole('seller');

        return $user;
    }

    private function employee(): User
    {
        $user = User::factory()->create([
            'user_type' => UserType::Employee,
            'email_verified_at' => now(),
            'status' => RecordStatus::Active,
        ]);
        $user->assignRole('employee');

        return $user;
    }

    private function admin(): User
    {
        $user = User::factory()->create([
            'user_type' => UserType::Admin,
            'email_verified_at' => now(),
            'status' => RecordStatus::Active,
        ]);
        $user->assignRole('admin');

        return $user;
    }

    private function sourcingUser(): User
    {
        $user = User::factory()->create([
            'user_type' => UserType::Sourcing,
            'email_verified_at' => now(),
            'status' => RecordStatus::Active,
        ]);
        $user->assignRole('sourcing');

        return $user;
    }
}
