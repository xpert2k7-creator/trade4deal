# Product category fields — Phase 2 (database layer)

## Category architecture

- **`products.product_type`** remains the seller-facing category (`App\Support\Enums\ProductType`).
- New **`categories`** table stores metadata rows keyed by the same string values (`textiles`, `electronics`, …).
- **`product_fields.category_id`** links field definitions to `categories.id` (not to a duplicate enum).

## Machinery → Other (field definitions)

- Excel has **no Industrial Machinery sheet**.
- **`categories`** includes `machinery` for alignment with `ProductType::Machinery`.
- **No `product_fields` rows** are seeded for `machinery`.
- **Phase 3** must resolve field schemas with:  
  `ProductType::Machinery` → use **`categories.key = other`** (same 8 “Other” specification fields).

## Duplicate “Other” fields skipped

These Excel rows are **not** seeded (core product columns or common registration fields):

| Excel field | Reason |
|-------------|--------|
| Product Name | `products.name` |
| Brand / Manufacturer | Common registration field (not on `products` today; not duplicated in EAV) |
| Product Description | `products.description` |

## File upload fields (Phase 3+)

Category **`field_type = file`** fields (e.g. SDS/MSDS, COA, test certificates) will store a **path string** in `product_field_values.value`, reusing the existing **`public` disk** pattern (`products.image_path` → `/uploads/...`). No separate media table in Phase 2.

## Seeding

```bash
php artisan migrate
php artisan db:seed --class=ProductCategoryFieldSeeder
```

Seeder is **idempotent** (`category.key` + `category_id` + `field_key`; options by `product_field_id` + `value`).

Source: `Trade4Deal_Seller_Product_Registration_Fields(1).xlsx` (encoded in `database/seeders/Support/ProductCategoryFieldDefinitions.php`).
