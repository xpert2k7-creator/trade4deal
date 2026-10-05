<?php

declare(strict_types=1);

namespace Tests\Feature\Seller;

use App\Domains\Product\Models\Product;
use App\Domains\Seller\Notifications\SellerEnquiryNotification;
use App\Models\User;
use App\Support\Enums\ProductType;
use App\Support\Enums\RecordStatus;
use App\Support\Enums\UserType;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SellerDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    private function seller(array $overrides = []): User
    {
        $user = User::factory()->seller()->create(array_merge([
            'user_type' => UserType::Seller,
        ], $overrides));
        $user->assignRole('seller');

        return $user;
    }

    public function test_seller_is_redirected_to_seller_dashboard(): void
    {
        $this->actingAs($this->seller())
            ->get(route('dashboard'))
            ->assertRedirect(route('seller.dashboard'));
    }

    public function test_seller_can_view_dashboard_and_edit_profile(): void
    {
        $seller = $this->seller([
            'company_name' => 'Acme Exports',
        ]);

        $this->actingAs($seller)
            ->get(route('seller.dashboard'))
            ->assertOk()
            ->assertSee('Seller Overview');

        $this->actingAs($seller)
            ->put(route('seller.profile.update'), [
                'company_name' => 'Acme Global Exports',
                'tagline' => 'Quality textiles worldwide',
                'about' => 'We export premium textiles to 40+ countries with reliable logistics.',
                'country' => 'India',
                'city' => 'Surat',
                'industries' => [ProductType::Textiles->value],
                'is_public' => true,
            ])
            ->assertRedirect(route('seller.profile.edit'));

        $this->assertDatabaseHas('users', [
            'id' => $seller->id,
            'company_name' => 'Acme Global Exports',
            'city' => 'Surat',
        ]);
    }

    public function test_seller_can_create_product(): void
    {
        $seller = $this->seller();

        $this->actingAs($seller)
            ->post(route('seller.products.store'), [
                'name' => 'Cotton Fabric Rolls',
                'description' => 'High quality cotton for apparel manufacturing.',
                'product_type' => ProductType::Textiles->value,
                'currency' => 'USD',
                'units' => 'meters',
                'min_order_qty' => '500',
                'price_from' => 2.5,
                'price_to' => 4.0,
                'status' => RecordStatus::Active->value,
            ])
            ->assertRedirect(route('seller.products.index'));

        $this->assertDatabaseHas('products', [
            'user_id' => $seller->id,
            'name' => 'Cotton Fabric Rolls',
            'status' => RecordStatus::Active->value,
        ]);
    }

    public function test_seller_can_upload_multiple_product_images_on_create_and_update(): void
    {
        Storage::fake('public');

        $seller = $this->seller();

        $this->actingAs($seller)
            ->post(route('seller.products.store'), [
                'name' => 'Multi Photo Widget',
                'description' => 'Sample product with gallery.',
                'product_type' => ProductType::Electronics->value,
                'currency' => 'USD',
                'units' => 'pieces',
                'status' => RecordStatus::Active->value,
                'product_images' => [
                    UploadedFile::fake()->image('a.jpg'),
                    UploadedFile::fake()->image('b.jpg'),
                ],
            ])
            ->assertRedirect(route('seller.products.index'));

        $product = Product::query()->where('user_id', $seller->id)->where('name', 'Multi Photo Widget')->first();
        $this->assertNotNull($product);
        $this->assertCount(2, $product->imagePathsList());
        $this->assertNotNull($product->image_path);

        foreach ($product->imagePathsList() as $path) {
            Storage::disk('public')->assertExists($path);
            $this->assertStringStartsWith('members/'.$seller->id.'/products/', $path);
        }

        $firstPath = $product->imagePathsList()[0];

        $this->actingAs($seller)
            ->put(route('seller.products.update', $product), [
                'name' => 'Multi Photo Widget',
                'description' => 'Sample product with gallery.',
                'product_type' => ProductType::Electronics->value,
                'currency' => 'USD',
                'units' => 'pieces',
                'status' => RecordStatus::Active->value,
                'remove_product_images' => [$firstPath],
                'product_images' => [
                    UploadedFile::fake()->image('c.jpg'),
                ],
            ])
            ->assertRedirect(route('seller.products.edit', $product));

        $product->refresh();
        $this->assertCount(2, $product->imagePathsList());
        Storage::disk('public')->assertMissing($firstPath);
    }

    public function test_public_seller_page_shows_product_images(): void
    {
        Storage::fake('public');

        $seller = $this->seller([
            'company_name' => 'BK International',
            'slug' => 'bk-international',
            'is_public' => true,
        ]);

        $this->actingAs($seller)
            ->post(route('seller.products.store'), [
                'name' => 'Catalog Tee',
                'description' => 'Cotton tee for export.',
                'product_type' => ProductType::Textiles->value,
                'currency' => 'USD',
                'units' => 'pieces',
                'status' => RecordStatus::Active->value,
                'product_images' => [
                    UploadedFile::fake()->image('catalog.jpg'),
                ],
            ])
            ->assertRedirect(route('seller.products.index'));

        $product = Product::query()->where('user_id', $seller->id)->where('name', 'Catalog Tee')->first();
        $this->assertNotNull($product);
        $imageUrl = $product->imageUrl();
        $this->assertNotNull($imageUrl);

        $this->get(route('sellers.show', 'bk-international'))
            ->assertOk()
            ->assertSee('Catalog Tee', false)
            ->assertSee($imageUrl, false);
    }

    public function test_buyer_cannot_access_seller_dashboard(): void
    {
        $buyer = User::factory()->create(['user_type' => UserType::Buyer]);
        $buyer->assignRole('buyer');

        $this->actingAs($buyer)
            ->get(route('seller.dashboard'))
            ->assertForbidden();
    }

    public function test_public_seller_page_shows_live_products_and_hides_phone(): void
    {
        $seller = $this->seller([
            'company_name' => 'Visible Seller Co',
            'phone' => '+91 99999 88888',
            'about' => 'We supply industrial machinery parts globally.',
            'is_public' => true,
        ]);

        Product::factory()->create([
            'user_id' => $seller->id,
            'name' => 'Gear Assemblies',
            'status' => RecordStatus::Active,
        ]);

        Product::factory()->draft()->create([
            'user_id' => $seller->id,
            'name' => 'Hidden Draft Product',
        ]);

        $this->get(route('sellers.show', $seller->slug))
            ->assertOk()
            ->assertSee('Visible Seller Co')
            ->assertSee('Gear Assemblies')
            ->assertSee('Enquire with seller')
            ->assertDontSee('+91 99999 88888')
            ->assertDontSee('Hidden Draft Product');
    }

    public function test_enquiry_emails_seller(): void
    {
        Notification::fake();

        $seller = $this->seller([
            'email' => 'seller-demo@example.com',
            'is_public' => true,
        ]);

        $this->post(route('sellers.contact', $seller->slug), [
            'name' => 'Buyer One',
            'email' => 'buyer@example.com',
            'company_name' => 'Buyer Corp',
            'country' => 'USA',
            'message' => 'We are interested in partnering for bulk supply this quarter.',
        ])
            ->assertRedirect(route('sellers.show', $seller->slug))
            ->assertSessionHas('success');

        Notification::assertSentOnDemand(SellerEnquiryNotification::class);
    }
}
