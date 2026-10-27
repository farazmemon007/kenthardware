<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Subcategory;
use App\Models\WarehouseStock;
use App\Models\Unit;
use App\Services\VariantSerialService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ProductImportExportController extends Controller
{
    // ──────────────────────────────────────────────────────────
    //  TEMPLATE  –  Standard ERP blank CSV with correct headers and example variants
    // ──────────────────────────────────────────────────────────
    public function template()
    {
        $headers = $this->csvHeaders();

        $callback = function () use ($headers) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $headers);

            // Row 1: Product A – Variant 1 (by_pieces, base row)
            fputcsv($handle, [
                '1001', 'ITEM-0001', 'T-Shirt V-Neck', 'Clothing', 'Shirts', 'Nike',
                'by_pieces', '1',
                '0', '0', '1',
                'T-Shirt V-Neck', 'S', 'Red', 'A-1', 'TS-01', 'Pcs', '1', '0',
                '1500', '1400', '1000', '0', '876543210001', '1', '50',
            ]);

            // Row 2: Product A – Variant 2 (child variant)
            fputcsv($handle, [
                '1002', 'ITEM-0001', 'T-Shirt V-Neck', 'Clothing', 'Shirts', 'Nike',
                'by_pieces', '1',
                '0', '0', '1',
                'T-Shirt V-Neck', 'L', 'Blue', 'A-1', 'TS-02', 'Pcs', '1', '0',
                '1600', '1500', '1000', '0', '876543210002', '0', '30',
            ]);

            // Row 3: Kg Product – base variant (1 Kg unit)
            fputcsv($handle, [
                '2001', 'ITEM-0002', 'Steel Wire', 'Hardware', 'Wires', 'General',
                'by_kg', '1',
                '0', '0', '1',
                'Steel Wire', '-', '-', 'B-2', 'SW-01', 'Kg', '1', '1000',
                '1000', '950', '700', '0', '876543210003', '1', '10',
            ]);

            // Row 4: Kg Product – child Pcs variant (500g = 0.5 Kg)
            fputcsv($handle, [
                '2002', 'ITEM-0002', 'Steel Wire', 'Hardware', 'Wires', 'General',
                'by_kg', '1',
                '0', '0', '1',
                'Steel Wire 500g Roll', '-', '-', 'B-2', 'SW-02', 'Pcs', '0.5', '500',
                '500', '470', '350', '5', '876543210004', '0', '20',
            ]);

            fclose($handle);
        };

        return response()->stream($callback, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="products_template.csv"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ]);
    }

    // ──────────────────────────────────────────────────────────
    //  TEMPLATE QUICKBOOKS / CLIENT FORMAT  –  International ERP Standard
    // ──────────────────────────────────────────────────────────
    public function templateQuickBooks()
    {
        $headers = [
            'Item #',
            'Item Name',
            'Attribute',
            'Size',
            'Location',
            'P-CTSS',
            'Department',
            'Cost',
            'Sale Price',
            'Sales Description',
            'Vendor Description',
            'Unit of Measure',
            'Stock',
        ];

        $callback = function () use ($headers) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $headers);

            // Sample rows matching client's list format exactly
            fputcsv($handle, [
                '2916', 'AD LOHA', '-', '1/4"', 'A-1', 'AD-01', 'Hardware', '15.50', '22.00', 'Ad Loha 1/4 Inch Steel', 'General Supplier', 'Pcs', '100'
            ]);
            fputcsv($handle, [
                '2915', 'AD LOHA', '-', '3/8"', 'A-1', 'AD-02', 'Hardware', '18.00', '25.00', 'Ad Loha 3/8 Inch Steel', 'General Supplier', 'Pcs', '150'
            ]);
            fputcsv($handle, [
                '2917', 'AD LOHA', '-', '1/2"', 'A-2', 'AD-03', 'Hardware', '22.50', '30.00', 'Ad Loha 1/2 Inch Steel', 'General Supplier', 'Pcs', '80'
            ]);
            fputcsv($handle, [
                '1781', 'AJC', 'Standard', '1-1/2"', 'B-4', 'AJC-01', 'Hardware', '45.00', '60.00', 'AJC Clamp 1-1/2', 'Alpha Hardware', 'Pcs', '50'
            ]);
            fputcsv($handle, [
                '494', 'BLT HEX', 'Zinc', '5/8"', 'C-1', 'BLT-01', 'Fasteners', '12.00', '16.50', 'Hex Bolt 5/8 Zinc Plated', 'Fastener Co.', 'Pcs', '200'
            ]);
            fputcsv($handle, [
                '20503', 'WIRE ROPE', 'Galvanized', '8mm', 'R-1', 'WR-08', 'Cables', '35.00', '48.00', 'Wire Rope 8mm Galvanized', 'Wire Corp', 'Ft', '500'
            ]);

            fclose($handle);
        };

        return response()->stream($callback, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="client_quickbooks_template.csv"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ]);
    }

    // ──────────────────────────────────────────────────────────
    //  EXPORT  –  all products as CSV (Splitting Variants)
    // ──────────────────────────────────────────────────────────
    public function export(Request $request)
    {
        $query = Product::with([
            'category_relation',
            'sub_category_relation',
            'brand'
        ])->orderBy('id');
        
        // Basic filtering
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active' ? 1 : 0);
        }

        $products = $query->get();
        $headers = $this->csvHeaders();

        $callback = function () use ($products, $headers) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $headers);

            foreach ($products as $p) {
                $sizeMode    = $p->size_mode ?? 'by_pieces';
                $pcsPerBox   = $p->pieces_per_box > 0 ? $p->pieces_per_box : 1;
                
                $variants = [];
                if (!empty($p->color)) {
                    $parsed = is_string($p->color) ? json_decode($p->color, true) : $p->color;
                    if (is_array($parsed) && count($parsed) > 0 && isset($parsed[0]['name'])) {
                        $variants = $parsed;
                    }
                }
                
                // Fallback to single variant row if no variants defined
                if (empty($variants)) {
                    $ws = WarehouseStock::where('product_id', $p->id)->first();
                    $stockPieces = $ws ? $ws->total_pieces : 0;
                    
                    $vUnit = $sizeMode === 'by_kg' ? 'Kg' : ($sizeMode === 'by_gm' ? 'Gm' : 'Pcs');
                    fputcsv($handle, [
                        $p->item_number ?? '',
                        $p->item_code,
                        $p->item_name,
                        $p->category_relation->name ?? '',
                        $p->sub_category_relation->name ?? '',
                        $p->brand->name ?? '',
                        $sizeMode,
                        $pcsPerBox,
                        $p->sale_discount_percent ?? 0,
                        $p->purchase_discount_percent ?? 0,
                        $p->is_active ? 1 : 0,
                        // Variant columns
                        $p->item_name,  // Variant Name
                        '-',            // Variant Size
                        '-',            // Variant Color
                        $p->location ?? '',
                        $p->p_code ?? '',
                        $vUnit,         // Variant Unit
                        '1',            // Variant Conv Factor
                        $vUnit === 'Kg' ? '1000' : '0', // Variant Piece Wt (g)
                        round($p->sale_price_per_piece ?? 0, 2),
                        round($p->wholesale_price ?? 0, 2),
                        round($p->purchase_price_per_piece ?? 0, 2),
                        '0',            // Variant Alert
                        $p->barcode_path, // Variant Barcode
                        '1',            // Variant Is Base
                        $stockPieces,   // Variant Stock
                    ]);
                } else {
                    foreach ($variants as $v) {
                        $vUnit = $v['unit'] ?? ($sizeMode === 'by_kg' ? 'Kg' : ($sizeMode === 'by_gm' ? 'Gm' : 'Pcs'));
                        $vFactor = isset($v['conv_factor']) ? $v['conv_factor'] : 1;
                        $vWeight = isset($v['weight_per_piece']) ? $v['weight_per_piece'] : (($vFactor > 0 && $vFactor < 10) ? $vFactor * 1000 : 0);
                        $vIsBase = isset($v['is_base_variant']) ? (int)$v['is_base_variant'] : 0;
                        $vAlert  = $v['alert'] ?? 0;

                        fputcsv($handle, [
                            $v['item_number'] ?? $p->item_number ?? '',
                            $p->item_code,
                            $p->item_name,
                            $p->category_relation->name ?? '',
                            $p->sub_category_relation->name ?? '',
                            $p->brand->name ?? '',
                            $sizeMode,
                            $pcsPerBox,
                            $p->sale_discount_percent ?? 0,
                            $p->purchase_discount_percent ?? 0,
                            $p->is_active ? 1 : 0,
                            // Variant columns
                            $v['name'] ?? $p->item_name,
                            $v['size'] ?? '-',
                            $v['color'] ?? '-',
                            $v['location'] ?? '',
                            $v['p_code'] ?? '',
                            $vUnit,
                            $vFactor,
                            $vWeight,
                            round($v['sale_price'] ?? $p->sale_price_per_piece ?? 0, 2),
                            round($v['wholesale_price'] ?? $p->wholesale_price ?? 0, 2),
                            round($v['purch_price'] ?? $p->purchase_price_per_piece ?? 0, 2),
                            $vAlert,
                            $v['barcode'] ?? $p->barcode_path,
                            $vIsBase,
                            $v['stock'] ?? 0,
                        ]);
                    }
                }
            }
            fclose($handle);
        };

        $filename = 'products_export_' . now()->format('Y-m-d_H-i') . '.csv';

        return response()->stream($callback, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ]);
    }

    // ──────────────────────────────────────────────────────────
    //  IMPORT STEP 1: VALIDATE & PREVIEW
    // ──────────────────────────────────────────────────────────
    public function importValidate(Request $request)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        $request->validate([
            'csv_file' => [
                'required',
                'file',
                'max:20480',
                function ($attribute, $value, $fail) {
                    $ext = strtolower($value->getClientOriginalExtension());
                    if (!in_array($ext, ['csv', 'txt', 'xlsx', 'xls'])) {
                        $fail('The file must be an Excel sheet (.xlsx, .xls) or CSV file (.csv).');
                    }
                },
            ],
            'import_mode' => 'required|in:create,update_only',
        ], [
            'csv_file.required' => 'Please select a file to import.',
            'csv_file.max'      => 'File size cannot exceed 20 MB.',
        ]);

        $mode = $request->input('import_mode');
        $autoCreate = $request->has('auto_create');
        
        $file = $request->file('csv_file');
        $extension = strtolower($file->getClientOriginalExtension());

        $rawRows = [];
        if (in_array($extension, ['xlsx', 'xls'])) {
            try {
                $hasPhpSpreadsheet = false;
                try {
                    $hasPhpSpreadsheet = class_exists(\PhpOffice\PhpSpreadsheet\IOFactory::class);
                } catch (\Throwable $t) {
                    $hasPhpSpreadsheet = false;
                }

                if ($hasPhpSpreadsheet) {
                    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getRealPath());
                    $sheet = $spreadsheet->getActiveSheet();
                    $rawRows = $sheet->toArray(null, true, true, false);
                } else {
                    $rawRows = $this->parseXlsxWithoutDependencies($file->getRealPath());
                }
            } catch (\Throwable $e) {
                try {
                    $rawRows = $this->parseXlsxWithoutDependencies($file->getRealPath());
                } catch (\Throwable $ex2) {
                    return redirect()->back()->with('error', 'Could not parse Excel file: ' . $ex2->getMessage());
                }
            }
        } else {
            $handle = fopen($file->getRealPath(), 'r');
            if ($handle === false) {
                return redirect()->back()->with('error', 'Could not open CSV file.');
            }
            $bom = fread($handle, 3);
            if ($bom !== "\xEF\xBB\xBF") rewind($handle);

            while (($r = fgetcsv($handle)) !== false) {
                $rawRows[] = $r;
            }
            fclose($handle);
        }

        if (empty($rawRows)) {
            return redirect()->back()->with('error', 'The uploaded file is empty or contains no data.');
        }

        $headerRow = array_shift($rawRows);
        if (!$headerRow || !is_array($headerRow)) {
            return redirect()->back()->with('error', 'CSV/Excel file is empty or missing headers.');
        }

        $headerMap = [];
        foreach ($headerRow as $i => $col) {
            if ($col !== null && trim((string)$col) !== '') {
                $headerMap[strtolower(trim((string)$col))] = $i;
            }
        }

        $get = function (array $row, $possibleNames, $default = '') use ($headerMap) {
            if (!is_array($possibleNames)) $possibleNames = [$possibleNames];
            foreach ($possibleNames as $pName) {
                $key = strtolower(trim((string)$pName));
                if (isset($headerMap[$key]) && isset($row[$headerMap[$key]]) && $row[$headerMap[$key]] !== null) {
                    return trim((string)$row[$headerMap[$key]]);
                }
            }
            return $default;
        };

        $cleanNumber = function ($val, $default = 0) {
            if ($val === null || $val === '') return $default;
            $clean = preg_replace('/[^\d.-]/', '', (string)$val);
            return is_numeric($clean) ? (float)$clean : $default;
        };

        // Flexible Header Validation: ensure at least one identifier column exists
        $hasIdentifier = false;
        $possibleIdentifierCols = [
            'item name', 'item_name', 'product name', 'product_name', 'item_name (*)', 'name',
            'item #', 'item_#', 'item no', 'item_no', 'item number', 'item_number', 'itemnum', 'item code', 'item_code',
            'sales description', 'description', 'vendor description'
        ];
        foreach ($possibleIdentifierCols as $hCol) {
            if (isset($headerMap[$hCol])) {
                $hasIdentifier = true;
                break;
            }
        }
        if (!$hasIdentifier) {
            return redirect()->back()->with('error', "Could not find an Item Name, Product Name, or Item # column. Please check your file headers.");
        }

        $categories = Category::pluck('name', 'id')->map(function($name) { return strtolower($name); })->toArray();
        $subCategories = Subcategory::pluck('name', 'id')->map(function($name) { return strtolower($name); })->toArray();
        $brands = Brand::pluck('name', 'id')->map(function($name) { return strtolower($name); })->toArray();
        
        $productsByRef = [];
        $errors = [];
        $barcodesSeen = [];
        
        // For preview tracking
        $masterDataToCreate = [
            'categories' => [],
            'subcategories' => [],
            'brands' => []
        ];

        foreach ($rawRows as $rowIndex => $row) {
            $rowNum = $rowIndex + 2;
            if (!is_array($row) || empty(array_filter($row, function($v) { return $v !== null && trim((string)$v) !== ''; }))) {
                continue;
            }

            $prodName = $get($row, ['item name', 'item_name', 'product name', 'product_name', 'item_name (*)', 'name']);
            if (empty($prodName)) {
                $prodName = $get($row, ['sales description', 'description', 'vendor description']);
            }

            $itemNumber = $get($row, ['item #', 'item_#', 'item no', 'item_no', 'item number', 'item_number', 'itemnum']);
            if (empty($prodName) && !empty($itemNumber)) {
                $prodName = 'Item #' . $itemNumber;
            }

            if (empty($prodName)) {
                // Skip footer / total summary rows often exported by QuickBooks (e.g. ,,,,,,,869256.56,,,)
                $hasIdentifierData = !empty($get($row, ['barcode', 'category', 'brand', 'size', 'department']));
                if (!$hasIdentifierData) {
                    continue;
                }
                $errors[] = ["row" => $rowNum, "msg" => "Product / Item Name is required."];
                continue;
            }

            // Group variants under one master product if item code is not explicitly given
            $rawProdRef = $get($row, ['item code', 'item_code', 'product reference', 'product_reference']);
            $isAutoGen = false;
            if (!empty($rawProdRef)) {
                $prodRef = $rawProdRef;
            } else {
                $cleanSlug = strtoupper(preg_replace('/[^A-Za-z0-9]/', '_', trim($prodName)));
                $prodRef = 'PROD_' . $cleanSlug;
                $isAutoGen = true;
            }

            $catName = $get($row, ['department', 'category', 'dept']);
            $subCatName = $get($row, ['sub_category', 'sub category', 'sub-category']);
            $brandName = $get($row, ['brand', 'vendor', 'vendor description', 'manufacturer']);
            $vBarcode = $get($row, ['variant barcode', 'barcode', 'upc']);
            
            if (!empty($vBarcode)) {
                if (isset($barcodesSeen[$vBarcode])) {
                    $errors[] = ["row" => $rowNum, "msg" => "Duplicate barcode '$vBarcode' found in CSV/Excel (Row ".$barcodesSeen[$vBarcode].")."];
                }
                $barcodesSeen[$vBarcode] = $rowNum;
            }
            
            // Check master data
            if (!empty($catName) && !in_array(strtolower($catName), $categories)) {
                if ($autoCreate) {
                    if (!in_array($catName, $masterDataToCreate['categories'])) {
                        $masterDataToCreate['categories'][] = $catName;
                    }
                } else {
                    $errors[] = ["row" => $rowNum, "msg" => "Category / Department '$catName' does not exist."];
                }
            }
            
            if (!empty($brandName) && !in_array(strtolower($brandName), $brands)) {
                if ($autoCreate) {
                    if (!in_array($brandName, $masterDataToCreate['brands'])) {
                        $masterDataToCreate['brands'][] = $brandName;
                    }
                }
            }

            // Variant-specific fields
            $vRawSize = $get($row, ['size', 'variant size', 'variant_size'], '-');
            $vSize = VariantSerialService::cleanHardwareSize($vRawSize);

            $vColor = $get($row, ['attribute', 'color', 'variant color', 'variant_color'], '-');
            $vLocation = $get($row, ['location', 'variant location', 'variant_location', 'bin', 'rack'], '');
            $vPCode = $get($row, ['p-ctss', 'p_ctss', 'pctss', 'p-code', 'p_code', 'pcode', 'variant p-code'], '');

            $vRawUnit = $get($row, ['unit of measure', 'unit_of_measure', 'uom', 'unit', 'variant unit', 'variant_unit'], 'Pcs');
            $vUnit = VariantSerialService::normalizeUnit($vRawUnit);

            $vPurchPrice = (float) $cleanNumber($get($row, ['cost', 'variant purch price', 'variant purchase price', 'purchase_price', 'purchase price', 'purch_price', 'cost price'], 0));
            $vSalePrice = (float) $cleanNumber($get($row, ['sale price', 'sale_price', 'price', 'retail price', 'variant sale price', 'sales price'], 0));
            if ($vSalePrice <= 0 && $vPurchPrice > 0) {
                $vSalePrice = $vPurchPrice;
            }
            $vWholesalePrice = (float) $cleanNumber($get($row, ['wholesale price', 'wholesale_price', 'wholesale', 'variant wholesale', 'variant wholesale price'], $vSalePrice));
            if ($vWholesalePrice <= 0) {
                $vWholesalePrice = $vSalePrice;
            }

            $vStock = max(0, (float) $cleanNumber($get($row, ['stock', 'on hand', 'on_hand', 'qty', 'variant stock', 'variant_stock', 'quantity'], 0)));
            $vDesc = $get($row, ['sales description', 'sales_description', 'description'], '');

            if (!isset($productsByRef[$prodRef])) {
                $rawSizeMode = strtolower(trim($get($row, ['size mode', 'size_mode', 'mode'], '')));
                $sizeMode = 'by_pieces';
                if (in_array($rawSizeMode, ['by_kg', 'kg', 'kgs', 'kilogram', 'kilograms']) || $vUnit === 'Kg') {
                    $sizeMode = 'by_kg';
                } elseif (in_array($rawSizeMode, ['by_gm', 'gm', 'g', 'gram', 'grams']) || $vUnit === 'Gm') {
                    $sizeMode = 'by_gm';
                } elseif (in_array($rawSizeMode, ['by_feet', 'ft', 'feet']) || $vUnit === 'Ft') {
                    $sizeMode = 'by_feet';
                } elseif (in_array($rawSizeMode, ['by_meter', 'meter', 'mtr']) || $vUnit === 'Meter') {
                    $sizeMode = 'by_meter';
                } elseif (in_array($rawSizeMode, ['by_cartons', 'carton', 'cartons']) || $vUnit === 'Carton') {
                    $sizeMode = 'by_cartons';
                }

                $productsByRef[$prodRef] = [
                    'ref'            => $prodRef,
                    'is_auto_gen'    => $isAutoGen,
                    'name'           => $prodName,
                    'item_number'    => (string)$itemNumber,
                    'category'       => $catName,
                    'sub_category'   => $subCatName,
                    'brand'          => $brandName,
                    'size_mode'      => $sizeMode,
                    'pcs_per_carton' => max(1, (int)$cleanNumber($get($row, ['pcs per box', 'pieces per box', 'pcs_per_carton'], 1), 1)),
                    'sale_discount'  => max(0, (float)$cleanNumber($get($row, ['sale discount %', 'sale_discount_%'], 0))),
                    'purch_discount' => max(0, (float)$cleanNumber($get($row, ['purchase discount %', 'purchase_discount_%'], 0))),
                    'is_active'      => (int)$get($row, ['is_active', 'is active'], 1),
                    'variants'       => []
                ];
            } else {
                if (empty($productsByRef[$prodRef]['item_number']) && !empty($itemNumber)) {
                    $productsByRef[$prodRef]['item_number'] = (string)$itemNumber;
                }
            }

            $vPieceWt = (float) $cleanNumber($get($row, ['variant piece wt (g)', 'variant piece weight (g)', 'variant_weight_per_piece', 'weight_per_piece', 'piece_weight'], 0));
            $rawConvFactor = $get($row, ['variant conv factor', 'variant_conv_factor', 'conv_factor', 'factor'], '');
            $vConvFactor = $rawConvFactor !== '' ? (float)$cleanNumber($rawConvFactor, 0) : 0;
            $isFirstVariant = empty($productsByRef[$prodRef]['variants']);
            $vIsBase = (int) $get($row, ['variant is base', 'variant_is_base', 'is_base'], $isFirstVariant ? 1 : 0);
            $vAlert  = (float) $cleanNumber($get($row, ['variant alert', 'variant_alert_qty', 'alert'], 0));

            $isWeightProduct = (isset($productsByRef[$prodRef]) && in_array($productsByRef[$prodRef]['size_mode'], ['by_kg', 'by_gm', 'by_ton']));

            // Auto-calculate conversion factor & piece weight (g)
            if ($isWeightProduct) {
                if ($vConvFactor > 0 && $vConvFactor < 1) {
                    if ($vPieceWt <= 0) {
                        $vPieceWt = (float) round($vConvFactor * 1000.0, 4);
                    }
                } elseif ($vPieceWt > 0) {
                    if ($rawConvFactor === '' || $vConvFactor <= 0 || $vConvFactor == 1) {
                        $vConvFactor = (float) round($vPieceWt / 1000.0, 8);
                    }
                } elseif ($vConvFactor <= 0) {
                    $vConvFactor = 1;
                    $vPieceWt = ($vUnit === 'Kg' || $vIsBase) ? 1000.0 : 0;
                }
            } else {
                if ($vPieceWt > 0 && ($rawConvFactor === '' || $vConvFactor <= 0 || $vConvFactor == 1)) {
                    $vConvFactor = $vPieceWt / 1000.0;
                } elseif ($vPieceWt <= 0 && $vConvFactor > 0 && $vConvFactor < 1) {
                    $vPieceWt = $vConvFactor * 1000.0;
                } elseif ($vConvFactor <= 0) {
                    $vConvFactor = 1;
                }
            }

            // Variant Data
            $productsByRef[$prodRef]['variants'][] = [
                'row'              => $rowNum,
                'item_number'      => (string)$itemNumber,
                'name'             => $get($row, ['variant name', 'variant_name'], $prodName),
                'size'             => $vSize,
                'color'            => $vColor,
                'location'         => $vLocation,
                'p_code'           => $vPCode,
                'unit'             => $vUnit,
                'conv_factor'      => $vConvFactor,
                'weight_per_piece' => $vPieceWt,
                'barcode'          => $vBarcode,
                'stock'            => $vStock,
                'sale_price'       => $vSalePrice,
                'wholesale_price'  => $vWholesalePrice,
                'purch_price'      => $vPurchPrice,
                'is_base_variant'  => $vIsBase,
                'alert'            => $vAlert,
                'description'      => $vDesc,
            ];
        }

        // Prepare Preview Summary: Match existing products by item_code OR by item_name
        $existingCodes = Product::pluck('id', 'item_code')->toArray();
        $existingNames = Product::pluck('id', 'item_name')->mapWithKeys(function ($id, $name) {
            return [strtolower(trim($name)) => $id];
        })->toArray();

        $productsToCreate = 0;
        $productsToUpdate = 0;
        $variantsToCreate = 0;
        $variantsToUpdate = 0;
        $ignored = 0;
        
        $validPayload = [];

        foreach ($productsByRef as $ref => $pData) {
            $existingId = null;
            if (isset($existingCodes[$ref])) {
                $existingId = $existingCodes[$ref];
            } elseif (isset($existingNames[strtolower(trim($pData['name']))])) {
                $existingId = $existingNames[strtolower(trim($pData['name']))];
            }

            $isUpdate = ($existingId !== null);
            
            if ($isUpdate) {
                $existingProduct = Product::find($existingId);
                $existingVariants = [];
                if (!empty($existingProduct->color)) {
                    $parsed = is_string($existingProduct->color) ? json_decode($existingProduct->color, true) : $existingProduct->color;
                    if (is_array($parsed)) $existingVariants = $parsed;
                }
                
                $newVCount = 0;
                $updVCount = 0;
                $filteredVariants = [];
                
                foreach ($pData['variants'] as $newV) {
                    $found = false;
                    foreach ($existingVariants as $eV) {
                        // 1. Match by unique item_number
                        if (!empty($eV['item_number']) && !empty($newV['item_number']) && (string)$eV['item_number'] === (string)$newV['item_number']) {
                            $found = true;
                            break;
                        }
                        // 2. Match by name + size + color
                        if (isset($eV['name']) && strcasecmp($eV['name'], $newV['name']) === 0) {
                            if (isset($eV['size']) && strcasecmp($eV['size'], $newV['size']) === 0) {
                                if (isset($eV['color']) && strcasecmp($eV['color'], $newV['color']) === 0) {
                                    $found = true;
                                    break;
                                }
                            }
                        }
                    }
                    if ($found) {
                        $updVCount++;
                        $filteredVariants[] = $newV;
                    } else {
                        $newVCount++;
                        $filteredVariants[] = $newV;
                    }
                }
                
                $pData['variants'] = $filteredVariants;
                if (empty($filteredVariants)) {
                    continue; // Skip product completely if all variants ignored
                }
                
                $productsToUpdate++;
                $variantsToUpdate += $updVCount;
                $variantsToCreate += $newVCount;
                
            } else {
                if ($mode === 'update_only') {
                    $ignored += count($pData['variants']);
                    continue; // Skip new products entirely
                }
                $productsToCreate++;
                $variantsToCreate += count($pData['variants']);
            }
            
            $validPayload[$ref] = $pData;
        }

        $payloadFile = 'temp_import_' . Auth::id() . '.json';
        file_put_contents(storage_path('app/' . $payloadFile), json_encode($validPayload));

        // Store payload in session for confirmation
        Session::put('import_payload', [
            'mode' => $mode,
            'auto_create' => $autoCreate,
            'products_file' => $payloadFile,
            'master_data' => $masterDataToCreate,
            'errors' => $errors,
            'preview_stats' => [
                'products_create' => $productsToCreate,
                'products_update' => $productsToUpdate,
                'variants_create' => $variantsToCreate,
                'variants_update' => $variantsToUpdate,
                'ignored' => $ignored,
                'master_create' => count($masterDataToCreate['categories']) + count($masterDataToCreate['brands'])
            ]
        ]);

        return redirect()->route('products.import.preview');
    }
    
    // ──────────────────────────────────────────────────────────
    //  IMPORT STEP 2: SHOW PREVIEW
    // ──────────────────────────────────────────────────────────
    public function importPreview()
    {
        if (!Session::has('import_payload')) {
            return redirect()->route('product')->with('error', 'Import session expired or invalid.');
        }
        
        $payload = Session::get('import_payload');
        $payload['products'] = [];
        if (isset($payload['products_file'])) {
            $path = storage_path('app/' . $payload['products_file']);
            if (file_exists($path)) {
                $payload['products'] = json_decode(file_get_contents($path), true);
            }
        }

        $flatItems = [];
        $totalVariants = 0;
        foreach ($payload['products'] as $ref => $p) {
            foreach ($p['variants'] as $v) {
                $totalVariants++;
                $flatItems[] = [
                    'row'         => $v['row'] ?? $totalVariants,
                    'item_number' => $v['item_number'] ?? '',
                    'name'        => $v['name'] ?? $p['name'],
                    'parent_name' => $p['name'],
                    'ref'         => $ref,
                    'category'    => $p['category'] ?? '-',
                    'brand'       => $p['brand'] ?? '-',
                    'attribute'   => $v['color'] ?? '-',
                    'size'        => $v['size'] ?? '-',
                    'cost'        => (float)($v['purch_price'] ?? 0),
                    'sale_price'  => (float)($v['sale_price'] ?? 0),
                    'stock'       => (float)($v['stock'] ?? 0),
                    'unit'        => $v['unit'] ?? 'Pcs',
                    'location'    => $v['location'] ?? '',
                    'p_code'      => $v['p_code'] ?? '',
                ];
            }
        }
        $payload['flat_items'] = $flatItems;
        $payload['total_variants'] = $totalVariants;

        return view('admin_panel.product.import_preview', compact('payload'));
    }
    
    // ──────────────────────────────────────────────────────────
    //  IMPORT STEP 3: CONFIRM & IMPORT
    // ──────────────────────────────────────────────────────────
    public function importConfirm(Request $request)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        if (!Session::has('import_payload')) {
            if ($request->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'Import session expired.']);
            }
            return redirect()->route('product')->with('error', 'Import session expired.');
        }

        $payload = Session::get('import_payload');
        $productsToProcess = [];
        if (isset($payload['products_file'])) {
            $path = storage_path('app/' . $payload['products_file']);
            if (file_exists($path)) {
                $productsToProcess = json_decode(file_get_contents($path), true);
            }
        }
        $autoCreate = $payload['auto_create'];
        
        $offset = (int) $request->input('offset', 0);
        $chunkSize = (int) $request->input('chunk_size', 0);
        $totalItems = count($productsToProcess);

        if ($chunkSize > 0) {
            $chunk = array_slice($productsToProcess, $offset, $chunkSize, true);
        } else {
            $chunk = $productsToProcess;
        }

        if (empty($chunk) && $request->ajax()) {
            Session::forget('import_payload');
            if (isset($path) && file_exists($path)) {
                @unlink($path);
            }
            return response()->json(['status' => 'success']);
        }

        $createdProducts = 0;
        $updatedProducts = 0;
        $createdVariants = 0;
        $updatedVariants = 0;

        DB::beginTransaction();
        try {
            // 1. Auto Create Master Data (Only on first chunk to save time)
            $catMap = Category::pluck('id', 'name')->mapWithKeys(function ($item, $key) { return [strtolower($key) => $item]; })->toArray();
            $brandMap = Brand::pluck('id', 'name')->mapWithKeys(function ($item, $key) { return [strtolower($key) => $item]; })->toArray();
            $subCatMap = Subcategory::pluck('id', 'name')->mapWithKeys(function ($item, $key) { return [strtolower($key) => $item]; })->toArray();
            $rackMap = \App\Models\StorageLocation::where('type', 'warehouse_rack')->pluck('id', 'name')->mapWithKeys(function ($id, $name) { return [strtolower(trim($name)) => $id]; })->toArray();
            $resolveRackId = function ($locStr) use ($rackMap) {
                if (empty(trim((string)$locStr))) return null;
                $clean = preg_replace('/\b([A-Za-z]+)\s+(\d+)\b/', '$1$2', trim($locStr));
                $clean = trim($clean, " /-");
                $tokens = preg_split('/[\/\-,\;\s]+/', strtolower($clean));
                foreach ($tokens as $t) {
                    if (isset($rackMap[$t])) {
                        return $rackMap[$t];
                    }
                }
                return null;
            };
            
            if ($autoCreate && $offset === 0) {
                foreach ($payload['master_data']['categories'] as $catName) {
                    $key = strtolower($catName);
                    if (!isset($catMap[$key])) {
                        $c = Category::create(['name' => $catName]);
                        $catMap[$key] = $c->id;
                    }
                }
                foreach ($payload['master_data']['brands'] as $brandName) {
                    $key = strtolower($brandName);
                    if (!isset($brandMap[$key])) {
                        $b = Brand::create(['name' => $brandName]);
                        $brandMap[$key] = $b->id;
                    }
                }
            }

            // =========================================================================
            // OPTIMIZED BATCH IMPORT LOGIC
            // =========================================================================
            $chunkItemCodes = array_keys($chunk);
            $existingProductsByCode = Product::whereIn('item_code', $chunkItemCodes)->get()->keyBy('item_code');

            $chunkNames = array_map(function ($p) { return strtolower(trim($p['name'])); }, $chunk);
            $existingProductsByName = Product::whereIn(DB::raw('LOWER(TRIM(item_name))'), $chunkNames)->get()->keyBy(function ($item) {
                return strtolower(trim($item->item_name));
            });

            $existingWarehouseStocks = WarehouseStock::where('warehouse_id', 1)
                ->whereIn('product_id', $existingProductsByCode->pluck('id')->merge($existingProductsByName->pluck('id'))->unique()->toArray())
                ->get()
                ->keyBy('product_id');

            $lastProduct = Product::orderBy('id', 'desc')->first();
            $nextProductId = $lastProduct ? $lastProduct->id + 1 : 1;

            foreach ($chunk as $ref => $pData) {
                $product = $existingProductsByCode->get($ref) ?? $existingProductsByName->get(strtolower(trim($pData['name'])));
                
                $cId = isset($catMap[strtolower($pData['category'])]) ? $catMap[strtolower($pData['category'])] : null;
                $bId = isset($brandMap[strtolower($pData['brand'])]) ? $brandMap[strtolower($pData['brand'])] : null;
                $sId = isset($subCatMap[strtolower($pData['sub_category'])]) ? $subCatMap[strtolower($pData['sub_category'])] : null;
                
                $finalVariants = [];
                $existingTotalStock = 0;
                
                if ($product) {
                    // Update Product
                    $existingVariants = [];
                    if (!empty($product->color)) {
                        $parsed = is_string($product->color) ? json_decode($product->color, true) : $product->color;
                        if (is_array($parsed)) $existingVariants = $parsed;
                    }
                    
                    if ($payload['mode'] === 'update_only') {
                        $finalVariants = []; 
                    } else {
                        $finalVariants = $existingVariants; 
                    }
                    
                    foreach ($pData['variants'] as $newV) {
                        $foundIdx = -1;
                        $oldBarcode = '';
                        
                        foreach ($finalVariants as $idx => $eV) {
                            if (!empty($eV['item_number']) && !empty($newV['item_number']) && (string)$eV['item_number'] === (string)$newV['item_number']) {
                                $foundIdx = $idx;
                                $oldBarcode = $eV['barcode'] ?? '';
                                break;
                            }
                            if (strcasecmp($eV['name'] ?? '', $newV['name']) === 0 && 
                                strcasecmp($eV['size'] ?? '', $newV['size']) === 0 && 
                                strcasecmp($eV['color'] ?? '', $newV['color']) === 0) {
                                $foundIdx = $idx;
                                $oldBarcode = $eV['barcode'] ?? '';
                                break;
                            }
                        }
                        
                        $vArr = [
                            'item_number'      => (string)($newV['item_number'] ?? ''),
                            'name'             => $newV['name'],
                            'size'             => $newV['size'],
                            'color'            => $newV['color'],
                            'location'         => $newV['location'] ?? '',
                            'p_code'           => $newV['p_code'] ?? '',
                            'unit'             => $newV['unit'] ?? ($pData['size_mode'] === 'by_kg' ? 'Kg' : 'Pcs'),
                            'conv_factor'      => (float)($newV['conv_factor'] ?? 1),
                            'weight_per_piece' => (float)($newV['weight_per_piece'] ?? 0),
                            'stock'            => $newV['stock'],
                            'sale_price'       => $newV['sale_price'],
                            'wholesale_price'  => $newV['wholesale_price'] ?? 0,
                            'purch_price'      => $newV['purch_price'],
                            'barcode'          => $newV['barcode'] ?: $oldBarcode,
                            'is_base_variant'  => (int)($newV['is_base_variant'] ?? 0),
                            'alert'            => (float)($newV['alert'] ?? 0),
                        ];
                        
                        if ($foundIdx >= 0) {
                            $finalVariants[$foundIdx] = $vArr;
                            $updatedVariants++;
                        } else {
                            $finalVariants[] = $vArr;
                            $createdVariants++;
                        }
                    }
                    
                    $stockTotal = 0;
                    foreach ($finalVariants as $v) {
                        $stk = (float) ($v['stock'] ?? 0);
                        $f = (float) ($v['conv_factor'] ?? 1);
                        $u = strtolower($v['unit'] ?? '');
                        if (($pData['size_mode'] === 'by_kg' || $pData['size_mode'] === 'by_gm') && ($u === 'pcs' || $u === 'pc') && $f > 0) {
                            $stockTotal += ($stk * $f);
                        } elseif ($u === 'gm' || $u === 'g') {
                            $stockTotal += ($stk / 1000.0);
                        } else {
                            $stockTotal += $stk;
                        }
                    }
                    
                    $product->update([
                        'item_name' => $pData['name'],
                        'item_number' => $finalVariants[0]['item_number'] ?? $product->item_number,
                        'category_id' => $cId ?? $product->category_id,
                        'sub_category_id' => $sId ?? $product->sub_category_id,
                        'brand_id' => $bId ?? $product->brand_id,
                        'size_mode' => $pData['size_mode'],
                        'pieces_per_box' => $pData['pcs_per_carton'],
                        'sale_discount_percent' => $pData['sale_discount'],
                        'purchase_discount_percent' => $pData['purch_discount'],
                        'sale_price_per_piece' => $finalVariants[0]['sale_price'] ?? 0,
                        'wholesale_price' => $finalVariants[0]['wholesale_price'] ?? 0,
                        'purchase_price_per_piece' => $finalVariants[0]['purch_price'] ?? 0,
                        'color' => json_encode($finalVariants),
                        'is_active' => $pData['is_active'],
                    ]);
                    
                    $ws = $existingWarehouseStocks->get($product->id);
                    if (!$ws) {
                        $ws = new WarehouseStock([
                            'warehouse_id' => 1,
                            'product_id' => $product->id
                        ]);
                    }

                    $diff = $stockTotal - ($ws->total_pieces ?? 0);
                    $ws->total_pieces = $stockTotal;
                    $ws->quantity = floor($stockTotal / max(1, $pData['pcs_per_carton']));
                    $targetLocStr = $finalVariants[0]['location'] ?? '';
                    $resolvedRackId = $resolveRackId($targetLocStr);
                    if ($resolvedRackId) {
                        $ws->storage_location_id = $resolvedRackId;
                    }
                    $ws->remarks = !empty($targetLocStr) ? $targetLocStr : 'Updated via Bulk Import';
                    $ws->save();
                    
                    if ($diff != 0) {
                        StockMovement::create([
                            'product_id' => $product->id,
                            'type'       => 'adjustment',
                            'qty'        => $diff,
                            'ref_type'   => 'IMPORT',
                            'note'       => 'Stock adjusted via CSV import',
                        ]);
                    }
                    
                    $updatedProducts++;
                    
                } else {
                    // Create New Product
                    foreach ($pData['variants'] as $newV) {
                        $finalVariants[] = [
                            'item_number'      => (string)($newV['item_number'] ?? ''),
                            'name'             => $newV['name'],
                            'size'             => $newV['size'],
                            'color'            => $newV['color'],
                            'location'         => $newV['location'] ?? '',
                            'p_code'           => $newV['p_code'] ?? '',
                            'unit'             => $newV['unit'] ?? ($pData['size_mode'] === 'by_kg' ? 'Kg' : 'Pcs'),
                            'conv_factor'      => (float)($newV['conv_factor'] ?? 1),
                            'weight_per_piece' => (float)($newV['weight_per_piece'] ?? 0),
                            'stock'            => $newV['stock'],
                            'sale_price'       => $newV['sale_price'],
                            'wholesale_price'  => $newV['wholesale_price'] ?? 0,
                            'purch_price'      => $newV['purch_price'],
                            'barcode'          => $newV['barcode'],
                            'is_base_variant'  => (int)($newV['is_base_variant'] ?? 0),
                            'alert'            => (float)($newV['alert'] ?? 0),
                        ];
                        $createdVariants++;
                    }

                    // Auto-assign unique serial numbers
                    $finalVariants = VariantSerialService::assignSerialNumbers($pData['name'], $finalVariants);
                    
                    $stockTotal = 0;
                    foreach ($finalVariants as $v) {
                        $stk = (float) ($v['stock'] ?? 0);
                        $f = (float) ($v['conv_factor'] ?? 1);
                        $u = strtolower($v['unit'] ?? '');
                        if (($pData['size_mode'] === 'by_kg' || $pData['size_mode'] === 'by_gm') && ($u === 'pcs' || $u === 'pc') && $f > 0) {
                            $stockTotal += ($stk * $f);
                        } elseif ($u === 'gm' || $u === 'g') {
                            $stockTotal += ($stk / 1000.0);
                        } else {
                            $stockTotal += $stk;
                        }
                    }

                    $unitObj = Unit::where('name', $finalVariants[0]['unit'] ?? 'Pcs')->first();
                    $unitId = $unitObj ? $unitObj->id : 1;
                    
                    $actualRef = 'ITEM-'.str_pad($nextProductId, 4, '0', STR_PAD_LEFT);
                    $nextProductId++;
                    
                    $product = Product::create([
                        'creater_id' => Auth::id(),
                        'item_code' => $actualRef,
                        'item_number' => $finalVariants[0]['item_number'] ?? null,
                        'unit_id' => $unitId,
                        'item_name' => $pData['name'],
                        'category_id' => $cId,
                        'sub_category_id' => $sId,
                        'brand_id' => $bId,
                        'size_mode' => $pData['size_mode'],
                        'pieces_per_box' => $pData['pcs_per_carton'],
                        'sale_discount_percent' => $pData['sale_discount'],
                        'purchase_discount_percent' => $pData['purch_discount'],
                        'sale_price_per_piece' => $finalVariants[0]['sale_price'] ?? 0,
                        'wholesale_price' => $finalVariants[0]['wholesale_price'] ?? 0,
                        'purchase_price_per_piece' => $finalVariants[0]['purch_price'] ?? 0,
                        'color' => json_encode($finalVariants),
                        'is_active' => $pData['is_active'],
                        'is_part' => 0,
                        'is_assembled' => 0,
                        'barcode_path' => rand(100000000000, 999999999999), 
                        'p_code' => $finalVariants[0]['p_code'] ?? null,
                        'total_m2' => 0,
                        'price_per_m2' => 0,
                        'purchase_price_per_m2' => 0,
                        'pieces_per_m2' => 0,
                        'height' => 0,
                        'width' => 0,
                    ]);
                    
                    $targetLocStr = $finalVariants[0]['location'] ?? '';
                    $resolvedRackId = $resolveRackId($targetLocStr);

                    WarehouseStock::create([
                        'warehouse_id'        => 1,
                        'product_id'          => $product->id,
                        'quantity'            => floor($stockTotal / max(1, $pData['pcs_per_carton'])),
                        'total_pieces'        => $stockTotal,
                        'storage_location_id' => $resolvedRackId,
                        'remarks'             => !empty($targetLocStr) ? $targetLocStr : 'Initial Stock via Import',
                    ]);
                    
                    if ($stockTotal > 0) {
                        StockMovement::create([
                            'product_id' => $product->id,
                            'type'       => 'adjustment',
                            'qty'        => $stockTotal,
                            'ref_type'   => 'IMPORT',
                            'note'       => 'Initial Stock via CSV import',
                        ]);
                    }
                    
                    $createdProducts++;
                }
            }

            DB::commit();
            
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'continue',
                    'processed' => $offset + count($chunk),
                    'total' => $totalItems,
                    'next_offset' => $offset + count($chunk)
                ]);
            }
            
            Session::forget('import_payload');
            if (isset($path) && file_exists($path)) {
                @unlink($path);
            }
            
            return redirect()->route('product')->with('success', "Import completed successfully.");
            
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax()) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
            }
            if (isset($path) && file_exists($path)) {
                @unlink($path);
            }
            return redirect()->route('product')->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    // ──────────────────────────────────────────────────────────
    //  Helper – canonical CSV column headers
    //  ORDER matches create_product form variant columns exactly
    // ──────────────────────────────────────────────────────────
    private function csvHeaders(): array
    {
        return [
            'Item #',               // Unique Item / Variant Number (ERP Standard)
            'Item Code',            // Product reference key
            'Product Name',         // item_name
            'Category',
            'Sub Category',
            'Brand',
            'Size Mode',            // by_pieces / by_kg / by_gm / by_cartons / by_feet / by_meter
            'Pieces Per Box',
            'Sale Discount %',
            'Purchase Discount %',
            'Is Active',
            // Variant columns — same order as create_product table headers
            'Variant Name',          // variant_name[]
            'Variant Size',          // variant_size[]
            'Variant Color',         // variant_color[]
            'Variant Location',      // variant_location[]
            'Variant P-Code',        // variant_p_code[]
            'Variant Unit',          // variant_unit[]: Kg/Pcs/Gm/Ft/Meter/Box/Dozen
            'Variant Conv Factor',   // variant_conv_factor[]
            'Variant Piece Wt (g)',  // variant_weight_per_piece[]
            'Variant Sale Price',    // variant_sale_price[]
            'Variant Wholesale',     // variant_wholesale_price[]
            'Variant Purch Price',   // variant_purchase_price[]
            'Variant Alert',         // variant_alert_qty[]
            'Variant Barcode',       // variant_barcode[]
            'Variant Is Base',       // variant_is_base[]: 1=base row 0=child
            'Variant Stock',         // variant_stock[] (initial stock in base unit)
        ];
    }

    /**
     * Standalone zero-dependency XLSX parser using PHP's native ZipArchive and SimpleXML.
     * Prevents hosting failures when PhpSpreadsheet composer package is missing.
     *
     * @param string $filePath
     * @return array
     * @throws \Exception
     */
    protected function parseXlsxWithoutDependencies(string $filePath): array
    {
        if (!class_exists('ZipArchive')) {
            throw new \Exception('PHP ZipArchive extension is required on the server to read Excel files.');
        }

        $zip = new \ZipArchive();
        if ($zip->open($filePath) !== true) {
            throw new \Exception('Unable to open Excel archive.');
        }

        // 1. Read shared strings
        $sharedStrings = [];
        $sharedStringsXml = $zip->getFromName('xl/sharedStrings.xml');
        if ($sharedStringsXml !== false) {
            $xml = @simplexml_load_string($sharedStringsXml);
            if ($xml) {
                foreach ($xml->si as $si) {
                    $str = '';
                    if (isset($si->t)) {
                        $str = (string)$si->t;
                    } elseif (isset($si->r)) {
                        foreach ($si->r as $r) {
                            $str .= (string)$r->t;
                        }
                    }
                    $sharedStrings[] = $str;
                }
            }
        }

        // 2. Locate first worksheet
        $sheetXmlContent = $zip->getFromName('xl/worksheets/sheet1.xml');
        if ($sheetXmlContent === false) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $name = $zip->getNameIndex($i);
                if (preg_match('#xl/worksheets/sheet\d+\.xml#i', $name)) {
                    $sheetXmlContent = $zip->getFromName($name);
                    break;
                }
            }
        }

        $zip->close();

        if ($sheetXmlContent === false) {
            throw new \Exception('No worksheet found in Excel file.');
        }

        $sheetXml = @simplexml_load_string($sheetXmlContent);
        if (!$sheetXml || !isset($sheetXml->sheetData)) {
            throw new \Exception('Invalid worksheet structure in Excel file.');
        }

        $rows = [];
        $colLetterToIndex = function ($letters) {
            $letters = strtoupper($letters);
            $len = strlen($letters);
            $idx = 0;
            for ($i = 0; $i < $len; $i++) {
                $idx = $idx * 26 + (ord($letters[$i]) - 64);
            }
            return $idx - 1;
        };

        foreach ($sheetXml->sheetData->row as $rowNode) {
            $currentRow = [];
            $maxCol = 0;

            foreach ($rowNode->c as $cell) {
                $cellRef = (string)$cell['r'];
                preg_match('/^([A-Z]+)(\d+)$/i', $cellRef, $matches);
                $colIdx = isset($matches[1]) ? $colLetterToIndex($matches[1]) : count($currentRow);

                $type = (string)$cell['t'];
                $val = '';

                if ($type === 's') {
                    $sIdx = (int)$cell->v;
                    $val = isset($sharedStrings[$sIdx]) ? $sharedStrings[$sIdx] : '';
                } elseif ($type === 'inlineStr' && isset($cell->is->t)) {
                    $val = (string)$cell->is->t;
                } elseif ($type === 'b') {
                    $val = ((string)$cell->v === '1') ? '1' : '0';
                } elseif (isset($cell->v)) {
                    $val = (string)$cell->v;
                }

                $currentRow[$colIdx] = $val;
                if ($colIdx > $maxCol) {
                    $maxCol = $colIdx;
                }
            }

            $normalizedRow = [];
            for ($c = 0; $c <= $maxCol; $c++) {
                $normalizedRow[$c] = isset($currentRow[$c]) ? $currentRow[$c] : '';
            }

            $rows[] = $normalizedRow;
        }

        return $rows;
    }
}

