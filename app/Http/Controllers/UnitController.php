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
        // $userId = Auth::id();
      $unit = Unit::get();
      return  view("admin_panel.unit.index",compact('unit'));


    }

    public function store(request $request){
        $name = trim($request->name ?? '');

        // If from product page or Quick Add, and unit already exists (case-insensitive)
        if (($request->page === 'product_page' || $request->ajax()) && empty($request->edit_id)) {
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


        if($request->has('edit_id') && $request->edit_id != '' || $request->edit_id != null ){
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
        $Company->name = $name;
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
