<?php

declare(strict_types=1);

namespace Tests\Feature\Seller;

use App\Domains\Product\Models\Product;
use App\Domains\Lead\Models\Lead;
use App\Domains\Seller\Notifications\SellerEnquiryNotification;
use App\Models\User;
use App\Support\Enums\ProductType;
use App\Support\Enums\RecordStatus;
use App\Support\Enums\UserPlan;
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

    public function test_seller_can_upload_company_logo(): void
    {
        Storage::fake('public');

        $seller = $this->seller([
            'company_name' => 'Logo Supplier Co',
            'country' => 'India',
        ]);

        $this->actingAs($seller)
            ->put(route('seller.profile.update'), [
                'company_name' => 'Logo Supplier Co',
                'country' => 'India',
                'logo' => new UploadedFile($this->tinyJpegPath(), 'logo.jpg', 'image/jpeg', null, true),
                'is_public' => true,
            ])
            ->assertRedirect(route('seller.profile.edit'));

        $seller->refresh();

        $this->assertNotNull($seller->logo_path);
        $this->assertSame('/uploads/'.$seller->logo_path, $seller->logoUrl());
        Storage::disk('public')->assertExists($seller->logo_path);
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
            'min_order_qty' => '500',
        ]);
    }

    public function test_seller_can_upload_product_image(): void
    {
        Storage::fake('public');

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
                'image' => new UploadedFile($this->tinyJpegPath(), 'fabric.jpg', 'image/jpeg', null, true),
            ])
            ->assertRedirect(route('seller.products.index'));

        $product = Product::query()->where('user_id', $seller->id)->firstOrFail();

        $this->assertNotNull($product->image_path);
        Storage::disk('public')->assertExists($product->image_path);
    }

    public function test_seller_dashboard_shows_category_matched_leads(): void
    {
        $seller = $this->seller([
            'industries' => [ProductType::Textiles->value],
        ]);

        Lead::factory()->create([
            'company_name' => 'Matched Buyer Co',
            'product_interest' => 'Cotton yarn sourcing',
            'product_type' => ProductType::Textiles,
            'status' => RecordStatus::Active,
            'published_at' => now()->subHours(25),
        ]);

        Lead::factory()->create([
            'company_name' => 'Different Buyer Co',
            'product_interest' => 'Excavator parts',
            'product_type' => ProductType::Machinery,
            'status' => RecordStatus::Active,
            'published_at' => now()->subHours(25),
        ]);

        $this->actingAs($seller)
            ->get(route('seller.dashboard'))
            ->assertOk()
            ->assertSee('Matching leads')
            ->assertSee('Textiles &amp; Apparel', false)
            ->assertSee('Cotton yarn sourcing')
            ->assertSee('Matched Buyer Co')
            ->assertDontSee('Excavator parts')
            ->assertDontSee('Different Buyer Co');
    }

    private function tinyJpegPath(): string
    {
        $path = tempnam(sys_get_temp_dir(), 'product-image-').'.jpg';

        file_put_contents(
            $path,
            base64_decode('/9j/4AAQSkZJRgABAQAAAQABAAD/2wBDAP//////////////////////////////////////////////////////////////////////////////////////2wBDAf//////////////////////////////////////////////////////////////////////////////////////wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAX/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIQAxAAAAGgP//EABQQAQAAAAAAAAAAAAAAAAAAAAD/2gAIAQEAAQUCf//EABQRAQAAAAAAAAAAAAAAAAAAABD/2gAIAQMBAT8BP//EABQRAQAAAAAAAAAAAAAAAAAAABD/2gAIAQIBAT8BP//EABQQAQAAAAAAAAAAAAAAAAAAAAD/2gAIAQEABj8Cf//EABQQAQAAAAAAAAAAAAAAAAAAAAD/2gAIAQEAAT8hf//aAAwDAQACAAMAAAAQ8P/EABQRAQAAAAAAAAAAAAAAAAAAABD/2gAIAQMBAT8QP//EABQRAQAAAAAAAAAAAAAAAAAAABD/2gAIAQIBAT8QP//EABQQAQAAAAAAAAAAAAAAAAAAAAD/2gAIAQEAAT8QP//Z')
        );

        return $path;
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

    public function test_gold_seller_public_page_shows_verified_supplier_badge(): void
    {
        $seller = $this->seller([
            'company_name' => 'Verified Supplier Co',
            'plan' => UserPlan::Gold,
            'is_public' => true,
        ]);

        $this->get(route('sellers.show', $seller->slug))
            ->assertOk()
            ->assertSee('Verified supplier by Trade4Deal');
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
