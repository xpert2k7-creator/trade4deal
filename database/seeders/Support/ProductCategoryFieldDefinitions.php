<?php

declare(strict_types=1);

namespace Database\Seeders\Support;

use App\Support\Enums\ProductFieldType;
use Illuminate\Support\Str;

/**
 * Category-specific product fields from Trade4Deal_Seller_Product_Registration_Fields(1).xlsx
 *
 * Machinery (product_type) has no sheet — Phase 3 resolves machinery → other for field lookup.
 * "Other" sheet duplicates skipped: Product Name, Brand / Manufacturer, Product Description.
 */
final class ProductCategoryFieldDefinitions
{
    public const SECTION = 'Category Specifications';

    /**
     * @return array<string, list<array{
     *     field_name: string,
     *     field_key: string,
     *     field_type: ProductFieldType,
     *     unit: ?string,
     *     options?: list<string>
     * }>>
     */
    public static function fieldsByCategoryKey(): array
    {
        return [
            'textiles' => self::textiles(),
            'electronics' => self::electronics(),
            'agriculture' => self::agriculture(),
            'chemicals' => self::chemicals(),
            'construction' => self::construction(),
            'medical' => self::medical(),
            'food' => self::food(),
            'other' => self::other(),
        ];
    }

    /**
     * @return list<array{key: string, name: string, sort_order: int}>
     */
    public static function categories(): array
    {
        return [
            ['key' => 'textiles', 'name' => 'Textiles & Apparel', 'sort_order' => 10],
            ['key' => 'electronics', 'name' => 'Electronics', 'sort_order' => 20],
            ['key' => 'agriculture', 'name' => 'Agriculture', 'sort_order' => 30],
            ['key' => 'chemicals', 'name' => 'Chemicals', 'sort_order' => 40],
            ['key' => 'construction', 'name' => 'Construction Materials', 'sort_order' => 50],
            ['key' => 'medical', 'name' => 'Medical Equipment', 'sort_order' => 60],
            ['key' => 'food', 'name' => 'Food & Beverage', 'sort_order' => 70],
            ['key' => 'other', 'name' => 'Other', 'sort_order' => 80],
            ['key' => 'machinery', 'name' => 'Industrial Machinery', 'sort_order' => 90],
        ];
    }

    /**
     * @param  list<string>  $options
     * @return array{field_name: string, field_key: string, field_type: ProductFieldType, unit: ?string, options?: list<string>}
     */
    private static function f(string $name, string $key, ProductFieldType $type, ?string $unit = null, array $options = []): array
    {
        $row = [
            'field_name' => $name,
            'field_key' => $key,
            'field_type' => $type,
            'unit' => $unit,
        ];

        if ($options !== []) {
            $row['options'] = $options;
        }

        return $row;
    }

    /** @return list<array{field_name: string, field_key: string, field_type: ProductFieldType, unit: ?string, options?: list<string>}> */
    private static function textiles(): array
    {
        return [
            self::f('Product Type', 'product_type', ProductFieldType::Select, null, ['Fabric', 'Garments', 'Yarn', 'Home Textile', 'Accessories']),
            self::f('Fabric / Material Type', 'fabric_material_type', ProductFieldType::Select, null, ['Cotton', 'Polyester', 'Silk', 'Wool', 'Linen', 'Blended']),
            self::f('Material Composition', 'material_composition', ProductFieldType::Text),
            self::f('Fabric Weight (GSM)', 'fabric_weight', ProductFieldType::Number, 'GSM'),
            self::f('Fabric Width', 'fabric_width', ProductFieldType::Number, 'inch / cm / m'),
            self::f('Yarn / Thread Count', 'yarn_thread_count', ProductFieldType::Text),
            self::f('Color / Pattern', 'color_pattern', ProductFieldType::Text),
            self::f('Garment Type', 'garment_type', ProductFieldType::Select, null, ['T-Shirt', 'Shirt', 'Jeans', 'Dress', 'Uniform', 'Other']),
            self::f('Size Range', 'size_range', ProductFieldType::Text, 'XS–XXL / Custom'),
            self::f('Gender / Age Group', 'gender_age_group', ProductFieldType::Select, null, ['Men', 'Women', 'Kids', 'Unisex']),
            self::f('Fabric Finish', 'fabric_finish', ProductFieldType::Select, null, ['Dyed', 'Printed', 'Embroidered', 'Plain']),
            self::f('Customization Available', 'customization_available', ProductFieldType::Boolean),
            self::f('MOQ per Color / Design', 'moq_per_color_design', ProductFieldType::Number),
            self::f('Textile Certification', 'textile_certification', ProductFieldType::Select, null, ['OEKO-TEX', 'GOTS', 'Other']),
        ];
    }

    /** @return list<array{field_name: string, field_key: string, field_type: ProductFieldType, unit: ?string, options?: list<string>}> */
    private static function electronics(): array
    {
        return [
            self::f('Product Type', 'product_type', ProductFieldType::Select, null, ['Consumer Electronics', 'Components', 'Industrial Electronics', 'Accessories']),
            self::f('Model Number', 'model_number', ProductFieldType::Text),
            self::f('Product Category', 'product_category', ProductFieldType::Select, null, ['Mobile Accessories', 'LED', 'PCB', 'Sensors', 'Appliances', 'Other']),
            self::f('Technical Specifications', 'technical_specifications', ProductFieldType::Textarea),
            self::f('Voltage / Power', 'voltage_power', ProductFieldType::Text),
            self::f('Frequency', 'frequency', ProductFieldType::Text),
            self::f('Input / Output', 'input_output', ProductFieldType::Text),
            self::f('Compatibility', 'compatibility', ProductFieldType::Text),
            self::f('Dimensions', 'dimensions', ProductFieldType::Text, 'Length × Width × Height'),
            self::f('Operating Temperature', 'operating_temperature', ProductFieldType::Text),
            self::f('Warranty', 'warranty', ProductFieldType::Text, 'Months / Years'),
            self::f('Certifications', 'certifications', ProductFieldType::Select, null, ['CE', 'RoHS', 'BIS', 'FCC', 'Other']),
            self::f('MOQ per Model', 'moq_per_model', ProductFieldType::Number),
            self::f('User Manual Available', 'user_manual_available', ProductFieldType::Boolean),
        ];
    }

    /** @return list<array{field_name: string, field_key: string, field_type: ProductFieldType, unit: ?string, options?: list<string>}> */
    private static function agriculture(): array
    {
        return [
            self::f('Product Type', 'product_type', ProductFieldType::Select, null, ['Grains', 'Pulses', 'Seeds', 'Fruits', 'Vegetables', 'Oilseeds', 'Fertilizers']),
            self::f('Crop / Commodity', 'crop_commodity', ProductFieldType::Text),
            self::f('Variety', 'variety', ProductFieldType::Text),
            self::f('Grade / Quality', 'grade_quality', ProductFieldType::Text),
            self::f('Harvest Season', 'harvest_season', ProductFieldType::Text, 'Month / Year'),
            self::f('Crop Year', 'crop_year', ProductFieldType::Number, 'Year'),
            self::f('Moisture', 'moisture', ProductFieldType::Number, '%'),
            self::f('Purity', 'purity', ProductFieldType::Number, '%'),
            self::f('Foreign Matter', 'foreign_matter', ProductFieldType::Number, '%'),
            self::f('Broken / Damaged', 'broken_damaged', ProductFieldType::Number, '%'),
            self::f('Organic / Conventional', 'organic_conventional', ProductFieldType::Select, null, ['Organic', 'Conventional']),
            self::f('Packaging', 'packaging', ProductFieldType::Text, '25 kg; 50 kg; Jumbo Bag; Bulk'),
            self::f('Storage Conditions', 'storage_conditions', ProductFieldType::Select, null, ['Dry', 'Cold Storage', 'Ambient']),
            self::f('Available Stock', 'available_stock', ProductFieldType::Number),
            self::f('Monthly Supply Capacity', 'monthly_supply_capacity', ProductFieldType::Number),
            self::f('Phytosanitary Certificate', 'phytosanitary_certificate', ProductFieldType::Select, null, ['Available', 'Not Available', 'Not Applicable']),
        ];
    }

    /** @return list<array{field_name: string, field_key: string, field_type: ProductFieldType, unit: ?string, options?: list<string>}> */
    private static function chemicals(): array
    {
        return [
            self::f('Chemical Type', 'chemical_type', ProductFieldType::Select, null, ['Organic', 'Inorganic', 'Industrial', 'Specialty', 'Laboratory']),
            self::f('Chemical Name', 'chemical_name', ProductFieldType::Text),
            self::f('CAS Number', 'cas_number', ProductFieldType::Text),
            self::f('Chemical Formula', 'chemical_formula', ProductFieldType::Text),
            self::f('Grade', 'grade', ProductFieldType::Select, null, ['Industrial', 'Food', 'Pharma', 'Laboratory', 'Technical']),
            self::f('Purity', 'purity', ProductFieldType::Number, '%'),
            self::f('Physical State', 'physical_state', ProductFieldType::Select, null, ['Solid', 'Liquid', 'Gas', 'Powder']),
            self::f('Appearance / Color', 'appearance_color', ProductFieldType::Text),
            self::f('Concentration', 'concentration', ProductFieldType::Number, '%'),
            self::f('pH Value', 'ph_value', ProductFieldType::Number),
            self::f('Density / Specific Gravity', 'density_specific_gravity', ProductFieldType::Number),
            self::f('Shelf Life', 'shelf_life', ProductFieldType::Number, 'Months'),
            self::f('Storage Conditions', 'storage_conditions', ProductFieldType::Text),
            self::f('Hazard Classification', 'hazard_classification', ProductFieldType::Text),
            self::f('UN Number', 'un_number', ProductFieldType::Text, 'If applicable'),
            self::f('Packaging', 'packaging', ProductFieldType::Select, null, ['Drum', 'Bag', 'IBC', 'Tanker', 'Other']),
            self::f('SDS / MSDS', 'sds_msds', ProductFieldType::File, 'PDF'),
            self::f('COA', 'coa', ProductFieldType::File, 'PDF'),
            self::f('GHS Label Available', 'ghs_label_available', ProductFieldType::Boolean),
        ];
    }

    /** @return list<array{field_name: string, field_key: string, field_type: ProductFieldType, unit: ?string, options?: list<string>}> */
    private static function construction(): array
    {
        return [
            self::f('Product Type', 'product_type', ProductFieldType::Select, null, ['Cement', 'Steel', 'Tiles', 'Pipes', 'Bricks', 'Sand', 'Stone', 'Glass']),
            self::f('Material Grade', 'material_grade', ProductFieldType::Text),
            self::f('Material / Composition', 'material_composition', ProductFieldType::Text),
            self::f('Size / Dimensions', 'size_dimensions', ProductFieldType::Text),
            self::f('Thickness', 'thickness', ProductFieldType::Number),
            self::f('Length / Width / Height', 'length_width_height', ProductFieldType::Text),
            self::f('Weight per Unit', 'weight_per_unit', ProductFieldType::Number),
            self::f('Strength / Load Capacity', 'strength_load_capacity', ProductFieldType::Number),
            self::f('Surface Finish', 'surface_finish', ProductFieldType::Select, null, ['Polished', 'Matte', 'Textured', 'Other']),
            self::f('Color / Design', 'color_design', ProductFieldType::Text),
            self::f('Applicable Standard', 'applicable_standard', ProductFieldType::Select, null, ['BIS', 'ASTM', 'ISO', 'EN', 'Other']),
            self::f('Application', 'application', ProductFieldType::Select, null, ['Residential', 'Commercial', 'Industrial', 'Infrastructure']),
            self::f('Packaging', 'packaging', ProductFieldType::Select, null, ['Bag', 'Bundle', 'Pallet', 'Bulk']),
            self::f('Test Certificate', 'test_certificate', ProductFieldType::File, 'PDF'),
        ];
    }

    /** @return list<array{field_name: string, field_key: string, field_type: ProductFieldType, unit: ?string, options?: list<string>}> */
    private static function medical(): array
    {
        return [
            self::f('Product Type', 'product_type', ProductFieldType::Select, null, ['Diagnostic', 'Surgical', 'Monitoring', 'Laboratory', 'Hospital Equipment']),
            self::f('Manufacturer', 'manufacturer', ProductFieldType::Text),
            self::f('Model Number', 'model_number', ProductFieldType::Text),
            self::f('Intended Use', 'intended_use', ProductFieldType::Text),
            self::f('Medical Device Classification', 'medical_device_classification', ProductFieldType::Text, 'Class / Category'),
            self::f('Technical Specifications', 'technical_specifications', ProductFieldType::Textarea),
            self::f('Dimensions / Weight', 'dimensions_weight', ProductFieldType::Text),
            self::f('Power Supply', 'power_supply', ProductFieldType::Text),
            self::f('Sterile / Non-Sterile', 'sterile_non_sterile', ProductFieldType::Select, null, ['Sterile', 'Non-Sterile']),
            self::f('Single Use / Reusable', 'single_use_reusable', ProductFieldType::Select, null, ['Single Use', 'Reusable']),
            self::f('Shelf Life', 'shelf_life', ProductFieldType::Text, 'Months / Years'),
            self::f('Warranty', 'warranty', ProductFieldType::Text, 'Months / Years'),
            self::f('Regulatory Approval', 'regulatory_approval', ProductFieldType::Text),
            self::f('ISO 13485 Certificate', 'iso_13485_certificate', ProductFieldType::File, 'PDF'),
            self::f('Product Registration Details', 'product_registration_details', ProductFieldType::Text),
            self::f('User Manual', 'user_manual', ProductFieldType::File, 'PDF'),
            self::f('Installation / Training', 'installation_training', ProductFieldType::Boolean),
            self::f('After-Sales Service', 'after_sales_service', ProductFieldType::Boolean),
        ];
    }

    /** @return list<array{field_name: string, field_key: string, field_type: ProductFieldType, unit: ?string, options?: list<string>}> */
    private static function food(): array
    {
        return [
            self::f('Product Type', 'product_type', ProductFieldType::Select, null, ['Rice', 'Sugar', 'Pulses', 'Edible Oil', 'Spices', 'Snacks', 'Beverages', 'Dairy', 'Dry Fruits']),
            self::f('Food Category', 'food_category', ProductFieldType::Select, null, ['Staples', 'Processed Food', 'Beverages', 'Dairy', 'Snacks', 'Other']),
            self::f('Variety / Grade', 'variety_grade', ProductFieldType::Text),
            self::f('Ingredients', 'ingredients', ProductFieldType::Textarea),
            self::f('Nutritional Information', 'nutritional_information', ProductFieldType::Text),
            self::f('Net Weight / Volume', 'net_weight_volume', ProductFieldType::Number, 'kg / g / L / ml'),
            self::f('Moisture', 'moisture', ProductFieldType::Number, '%'),
            self::f('Purity / Quality Parameters', 'purity_quality_parameters', ProductFieldType::Text),
            self::f('Shelf Life', 'shelf_life', ProductFieldType::Number, 'Months'),
            self::f('Manufacturing / Batch Date', 'manufacturing_batch_date', ProductFieldType::Date),
            self::f('Expiry / Best Before', 'expiry_best_before', ProductFieldType::Date),
            self::f('Storage', 'storage', ProductFieldType::Select, null, ['Ambient', 'Refrigerated', 'Frozen']),
            self::f('Packaging Type', 'packaging_type', ProductFieldType::Select, null, ['Pouch', 'Bottle', 'Jar', 'Carton', 'Bulk']),
            self::f('Private Label Available', 'private_label_available', ProductFieldType::Boolean),
            self::f('Certifications', 'certifications', ProductFieldType::Select, null, ['FSSAI', 'HACCP', 'ISO 22000', 'Organic', 'Other']),
            self::f('Allergen Information', 'allergen_information', ProductFieldType::Text),
            self::f('Lab Test Report', 'lab_test_report', ProductFieldType::File, 'PDF'),
            self::f('Batch / Lot Number', 'batch_lot_number', ProductFieldType::Text),
        ];
    }

    /**
     * Skipped (duplicate of core product columns): Product Name, Brand / Manufacturer, Product Description.
     *
     * @return list<array{field_name: string, field_key: string, field_type: ProductFieldType, unit: ?string, options?: list<string>}> */
    private static function other(): array
    {
        return [
            self::f('Product Category', 'product_category', ProductFieldType::Text),
            self::f('Product Subcategory', 'product_subcategory', ProductFieldType::Text),
            self::f('Model / SKU', 'model_sku', ProductFieldType::Text),
            self::f('Material / Composition', 'material_composition', ProductFieldType::Text),
            self::f('Technical Specifications', 'technical_specifications', ProductFieldType::Textarea),
            self::f('Size / Dimensions', 'size_dimensions', ProductFieldType::Text),
            self::f('Weight', 'weight', ProductFieldType::Number),
            self::f('Grade / Quality', 'grade_quality', ProductFieldType::Text),
        ];
    }

    public static function optionValue(string $label): string
    {
        $value = Str::slug($label);

        return $value !== '' ? $value : Str::lower(Str::snake($label));
    }
}
