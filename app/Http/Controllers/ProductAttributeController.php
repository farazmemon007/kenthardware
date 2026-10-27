<?php

namespace App\Http\Controllers;

use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductAttributeController extends Controller
{
    /**
     * Default preset master attributes to seed when database table is empty.
     */
    protected array $presetAttributes = [
        ['name' => 'Brand', 'display_type' => 'Radio', 'creation' => 'Instantly', 'default_values' => ['Kent', 'Standard']],
        ['name' => 'gender', 'display_type' => 'Radio', 'creation' => 'Instantly', 'default_values' => ['Men', 'Women', 'Unisex', 'Kids']],
        ['name' => 'manufacturer', 'display_type' => 'Radio', 'creation' => 'Instantly', 'default_values' => ['Local', 'Imported']],
        ['name' => 'age group', 'display_type' => 'Radio', 'creation' => 'Instantly', 'default_values' => ['Adult', 'Teen', 'Kids', 'Infant']],
        ['name' => 'Wictor Blue', 'display_type' => 'Multi-checkbox', 'creation' => 'Never', 'default_values' => ['Default']],
        ['name' => 'Shoes size', 'display_type' => 'Pills', 'creation' => 'Never', 'default_values' => ['6', '7', '8', '9', '10', '11', '12']],
        ['name' => 'Sale Description', 'display_type' => 'Radio', 'creation' => 'Dynamically', 'default_values' => ['Standard', 'Promo', 'Clearance']],
        ['name' => 'Attribute', 'display_type' => 'Radio', 'creation' => 'Dynamically', 'default_values' => ['Option 1', 'Option 2', 'Option 3']],
        ['name' => 'Color', 'display_type' => 'Color', 'creation' => 'Instantly', 'default_values' => ['White', 'Black', 'Blue', 'Red', 'Green', 'Yellow', 'Silver', 'Gold']],
        ['name' => 'Size', 'display_type' => 'Pills', 'creation' => 'Instantly', 'default_values' => ['1/2', '3/4', '3/8', '1', '1 1/4', '1 1/2', '2', '2 1/2', '3']],
        ['name' => 'Material', 'display_type' => 'Select', 'creation' => 'Instantly', 'default_values' => ['Steel', 'Stainless Steel', 'Brass', 'Iron', 'Aluminum', 'Plastic', 'Wood']],
        ['name' => 'Finish', 'display_type' => 'Radio', 'creation' => 'Dynamically', 'default_values' => ['Zinc Plated', 'Black Oxide', 'Polished', 'Chrome', 'Nickel', 'Matte']],
        ['name' => 'Weight', 'display_type' => 'Radio', 'creation' => 'Never', 'default_values' => ['50g', '100g', '250g', '500g', '1kg', '5kg']],
        ['name' => 'Dimensions', 'display_type' => 'Radio', 'creation' => 'Never', 'default_values' => ['Small', 'Medium', 'Large', 'XL']],
        ['name' => 'Style', 'display_type' => 'Radio', 'creation' => 'Dynamically', 'default_values' => ['Standard', 'Modern', 'Classic', 'Heavy Duty']],
        ['name' => 'Pattern', 'display_type' => 'Radio', 'creation' => 'Instantly', 'default_values' => ['Plain', 'Striped', 'Textured']],
        ['name' => 'Capacity', 'display_type' => 'Radio', 'creation' => 'Never', 'default_values' => ['100ml', '250ml', '500ml', '1L', '5L']],
        ['name' => 'Grade', 'display_type' => 'Radio', 'creation' => 'Instantly', 'default_values' => ['Grade 2', 'Grade 5', 'Grade 8', 'Class 8.8', 'Class 10.9', 'Class 12.9', 'A2-70', 'A4-80']],
        ['name' => 'Warranty', 'display_type' => 'Radio', 'creation' => 'Never', 'default_values' => ['No Warranty', '6 Months', '1 Year', '2 Years', '5 Years']],
        ['name' => 'Power', 'display_type' => 'Radio', 'creation' => 'Never', 'default_values' => ['100W', '250W', '500W', '1000W']],
        ['name' => 'Voltage', 'display_type' => 'Radio', 'creation' => 'Never', 'default_values' => ['12V', '24V', '110V', '220V', '240V']],
        ['name' => 'Packaging', 'display_type' => 'Radio', 'creation' => 'Never', 'default_values' => ['Box', 'Packet', 'Carton', 'Bag', 'Roll']],
        ['name' => 'Series', 'display_type' => 'Radio', 'creation' => 'Dynamically', 'default_values' => ['Series A', 'Series B', 'Series Pro', 'Series Max']],
        ['name' => 'Thickness', 'display_type' => 'Radio', 'creation' => 'Instantly', 'default_values' => ['0.5mm', '1mm', '1.5mm', '2mm', '3mm', '4mm', '5mm']],
        ['name' => 'Length', 'display_type' => 'Radio', 'creation' => 'Never', 'default_values' => ['1/2', '3/4', '1', '1 1/4', '1 1/2', '2', '2 1/2', '3', '4']],
        ['name' => 'Model Year', 'display_type' => 'Radio', 'creation' => 'Never', 'default_values' => ['2022', '2023', '2024', '2025', '2026']],
        ['name' => 'Fabric', 'display_type' => 'Radio', 'creation' => 'Instantly', 'default_values' => ['Cotton', 'Polyester', 'Wool', 'Silk', 'Nylon']],
        ['name' => 'Fit Type', 'display_type' => 'Pills', 'creation' => 'Instantly', 'default_values' => ['Regular', 'Slim', 'Loose', 'Comfort']],
        ['name' => 'Origin Country', 'display_type' => 'Radio', 'creation' => 'Never', 'default_values' => ['Pakistan', 'China', 'Japan', 'Germany', 'USA', 'Taiwan']]
    ];

    /**
     * Get all master attributes with values. Seeds presets if empty.
     */
    public function index()
    {
        $count = ProductAttribute::count();
        if ($count === 0) {
            $this->seedPresets();
        }

        $attributes = ProductAttribute::with(['values' => function($q) {
            $q->orderBy('sequence')->orderBy('id');
        }])->orderBy('id', 'asc')->get();

        $formatted = $attributes->map(function ($attr) {
            return [
                'id' => $attr->id,
                'name' => $attr->name,
                'display_type' => $attr->display_type,
                'creation' => $attr->creation_mode,
                'default_values' => $attr->values->pluck('value')->toArray(),
                'values_detail' => $attr->values->map(function ($v) {
                    return [
                        'id' => $v->id,
                        'value' => $v->value,
                        'color_hex' => $v->color_hex,
                        'extra_price' => (float)$v->extra_price,
                        'is_custom' => $v->is_custom,
                    ];
                }),
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $formatted,
        ]);
    }

    /**
     * Store a newly created attribute and its values in database with strict duplicate prevention.
     */
    public function store(Request $request)
    {
        $rawName = trim((string)$request->input('name', ''));
        if ($rawName === '') {
            return response()->json([
                'status' => 'error',
                'message' => 'Attribute name is required.'
            ], 422);
        }

        // Check duplicate attribute name (case-insensitive)
        $cleanLower = strtolower($rawName);
        $duplicate = ProductAttribute::whereRaw('LOWER(TRIM(name)) = ?', [$cleanLower])->first();
        if ($duplicate) {
            return response()->json([
                'status' => 'error',
                'message' => "Attribute name '{$rawName}' already exists. Duplicate attributes are not allowed.",
                'existing_attribute' => [
                    'id' => $duplicate->id,
                    'name' => $duplicate->name,
                    'display_type' => $duplicate->display_type,
                    'creation' => $duplicate->creation_mode,
                    'default_values' => $duplicate->values()->pluck('value')->toArray(),
                ]
            ], 422);
        }

        $displayType = trim((string)$request->input('display_type', 'Radio')) ?: 'Radio';
        $creationMode = trim((string)$request->input('creation', 'Instantly')) ?: 'Instantly';

        DB::beginTransaction();
        try {
            $attribute = ProductAttribute::create([
                'name' => $rawName,
                'display_type' => $displayType,
                'creation_mode' => $creationMode,
            ]);

            // Process values
            $rawValues = $request->input('values', []);
            if (is_string($rawValues)) {
                $rawValues = json_decode($rawValues, true) ?: [];
            }

            $savedValues = [];
            $seenLower = [];
            $seq = 0;

            foreach ($rawValues as $valItem) {
                $valName = is_array($valItem) ? trim((string)($valItem['name'] ?? $valItem['value'] ?? '')) : trim((string)$valItem);
                if ($valName === '') continue;

                $valLower = strtolower($valName);
                if (in_array($valLower, $seenLower, true)) {
                    continue; // Skip duplicate values within this attribute
                }
                $seenLower[] = $valLower;

                $colorHex = is_array($valItem) ? ($valItem['color_hex'] ?? null) : null;
                $extraPrice = is_array($valItem) ? (float)($valItem['extra_price'] ?? 0.00) : 0.00;

                $attrVal = ProductAttributeValue::create([
                    'product_attribute_id' => $attribute->id,
                    'value' => $valName,
                    'color_hex' => $colorHex,
                    'extra_price' => $extraPrice,
                    'sequence' => $seq++,
                ]);

                $savedValues[] = $valName;
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => "Attribute '{$attribute->name}' saved successfully in database.",
                'attribute' => [
                    'id' => $attribute->id,
                    'name' => $attribute->name,
                    'display_type' => $attribute->display_type,
                    'creation' => $attribute->creation_mode,
                    'default_values' => $savedValues,
                ]
            ], 200);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Attribute store error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to save attribute: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing attribute and its values with duplicate check.
     */
    public function update(Request $request, $id)
    {
        $attribute = ProductAttribute::find($id);
        if (!$attribute) {
            // Also try finding by name if id is a name string
            $attribute = ProductAttribute::whereRaw('LOWER(TRIM(name)) = ?', [strtolower(trim($id))])->first();
            if (!$attribute) {
                return response()->json(['status' => 'error', 'message' => 'Attribute not found.'], 404);
            }
        }

        $rawName = trim((string)$request->input('name', $attribute->name));
        if ($rawName === '') {
            return response()->json(['status' => 'error', 'message' => 'Attribute name is required.'], 422);
        }

        // Duplicate check excluding self
        $cleanLower = strtolower($rawName);
        $duplicate = ProductAttribute::where('id', '!=', $attribute->id)
            ->whereRaw('LOWER(TRIM(name)) = ?', [$cleanLower])
            ->first();

        if ($duplicate) {
            return response()->json([
                'status' => 'error',
                'message' => "Attribute name '{$rawName}' is already used by another attribute."
            ], 422);
        }

        $displayType = trim((string)$request->input('display_type', $attribute->display_type)) ?: 'Radio';
        $creationMode = trim((string)$request->input('creation', $attribute->creation_mode)) ?: 'Instantly';

        DB::beginTransaction();
        try {
            $attribute->name = $rawName;
            $attribute->display_type = $displayType;
            $attribute->creation_mode = $creationMode;
            $attribute->save();

            // If values were supplied, update values list
            if ($request->has('values')) {
                $rawValues = $request->input('values', []);
                if (is_string($rawValues)) {
                    $rawValues = json_decode($rawValues, true) ?: [];
                }

                $seenLower = [];
                $savedValues = [];
                $seq = 0;

                // Delete old values and re-create clean unique list
                ProductAttributeValue::where('product_attribute_id', $attribute->id)->delete();

                foreach ($rawValues as $valItem) {
                    $valName = is_array($valItem) ? trim((string)($valItem['name'] ?? $valItem['value'] ?? '')) : trim((string)$valItem);
                    if ($valName === '') continue;

                    $valLower = strtolower($valName);
                    if (in_array($valLower, $seenLower, true)) {
                        continue;
                    }
                    $seenLower[] = $valLower;

                    $colorHex = is_array($valItem) ? ($valItem['color_hex'] ?? null) : null;
                    $extraPrice = is_array($valItem) ? (float)($valItem['extra_price'] ?? 0.00) : 0.00;

                    ProductAttributeValue::create([
                        'product_attribute_id' => $attribute->id,
                        'value' => $valName,
                        'color_hex' => $colorHex,
                        'extra_price' => $extraPrice,
                        'sequence' => $seq++,
                    ]);

                    $savedValues[] = $valName;
                }
            } else {
                $savedValues = $attribute->values()->pluck('value')->toArray();
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => "Attribute '{$attribute->name}' updated successfully in database.",
                'attribute' => [
                    'id' => $attribute->id,
                    'name' => $attribute->name,
                    'display_type' => $attribute->display_type,
                    'creation' => $attribute->creation_mode,
                    'default_values' => $savedValues,
                ]
            ], 200);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Attribute update error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to update attribute.'], 500);
        }
    }

    /**
     * Delete an attribute from database.
     */
    public function destroy($id)
    {
        $attribute = ProductAttribute::find($id);
        if (!$attribute) {
            $attribute = ProductAttribute::whereRaw('LOWER(TRIM(name)) = ?', [strtolower(trim($id))])->first();
        }

        if (!$attribute) {
            return response()->json(['status' => 'error', 'message' => 'Attribute not found.'], 404);
        }

        $name = $attribute->name;
        $attribute->delete();

        return response()->json([
            'status' => 'success',
            'message' => "Attribute '{$name}' deleted successfully from database."
        ]);
    }

    /**
     * Add a single value to an attribute (e.g. on tag creation "+ Create 'value'") with duplicate check.
     */
    public function addValue(Request $request, $id)
    {
        $attribute = ProductAttribute::find($id);
        if (!$attribute) {
            $attribute = ProductAttribute::whereRaw('LOWER(TRIM(name)) = ?', [strtolower(trim($id))])->first();
        }

        $val = trim((string)$request->input('value', ''));
        if ($val === '') {
            return response()->json(['status' => 'error', 'message' => 'Value cannot be empty.'], 422);
        }

        if (!$attribute) {
            // Auto-create attribute if it didn't exist yet!
            $attrName = trim((string)$request->input('attribute_name', (string)$id));
            if ($attrName === '') {
                return response()->json(['status' => 'error', 'message' => 'Attribute not found.'], 404);
            }
            $attribute = ProductAttribute::firstOrCreate(
                ['name' => $attrName],
                ['display_type' => 'Radio', 'creation_mode' => 'Instantly']
            );
        }

        $valLower = strtolower($val);
        $existing = ProductAttributeValue::where('product_attribute_id', $attribute->id)
            ->whereRaw('LOWER(TRIM(value)) = ?', [$valLower])
            ->first();

        if ($existing) {
            return response()->json([
                'status' => 'success',
                'message' => 'Value already exists for this attribute.',
                'value' => [
                    'id' => $existing->id,
                    'value' => $existing->value,
                    'color_hex' => $existing->color_hex,
                    'extra_price' => (float)$existing->extra_price,
                ]
            ]);
        }

        $maxSeq = ProductAttributeValue::where('product_attribute_id', $attribute->id)->max('sequence') ?? 0;

        $newVal = ProductAttributeValue::create([
            'product_attribute_id' => $attribute->id,
            'value' => $val,
            'color_hex' => $request->input('color_hex'),
            'extra_price' => (float)($request->input('extra_price', 0.00)),
            'sequence' => $maxSeq + 1,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => "Value '{$val}' saved to attribute '{$attribute->name}'.",
            'value' => [
                'id' => $newVal->id,
                'value' => $newVal->value,
                'color_hex' => $newVal->color_hex,
                'extra_price' => (float)$newVal->extra_price,
            ]
        ]);
    }

    /**
     * Seeds preset attributes into database if table is empty.
     */
    protected function seedPresets()
    {
        DB::beginTransaction();
        try {
            foreach ($this->presetAttributes as $item) {
                $attr = ProductAttribute::firstOrCreate(
                    ['name' => $item['name']],
                    [
                        'display_type' => $item['display_type'],
                        'creation_mode' => $item['creation'],
                    ]
                );

                $seq = 0;
                foreach ($item['default_values'] as $val) {
                    ProductAttributeValue::firstOrCreate(
                        [
                            'product_attribute_id' => $attr->id,
                            'value' => $val,
                        ],
                        [
                            'sequence' => $seq++,
                        ]
                    );
                }
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Seeding preset attributes failed: ' . $e->getMessage());
        }
    }
}
