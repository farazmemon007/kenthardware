<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Product;
use App\Models\StorageLocation;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use App\Services\StockEntryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseStockController extends Controller
{
    protected $stockService;

    public function __construct(StockEntryService $stockService)
    {
        $this->stockService = $stockService;
    }

    // /////////
    public function getByWarehouse($warehouseId)
    {
        $products = WarehouseStock::with('product')
            ->where('warehouse_id', $warehouseId)
            ->get()
            ->map(function ($row) {
                return [
                    'id' => $row->product->id,
                    'name' => $row->product->item_name,
                    'qty' => $row->quantity,
                ];
            });

        return response()->json($products);
    }

    // ////////////////

    public function searchWarehouses(Request $request)
    {
        $term = $request->get('q', '');

        $warehouses = Warehouse::query()
            ->select('id', 'warehouse_name')
            ->when($term, function ($query) use ($term) {
                $query->where('warehouse_name', 'like', "%{$term}%");
            })
            ->limit(20)
            ->get();

        return response()->json($warehouses->map(function ($w) {
            return [
                'id' => $w->id,
                'text' => $w->warehouse_name,
            ];
        }));
    }

    public function index()
    {
        $stocks = WarehouseStock::with('warehouse', 'product')->latest()->paginate(15);

        $warehouses = Warehouse::orderBy('warehouse_name')->get();
        $branches = Branch::orderBy('name')->get();
        $storageLocations = StorageLocation::with(['warehouse', 'branch'])->orderBy('name')->get();
        $products = Product::select('id', 'item_name', 'item_code', 'pieces_per_box', 'image', 'color', 'unit_id')
            ->with('unit')
            ->get();

        $locationsData = [];
        foreach ($storageLocations as $loc) {
            $locationsData[$loc->id] = [
                'location' => $loc,
                'items' => [],
                'total_pieces' => 0,
                'total_boxes' => 0,
            ];
        }

        $unassignedWarehouseItems = [];
        $unassignedShopItems = [];

        foreach ($products as $p) {
            $colors = is_string($p->color) ? json_decode($p->color, true) : $p->color;
            if (!is_array($colors)) {
                continue;
            }

            $unitName = $p->unit ? $p->unit->name : 'Pcs';
            $ppb = (int) ($p->pieces_per_box ?? 0);

            foreach ($colors as $c) {
                $locStr = trim($c['location'] ?? '');
                $qty = (float) ($c['stock'] ?? $c['stock_quantity'] ?? 0);
                $matchedLocId = null;

                if ($locStr !== '') {
                    $locLower = strtolower($locStr);
                    foreach ($storageLocations as $loc) {
                        $nameLower = strtolower(trim($loc->name));
                        $codeLower = $loc->code ? strtolower(trim($loc->code)) : '';
                        $comboLower = $codeLower ? "{$nameLower} ({$codeLower})" : $nameLower;

                        if ($locLower === $nameLower ||
                            ($codeLower && $locLower === $codeLower) ||
                            $locLower === $comboLower ||
                            str_contains($locLower, $nameLower)) {
                            $matchedLocId = $loc->id;
                            break;
                        }
                    }
                }

                $boxCount = 0;
                $looseCount = $qty;
                if ($ppb > 0 && $qty > 0) {
                    $boxCount = floor($qty / $ppb);
                    $looseCount = $qty % $ppb;
                }

                $itemData = [
                    'product_id' => $p->id,
                    'product_name' => $p->item_name,
                    'product_code' => $p->item_code,
                    'product_image' => $p->image,
                    'variant_name' => $c['name'] ?? ($c['color'] ?? 'Standard'),
                    'size' => $c['size'] ?? '',
                    'color' => $c['color'] ?? '',
                    'barcode' => $c['barcode'] ?? '',
                    'serial_no' => $c['serial_no'] ?? '',
                    'stock' => $qty,
                    'boxes' => $boxCount,
                    'loose' => $looseCount,
                    'pieces_per_box' => $ppb,
                    'unit' => $c['unit'] ?? $unitName,
                    'location_str' => $locStr,
                ];

                if ($matchedLocId && isset($locationsData[$matchedLocId])) {
                    $locationsData[$matchedLocId]['items'][] = $itemData;
                    $locationsData[$matchedLocId]['total_pieces'] += $qty;
                    $locationsData[$matchedLocId]['total_boxes'] += $boxCount;
                } elseif ($locStr !== '' || $qty > 0) {
                    if (stripos($locStr, 'shelf') !== false) {
                        $unassignedShopItems[] = $itemData;
                    } else {
                        $unassignedWarehouseItems[] = $itemData;
                    }
                }
            }
        }

        // Group into warehouses
        $warehouseGroups = [];
        $totalWarehouseStock = 0;
        $totalWarehouseBoxes = 0;
        $totalRacksCount = 0;

        foreach ($warehouses as $wh) {
            $whRacks = [];
            $whPieces = 0;
            $whBoxes = 0;
            foreach ($locationsData as $locId => $data) {
                if ($data['location']->type === 'warehouse_rack' && $data['location']->warehouse_id == $wh->id) {
                    $whRacks[] = $data;
                    $whPieces += $data['total_pieces'];
                    $whBoxes += $data['total_boxes'];
                    $totalRacksCount++;
                }
            }
            $warehouseGroups[] = [
                'warehouse' => $wh,
                'racks' => $whRacks,
                'total_pieces' => $whPieces,
                'total_boxes' => $whBoxes,
            ];
            $totalWarehouseStock += $whPieces;
            $totalWarehouseBoxes += $whBoxes;
        }

        // Include any warehouse racks not tied to a specific warehouse
        $unassignedRacks = [];
        foreach ($locationsData as $locId => $data) {
            if ($data['location']->type === 'warehouse_rack' && empty($data['location']->warehouse_id)) {
                $unassignedRacks[] = $data;
                $totalWarehouseStock += $data['total_pieces'];
                $totalWarehouseBoxes += $data['total_boxes'];
                $totalRacksCount++;
            }
        }
        if (!empty($unassignedRacks)) {
            $warehouseGroups[] = [
                'warehouse' => (object) ['id' => 0, 'warehouse_name' => 'General / Other Racks'],
                'racks' => $unassignedRacks,
                'total_pieces' => array_sum(array_column($unassignedRacks, 'total_pieces')),
                'total_boxes' => array_sum(array_column($unassignedRacks, 'total_boxes')),
            ];
        }

        // Group into shops / branches
        $shopGroups = [];
        $totalShopStock = 0;
        $totalShopBoxes = 0;
        $totalShelvesCount = 0;

        $processedShelfIds = [];
        foreach ($branches as $branch) {
            $shopShelves = [];
            $shopPieces = 0;
            $shopBoxes = 0;
            foreach ($locationsData as $locId => $data) {
                if ($data['location']->type === 'shop_shelf' && $data['location']->branch_id == $branch->id) {
                    $shopShelves[] = $data;
                    $shopPieces += $data['total_pieces'];
                    $shopBoxes += $data['total_boxes'];
                    $totalShelvesCount++;
                    $processedShelfIds[] = $locId;
                }
            }
            $shopGroups[] = [
                'branch' => $branch,
                'shelves' => $shopShelves,
                'total_pieces' => $shopPieces,
                'total_boxes' => $shopBoxes,
            ];
            $totalShopStock += $shopPieces;
            $totalShopBoxes += $shopBoxes;
        }

        // Catch any shelves without assigned branch
        $unassignedShelves = [];
        foreach ($locationsData as $locId => $data) {
            if ($data['location']->type === 'shop_shelf' && !in_array($locId, $processedShelfIds)) {
                $unassignedShelves[] = $data;
                $totalShopStock += $data['total_pieces'];
                $totalShopBoxes += $data['total_boxes'];
                $totalShelvesCount++;
            }
        }
        if (!empty($unassignedShelves)) {
            if (!empty($shopGroups)) {
                // If we have a branch, add to the first branch or separate general group
                $shopGroups[0]['shelves'] = array_merge($shopGroups[0]['shelves'], $unassignedShelves);
                $shopGroups[0]['total_pieces'] += array_sum(array_column($unassignedShelves, 'total_pieces'));
                $shopGroups[0]['total_boxes'] += array_sum(array_column($unassignedShelves, 'total_boxes'));
            } else {
                $shopGroups[] = [
                    'branch' => (object) ['id' => 0, 'name' => 'Main Shop'],
                    'shelves' => $unassignedShelves,
                    'total_pieces' => array_sum(array_column($unassignedShelves, 'total_pieces')),
                    'total_boxes' => array_sum(array_column($unassignedShelves, 'total_boxes')),
                ];
            }
        }

        // Total stocked variants
        $totalStockedVariants = 0;
        foreach ($locationsData as $d) {
            foreach ($d['items'] as $item) {
                if ($item['stock'] > 0) {
                    $totalStockedVariants++;
                }
            }
        }
        foreach ($unassignedWarehouseItems as $it) {
            if ($it['stock'] > 0) {
                $totalStockedVariants++;
            }
        }
        foreach ($unassignedShopItems as $it) {
            if ($it['stock'] > 0) {
                $totalStockedVariants++;
            }
        }

        return view('admin_panel.warehouses.warehouse_stocks.index', compact(
            'stocks',
            'warehouses',
            'branches',
            'warehouseGroups',
            'shopGroups',
            'unassignedWarehouseItems',
            'unassignedShopItems',
            'totalWarehouseStock',
            'totalWarehouseBoxes',
            'totalShopStock',
            'totalShopBoxes',
            'totalRacksCount',
            'totalShelvesCount',
            'totalStockedVariants'
        ));
    }

    public function show($id)
    {
        return redirect()->route('warehouse_stocks.index');
    }

    // Removed create() and edit() pages as per request

    public function store(Request $request)
    {
        $validated = $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'product_id' => 'required|exists:products,id',
            'total_pieces' => 'required|integer|min:0',
            'total_box' => 'required|integer|min:0',
            'remarks' => 'nullable|string|max:255',
        ]);

        try {
            $this->stockService->addStock(
                $validated['warehouse_id'],
                $validated['product_id'],
                $validated['total_pieces'],
                $validated['total_box'],
                $request->input('remarks')
            );

            return response()->json([
                'success' => true,
                'message' => 'Stock added successfully!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error adding stock: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get data for editing a stock record via AJAX.
     * This allows us to populate the same modal for editing.
     */
    public function editData($id)
    {
        $stock = WarehouseStock::with(['product', 'warehouse'])->findOrFail($id);

        $piecesPerBox = $stock->product->pieces_per_box ?? 0;

        // Calculate current boxes/loose for display if needed
        $boxes = 0;
        $loose = $stock->total_pieces;

        if ($piecesPerBox > 0) {
            $boxes = floor($stock->total_pieces / $piecesPerBox);
            $loose = $stock->total_pieces % $piecesPerBox;
        }

        return response()->json([
            'id' => $stock->id,
            'warehouse_id' => $stock->warehouse_id,
            'warehouse_name' => $stock->warehouse->warehouse_name ?? '',
            'product_id' => $stock->product_id,
            'product_name' => $stock->product->item_name,
            'product_code' => $stock->product->item_code,
            'pieces_per_box' => $piecesPerBox,
            'total_pieces' => $stock->total_pieces,
            'image' => $stock->product->image ? asset('uploads/products/'.$stock->product->image) : null,
            'remarks' => $stock->remarks,
            // For editing, we might want to let them adjust the total quantity directly or add to it.
            // But usually "Edit" means setting the state.
            // However, the prompt implies "Add Stock" modal style for both.
            // If it is a true "Edit", we usually allow changing the absolute value.
            // Given the context of "Add Stock" modal, we will pre-fill getting ready for an *update*.
            // Since the user asked for "Edit" to open the "same model", we'll treat it as managing that record.
        ]);
    }

    public function update(Request $request, $id)
    {
        // For now, let's assume 'update' means adjusting the stock to a new value OR adding to it.
        // If we reuse the "Add Stock" modal logic, it usually *adds* to stock.
        // But "Edit" button implies changing the existing record.
        // Let's implement a standard update that *sets* the value, but logs the difference.

        $warehouseStock = WarehouseStock::findOrFail($id);

        $request->validate([
            'total_pieces' => 'required|integer|min:0',
            'remarks' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $warehouseStock) {
            $oldQty = $warehouseStock->total_pieces;
            $newQty = $request->total_pieces;
            $delta = $newQty - $oldQty;

            if ($delta != 0) {
                // Update
                $warehouseStock->total_pieces = $newQty;
                $warehouseStock->remarks = $request->remarks;
                $warehouseStock->save();

                // Log Movement
                DB::table('stock_movements')->insert([
                    'product_id' => $warehouseStock->product_id,
                    'warehouse_id' => $warehouseStock->warehouse_id,
                    'type' => 'adjustment', // 'adjustment' for edit
                    'qty' => $delta, // Can be negative
                    'ref_type' => 'MANUAL_EDIT_MODAL',
                    'ref_id' => $warehouseStock->id,
                    'note' => 'Manual Stock Edit via Modal',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Stock updated successfully!',
        ]);
    }

    public function destroy(WarehouseStock $warehouseStock)
    {
        $warehouseStock->delete();

        return back()->with('success', 'Stock deleted successfully.');
    }

    // --- AJAX Methods ---

    public function searchProducts(Request $request)
    {
        $term = $request->get('q', '');

        $products = Product::query()
            ->select('id', 'item_name', 'item_code', 'pieces_per_box', 'image')
            ->when($term, function ($query) use ($term) {
                $query->where('item_name', 'like', "%{$term}%")
                    ->orWhere('item_code', 'like', "%{$term}%");
            })
            ->limit(20)
            ->get();

        return response()->json($products->map(function ($p) {
            return [
                'id' => $p->id,
                'text' => "{$p->item_code} - {$p->item_name}",
                'item_name' => $p->item_name,
                'item_code' => $p->item_code,
                'pieces_per_box' => $p->pieces_per_box ?? 0,
                'image' => $p->image ? asset('uploads/products/'.$p->image) : null,
            ];
        }));
    }

    public function getWarehouseStock(Request $request)
    {
        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'product_id' => 'required|exists:products,id',
        ]);

        $stock = WarehouseStock::where('warehouse_id', $request->warehouse_id)
            ->where('product_id', $request->product_id)
            ->first();

        return response()->json([
            'total_pieces' => $stock ? $stock->total_pieces : 0,
        ]);
    }
}
