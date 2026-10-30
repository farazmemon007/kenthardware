<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\CycleCount;
use App\Models\CycleCountItem;
use App\Models\Product;
use App\Models\StockAdjustment;
use App\Models\StorageLocation;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use App\Services\CycleCountClassificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CycleCountController extends Controller
{
    protected $classificationService;

    public function __construct(CycleCountClassificationService $classificationService)
    {
        $this->middleware('auth');
        $this->classificationService = $classificationService;
    }

    /**
     * Image 2: Cycle Count Schedule (Owner / In-charge overview)
     */
    public function index(Request $request)
    {
        $query = CycleCount::with(['location', 'counter', 'approver', 'warehouse', 'branch'])
            ->orderBy('count_date', 'desc')
            ->orderBy('id', 'desc');

        // Week filter
        $weekFilter = $request->input('week', 'all');
        if ($weekFilter === 'this_week') {
            $query->whereBetween('count_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($weekFilter === 'last_week') {
            $query->whereBetween('count_date', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()]);
        } elseif ($weekFilter === 'next_week') {
            $query->whereBetween('count_date', [Carbon::now()->addWeek()->startOfWeek(), Carbon::now()->addWeek()->endOfWeek()]);
        }

        // Role check: If employee, only show counts assigned to this employee
        if (Auth::user() && Auth::user()->usertype !== 'admin') {
            $query->where('assigned_to', Auth::id());
        } elseif ($request->filled('staff_id') && $request->staff_id !== 'all') {
            $query->where('assigned_to', $request->staff_id);
        }

        // Status filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $counts = $query->paginate(15)->appends($request->all());

        // Summary metrics (scoped to staff member if not admin)
        $now = Carbon::now();
        $thisWeekStart = $now->copy()->startOfWeek();
        $thisWeekEnd = $now->copy()->endOfWeek();

        $isStaff = Auth::check() && Auth::user()->usertype !== 'admin';
        $baseStatQuery = CycleCount::query();
        if ($isStaff) {
            $baseStatQuery->where('assigned_to', Auth::id());
        }

        $totalCountsCount = (clone $baseStatQuery)->count();
        $completedThisWeek = (clone $baseStatQuery)->where('status', 'approved')
            ->whereBetween('approved_at', [$thisWeekStart, $thisWeekEnd])
            ->count();
        // If 0 approved this week, fallback to total approved for staff overview
        if ($completedThisWeek === 0 && $isStaff) {
            $completedThisWeek = (clone $baseStatQuery)->where('status', 'approved')->count();
        }
        $pendingApproval = (clone $baseStatQuery)->where('status', 'submitted')->count();
        $inProgressCount = (clone $baseStatQuery)->where('status', 'started')->count();

        $allStaff = $this->getAssignableStaff();

        return view('admin_panel.cycle_count.schedule', compact(
            'counts',
            'totalCountsCount',
            'completedThisWeek',
            'pendingApproval',
            'inProgressCount',
            'allStaff',
            'weekFilter'
        ));
    }

    /**
     * Helper to load all active staff from HR Employee Management + Admin Users
     */
    protected function getAssignableStaff()
    {
        $staffList = collect();

        // 1. Fetch active employees from HR Employee Management
        $hrEmployees = \App\Models\Hr\Employee::where('status', 'active')
            ->orWhereNull('status')
            ->with(['department', 'designation', 'user'])
            ->orderBy('first_name')
            ->get();

        foreach ($hrEmployees as $emp) {
            $user = $emp->user;
            if (!$user && $emp->email) {
                $user = User::where('email', $emp->email)->first();
            }
            if (!$user) {
                $user = User::create([
                    'name' => trim($emp->first_name . ' ' . $emp->last_name),
                    'email' => $emp->email ?: ('emp_' . $emp->id . '@kent.local'),
                    'password' => bcrypt('12345678'),
                    'usertype' => 'employee',
                ]);
            }
            if ($emp->user_id !== $user->id) {
                $emp->user_id = $user->id;
                $emp->save();
            }

            $staffList->push((object)[
                'id'       => $user->id,
                'name'     => trim($emp->first_name . ' ' . $emp->last_name),
                'email'    => $emp->email,
                'usertype' => 'employee',
                'dept'     => $emp->department ? $emp->department->name : '',
                'desig'    => $emp->designation ? $emp->designation->name : 'Staff',
            ]);
        }

        // 2. Also include Admin users (Super Admin / In-charge)
        $adminUsers = User::where('usertype', 'admin')->get();
        foreach ($adminUsers as $adm) {
            if (!$staffList->contains('id', $adm->id)) {
                $staffList->push((object)[
                    'id'       => $adm->id,
                    'name'     => $adm->name,
                    'email'    => $adm->email,
                    'usertype' => 'admin',
                    'dept'     => 'Management',
                    'desig'    => 'Owner / Admin',
                ]);
            }
        }

        return $staffList;
    }

    /**
     * Image 1: Assign Cycle Count (Owner / In-charge only)
     */
    public function create()
    {
        $locations = $this->classificationService->getLocationAuditPriorities();

        // Weekly workload per staff (counts assigned for the current week)
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $staffUsers = $this->getAssignableStaff();
        $staffWorkload = [];

        foreach ($staffUsers as $st) {
            $wCount = CycleCount::where('assigned_to', $st->id)
                ->whereBetween('count_date', [$startOfWeek, $endOfWeek])
                ->where('status', '!=', 'cancelled')
                ->count();
            $staffWorkload[$st->id] = $wCount;
        }

        $nextCountNumber = $this->classificationService->generateCountNumber();
        $todayDate = Carbon::today()->format('Y-m-d');

        return view('admin_panel.cycle_count.assign', compact(
            'locations',
            'staffUsers',
            'staffWorkload',
            'nextCountNumber',
            'todayDate'
        ));
    }

    /**
     * Store a newly created Cycle Count assignment
     */
    public function store(Request $request)
    {
        $request->validate([
            'count_type'   => 'required|string',
            'count_date'   => 'required|date',
            'assigned_to'  => 'required|exists:users,id',
            'approver_id'  => 'required|exists:users,id',
        ]);

        // Four-eyes principle validation: Approver can never be the counter
        if ($request->assigned_to == $request->approver_id) {
            return back()->withInput()->withErrors([
                'approver_id' => 'Approver can never be the counter (four-eyes principle). Please select a different approver.',
            ]);
        }

        // Support both single location_id and multiple location_ids[]
        $locationIds = [];
        if ($request->has('location_ids') && is_array($request->input('location_ids'))) {
            $locationIds = array_values(array_filter($request->input('location_ids')));
        } elseif ($request->filled('location_id')) {
            $locationIds = [(int)$request->input('location_id')];
        }

        if (empty($locationIds)) {
            return back()->withInput()->withErrors([
                'location_id' => 'Please select at least one location to count.',
            ]);
        }

        // Check for existing open count on any of the selected locations
        $existingOpen = CycleCount::whereIn('location_id', $locationIds)
            ->whereIn('status', ['not_started', 'started', 'submitted', 'draft'])
            ->with('location')
            ->first();

        if ($existingOpen) {
            $locName = $existingOpen->location ? $existingOpen->location->name : 'one of the selected locations';
            return back()->withInput()->withErrors([
                'location_id' => "Location '{$locName}' already has an open count ({$existingOpen->count_number}) in progress.",
            ]);
        }

        $isDraft = $request->input('action') === 'draft';
        $createdCounts = [];

        DB::beginTransaction();
        try {
            $allActive = Product::where('is_active', true)->with(['warehouseStocks', 'category'])->get();

            foreach ($locationIds as $index => $locId) {
                $location = StorageLocation::with(['warehouse', 'branch'])->findOrFail($locId);
                $itemsToCreate = $this->collectItemsForLocation($location, $allActive);
                $zone = $location->zone_or_aisle ?: ($location->warehouse ? $location->warehouse->warehouse_name : ($location->branch ? $location->branch->name : 'General'));
                $countNumber = $this->classificationService->generateCountNumber($index);

                $cycleCount = CycleCount::create([
                    'count_number'   => $countNumber,
                    'count_type'     => $request->count_type,
                    'location_id'    => $location->id,
                    'location_name'  => $location->full_display_name,
                    'zone'           => $zone,
                    'warehouse_id'   => $location->warehouse_id,
                    'branch_id'      => $location->branch_id,
                    'count_date'     => $request->count_date,
                    'time_window'    => $request->time_window,
                    'assigned_to'    => $request->assigned_to,
                    'approver_id'    => $request->approver_id,
                    'notes'          => $request->notes,
                    'status'         => $isDraft ? 'draft' : 'not_started',
                    'total_items'    => count($itemsToCreate),
                    'entered_items'  => 0,
                    'created_by'     => Auth::id(),
                ]);

                foreach ($itemsToCreate as $itemData) {
                    $itemData['cycle_count_id'] = $cycleCount->id;
                    CycleCountItem::create($itemData);
                }

                $createdCounts[] = $countNumber;
            }

            DB::commit();

            $countQty = count($createdCounts);
            $countListStr = implode(', ', $createdCounts);

            if ($countQty === 1) {
                $msg = $isDraft
                    ? "Cycle count {$countListStr} saved as draft."
                    : "Cycle count {$countListStr} assigned successfully to staff.";
            } else {
                $msg = $isDraft
                    ? "{$countQty} cycle counts ({$countListStr}) saved as draft."
                    : "{$countQty} cycle counts ({$countListStr}) assigned successfully to staff.";
            }

            return redirect()->route('cycle_count.index')->with('success', $msg);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Failed to assign count: ' . $e->getMessage()]);
        }
    }

    /**
     * Helper: Collect all countable items for a given storage location.
     */
    private function collectItemsForLocation(StorageLocation $location, $allActive = null): array
    {
        if ($allActive === null) {
            $allActive = Product::where('is_active', true)->with(['warehouseStocks', 'category'])->get();
        }

        $locName  = strtolower(trim($location->name));
        $locCode  = $location->code ? strtolower(trim($location->code)) : '';
        $cleanLocCode = (str_starts_with($locCode, 'r-') || str_starts_with($locCode, 's-')) ? substr($locCode, 2) : $locCode;

        $itemsToCreate = [];
        $capturedProductIds = [];

        foreach ($allActive as $prod) {
            $catName = ($prod->category ? $prod->category->name : null) ?: 'Uncategorized';
            $abcClass  = $prod->abc_class  ?: 'C';
            $countDays = $prod->cycle_count_days ?: 30;
            $velocityClass = "Class {$abcClass} ({$countDays}d)";
            $cost = (float)($prod->purchase_price_per_piece ?: ($prod->purchase_price_per_box ?: 0));

            // Check variants
            if ($prod->color) {
                $variants = is_string($prod->color) ? json_decode($prod->color, true) : $prod->color;
                if (is_array($variants)) {
                    foreach ($variants as $v) {
                        $vl = strtolower(trim($v['location'] ?? ''));
                        $vlTokens = preg_split('/[\/\-,\;\s]+/', preg_replace('/\b([A-Za-z]+)\s+(\d+)\b/', '$1$2', $vl));
                        $vlTokens = array_filter(array_map('trim', $vlTokens));
                        $isTokenMatch = $vl && (
                            $vl === $locName ||
                            ($locCode && $vl === $locCode) ||
                            ($cleanLocCode && $vl === $cleanLocCode) ||
                            in_array($locName, $vlTokens, true) ||
                            ($locCode && in_array($locCode, $vlTokens, true)) ||
                            ($cleanLocCode && in_array($cleanLocCode, $vlTokens, true))
                        );

                        if ($isTokenMatch) {
                            $capturedProductIds[] = $prod->id;
                            $vStock = (float)($v['stock'] ?? $v['current_stock'] ?? 0);
                            $vCost  = (float)($v['purch_price'] ?? $cost);
                            $attr   = ($v['color'] && $v['color'] !== '-') ? trim($v['color']) : '';
                            $size   = ($v['size'] && $v['size'] !== '-') ? trim($v['size']) : '';
                            $nameWithAttr = $prod->item_name;
                            if ($size || $attr) {
                                $nameWithAttr .= ' (' . trim($size . ' ' . $attr) . ')';
                            }

                            $itemsToCreate[] = [
                                'product_id'     => $prod->id,
                                'item_code'      => $v['serial_no'] ?? ($v['barcode'] ?? $prod->item_code),
                                'item_name'      => $nameWithAttr,
                                'p_code'         => $v['p_code'] ?? ($v['serial_no'] ?? $prod->p_code),
                                'category_name'  => $catName,
                                'image_path'     => $v['image'] ?? $prod->image,
                                'barcode_path'   => $v['barcode'] ?? $prod->barcode_path,
                                'velocity_class' => $velocityClass,
                                'unit'           => $v['unit'] ?? ($prod->packing_name ?: 'Pcs'),
                                'location_name'  => $v['location'] ?? $location->name,
                                'system_qty'     => $vStock,
                                'counted_qty'    => null,
                                'difference'     => 0.00,
                                'unit_cost'      => $vCost,
                                'variance_value' => 0.00,
                                'status'         => 'pending',
                                'is_entered'     => false,
                            ];
                        }
                    }
                }
            }

            // Check warehouse stocks if not already captured as variants
            if (!in_array($prod->id, $capturedProductIds)) {
                foreach ($prod->warehouseStocks as $ws) {
                    $isLocMatch = false;
                    if ($ws->storage_location_id == $location->id) {
                        $isLocMatch = true;
                    } elseif ($location->warehouse_id && $ws->warehouse_id == $location->warehouse_id) {
                        $rem = strtolower(trim($ws->remarks ?? ''));
                        $remTokens = preg_split('/[\/\-,\;\s]+/', preg_replace('/\b([A-Za-z]+)\s+(\d+)\b/', '$1$2', $rem));
                        $remTokens = array_filter(array_map('trim', $remTokens));
                        if ($rem && (
                            $rem === $locName ||
                            ($locCode && $rem === $locCode) ||
                            ($cleanLocCode && $rem === $cleanLocCode) ||
                            in_array($locName, $remTokens, true) ||
                            ($locCode && in_array($locCode, $remTokens, true)) ||
                            ($cleanLocCode && in_array($cleanLocCode, $remTokens, true))
                        )) {
                            $isLocMatch = true;
                        }
                    }

                    if ($isLocMatch) {
                        $capturedProductIds[] = $prod->id;
                        $itemsToCreate[] = [
                            'product_id'     => $prod->id,
                            'item_code'      => $prod->item_code,
                            'item_name'      => $prod->item_name,
                            'p_code'         => $prod->p_code,
                            'category_name'  => $catName,
                            'image_path'     => $prod->image,
                            'barcode_path'   => $prod->barcode_path,
                            'velocity_class' => $velocityClass,
                            'unit'           => $prod->packing_name ?: 'Pcs',
                            'location_name'  => $location->name,
                            'system_qty'     => (float)$ws->total_pieces,
                            'counted_qty'    => null,
                            'difference'     => 0.00,
                            'unit_cost'      => $cost,
                            'variance_value' => 0.00,
                            'status'         => 'pending',
                            'is_entered'     => false,
                        ];
                    }
                }
            }
        }

        // Fallback: warehouse stock products if no specific location tags found
        if (empty($itemsToCreate)) {
            if ($location->warehouse_id) {
                $wsRows = WarehouseStock::where('warehouse_id', $location->warehouse_id)
                    ->where('total_pieces', '>', 0)
                    ->with('product.category')
                    ->take(50)
                    ->get();
                foreach ($wsRows as $ws) {
                    if ($ws->product) {
                        $p = $ws->product;
                        $catName = ($p->category ? $p->category->name : null) ?: 'Uncategorized';
                        $abcClass  = $p->abc_class ?: 'C';
                        $countDays = $p->cycle_count_days ?: 30;
                        $itemsToCreate[] = [
                            'product_id'     => $p->id,
                            'item_code'      => $p->item_code,
                            'item_name'      => $p->item_name,
                            'p_code'         => $p->p_code,
                            'category_name'  => $catName,
                            'image_path'     => $p->image,
                            'barcode_path'   => $p->barcode_path,
                            'velocity_class' => "Class {$abcClass} ({$countDays}d)",
                            'unit'           => $p->packing_name ?: 'Pcs',
                            'location_name'  => $location->name,
                            'system_qty'     => (float)$ws->total_pieces,
                            'counted_qty'    => null,
                            'difference'     => 0.00,
                            'unit_cost'      => (float)($p->purchase_price_per_piece ?: 0),
                            'variance_value' => 0.00,
                            'status'         => 'pending',
                            'is_entered'     => false,
                        ];
                    }
                }
            }
            if (empty($itemsToCreate)) {
                foreach ($allActive->take(30) as $p) {
                    $catName = ($p->category ? $p->category->name : null) ?: 'Uncategorized';
                    $abcClass  = $p->abc_class ?: 'C';
                    $countDays = $p->cycle_count_days ?: 30;
                    $itemsToCreate[] = [
                        'product_id'     => $p->id,
                        'item_code'      => $p->item_code,
                        'item_name'      => $p->item_name,
                        'p_code'         => $p->p_code,
                        'category_name'  => $catName,
                        'image_path'     => $p->image,
                        'barcode_path'   => $p->barcode_path,
                        'velocity_class' => "Class {$abcClass} ({$countDays}d)",
                        'unit'           => $p->packing_name ?: 'Pcs',
                        'location_name'  => $location->name,
                        'system_qty'     => (float)$p->warehouseStocks->sum('total_pieces'),
                        'counted_qty'    => null,
                        'difference'     => 0.00,
                        'unit_cost'      => (float)($p->purchase_price_per_piece ?: 0),
                        'variance_value' => 0.00,
                        'status'         => 'pending',
                        'is_entered'     => false,
                    ];
                }
            }
        }

        return $itemsToCreate;
    }

    /**
     * Image 3: Count Sheet (Staff Portal — BLIND COUNT)
     * Counter sees NO system stock numbers. Timer tracks elapsed duration.
     */
    public function sheet($id)
    {
        $cycleCount = CycleCount::with(['location', 'counter', 'approver'])->findOrFail($id);

        // Security: Employee can only see their assigned count, or admin can inspect
        if (Auth::user()->usertype !== 'admin' && Auth::id() != $cycleCount->assigned_to) {
            abort(403, 'Unauthorized access to this count sheet.');
        }

        // Fetch blind items: system_qty is NOT passed to the view!
        $items = CycleCountItem::where('cycle_count_id', $cycleCount->id)
            ->select('id', 'cycle_count_id', 'product_id', 'item_code', 'item_name', 'p_code', 'category_name', 'image_path', 'barcode_path', 'velocity_class', 'unit', 'location_name', 'counted_qty', 'is_entered', 'status')
            ->orderBy('id', 'asc')
            ->get();

        return view('admin_panel.cycle_count.count_sheet', compact('cycleCount', 'items'));
    }

    /**
     * AJAX: Start Live Count Timer
     */
    public function startTimer($id)
    {
        $cycleCount = CycleCount::findOrFail($id);

        if (!$cycleCount->started_at) {
            $cycleCount->started_at = Carbon::now();
        }
        if ($cycleCount->status === 'not_started' || $cycleCount->status === 'draft') {
            $cycleCount->status = 'started';
        }
        $cycleCount->save();

        return response()->json([
            'status'     => 'success',
            'started_at' => $cycleCount->started_at->toIso8601String(),
            'message'    => 'Count timer started.',
        ]);
    }

    /**
     * AJAX: Save single counted item (Real-time auto-saving with green checkmark)
     */
    public function saveItemCount(Request $request, $id)
    {
        $cycleCount = CycleCount::findOrFail($id);
        $itemId = $request->input('item_id');
        $countedQty = $request->input('counted_qty');

        $item = CycleCountItem::where('cycle_count_id', $cycleCount->id)->findOrFail($itemId);

        if ($countedQty === null || $countedQty === '') {
            $item->counted_qty = null;
            $item->is_entered = false;
            $item->entered_at = null;
        } else {
            $item->counted_qty = (float)$countedQty;
            $item->is_entered = true;
            $item->entered_at = Carbon::now();
        }
        $item->save();

        // Update count progress
        $enteredCount = CycleCountItem::where('cycle_count_id', $cycleCount->id)
            ->where('is_entered', true)
            ->count();

        $cycleCount->entered_items = $enteredCount;
        if ($cycleCount->status === 'not_started') {
            $cycleCount->status = 'started';
            if (!$cycleCount->started_at) {
                $cycleCount->started_at = Carbon::now();
            }
        }
        $cycleCount->save();

        $percent = $cycleCount->total_items > 0 ? (int)round(($enteredCount / $cycleCount->total_items) * 100) : 0;

        return response()->json([
            'status'           => 'success',
            'item_id'          => $item->id,
            'is_entered'       => $item->is_entered,
            'entered_items'    => $enteredCount,
            'total_items'      => $cycleCount->total_items,
            'progress_percent' => $percent,
        ]);
    }

    /**
     * Counter Submits Count Sheet
     * Stops timer, computes duration, calculates differences & locks sheet
     */
    public function submitCount(Request $request, $id)
    {
        $cycleCount = CycleCount::with('items')->findOrFail($id);

        $now = Carbon::now();
        if (!$cycleCount->started_at) {
            $cycleCount->started_at = $now->copy()->subMinutes(15);
        }
        $cycleCount->submitted_at = $now;
        $cycleCount->duration_seconds = $cycleCount->started_at->diffInSeconds($now);

        $matched = 0;
        $variances = 0;
        $totalVarianceValue = 0.0;

        foreach ($cycleCount->items as $item) {
            $counted = $item->counted_qty !== null ? (float)$item->counted_qty : 0.0;
            $diff = $counted - (float)$item->system_qty;
            $item->difference = $diff;
            $item->variance_value = $diff * (float)$item->unit_cost;

            if (abs($diff) < 0.0001) {
                $item->status = 'matched';
                $matched++;
            } else {
                $item->status = 'needs_reason';
                $variances++;
                $totalVarianceValue += $item->variance_value;
            }

            // Smart Hint detection: look for opposite discrepancy in nearby locations or same count
            if ($diff < 0) {
                $item->smart_hint = "Possible misplaced item. Check adjacent bins/shelves.";
            }

            $item->save();
        }

        $cycleCount->status = 'submitted';
        $cycleCount->matched_items = $matched;
        $cycleCount->variance_items = $variances;
        $cycleCount->total_variance_value = $totalVarianceValue;
        $cycleCount->save();

        return response()->json([
            'status'   => 'success',
            'message'  => "Count sheet submitted successfully! Total duration: {$cycleCount->formatted_duration}.",
            'redirect' => route('cycle_count.index'),
        ]);
    }

    /**
     * Image 4: Approve Count (Owner / In-charge Reconciliation)
     * Discrepancies, Mandatory Reasons, Smart Hints & Sidebar Analytics
     */
    public function approveScreen($id)
    {
        $cycleCount = CycleCount::with(['location', 'counter', 'approver', 'items.product'])->findOrFail($id);

        // Security check: Approval screen is strictly for Admin / In-charge, NEVER the counter
        if (Auth::user()->usertype !== 'admin' && Auth::id() != $cycleCount->approver_id) {
            abort(403, 'Unauthorized access. Screen 4 (Approval) is strictly for Owner / In-charge only.');
        }
        if (Auth::user()->usertype !== 'admin' && Auth::id() == $cycleCount->assigned_to) {
            abort(403, 'Four-eyes security violation: A counter cannot approve their own count.');
        }

        // Discrepancy Items with calculations
        $items = $cycleCount->items;
        $variances = $items->where('status', '!=', 'matched');
        $matched = $items->where('status', '===', 'matched');

        // Sidebar Analytics: 5 reasons breakdown for this count & historical
        $reasonCounts = CycleCountItem::whereNotNull('variance_reason')
            ->select('variance_reason', DB::raw('count(*) as count'))
            ->groupBy('variance_reason')
            ->pluck('count', 'variance_reason')
            ->toArray();

        // Repeat variances in last 90 days for items in this count
        $prodIds = $items->pluck('product_id')->toArray();
        $repeatVariancesCount = CycleCountItem::whereIn('product_id', $prodIds)
            ->where('created_at', '>=', Carbon::now()->subDays(90))
            ->where('difference', '!=', 0)
            ->count();

        return view('admin_panel.cycle_count.approve', compact(
            'cycleCount',
            'items',
            'variances',
            'matched',
            'reasonCounts',
            'repeatVariancesCount'
        ));
    }

    /**
     * AJAX: Update Discrepancy Reason on an Item (THEFT, DAMAGE, GIVEN, ENTRY ERROR, MISPLACED)
     */
    public function updateItemReason(Request $request, $id)
    {
        $request->validate([
            'item_id'         => 'required|exists:cycle_count_items,id',
            'variance_reason' => 'required|in:THEFT,DAMAGE,GIVEN,ENTRY ERROR,MISPLACED',
        ]);

        $item = CycleCountItem::where('cycle_count_id', $id)->findOrFail($request->item_id);
        $item->variance_reason = $request->variance_reason;
        $item->reason_notes = $request->input('reason_notes');
        $item->misplaced_location = $request->input('misplaced_location');
        $item->status = 'ready_to_approve';
        $item->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'Reason updated successfully.',
            'item_id' => $item->id,
            'item_status' => $item->status,
        ]);
    }

    /**
     * Owner approves single item
     */
    public function approveItem(Request $request, $id, $itemId)
    {
        $cycleCount = CycleCount::findOrFail($id);
        $item = CycleCountItem::where('cycle_count_id', $cycleCount->id)->findOrFail($itemId);

        if ($item->difference != 0 && empty($item->variance_reason)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Please select a reason (Theft, Damage, Given, Entry Error, Misplaced) before approving this variance.',
            ], 422);
        }

        DB::beginTransaction();
        try {
            $this->applyStockAdjustmentForItem($cycleCount, $item);
            $item->status = 'approved';
            $item->save();

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => "Item '{$item->item_name}' approved and stock adjusted.",
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Owner approves all ready items (Post Stock Adjustments & finalize count)
     */
    public function approveAll(Request $request, $id)
    {
        $cycleCount = CycleCount::with('items.product')->findOrFail($id);

        // Check if any variance items are missing mandatory reasons
        $missingReasonItems = $cycleCount->items->filter(function ($it) {
            return abs($it->difference) > 0.0001 && empty($it->variance_reason) && $it->status !== 'approved';
        });

        if ($missingReasonItems->isNotEmpty()) {
            return back()->withErrors([
                'error' => "Cannot approve: {$missingReasonItems->count()} variance item(s) are missing a mandatory reason (Theft, Damage, Given, Entry Error, Misplaced).",
            ]);
        }

        DB::beginTransaction();
        try {
            $now = Carbon::now();

            foreach ($cycleCount->items as $item) {
                if ($item->status !== 'approved') {
                    $this->applyStockAdjustmentForItem($cycleCount, $item);
                    $item->status = 'approved';
                    $item->save();
                }

                // Update product last_counted_at and next_count_date
                if ($item->product) {
                    $prod = $item->product;
                    $prod->last_counted_at = $now;
                    $days = $prod->cycle_count_days ?: 30;
                    $prod->next_count_date = $now->copy()->addDays($days)->toDateString();
                    $prod->save();
                }
            }

            // Update location last_counted_at
            if ($cycleCount->location) {
                $cycleCount->location->last_counted_at = $now;
                $cycleCount->location->save();
            }

            $cycleCount->status = 'approved';
            $cycleCount->approved_at = $now;
            $cycleCount->approved_by = Auth::id();
            $cycleCount->save();

            DB::commit();

            return redirect()->route('cycle_count.index')->with('success', "Cycle count {$cycleCount->count_number} has been fully approved and stock adjustments posted.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Approval failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Request Recount for all variances or specific items
     */
    public function requestRecount(Request $request, $id)
    {
        $cycleCount = CycleCount::with('items')->findOrFail($id);

        foreach ($cycleCount->items as $item) {
            if (abs($item->difference) > 0.0001) {
                $item->status = 'recount';
                $item->is_entered = false;
                $item->counted_qty = null;
                $item->save();
            }
        }

        $cycleCount->status = 'recount_requested';
        $cycleCount->entered_items = $cycleCount->items->where('is_entered', true)->count();
        $cycleCount->submitted_at = null;
        $cycleCount->save();

        return redirect()->route('cycle_count.index')->with('warning', "Recount requested for variances in {$cycleCount->count_number}. Staff has been notified.");
    }

    /**
     * Reassign count to another staff member
     */
    public function reassign(Request $request, $id)
    {
        $request->validate([
            'staff_id' => 'required|exists:users,id',
        ]);

        $cycleCount = CycleCount::findOrFail($id);
        $cycleCount->assigned_to = $request->staff_id;
        if ($request->filled('count_date')) {
            $cycleCount->count_date = $request->count_date;
        }
        $cycleCount->save();

        return back()->with('success', "Count {$cycleCount->count_number} reassigned successfully.");
    }

    /**
     * Cancel count
     */
    public function cancel(Request $request, $id)
    {
        $cycleCount = CycleCount::findOrFail($id);
        $cycleCount->status = 'cancelled';
        $cycleCount->save();

        return back()->with('success', "Count {$cycleCount->count_number} cancelled.");
    }

    /**
     * Helper to post stock adjustment record and update warehouse/branch stock
     */
    protected function applyStockAdjustmentForItem(CycleCount $cycleCount, CycleCountItem $item)
    {
        if (abs($item->difference) < 0.0001) {
            return; // No difference to adjust
        }

        $prod = $item->product;
        $whId = $cycleCount->warehouse_id;

        // Post to stock_adjustments table
        StockAdjustment::create([
            'user_id'      => Auth::id(),
            'warehouse_id' => $whId,
            'product_id'   => $item->product_id,
            'type'         => $item->difference > 0 ? 'add' : 'subtract',
            'qty'          => abs($item->difference),
            'old_stock'    => $item->system_qty,
            'new_stock'    => $item->counted_qty !== null ? $item->counted_qty : 0,
            'reason'       => "Cycle Count {$cycleCount->count_number} Audit: " . ($item->variance_reason ?: 'Correction') . ($item->reason_notes ? " - {$item->reason_notes}" : ''),
        ]);

        // Update warehouse_stocks if warehouse count
        if ($whId) {
            $whStock = WarehouseStock::firstOrNew([
                'warehouse_id' => $whId,
                'product_id'   => $item->product_id,
            ]);
            $whStock->total_pieces = max(0, (float)$item->counted_qty);
            $whStock->quantity = max(0, (float)$item->counted_qty);
            $whStock->save();
        }

        // Update products total stock
        if ($prod) {
            $prod->total_stock_qty = $prod->warehouseStocks()->sum('total_pieces');
            $prod->save();
        }
    }
}
