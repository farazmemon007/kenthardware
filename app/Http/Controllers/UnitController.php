<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
class UnitController extends Controller
{
    public function index()
    {
        $unit = Unit::orderBy('id', 'desc')->get();
        return view("admin_panel.unit.index", compact('unit'));
    }

    public function show($id)
    {
        $unit = Unit::findOrFail($id);
        return response()->json($unit);
    }

    public function store(request $request){
        $name = trim($request->name ?? '');

        // If from product page or Quick Add, and unit already exists (case-insensitive)
        if (($request->page === 'product_page' || $request->is_quick_add) && empty($request->edit_id)) {
            $existing = Unit::whereRaw('LOWER(TRIM(name)) = ?', [strtolower($name)])->first();
            if ($existing) {
                return response()->json([
                    'status' => 'success',
                    'success' => "Unit '{$existing->name}' already exists and has been selected.",
                    'unit' => $existing,
                    'id' => $existing->id,
                    'name' => $existing->name
                ]);
            }
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:units,name,'.$request->edit_id,
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], 422);
            }
            return ['errors' => $validator->errors()];
        }

        if($request->has('edit_id') && $request->edit_id != '' && $request->edit_id != null ){
            $Company = Unit::find($request->edit_id);
            $msg = [
                'status' => 'success',
                'success' => 'Unit Updated Successfully',
                'reload' => true
            ];
        }
        else{
            $Company = new Unit();
            $msg = [
                'status' => 'success',
                'success' => 'Unit Created Successfully',
                'redirect' => route('Unit.home')
            ];
        }

        $baseUnit = trim($request->base_unit ?? '') ?: $name;
        $unitType = trim($request->unit_type ?? '') ?: 'Count';

        // Parse Multiple Units of Measure (QuickBooks POS UOM Set)
        $uomData = null;
        if ($request->has('uom_data') && !empty($request->uom_data)) {
            $uomData = is_array($request->uom_data) ? $request->uom_data : json_decode($request->uom_data, true);
        } elseif ($request->has('related_names') && is_array($request->related_names)) {
            $unitsList = [];
            $ratios = $request->related_ratios ?? [];
            foreach ($request->related_names as $idx => $rName) {
                $rName = trim($rName ?? '');
                $rRatio = (float) ($ratios[$idx] ?? 0);
                if ($rName && $rRatio > 0) {
                    $unitsList[] = [
                        'name' => $rName,
                        'ratio' => $rRatio
                    ];
                }
            }
            if (!empty($unitsList)) {
                $uomData = [
                    'base_unit' => $baseUnit,
                    'order_unit' => trim($request->order_unit ?? '') ?: $baseUnit,
                    'sell_unit' => trim($request->sell_unit ?? '') ?: $baseUnit,
                    'units' => $unitsList
                ];
            }
        }

        $Company->name = $name;
        $Company->base_unit = $baseUnit;
        $Company->unit_type = $unitType;
        $Company->uom_data = $uomData;
        $Company->save();

        $msg['unit'] = $Company;
        $msg['id'] = $Company->id;
        $msg['name'] = $Company->name;

        return response()->json($msg);
    }

    public function delete($id)
    {

        $company = Unit::find($id);
        if ($company) {
            $company->delete();
            $msg = [
                'success' => 'Unit Deleted Successfully',
                'reload' =>  route('Unit.home'),
            ];
        } else {
            $msg = ['error' => 'Unit Not Found'];
        }
        return response()->json($msg);
    }
}
