<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'uom_data' => 'array',
        'attributes_data' => 'array',
    ];

    public function getImageUrlAttribute()
    {
        $img = $this->image ?: $this->web_main_image;
        if (empty($img)) {
            return null;
        }
        if (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) {
            return $img;
        }
        if (file_exists(public_path('uploads/products/' . $img))) {
            return asset('uploads/products/' . $img);
        }
        if (file_exists(public_path($img))) {
            return asset($img);
        }
        return asset('uploads/products/' . $img);
    }

    public function getUomUnitsList()
    {
        $baseName = $this->unit ? $this->unit->name : 'Pcs';
        $basePrice = (float)($this->sale_price_per_piece ?: ($this->retail_price ?: 0));
        $baseCost = (float)($this->purchase_price_per_piece ?: 0);

        $uom = is_string($this->uom_data) ? json_decode($this->uom_data, true) : $this->uom_data;

        // Fallback to unit's uom_data if product has no custom uom_data
        if (!is_array($uom) || empty($uom)) {
            if ($this->unit && !empty($this->unit->uom_data)) {
                $uom = is_string($this->unit->uom_data) ? json_decode($this->unit->uom_data, true) : $this->unit->uom_data;
            }
        }

        // If still no uom data defined, check if product has pieces_per_box > 1 (Carton / Box pack)
        if (!is_array($uom) || empty($uom)) {
            $list = [
                [
                    'name' => $baseName,
                    'ratio' => 1.0,
                    'is_base' => true,
                    'price' => $basePrice,
                    'cost' => $baseCost,
                    'upc' => $this->item_code ?? '',
                    'barcode' => $this->item_code ?? '',
                    'alu' => '',
                    'alt_lookup' => '',
                    'unit_index' => 0
                ]
            ];

            if ($this->pieces_per_box > 1) {
                $ppb = (float)$this->pieces_per_box;
                $cartonName = !empty($this->packing_name) ? $this->packing_name : (($this->size_mode === 'by_cartons') ? 'Carton' : 'Box');
                $cartonPrice = (float)($this->sale_price_per_box > 0 ? $this->sale_price_per_box : ($basePrice * $ppb));
                $cartonCost = (float)($this->purchase_price_per_box > 0 ? $this->purchase_price_per_box : ($baseCost * $ppb));

                $list[] = [
                    'name' => $cartonName,
                    'ratio' => $ppb,
                    'is_base' => false,
                    'price' => $cartonPrice,
                    'cost' => $cartonCost,
                    'upc' => ($this->item_code ? $this->item_code . '-CTN' : ''),
                    'barcode' => ($this->item_code ? $this->item_code . '-CTN' : ''),
                    'alu' => '',
                    'alt_lookup' => '',
                    'unit_index' => 1
                ];
            }

            return $list;
        }

        $actualBaseName = is_array($uom['base_unit'] ?? null) 
            ? ($uom['base_unit']['name'] ?? null) 
            : ($uom['base_unit_name'] ?? (is_string($uom['base_unit'] ?? null) ? $uom['base_unit'] : null));

        $actualBasePrice = (is_array($uom['base_unit'] ?? null) && isset($uom['base_unit']['price']) && (float)$uom['base_unit']['price'] > 0) 
            ? (float)$uom['base_unit']['price'] 
            : $basePrice;

        $actualBaseCost = (is_array($uom['base_unit'] ?? null) && isset($uom['base_unit']['cost']) && (float)$uom['base_unit']['cost'] > 0) 
            ? (float)$uom['base_unit']['cost'] 
            : $baseCost;

        $baseUpc = is_array($uom['base_unit'] ?? null) 
            ? ($uom['base_unit']['upc'] ?? ($uom['base_unit']['barcode'] ?? null)) 
            : null;

        $baseAlu = is_array($uom['base_unit'] ?? null) 
            ? ($uom['base_unit']['alu'] ?? ($uom['base_unit']['alt_lookup'] ?? null)) 
            : null;

        // If not explicit in base_unit, inspect units array for the base unit definition
        if (!empty($uom['units']) && is_array($uom['units'])) {
            foreach ($uom['units'] as $u) {
                if (!empty($u['is_base']) || (isset($u['ratio']) && (float)$u['ratio'] == 1.0)) {
                    if (!$actualBaseName) $actualBaseName = $u['name'];
                    if (!$baseUpc) $baseUpc = $u['upc'] ?? ($u['barcode'] ?? null);
                    if (!$baseAlu) $baseAlu = $u['alu'] ?? ($u['alt_lookup'] ?? null);
                    if (isset($u['price']) && (float)$u['price'] > 0 && (!isset($uom['base_unit']['price']) || (float)$uom['base_unit']['price'] <= 0)) {
                        $actualBasePrice = (float)$u['price'];
                    }
                    if (isset($u['cost']) && (float)$u['cost'] > 0 && (!isset($uom['base_unit']['cost']) || (float)$uom['base_unit']['cost'] <= 0)) {
                        $actualBaseCost = (float)$u['cost'];
                    }
                    break;
                }
            }
        }
        if (!$actualBaseName) {
            $actualBaseName = $baseName;
        }
        if (!$baseUpc) $baseUpc = $this->item_code ?? '';
        if (!$baseAlu) $baseAlu = '';

        $list = [
            [
                'name' => $actualBaseName,
                'ratio' => 1.0,
                'is_base' => true,
                'price' => $actualBasePrice,
                'cost' => $actualBaseCost,
                'upc' => $baseUpc,
                'barcode' => $baseUpc,
                'alu' => $baseAlu,
                'alt_lookup' => $baseAlu,
                'unit_index' => 0
            ]
        ];

        if (!empty($uom['units']) && is_array($uom['units'])) {
            foreach ($uom['units'] as $idx => $u) {
                if (empty($u['name'])) continue;
                if (strtolower(trim($u['name'])) === strtolower(trim($actualBaseName))) continue;
                $ratio = (float)($u['ratio'] ?? ($u['base_units'] ?? 1));
                if ($ratio <= 0) $ratio = 1.0;

                $unitPrice = isset($u['price']) && (float)$u['price'] > 0 ? (float)$u['price'] : ($actualBasePrice * $ratio);
                $unitCost = isset($u['cost']) && (float)$u['cost'] > 0 ? (float)$u['cost'] : ($actualBaseCost * $ratio);

                $list[] = [
                    'name' => $u['name'],
                    'ratio' => $ratio,
                    'is_base' => false,
                    'price' => $unitPrice,
                    'cost' => $unitCost,
                    'upc' => $u['upc'] ?? ($u['barcode'] ?? ''),
                    'barcode' => $u['upc'] ?? ($u['barcode'] ?? ''),
                    'alu' => $u['alu'] ?? ($u['alt_lookup'] ?? ''),
                    'alt_lookup' => $u['alu'] ?? ($u['alt_lookup'] ?? ''),
                    'unit_index' => $idx + 1
                ];
            }
        }

        return $list;
    }

    public function getUomSellUnit()
    {
        $uom = is_string($this->uom_data) ? json_decode($this->uom_data, true) : $this->uom_data;
        if (!is_array($uom) || empty($uom)) {
            if ($this->unit && !empty($this->unit->uom_data)) {
                $uom = is_string($this->unit->uom_data) ? json_decode($this->unit->uom_data, true) : $this->unit->uom_data;
            }
        }
        if (is_array($uom) && (!empty($uom['sell_unit']) || !empty($uom['sell_by']))) {
            return $uom['sell_unit'] ?? $uom['sell_by'];
        }
        return $this->unit ? $this->unit->name : 'Pcs';
    }

    public function getUomOrderUnit()
    {
        $uom = is_string($this->uom_data) ? json_decode($this->uom_data, true) : $this->uom_data;
        if (!is_array($uom) || empty($uom)) {
            if ($this->unit && !empty($this->unit->uom_data)) {
                $uom = is_string($this->unit->uom_data) ? json_decode($this->unit->uom_data, true) : $this->unit->uom_data;
            }
        }
        if (is_array($uom) && (!empty($uom['order_unit']) || !empty($uom['order_by']))) {
            return $uom['order_unit'] ?? $uom['order_by'];
        }
        return $this->unit ? $this->unit->name : 'Pcs';
    }

    // protected $fillable = [
    //     'creater_id', 'category_id', 'sub_category_id', 'item_code', 'item_name', 'size',
    //     'opening_carton_quantity', 'carton_quantity', 'loose_pieces', 'pcs_in_carton',
    //     'wholesale_price', 'retail_price', 'initial_stock', 'alert_quantity'
    // ];
    // app/Models/Product.php
    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class, 'warehouse_stocks', 'product_id', 'warehouse_id')
            ->withPivot('quantity', 'price', 'remarks');
    }

    // app/Models/Product.php

    public function activeDiscount()
    {
        return $this->hasOne(ProductDiscount::class, 'product_id')
            ->where('status', 1); // only active discount
    }

    public function category_relation()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function sub_category_relation()
    {
        return $this->belongsTo(Subcategory::class, 'sub_category_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class, 'sub_category_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function stock()
    {
        return $this->hasOne(Stock::class);
    }

    public function warehouseStocks()
    {
        return $this->hasMany(\App\Models\WarehouseStock::class);
    }

    public function movements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function scopeWithAvailable($q)
    {
        return $q->withSum('movements as available_qty', 'qty'); // sum of ledger
    }

    public function webImages()
    {
        return $this->hasMany(ProductWebImage::class)->orderBy('sort_order', 'asc');
    }

    protected static function booted()
    {
        static::saved(function ($product) {
            if (!is_null($product->alert_quantity)) {
                $totalPieces = \App\Models\WarehouseStock::where('product_id', $product->id)->sum('total_pieces');
                if ($totalPieces < $product->alert_quantity) {
                    \App\Models\SystemNotification::createStockAlertNotification($product, $totalPieces);
                }
            }
        });
    }
}
