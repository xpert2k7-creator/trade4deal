<?php

declare(strict_types=1);

namespace Tests\Feature\Product;

use App\Domains\Product\Models\Product;
use App\Models\User;
use App\Support\Enums\ProductType;
use App\Support\Enums\RecordStatus;
use App\Support\Enums\UserType;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductImageUploadLogTest extends TestCase
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
            'is_public' => true,
        ], $overrides));
        $user->assignRole('seller');

        return $user;
    }

    public function test_successful_product_upload_persists_images_logs_and_public_page(): void
    {
        Storage::fake('public');

        $logPath = storage_path('logs/laravel.log');
        $logOffset = is_file($logPath) ? filesize($logPath) : 0;

        $seller = $this->seller(['slug' => 'bk-international']);

        $this->actingAs($seller)
            ->post(route('seller.products.store'), [
                'name' => 'Logged Upload Tee',
                'description' => 'Upload test product.',
                'product_type' => ProductType::Textiles->value,
                'currency' => 'USD',
                'units' => 'pieces',
                'status' => RecordStatus::Active->value,
                'product_images' => [
                    UploadedFile::fake()->image('logged-tee.jpg'),
                ],
            ])
            ->assertRedirect(route('seller.products.index'));

        $product = Product::query()->where('name', 'Logged Upload Tee')->first();
        $this->assertNotNull($product);
        $this->assertCount(1, $product->imagePathsList());
        $this->assertStringStartsWith('members/'.$seller->id.'/products/', $product->imagePathsList()[0]);
        Storage::disk('public')->assertExists($product->imagePathsList()[0]);

        $imageUrl = $product->imageUrl();
        $this->assertNotNull($imageUrl);

        $this->get(route('sellers.show', 'bk-international'))
            ->assertOk()
            ->assertSee('Logged Upload Tee', false)
            ->assertSee($imageUrl, false);

        $this->assertFileExists($logPath);
        $newLog = substr((string) file_get_contents($logPath), $logOffset);
        $this->assertStringContainsString('product_image.upload.stored', $newLog);
        $this->assertStringContainsString('product_image.merge_complete', $newLog);
        $this->assertStringContainsString('product_image.product_saved', $newLog);
        $this->assertStringContainsString('members/'.$seller->id.'/products/', $newLog);
    }

    public function test_invalid_image_type_is_rejected_before_product_is_created(): void
    {
        Storage::fake('public');

        $seller = $this->seller();

        $this->actingAs($seller)
            ->post(route('seller.products.store'), [
                'name' => 'Should Not Save Images',
                'description' => 'Invalid mime test.',
                'product_type' => ProductType::Textiles->value,
                'currency' => 'USD',
                'units' => 'pieces',
                'status' => RecordStatus::Active->value,
                'product_images' => [
                    UploadedFile::fake()->create('broken.txt', 10, 'text/plain'),
                ],
            ])
            ->assertSessionHasErrors('product_images.0');

        $this->assertDatabaseMissing('products', [
            'user_id' => $seller->id,
            'name' => 'Should Not Save Images',
        ]);
    }
}
