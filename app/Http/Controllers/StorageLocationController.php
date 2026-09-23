<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\StorageLocation;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StorageLocationController extends Controller
{
    /**
     * Display a listing of Warehouse Racks and Shop Shelves.
     */
    public function index(Request $request)
    {
        $warehouses = Warehouse::orderBy('warehouse_name')->get();
        $branches = Branch::orderBy('name')->get();

        $racks = StorageLocation::with(['warehouse', 'creator'])
            ->where('type', 'warehouse_rack')
            ->latest()
            ->get();

        $shelves = StorageLocation::with(['branch', 'creator'])
            ->where('type', 'shop_shelf')
            ->latest()
            ->get();

        return view('admin_panel.storage_locations.index', compact('warehouses', 'branches', 'racks', 'shelves'));
    }

    /**
     * Store or update a storage location (Rack or Shelf).
     */
    public function store(Request $request)
    {
        $id = $request->input('id');
        $type = $request->input('type', 'warehouse_rack');

        $rules = [
            'type' => 'required|in:warehouse_rack,shop_shelf',
            'name' => 'required|string|max:191',
            'code' => 'nullable|string|max:100',
            'zone_or_aisle' => 'nullable|string|max:191',
            'description' => 'nullable|string|max:1000',
        ];

        if ($type === 'warehouse_rack') {
            $rules['warehouse_id'] = 'required|exists:warehouses,id';
        } else {
            $rules['branch_id'] = 'required|exists:branches,id';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        // Duplicate entry check
        $query = StorageLocation::where('type', $type)
            ->where('name', trim($request->name));

        if ($id) {
            $query->where('id', '!=', $id);
        }

        if ($type === 'warehouse_rack') {
            $query->where('warehouse_id', $request->warehouse_id);
            $alreadyExists = $query->exists();
            if ($alreadyExists) {
                $msg = 'This Rack name already exists in this Warehouse.';
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['errors' => ['name' => [$msg]]], 422);
                }
                return back()->withErrors(['name' => $msg])->withInput();
            }
        } else {
            $query->where('branch_id', $request->branch_id);
            $alreadyExists = $query->exists();
            if ($alreadyExists) {
                $msg = 'This Shelf name already exists in this Shop / Branch.';
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['errors' => ['name' => [$msg]]], 422);
                }
                return back()->withErrors(['name' => $msg])->withInput();
            }
        }

        $data = [
            'type' => $type,
            'name' => trim($request->name),
            'code' => $request->code ? trim($request->code) : null,
            'zone_or_aisle' => $request->zone_or_aisle ? trim($request->zone_or_aisle) : null,
            'description' => $request->description ? trim($request->description) : null,
            'warehouse_id' => $type === 'warehouse_rack' ? $request->warehouse_id : null,
            'branch_id' => $type === 'shop_shelf' ? $request->branch_id : null,
        ];

        if ($id) {
            $location = StorageLocation::findOrFail($id);
            $location->update($data);
            $successMsg = $type === 'warehouse_rack' ? 'Warehouse Rack updated successfully!' : 'Shop Shelf updated successfully!';
        } else {
            $data['creater_id'] = Auth::id();
            $location = StorageLocation::create($data);
            $successMsg = $type === 'warehouse_rack' ? 'Warehouse Rack created successfully!' : 'Shop Shelf created successfully!';
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => $successMsg,
                'location' => $location,
                'reload' => true,
            ]);
        }

        return back()->with('success', $successMsg);
    }

    /**
     * Delete a storage location.
     */
    public function delete($id)
    {
        $location = StorageLocation::findOrFail($id);
        $typeName = $location->type === 'warehouse_rack' ? 'Warehouse Rack' : 'Shop Shelf';
        $location->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => "{$typeName} deleted successfully!",
                'reload' => true,
            ]);
        }

        return back()->with('success', "{$typeName} deleted successfully!");
    }

    /**
     * Get JSON array of all active racks and shelves for variant inputs/datalists.
     */
    public function getJson(Request $request)
    {
        $warehouseId = $request->query('warehouse_id');
        $branchId = $request->query('branch_id');

        $query = StorageLocation::with(['warehouse:id,warehouse_name', 'branch:id,name']);

        if ($warehouseId) {
            $query->where(function($q) use ($warehouseId) {
                $q->where('warehouse_id', $warehouseId)
                  ->orWhereNull('warehouse_id');
            });
        }

        if ($branchId) {
            $query->where(function($q) use ($branchId) {
                $q->where('branch_id', $branchId)
                  ->orWhereNull('branch_id');
            });
        }

        $allLocations = $query->orderBy('name')->get();

        $formatted = $allLocations->map(function ($loc) {
            return [
                'id' => $loc->id,
                'type' => $loc->type,
                'name' => $loc->name,
                'code' => $loc->code,
                'zone_or_aisle' => $loc->zone_or_aisle,
                'parent_id' => $loc->type === 'warehouse_rack' ? $loc->warehouse_id : $loc->branch_id,
                'parent_name' => $loc->type === 'warehouse_rack' 
                    ? ($loc->warehouse ? $loc->warehouse->warehouse_name : 'Warehouse')
                    : ($loc->branch ? $loc->branch->name : 'Shop'),
                'display_label' => $loc->full_display_name,
                // Clean short code or name for fast typing into variant row
                'value' => $loc->code ? "{$loc->name} ({$loc->code})" : $loc->name,
            ];
        });

        return response()->json([
            'locations' => $formatted,
            'racks' => $formatted->where('type', 'warehouse_rack')->values(),
            'shelves' => $formatted->where('type', 'shop_shelf')->values(),
        ]);
    }
}
