<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Hr\Designation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DesignationController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('hr.designations.view')) {
            abort(403, 'Unauthorized action.');
        }
        $designations = Designation::latest()->paginate(12);
        return view('hr.designations.index', compact('designations'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:hr_designations,name,' . $request->edit_id,
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'requires_location' => $request->has('requires_location') ? 1 : 0,
        ];

        if ($request->filled('edit_id')) {
            if (!auth()->user()->can('hr.designations.edit')) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['error' => 'Unauthorized action.'], 403);
                }
                return redirect()->back()->with('error', 'Unauthorized action.');
            }
            $designation = Designation::findOrFail($request->edit_id);
            $designation->update($data);
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => 'Designation Updated Successfully',
                    'reload' => true
                ]);
            }
            return redirect()->route('hr.designations.index')->with('success', 'Designation Updated Successfully');
        } else {
            if (!auth()->user()->can('hr.designations.create')) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['error' => 'Unauthorized action.'], 403);
                }
                return redirect()->back()->with('error', 'Unauthorized action.');
            }
            Designation::create($data);
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => 'Designation Created Successfully',
                    'reload' => true
                ]);
            }
            return redirect()->route('hr.designations.index')->with('success', 'Designation Created Successfully');
        }
    }

    public function destroy(Designation $designation)
    {
        if (!auth()->user()->can('hr.designations.delete')) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['error' => 'Unauthorized action.'], 403);
            }
            return redirect()->back()->with('error', 'Unauthorized action.');
        }
        $designation->delete();
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => 'Designation Deleted Successfully',
                'reload' => true
            ]);
        }
        return redirect()->route('hr.designations.index')->with('success', 'Designation Deleted Successfully');
    }
}
