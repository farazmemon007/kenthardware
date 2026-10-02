<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\StorageLocation;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use App\Services\PCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OpeningStockController extends Controller
{
    /**
     * Check permissions for opening stock management.
     */
    private function checkPermission()
    {
        $user = Auth::user();
        if (!$user) {
            abort(401);
        }

        if ($user->email === 'admin@admin.com' || (method_exists($user, 'hasRole') && ($user->hasRole('Super Admin') || $user->hasRole('Admin')))) {
            return true;
        }

        if ($user->can('warehouse.stock.view') || $user->can('stock.adjust.view') || $user->can('products.view') || $user->can('products.create')) {
            return true;
        }

        abort(403, 'Unauthorized action. You do not have permission to access Opening Stock.');
    }

    /**
     * Display the Opening Stock management page.
     */
    public function index(Request $request)
    {
        $this->checkPermission();

        // 1. Get Permitted Warehouses
        $user = Auth::user();
        $warehouses = Warehouse::orderBy('warehouse_name')->get();
        if ($warehouses->isEmpty()) {
            $warehouses = collect([
                Warehouse::create([
                    'warehouse_name' => 'Main Warehouse',
                    'location' => 'Main',
                ])
            ]);
        }

        $selectedWarehouseId = (int) $request->get('warehouse_id', $warehouses->first()->id);
        $selectedWarehouse = $warehouses->firstWhere('id', $selectedWarehouseId) ?? $warehouses->first();

        // 2. Storage Locations & Branches for selection
        $branches = Branch::orderBy('name')->get();
        $storageLocations = StorageLocation::with(['warehouse:id,warehouse_name', 'branch:id,name'])->orderBy('name')->get();

        // 3. Categories and Brands for Filtering
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();

        // 4. Filters & Search
        $search = trim($request->get('search', ''));
        $categoryId = $request->get('category_id');
        $brandId = $request->get('brand_id');
        $productId = $request->get('product_id');
        $filterType = $request->get('filter_type', 'all'); // 'all', 'missing_location', 'missing_price', 'zero_stock'
        $perPage = (int) $request->get('per_page', 1);

        // 5. Query Products
        $query = Product::with(['category_relation', 'brand', 'unit'])
            ->with(['warehouseStocks' => function ($q) use ($selectedWarehouseId) {
                $q->where('warehouse_id', $selectedWarehouseId);
            }]);

        if (!empty($productId)) {
            $query->where('id', $productId);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('item_name', 'like', "%{$search}%")
                    ->orWhere('item_code', 'like', "%{$search}%")
                    ->orWhere('barcode_path', 'like', "%{$search}%")
                    ->orWhere('color', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%");
            });
        }

        if (!empty($categoryId)) {
            $query->where('category_id', $categoryId);
        }

        if (!empty($brandId)) {
            $query->where('brand_id', $brandId);
        }

        // Stats Counters for top summary cards
        $allProductsCount = Product::count();
        
        $products = $query->latest('id')->paginate($perPage)->appends($request->query());

        // Transform products into expandable / matrix items
        $items = [];
        $totalVariantsCount = 0;
        $missingLocationCount = 0;
        $missingPriceCount = 0;
        $zeroStockCount = 0;

        foreach ($products as $p) {
            $whStock = $p->warehouseStocks->first();
            $whTotalPieces = (float) ($whStock ? $whStock->total_pieces : 0);
            $whBoxes = (float) ($whStock ? $whStock->quantity : 0);

            $variants = [];
            if (!empty($p->color)) {
                $decoded = is_string($p->color) ? json_decode($p->color, true) : $p->color;
                if (is_array($decoded) && count($decoded) > 0) {
                    $variants = $decoded;
                }
            }

            $hasVariants = count($variants) > 0;
            $matrixRows = [];

            if ($hasVariants) {
                foreach ($variants as $idx => $v) {
                    $totalVariantsCount++;
                    $vLocation = trim($v['location'] ?? $v['rack_shelf'] ?? '');
                    $vStock = (float) ($v['stock'] ?? $v['stock_quantity'] ?? 0);
                    $vConv = (float) ($v['conv_factor'] ?? ($p->pieces_per_box ?: 1));
                    if ($vConv <= 0) $vConv = 1;

                    $vWeight = (float) ($v['weight_per_piece'] ?? ($p->weight_per_piece ?: 0));
                    $vSale = (float) ($v['sale_price'] ?? ($p->sale_price_per_piece ?: 0));
                    $vCost = (float) ($v['purch_price'] ?? ($p->purchase_price_per_piece ?: 0));
                    $vRot = (float) ($v['wholesale_price'] ?? ($p->wholesale_price ?: 0));

                    $vSkyPCode = !empty($v['sky_p_code']) ? $v['sky_p_code'] : (!empty($v['p_code']) ? $v['p_code'] : PCodeService::encode($vSale));
                    $vRotPCode = !empty($v['rot_p_code']) ? $v['rot_p_code'] : PCodeService::encode($vRot);

                    $isMissingLoc = empty($vLocation);
                    $isMissingPrice = ($vSale <= 0 || $vRot <= 0);
                    $isZeroStock = ($vStock <= 0);

                    if ($isMissingLoc) $missingLocationCount++;
                    if ($isMissingPrice) $missingPriceCount++;
                    if ($isZeroStock) $zeroStockCount++;

                    // Filter check
                    $matchesFilter = true;
                    if ($filterType === 'missing_location' && !$isMissingLoc) $matchesFilter = false;
                    if ($filterType === 'missing_price' && !$isMissingPrice) $matchesFilter = false;
                    if ($filterType === 'zero_stock' && !$isZeroStock) $matchesFilter = false;
                    if ($filterType === 'complete' && ($isMissingLoc || $isMissingPrice || $isZeroStock)) $matchesFilter = false;

                    $matrixRows[] = [
                        'row_id' => "prod_{$p->id}_var_{$idx}",
                        'product_id' => $p->id,
                        'variant_index' => $idx,
                        'is_variant' => true,
                        'product_name' => $p->item_name,
                        'item_code' => $p->item_code,
                        'serial_no' => $v['serial_no'] ?? sprintf('%s-%04d', $p->item_code ?: 'ITM', $idx + 1),
                        'ref' => $v['sku'] ?? $p->item_code,
                        'variant_title' => $this->formatVariantTitle($v['name'] ?? '', $p->item_name, $v['size'] ?? '-', $v['color'] ?? '-', $idx),
                        'attributes' => [
                            'size' => $v['size'] ?? '-',
                            'color' => $v['color'] ?? '-',
                        ],
                        'location' => $vLocation,
                        'stock' => $vStock,
                        'conv_factor' => $vConv,
                        'weight_per_piece' => $vWeight,
                        'sky_price' => $vSale,
                        'sky_pcode' => $vSkyPCode,
                        'cost' => $vCost,
                        'rot_price' => $vRot,
                        'rot_pcode' => $vRotPCode,
                        'barcode' => $v['barcode'] ?? $p->barcode_path,
                        'unit' => $v['unit'] ?? ($p->unit->name ?? 'Pcs'),
                        'matches_filter' => $matchesFilter,
                    ];
                }
            } else {
                // Non-variant / Standard Single Product Row
                $totalVariantsCount++;
                $pLocation = trim($p->remarks ?? '');
                $pStock = $whTotalPieces;
                $pConv = (float) ($p->pieces_per_box ?: 1);
                if ($pConv <= 0) $pConv = 1;

                $pWeight = (float) ($p->weight_per_piece ?: 0);
                $pSale = (float) ($p->sale_price_per_piece ?: 0);
                $pCost = (float) ($p->purchase_price_per_piece ?: 0);
                $pRot = (float) ($p->wholesale_price ?: 0);

                $pSkyPCode = !empty($p->p_code) ? $p->p_code : PCodeService::encode($pSale);
                $pRotPCode = !empty($p->rot_p_code) ? $p->rot_p_code : PCodeService::encode($pRot);

                $isMissingLoc = empty($pLocation);
                $isMissingPrice = ($pSale <= 0 || $pRot <= 0);
                $isZeroStock = ($pStock <= 0);

                if ($isMissingLoc) $missingLocationCount++;
                if ($isMissingPrice) $missingPriceCount++;
                if ($isZeroStock) $zeroStockCount++;

                $matchesFilter = true;
                if ($filterType === 'missing_location' && !$isMissingLoc) $matchesFilter = false;
                if ($filterType === 'missing_price' && !$isMissingPrice) $matchesFilter = false;
                if ($filterType === 'zero_stock' && !$isZeroStock) $matchesFilter = false;
                if ($filterType === 'complete' && ($isMissingLoc || $isMissingPrice || $isZeroStock)) $matchesFilter = false;

                $matrixRows[] = [
                    'row_id' => "prod_{$p->id}_master",
                    'product_id' => $p->id,
                    'variant_index' => 'master',
                    'is_variant' => false,
                    'product_name' => $p->item_name,
                    'item_code' => $p->item_code,
                    'serial_no' => $p->item_code ?: 'ITEM-' . $p->id,
                    'ref' => $p->item_code,
                    'variant_title' => 'Standard Item',
                    'attributes' => [
                        'size' => '-',
                        'color' => '-',
                    ],
                    'location' => $pLocation,
                    'stock' => $pStock,
                    'conv_factor' => $pConv,
                    'weight_per_piece' => $pWeight,
                    'sky_price' => $pSale,
                    'sky_pcode' => $pSkyPCode,
                    'cost' => $pCost,
                    'rot_price' => $pRot,
                    'rot_pcode' => $pRotPCode,
                    'barcode' => $p->barcode_path,
                    'unit' => $p->unit->name ?? 'Pcs',
                    'matches_filter' => $matchesFilter,
                ];
            }

            $items[] = [
                'product' => $p,
                'has_variants' => $hasVariants,
                'matrix_rows' => $matrixRows,
                'wh_total_pieces' => $whTotalPieces,
                'wh_boxes' => $whBoxes,
            ];
        }

        // Active PCode Mapping for JS client-side encryption
        $currentPCodeMapping = PCodeService::getMapping();

        return view('admin_panel.opening_stock.index', compact(
            'warehouses',
            'selectedWarehouse',
            'branches',
            'storageLocations',
            'categories',
            'brands',
            'products',
            'items',
            'currentPCodeMapping',
            'search',
            'categoryId',
            'brandId',
            'productId',
            'filterType',
            'allProductsCount',
            'totalVariantsCount',
            'missingLocationCount',
            'missingPriceCount',
            'zeroStockCount'
        ));
    }

    /**
     * AJAX Search products for the Product Selection Modal.
     */
    public function searchProducts(Request $request)
    {
        $this->checkPermission();
        $q = trim($request->get('q', ''));
        $categoryId = $request->get('category_id');
        $brandId = $request->get('brand_id');
        $warehouseId = (int) $request->get('warehouse_id', 1);

        $query = Product::with(['category_relation:id,name', 'brand:id,name', 'unit:id,name'])
            ->with(['warehouseStocks' => function ($sq) use ($warehouseId) {
                $sq->where('warehouse_id', $warehouseId);
            }]);

        if (!empty($q)) {
            $query->where(function ($sq) use ($q) {
                $sq->where('item_name', 'like', "%{$q}%")
                    ->orWhere('item_code', 'like', "%{$q}%")
                    ->orWhere('barcode_path', 'like', "%{$q}%")
                    ->orWhere('color', 'like', "%{$q}%")
                    ->orWhere('model', 'like', "%{$q}%");
            });
        }

        if (!empty($categoryId)) {
            $query->where('category_id', $categoryId);
        }

        if (!empty($brandId)) {
            $query->where('brand_id', $brandId);
        }

        $products = $query->latest('id')->limit(40)->get();

        $results = [];
        foreach ($products as $p) {
            $variants = [];
            if (!empty($p->color)) {
                $decoded = is_string($p->color) ? json_decode($p->color, true) : $p->color;
                if (is_array($decoded)) {
                    $variants = $decoded;
                }
            }

            $whStock = $p->warehouseStocks->first();
            $whTotalPieces = (float) ($whStock ? $whStock->total_pieces : 0);

            $results[] = [
                'id' => $p->id,
                'item_name' => $p->item_name,
                'item_code' => $p->item_code ?: 'NO-CODE',
                'barcode' => $p->barcode_path ?: '',
                'category_name' => $p->category_relation ? $p->category_relation->name : '-',
                'brand_name' => $p->brand ? $p->brand->name : '-',
                'unit_name' => $p->unit ? $p->unit->name : 'Pcs',
                'variant_count' => count($variants) > 0 ? count($variants) : 1,
                'has_variants' => count($variants) > 0,
                'current_stock' => $whTotalPieces,
            ];
        }

        return response()->json([
            'success' => true,
            'products' => $results,
        ]);
    }

    /**
     * AJAX Fetch single product details with formatted matrix rows for Opening Stock.
     */
    public function fetchProduct(Request $request)
    {
        $this->checkPermission();
        $productId = (int) $request->get('product_id');
        $warehouseId = (int) $request->get('warehouse_id', 1);

        $product = Product::with(['category_relation', 'brand', 'unit'])
            ->with(['warehouseStocks' => function ($q) use ($warehouseId) {
                $q->where('warehouse_id', $warehouseId);
            }])
            ->find($productId);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.',
            ], 404);
        }

        $whStock = $product->warehouseStocks->first();
        $whTotalPieces = (float) ($whStock ? $whStock->total_pieces : 0);
        $whBoxes = (float) ($whStock ? $whStock->quantity : 0);

        $variants = [];
        if (!empty($product->color)) {
            $decoded = is_string($product->color) ? json_decode($product->color, true) : $product->color;
            if (is_array($decoded) && count($decoded) > 0) {
                $variants = $decoded;
            }
        }

        $hasVariants = count($variants) > 0;
        $matrixRows = [];

        if ($hasVariants) {
            foreach ($variants as $idx => $v) {
                $vLocation = trim($v['location'] ?? $v['rack_shelf'] ?? '');
                $vStock = (float) ($v['stock'] ?? $v['stock_quantity'] ?? 0);
                $vConv = (float) ($v['conv_factor'] ?? ($product->pieces_per_box ?: 1));
                if ($vConv <= 0) $vConv = 1;

                $vWeight = (float) ($v['weight_per_piece'] ?? ($product->weight_per_piece ?: 0));
                $vSale = (float) ($v['sale_price'] ?? ($product->sale_price_per_piece ?: 0));
                $vCost = (float) ($v['purch_price'] ?? ($product->purchase_price_per_piece ?: 0));
                $vRot = (float) ($v['wholesale_price'] ?? ($product->wholesale_price ?: 0));

                $vSkyPCode = !empty($v['sky_p_code']) ? $v['sky_p_code'] : (!empty($v['p_code']) ? $v['p_code'] : PCodeService::encode($vSale));
                $vRotPCode = !empty($v['rot_p_code']) ? $v['rot_p_code'] : PCodeService::encode($vRot);

                $matrixRows[] = [
                    'row_id' => "prod_{$product->id}_var_{$idx}",
                    'product_id' => $product->id,
                    'variant_index' => $idx,
                    'is_variant' => true,
                    'product_name' => $product->item_name,
                    'item_code' => $product->item_code,
                    'serial_no' => $v['serial_no'] ?? sprintf('%s-%04d', $product->item_code ?: 'ITM', $idx + 1),
                    'ref' => $v['sku'] ?? $product->item_code,
                    'variant_title' => $this->formatVariantTitle($v['name'] ?? '', $product->item_name, $v['size'] ?? '-', $v['color'] ?? '-', $idx),
                    'attributes' => [
                        'size' => $v['size'] ?? '-',
                        'color' => $v['color'] ?? '-',
                    ],
                    'location' => $vLocation,
                    'stock' => $vStock,
                    'conv_factor' => $vConv,
                    'weight_per_piece' => $vWeight,
                    'sky_price' => $vSale,
                    'sky_pcode' => $vSkyPCode,
                    'cost' => $vCost,
                    'rot_price' => $vRot,
                    'rot_pcode' => $vRotPCode,
                    'barcode' => $v['barcode'] ?? $product->barcode_path,
                    'unit' => $product->unit->name ?? 'Pcs',
                ];
            }
        } else {
            $pLocation = trim($product->remarks ?: '');
            $pStock = $whBoxes > 0 ? $whBoxes : (float) ($whTotalPieces > 0 ? $whTotalPieces : 0);
            $pConv = (float) ($product->pieces_per_box ?: 1);
            if ($pConv <= 0) $pConv = 1;

            $pWeight = (float) ($product->weight_per_piece ?: 0);
            $pSale = (float) ($product->sale_price_per_piece ?: 0);
            $pCost = (float) ($product->purchase_price_per_piece ?: 0);
            $pRot = (float) ($product->wholesale_price ?: 0);

            $pSkyPCode = !empty($product->p_code) ? $product->p_code : PCodeService::encode($pSale);
            $pRotPCode = !empty($product->rot_p_code) ? $product->rot_p_code : PCodeService::encode($pRot);

            $matrixRows[] = [
                'row_id' => "prod_{$product->id}_master",
                'product_id' => $product->id,
                'variant_index' => 'master',
                'is_variant' => false,
                'product_name' => $product->item_name,
                'item_code' => $product->item_code,
                'serial_no' => $product->item_code ?: 'ITEM-' . $product->id,
                'ref' => $product->item_code,
                'variant_title' => 'Standard Item',
                'attributes' => [
                    'size' => '-',
                    'color' => '-',
                ],
                'location' => $pLocation,
                'stock' => $pStock,
                'conv_factor' => $pConv,
                'weight_per_piece' => $pWeight,
                'sky_price' => $pSale,
                'sky_pcode' => $pSkyPCode,
                'cost' => $pCost,
                'rot_price' => $pRot,
                'rot_pcode' => $pRotPCode,
                'barcode' => $product->barcode_path,
                'unit' => $product->unit->name ?? 'Pcs',
            ];
        }

        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'name' => $product->item_name,
                'code' => $product->item_code ?: 'NO-CODE',
                'barcode' => $product->barcode_path,
                'category' => $product->category_relation ? $product->category_relation->name : '-',
                'brand' => $product->brand ? $product->brand->name : '-',
                'unit' => $product->unit ? $product->unit->name : 'Pcs',
                'wh_total_pieces' => $whTotalPieces,
                'wh_boxes' => $whBoxes,
                'has_variants' => $hasVariants,
                'edit_url' => route('products.edit', $product->id),
            ],
            'rows' => $matrixRows,
        ]);
    }

    /**
     * Save a single row via AJAX.
     */
    public function saveRow(Request $request)
    {
        $this->checkPermission();

        $request->validate([
            'warehouse_id'     => 'required|exists:warehouses,id',
            'product_id'       => 'required|exists:products,id',
            'variant_index'    => 'required',
            'location'         => 'nullable|string|max:191',
            'stock'            => 'nullable|numeric|min:0',
            'conv_factor'      => 'nullable|numeric|min:0.01',
            'weight_per_piece' => 'nullable|numeric|min:0',
            'sky_price'        => 'nullable|numeric|min:0',
            'cost'             => 'nullable|numeric|min:0',
            'rot_price'        => 'nullable|numeric|min:0',
        ]);

        $warehouseId = (int) $request->warehouse_id;
        $productId = (int) $request->product_id;
        $variantIdx = $request->variant_index;

        $location = trim($request->location ?? '');
        $stock = (float) ($request->stock ?? 0);
        $convFactor = (float) ($request->conv_factor ?? 1);
        if ($convFactor <= 0) $convFactor = 1;

        $weight = (float) ($request->weight_per_piece ?? 0);
        $skyPrice = (float) ($request->sky_price ?? 0);
        $cost = (float) ($request->cost ?? 0);
        $rotPrice = (float) ($request->rot_price ?? 0);

        $skyPCode = PCodeService::encode($skyPrice);
        $rotPCode = PCodeService::encode($rotPrice);

        try {
            DB::transaction(function () use ($productId, $warehouseId, $variantIdx, $location, $stock, $convFactor, $weight, $skyPrice, $cost, $rotPrice, $skyPCode, $rotPCode) {
                $product = Product::lockForUpdate()->findOrFail($productId);
                $userId = Auth::id() ?: 1;

                if ($variantIdx !== 'master') {
                    // Update variant in color JSON
                    $variants = [];
                    if (!empty($product->color)) {
                        $parsed = is_string($product->color) ? json_decode($product->color, true) : $product->color;
                        if (is_array($parsed)) {
                            $variants = $parsed;
                        }
                    }

                    $idx = (int) $variantIdx;
                    if (isset($variants[$idx])) {
                        $variants[$idx]['location'] = $location;
                        $variants[$idx]['stock'] = $stock;
                        $variants[$idx]['conv_factor'] = $convFactor;
                        $variants[$idx]['weight_per_piece'] = $weight;
                        $variants[$idx]['sale_price'] = $skyPrice;
                        $variants[$idx]['purch_price'] = $cost;
                        $variants[$idx]['wholesale_price'] = $rotPrice;
                        $variants[$idx]['p_code'] = $skyPCode;
                        $variants[$idx]['sky_p_code'] = $skyPCode;
                        $variants[$idx]['rot_p_code'] = $rotPCode;

                        $product->color = json_encode($variants);

                        // If base variant or first variant, keep product root prices updated
                        if ($idx === 0 || !empty($variants[$idx]['is_base_variant'])) {
                            $product->sale_price_per_piece = $skyPrice;
                            $product->purchase_price_per_piece = $cost;
                            $product->wholesale_price = $rotPrice;
                            $product->p_code = $skyPCode;
                            $product->rot_p_code = $rotPCode;
                            $product->pieces_per_box = $convFactor;
                            $product->weight_per_piece = $weight;
                        }
                    }

                    $product->save();

                    // Recalculate total pieces for all variants
                    $totalVariantPieces = 0;
                    foreach ($variants as $v) {
                        $vQty = (float) ($v['stock'] ?? 0);
                        $vConv = (float) ($v['conv_factor'] ?? 1);
                        if ($vConv <= 0) $vConv = 1;
                        $totalVariantPieces += ($vQty * $vConv);
                    }

                    $whStock = WarehouseStock::firstOrNew([
                        'warehouse_id' => $warehouseId,
                        'product_id'   => $productId,
                    ]);

                    $oldPieces = (float) ($whStock->total_pieces ?? 0);
                    $whStock->total_pieces = $totalVariantPieces;
                    $whStock->quantity = $convFactor > 0 ? floor($totalVariantPieces / $convFactor) : $totalVariantPieces;
                    $whStock->remarks = !empty($location) ? $location : ($whStock->remarks ?: 'Opening Stock Entry');
                    $whStock->save();

                    // Log stock movement if stock changed
                    $delta = $totalVariantPieces - $oldPieces;
                    if ($delta != 0) {
                        DB::table('stock_movements')->insert([
                            'product_id'   => $productId,
                            'type'         => 'opening_stock',
                            'qty'          => $delta,
                            'ref_type'     => 'OPENING_STOCK',
                            'ref_id'       => $productId,
                            'note'         => "Opening Stock updated for variant {$idx} (Qty: {$stock})",
                            'created_at'   => now(),
                            'updated_at'   => now(),
                        ]);
                    }
                } else {
                    // Standard product without variants
                    $product->sale_price_per_piece = $skyPrice;
                    $product->purchase_price_per_piece = $cost;
                    $product->wholesale_price = $rotPrice;
                    $product->p_code = $skyPCode;
                    $product->rot_p_code = $rotPCode;
                    $product->pieces_per_box = $convFactor;
                    $product->weight_per_piece = $weight;
                    $product->remarks = $location;
                    $product->save();

                    $whStock = WarehouseStock::firstOrNew([
                        'warehouse_id' => $warehouseId,
                        'product_id'   => $productId,
                    ]);

                    $oldPieces = (float) ($whStock->total_pieces ?? 0);
                    $newTotalPieces = $stock * $convFactor;
                    $whStock->total_pieces = $newTotalPieces;
                    $whStock->quantity = $stock;
                    $whStock->remarks = !empty($location) ? $location : ($whStock->remarks ?: 'Opening Stock Entry');
                    $whStock->save();

                    $delta = $newTotalPieces - $oldPieces;
                    if ($delta != 0) {
                        DB::table('stock_movements')->insert([
                            'product_id'   => $productId,
                            'type'         => 'opening_stock',
                            'qty'          => $delta,
                            'ref_type'     => 'OPENING_STOCK',
                            'ref_id'       => $productId,
                            'note'         => "Opening Stock updated (Qty: {$stock})",
                            'created_at'   => now(),
                            'updated_at'   => now(),
                        ]);
                    }
                }
            });

            return response()->json([
                'success'    => true,
                'message'    => 'Opening stock and pricing saved successfully!',
                'sky_pcode'  => $skyPCode,
                'rot_pcode'  => $rotPCode,
                'location'   => $location,
                'stock'      => $stock,
            ]);

        } catch (\Exception $e) {
            Log::error('Error saving opening stock row: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to save opening stock: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Batch save multiple rows at once.
     */
    public function saveBatch(Request $request)
    {
        $this->checkPermission();

        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'rows'         => 'required|array|min:1',
        ]);

        $warehouseId = (int) $request->warehouse_id;
        $rows = $request->rows;
        $updatedCount = 0;

        try {
            DB::transaction(function () use ($warehouseId, $rows, &$updatedCount) {
                // Group rows by product_id to minimize writes
                $grouped = [];
                foreach ($rows as $r) {
                    $pId = (int) ($r['product_id'] ?? 0);
                    if ($pId > 0) {
                        $grouped[$pId][] = $r;
                    }
                }

                foreach ($grouped as $productId => $productRows) {
                    $product = Product::lockForUpdate()->find($productId);
                    if (!$product) continue;

                    $variants = [];
                    if (!empty($product->color)) {
                        $parsed = is_string($product->color) ? json_decode($product->color, true) : $product->color;
                        if (is_array($parsed)) {
                            $variants = $parsed;
                        }
                    }

                    $hasVariants = count($variants) > 0;
                    $isMasterUpdated = false;

                    foreach ($productRows as $row) {
                        $variantIdx = $row['variant_index'] ?? 'master';
                        $location = trim($row['location'] ?? '');
                        $stock = (float) ($row['stock'] ?? 0);
                        $convFactor = (float) ($row['conv_factor'] ?? 1);
                        if ($convFactor <= 0) $convFactor = 1;

                        $weight = (float) ($row['weight_per_piece'] ?? 0);
                        $skyPrice = (float) ($row['sky_price'] ?? 0);
                        $cost = (float) ($row['cost'] ?? 0);
                        $rotPrice = (float) ($row['rot_price'] ?? 0);

                        $skyPCode = PCodeService::encode($skyPrice);
                        $rotPCode = PCodeService::encode($rotPrice);

                        if ($variantIdx !== 'master' && $hasVariants) {
                            $idx = (int) $variantIdx;
                            if (isset($variants[$idx])) {
                                $variants[$idx]['location'] = $location;
                                $variants[$idx]['stock'] = $stock;
                                $variants[$idx]['conv_factor'] = $convFactor;
                                $variants[$idx]['weight_per_piece'] = $weight;
                                $variants[$idx]['sale_price'] = $skyPrice;
                                $variants[$idx]['purch_price'] = $cost;
                                $variants[$idx]['wholesale_price'] = $rotPrice;
                                $variants[$idx]['p_code'] = $skyPCode;
                                $variants[$idx]['sky_p_code'] = $skyPCode;
                                $variants[$idx]['rot_p_code'] = $rotPCode;

                                if ($idx === 0 || !empty($variants[$idx]['is_base_variant'])) {
                                    $product->sale_price_per_piece = $skyPrice;
                                    $product->purchase_price_per_piece = $cost;
                                    $product->wholesale_price = $rotPrice;
                                    $product->p_code = $skyPCode;
                                    $product->rot_p_code = $rotPCode;
                                    $product->pieces_per_box = $convFactor;
                                    $product->weight_per_piece = $weight;
                                    $isMasterUpdated = true;
                                }
                            }
                        } else {
                            $product->sale_price_per_piece = $skyPrice;
                            $product->purchase_price_per_piece = $cost;
                            $product->wholesale_price = $rotPrice;
                            $product->p_code = $skyPCode;
                            $product->rot_p_code = $rotPCode;
                            $product->pieces_per_box = $convFactor;
                            $product->weight_per_piece = $weight;
                            $product->remarks = $location;
                            $isMasterUpdated = true;

                            // Non-variant warehouse stock calculation
                            $whStock = WarehouseStock::firstOrNew([
                                'warehouse_id' => $warehouseId,
                                'product_id'   => $productId,
                            ]);
                            $oldPieces = (float) ($whStock->total_pieces ?? 0);
                            $newPieces = $stock * $convFactor;
                            $whStock->total_pieces = $newPieces;
                            $whStock->quantity = $stock;
                            $whStock->remarks = !empty($location) ? $location : ($whStock->remarks ?: 'Opening Stock Batch');
                            $whStock->save();

                            $delta = $newPieces - $oldPieces;
                            if ($delta != 0) {
                                DB::table('stock_movements')->insert([
                                    'product_id'   => $productId,
                                    'type'         => 'opening_stock',
                                    'qty'          => $delta,
                                    'ref_type'     => 'OPENING_STOCK',
                                    'ref_id'       => $productId,
                                    'note'         => "Opening Stock batch update (Qty: {$stock})",
                                    'created_at'   => now(),
                                    'updated_at'   => now(),
                                ]);
                            }
                        }
                        $updatedCount++;
                    }

                    if ($hasVariants) {
                        $product->color = json_encode($variants);
                        $product->save();

                        // Sum variant pieces
                        $totalVariantPieces = 0;
                        $firstConv = 1;
                        foreach ($variants as $v) {
                            $vQty = (float) ($v['stock'] ?? 0);
                            $vConv = (float) ($v['conv_factor'] ?? 1);
                            if ($vConv <= 0) $vConv = 1;
                            $totalVariantPieces += ($vQty * $vConv);
                            if ($firstConv === 1 && $vConv > 1) $firstConv = $vConv;
                        }

                        $whStock = WarehouseStock::firstOrNew([
                            'warehouse_id' => $warehouseId,
                            'product_id'   => $productId,
                        ]);
                        $oldPieces = (float) ($whStock->total_pieces ?? 0);
                        $whStock->total_pieces = $totalVariantPieces;
                        $whStock->quantity = $firstConv > 0 ? floor($totalVariantPieces / $firstConv) : $totalVariantPieces;
                        $whStock->save();

                        $delta = $totalVariantPieces - $oldPieces;
                        if ($delta != 0) {
                            DB::table('stock_movements')->insert([
                                'product_id'   => $productId,
                                'type'         => 'opening_stock',
                                'qty'          => $delta,
                                'ref_type'     => 'OPENING_STOCK',
                                'ref_id'       => $productId,
                                'note'         => "Opening Stock batch update for variants (Total: {$totalVariantPieces})",
                                'created_at'   => now(),
                                'updated_at'   => now(),
                            ]);
                        }
                    } else if ($isMasterUpdated) {
                        $product->save();
                    }
                }
            });

            return response()->json([
                'success' => true,
                'message' => "Successfully updated {$updatedCount} item(s) opening stock and pricing!",
            ]);

        } catch (\Exception $e) {
            Log::error('Error batch saving opening stock: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Batch save failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Resolves a clean, professional variant display title without repetitive product names or raw brackets.
     */
    private function formatVariantTitle($vName, $productName, $size = '', $color = '', $idx = 0)
    {
        $rawName = trim((string) $vName);
        $prodName = trim((string) $productName);

        // Remove product name if it was prefixed into the variant name
        $clean = $rawName;
        if (!empty($prodName)) {
            $clean = trim(str_ireplace($prodName, '', $clean));
        }

        // Match and strip outermost parentheses e.g. "(Standard / 1/2 / 1)"
        if (preg_match('/^\((.*)\)$/', trim($clean), $m)) {
            $clean = trim($m[1]);
        }
        $clean = trim($clean, " -/():");

        // If slash-separated combinations e.g. "Standard / 1/2 / 1"
        if (strpos($clean, '/') !== false) {
            $parts = array_map('trim', explode('/', $clean));
            $filteredParts = [];

            $normSize = strtolower(trim((string)$size));
            $normColor = strtolower(trim((string)$color));

            foreach ($parts as $p) {
                $normP = strtolower($p);
                // Exclude parts that duplicate the separate Size and Color badges
                if (($normSize !== '' && $normSize !== '-' && $normP === $normSize) ||
                    ($normColor !== '' && $normColor !== '-' && $normP === $normColor)) {
                    continue;
                }
                $filteredParts[] = $p;
            }

            if (!empty($filteredParts)) {
                $clean = implode(' • ', $filteredParts);
            } else {
                $clean = implode(' • ', $parts);
            }
        }

        // Fallback if empty or identical to product name
        if (empty($clean) || strtolower($clean) === strtolower($prodName)) {
            $attrParts = [];
            if (!empty($size) && $size !== '-') $attrParts[] = $size;
            if (!empty($color) && $color !== '-') $attrParts[] = $color;

            if (!empty($attrParts)) {
                $clean = implode(' • ', $attrParts);
            } else {
                $clean = 'Variant ' . ($idx + 1);
            }
        }

        return ucwords(strtolower($clean));
    }
}
