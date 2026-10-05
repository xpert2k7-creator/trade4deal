<?php

declare(strict_types=1);

namespace Tests\Feature\Product;

use App\Domains\Product\Models\Product;
use App\Domains\Product\Models\ProductField;
use App\Domains\Product\Models\ProductFieldValue;
use App\Domains\Product\Services\ProductCategoryResolver;
use App\Domains\Product\Services\ProductDynamicFieldService;
use App\Models\User;
use App\Support\Enums\ProductType;
use App\Support\Enums\RecordStatus;
use App\Support\Enums\UserType;
use Database\Seeders\ProductCategoryFieldSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductDynamicFieldsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
        $this->seed(ProductCategoryFieldSeeder::class);
    }

    private function seller(): User
    {
        $user = User::factory()->seller()->create(['user_type' => UserType::Seller]);
        $user->assignRole('seller');

        return $user;
    }

    public function test_field_counts_per_category_and_machinery_uses_other(): void
    {
        $service = app(ProductDynamicFieldService::class);

        $this->assertSame(14, $service->activeFieldsForProductType(ProductType::Electronics)->count());
        $this->assertSame(8, $service->activeFieldsForProductType(ProductType::Machinery)->count());
        $this->assertSame(
            ProductCategoryResolver::fieldCategoryKey(ProductType::Machinery),
            ProductCategoryResolver::fieldCategoryKey(ProductType::Other),
        );
    }

    public function test_category_fields_endpoint_returns_machinery_other_fields(): void
    {
        $seller = $this->seller();

        $this->actingAs($seller)
            ->getJson(route('seller.products.category-fields', ['product_type' => ProductType::Machinery->value]))
            ->assertOk()
            ->assertJsonCount(8, 'fields');
    }

    public function test_create_product_with_dynamic_fields(): void
    {
        $seller = $this->seller();
        $field = ProductField::query()
            ->whereHas('category', fn ($q) => $q->where('key', 'electronics'))
            ->where('field_key', 'model_number')
            ->firstOrFail();

        $this->actingAs($seller)
            ->post(route('seller.products.store'), [
                'name' => 'Test Device',
                'description' => 'Desc',
                'product_type' => ProductType::Electronics->value,
                'currency' => 'USD',
                'units' => 'pieces',
                'status' => RecordStatus::Active->value,
                'dynamic_fields' => [
                    $field->id => 'ABC-123',
                ],
            ])
            ->assertRedirect(route('seller.products.index'));

        $product = Product::query()->where('name', 'Test Device')->firstOrFail();

        $this->assertDatabaseHas('product_field_values', [
            'product_id' => $product->id,
            'product_field_id' => $field->id,
            'value' => 'ABC-123',
        ]);
    }

    public function test_update_dynamic_field_uses_single_row(): void
    {
        $seller = $this->seller();
        $field = ProductField::query()
            ->whereHas('category', fn ($q) => $q->where('key', 'electronics'))
            ->where('field_key', 'model_number')
            ->firstOrFail();

        $product = Product::factory()->create([
            'user_id' => $seller->id,
            'product_type' => ProductType::Electronics,
        ]);

        $payload = [
            'name' => $product->name,
            'description' => $product->description,
            'product_type' => ProductType::Electronics->value,
            'currency' => 'USD',
            'units' => 'pieces',
            'status' => RecordStatus::Active->value,
            'dynamic_fields' => [$field->id => 'First'],
        ];

        $this->actingAs($seller)->put(route('seller.products.update', $product), $payload);
        $this->actingAs($seller)->put(route('seller.products.update', $product), array_merge($payload, [
            'dynamic_fields' => [$field->id => 'Second'],
        ]));

        $this->assertSame(1, ProductFieldValue::query()->where('product_id', $product->id)->count());
        $this->assertSame('Second', ProductFieldValue::query()->where('product_field_id', $field->id)->value('value'));
    }

    public function test_empty_optional_field_removes_existing_value(): void
    {
        $seller = $this->seller();
        $field = ProductField::query()
            ->whereHas('category', fn ($q) => $q->where('key', 'electronics'))
            ->where('field_key', 'model_number')
            ->firstOrFail();

        $product = Product::factory()->create([
            'user_id' => $seller->id,
            'product_type' => ProductType::Electronics,
        ]);

        ProductFieldValue::query()->create([
            'product_id' => $product->id,
            'product_field_id' => $field->id,
            'value' => 'Remove-me',
        ]);

        $this->actingAs($seller)->put(route('seller.products.update', $product), [
            'name' => $product->name,
            'product_type' => ProductType::Electronics->value,
            'currency' => 'USD',
            'units' => 'pieces',
            'status' => RecordStatus::Active->value,
            'dynamic_fields' => [$field->id => null],
        ]);

        $this->assertDatabaseMissing('product_field_values', [
            'product_id' => $product->id,
            'product_field_id' => $field->id,
        ]);
    }

    public function test_category_change_removes_old_field_values(): void
    {
        $seller = $this->seller();
        $electronicsField = ProductField::query()
            ->whereHas('category', fn ($q) => $q->where('key', 'electronics'))
            ->where('field_key', 'model_number')
            ->firstOrFail();
        $agricultureField = ProductField::query()
            ->whereHas('category', fn ($q) => $q->where('key', 'agriculture'))
            ->where('field_key', 'crop_commodity')
            ->firstOrFail();

        $product = Product::factory()->create([
            'user_id' => $seller->id,
            'product_type' => ProductType::Electronics,
        ]);

        ProductFieldValue::query()->create([
            'product_id' => $product->id,
            'product_field_id' => $electronicsField->id,
            'value' => 'E-1',
        ]);

        $this->actingAs($seller)->put(route('seller.products.update', $product), [
            'name' => $product->name,
            'product_type' => ProductType::Agriculture->value,
            'currency' => 'USD',
            'units' => 'pieces',
            'status' => RecordStatus::Active->value,
            'dynamic_fields' => [
                $agricultureField->id => 'Wheat',
            ],
        ]);

        $this->assertDatabaseMissing('product_field_values', [
            'product_id' => $product->id,
            'product_field_id' => $electronicsField->id,
        ]);
        $this->assertDatabaseHas('product_field_values', [
            'product_id' => $product->id,
            'product_field_id' => $agricultureField->id,
            'value' => 'Wheat',
        ]);
    }

    public function test_invalid_field_id_from_other_category_is_rejected(): void
    {
        $seller = $this->seller();
        $chemicalField = ProductField::query()
            ->whereHas('category', fn ($q) => $q->where('key', 'chemicals'))
            ->firstOrFail();

        $this->actingAs($seller)
            ->post(route('seller.products.store'), [
                'name' => 'Bad Injection',
                'product_type' => ProductType::Electronics->value,
                'currency' => 'USD',
                'units' => 'pieces',
                'status' => RecordStatus::Active->value,
                'dynamic_fields' => [
                    $chemicalField->id => 'hack',
                ],
            ])
            ->assertSessionHasErrors('dynamic_fields.'.$chemicalField->id);

        $this->assertDatabaseMissing('products', ['name' => 'Bad Injection']);
    }

    public function test_invalid_select_option_fails_validation(): void
    {
        $seller = $this->seller();
        $field = ProductField::query()
            ->whereHas('category', fn ($q) => $q->where('key', 'electronics'))
            ->where('field_key', 'certifications')
            ->firstOrFail();

        $this->actingAs($seller)
            ->post(route('seller.products.store'), [
                'name' => 'Invalid Select',
                'product_type' => ProductType::Electronics->value,
                'currency' => 'USD',
                'units' => 'pieces',
                'status' => RecordStatus::Active->value,
                'dynamic_fields' => [
                    $field->id => 'not-a-real-cert',
                ],
            ])
            ->assertSessionHasErrors('dynamic_fields.'.$field->id);
    }

    public function test_boolean_field_stores_normalized_value(): void
    {
        $seller = $this->seller();
        $field = ProductField::query()
            ->whereHas('category', fn ($q) => $q->where('key', 'electronics'))
            ->where('field_key', 'user_manual_available')
            ->firstOrFail();

        $this->actingAs($seller)->post(route('seller.products.store'), [
            'name' => 'Bool Product',
            'product_type' => ProductType::Electronics->value,
            'currency' => 'USD',
            'units' => 'pieces',
            'status' => RecordStatus::Active->value,
            'dynamic_fields' => [$field->id => '1'],
        ]);

        $product = Product::query()->where('name', 'Bool Product')->firstOrFail();

        $this->assertDatabaseHas('product_field_values', [
            'product_id' => $product->id,
            'product_field_id' => $field->id,
            'value' => '1',
        ]);
    }

    public function test_file_field_stores_path(): void
    {
        Storage::fake('public');
        $seller = $this->seller();
        $field = ProductField::query()
            ->whereHas('category', fn ($q) => $q->where('key', 'chemicals'))
            ->where('field_key', 'coa')
            ->firstOrFail();

        $this->actingAs($seller)->post(route('seller.products.store'), [
            'name' => 'File Product',
            'product_type' => ProductType::Chemicals->value,
            'currency' => 'USD',
            'units' => 'pieces',
            'status' => RecordStatus::Active->value,
            'dynamic_fields' => [
                $field->id => UploadedFile::fake()->create('coa.pdf', 100, 'application/pdf'),
            ],
        ]);

        $product = Product::query()->where('name', 'File Product')->firstOrFail();
        $path = ProductFieldValue::query()->where('product_field_id', $field->id)->value('value');

        $this->assertNotNull($path);
        Storage::disk('public')->assertExists($path);
        $this->assertSame($product->id, ProductFieldValue::query()->where('product_field_id', $field->id)->value('product_id'));
    }

    public function test_edit_form_shows_saved_dynamic_values(): void
    {
        $seller = $this->seller();
        $field = ProductField::query()
            ->whereHas('category', fn ($q) => $q->where('key', 'electronics'))
            ->where('field_key', 'model_number')
            ->firstOrFail();

        $product = Product::factory()->create([
            'user_id' => $seller->id,
            'product_type' => ProductType::Electronics,
        ]);

        ProductFieldValue::query()->create([
            'product_id' => $product->id,
            'product_field_id' => $field->id,
            'value' => 'EDIT-999',
        ]);

        $this->actingAs($seller)
            ->get(route('seller.products.edit', $product))
            ->assertOk()
            ->assertSee('EDIT-999', false);
    }

    public function test_seller_cannot_update_another_sellers_product_dynamic_values(): void
    {
        $owner = $this->seller();
        $intruder = $this->seller();
        $product = Product::factory()->create(['user_id' => $owner->id, 'product_type' => ProductType::Electronics]);

        $this->actingAs($intruder)
            ->put(route('seller.products.update', $product), [
                'name' => 'Hijacked',
                'product_type' => ProductType::Electronics->value,
                'currency' => 'USD',
                'units' => 'pieces',
                'status' => RecordStatus::Active->value,
            ])
            ->assertForbidden();
    }
}
