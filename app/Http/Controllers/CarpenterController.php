<?php

namespace App\Http\Controllers;

use App\Models\Carpenter;
use App\Models\CarpenterCommission;
use App\Models\Sale;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CarpenterController extends Controller
{
    /**
     * Display a listing of carpenters.
     */
    public function index(Request $request)
    {
        $query = Carpenter::query();

        if ($request->filled('search')) {
            $term = trim($request->search);
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('phone', 'like', "%{$term}%")
                  ->orWhere('cnic', 'like', "%{$term}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $thresholdDays = (int) \App\Models\SystemSetting::get('carpenter_inactivity_days', 7);
        if ($thresholdDays <= 0) $thresholdDays = 7;

        $carpenters = $query->with(['lastSale', 'lastCommission'])
                            ->withCount(['commissions as total_commissions_count', 'sales as total_sales_count'])
                            ->withSum('commissions as total_commission_amount', 'commission_amount')
                            ->orderBy('name')
                            ->paginate(15);

        // Count active carpenters who haven't had a sale for >= thresholdDays
        $inactiveCount = Carpenter::active()->with('lastSale')->get()->filter(function ($c) use ($thresholdDays) {
            return $c->days_inactive >= $thresholdDays;
        })->count();

        return view('admin_panel.carpenters.index', compact('carpenters', 'thresholdDays', 'inactiveCount'));
    }

    /**
     * Store a newly created carpenter in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'cnic' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'default_commission_type' => 'required|in:percentage,fixed',
            'default_commission_rate' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $validated['status'] = $request->has('status') ? 1 : ($request->input('status', 1) ? 1 : 0);

        $carpenter = Carpenter::create($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Carpenter added successfully.',
                'carpenter' => $carpenter,
            ]);
        }

        return redirect()->route('carpenters.index')->with('success', 'Carpenter added successfully.');
    }

    /**
     * Update the specified carpenter.
     */
    public function update(Request $request, $id)
    {
        $carpenter = Carpenter::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'cnic' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'default_commission_type' => 'required|in:percentage,fixed',
            'default_commission_rate' => 'required|numeric|min:0',
            'status' => 'required|in:0,1',
            'notes' => 'nullable|string',
        ]);

        $carpenter->update($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Carpenter updated successfully.',
                'carpenter' => $carpenter,
            ]);
        }

        return redirect()->route('carpenters.index')->with('success', 'Carpenter updated successfully.');
    }

    /**
     * Remove or toggle the specified carpenter.
     */
    public function destroy($id)
    {
        $carpenter = Carpenter::findOrFail($id);

        if ($carpenter->commissions()->exists() || $carpenter->sales()->exists()) {
            // Soft deactivate
            $carpenter->status = 0;
            $carpenter->save();
            return redirect()->route('carpenters.index')->with('warning', 'Carpenter has past sales/commissions. Status set to inactive.');
        }

        $carpenter->delete();
        return redirect()->route('carpenters.index')->with('success', 'Carpenter deleted successfully.');
    }

    /**
     * AJAX fetch active carpenters for POS dropdown.
     */
    public function ajaxList()
    {
        $carpenters = Carpenter::active()
            ->select('id', 'name', 'phone', 'default_commission_type', 'default_commission_rate')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'carpenters' => $carpenters,
        ]);
    }

    /**
     * AJAX quick store carpenter directly from POS screen modal.
     */
    public function ajaxStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'cnic' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'default_commission_type' => 'required|in:percentage,fixed',
            'default_commission_rate' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $validated['status'] = 1;

        $carpenter = Carpenter::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Carpenter created successfully.',
            'carpenter' => $carpenter,
        ]);
    }

    /**
     * Display listing of Carpenter Commissions with filters and stats.
     */
    public function commissionsIndex(Request $request)
    {
        $query = CarpenterCommission::with(['carpenter', 'sale.customer_relation', 'approver', 'payer', 'paymentAccount']);

        // Filter by carpenter
        if ($request->filled('carpenter_id')) {
            $query->where('carpenter_id', $request->carpenter_id);
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereDate('created_at', '>=', $request->start_date)
                  ->whereDate('created_at', '<=', $request->end_date);
        }

        // Search by Invoice #
        if ($request->filled('search')) {
            $term = trim($request->search);
            $query->whereHas('sale', function ($q) use ($term) {
                $q->where('invoice_no', 'like', "%{$term}%")
                  ->orWhere('walkin_name', 'like', "%{$term}%");
            })->orWhereHas('carpenter', function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('phone', 'like', "%{$term}%");
            });
        }

        // Calculate summary statistics
        $statsBase = clone $query;
        $totalCommission = (clone $statsBase)->sum('commission_amount');
        $pendingCommission = (clone $statsBase)->where('status', 'pending')->sum('commission_amount');
        $approvedCommission = (clone $statsBase)->where('status', 'approved')->sum('commission_amount');
        $paidCommission = (clone $statsBase)->where('status', 'paid')->sum('commission_amount');

        $pendingCount = (clone $statsBase)->where('status', 'pending')->count();
        $approvedCount = (clone $statsBase)->where('status', 'approved')->count();
        $paidCount = (clone $statsBase)->where('status', 'paid')->count();
        $totalCount = (clone $statsBase)->count();

        $commissions = $query->orderByDesc('id')->paginate(20);

        $carpenters = Carpenter::orderBy('name')->get();
        $accounts = Account::whereHas('head', function ($q) {
            $q->whereIn('name', ['Cash', 'Bank']);
        })->where('status', 1)->orderBy('title')->get();

        if ($accounts->isEmpty()) {
            $accounts = Account::where('status', 1)->orderBy('title')->get();
        }

        return view('admin_panel.carpenters.commissions', compact(
            'commissions',
            'carpenters',
            'accounts',
            'totalCommission',
            'pendingCommission',
            'approvedCommission',
            'paidCommission',
            'totalCount',
            'pendingCount',
            'approvedCount',
            'paidCount'
        ));
    }

    /**
     * Approve Carpenter Commission.
     */
    public function approveCommission(Request $request, $id)
    {
        $commission = CarpenterCommission::findOrFail($id);

        if ($commission->status === 'paid') {
            return response()->json(['success' => false, 'message' => 'Commission is already paid.'], 400);
        }

        $commission->status = 'approved';
        $commission->approved_by = auth()->id();
        $commission->approved_at = now();
        $commission->save();

        // Also mark on Sale model
        if ($commission->sale) {
            $commission->sale->carpenter_commission_approved = 1;
            $commission->sale->save();
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Commission #{$commission->id} approved successfully.",
                'commission' => $commission,
            ]);
        }

        return redirect()->back()->with('success', "Commission for {$commission->carpenter->name} approved successfully.");
    }

    /**
     * Batch Approve Commissions.
     */
    public function batchApprove(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids) || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'No commissions selected.'], 400);
        }

        $now = now();
        $userId = auth()->id();

        $updated = CarpenterCommission::whereIn('id', $ids)
            ->where('status', 'pending')
            ->update([
                'status' => 'approved',
                'approved_by' => $userId,
                'approved_at' => $now,
            ]);

        $saleIds = CarpenterCommission::whereIn('id', $ids)->pluck('sale_id');
        Sale::whereIn('id', $saleIds)->update(['carpenter_commission_approved' => 1]);

        return response()->json([
            'success' => true,
            'message' => "{$updated} commissions approved successfully.",
        ]);
    }

    /**
     * Pay Approved Commission.
     */
    public function payCommission(Request $request, $id)
    {
        $request->validate([
            'payment_account_id' => 'required|exists:accounts,id',
            'paid_amount' => 'nullable|numeric|min:0.01',
            'notes' => 'nullable|string',
        ]);

        $commission = CarpenterCommission::with(['carpenter', 'sale'])->findOrFail($id);

        if ($commission->status === 'paid') {
            return response()->json(['success' => false, 'message' => 'Commission is already paid.'], 400);
        }

        $paidAmount = $request->filled('paid_amount') ? (float)$request->paid_amount : (float)$commission->commission_amount;
        $accountId = $request->payment_account_id;

        DB::beginTransaction();
        try {
            $commission->status = 'paid';
            $commission->paid_by = auth()->id();
            $commission->paid_at = now();
            $commission->payment_account_id = $accountId;
            if ($request->filled('notes')) {
                $commission->notes = trim($commission->notes . ' ' . $request->notes);
            }
            $commission->save();

            // Try to record Payment Voucher / Expense entry for accounting integrity
            try {
                $voucherService = app(\App\Services\VoucherService::class);
                $balanceService = app(\App\Services\BalanceService::class);
                
                // Expense account: look for Commission Expense or Sales Revenue/Expense
                $expenseAccount = Account::where('title', 'like', '%Commission%')
                    ->orWhere('title', 'like', '%Expense%')
                    ->first();
                $expenseAccountId = $expenseAccount ? $expenseAccount->id : $balanceService->getSalesRevenueId();

                $voucherData = [
                    'voucher_type' => \App\Models\VoucherMaster::TYPE_PAYMENT,
                    'date' => now()->format('Y-m-d'),
                    'status' => \App\Models\VoucherMaster::STATUS_POSTED,
                    'remarks' => "Carpenter Commission Paid: {$commission->carpenter->name} (Sale #{$commission->sale->invoice_no})",
                ];

                $lines = [
                    [
                        'account_id' => $expenseAccountId,
                        'debit' => $paidAmount,
                        'credit' => 0,
                        'narration' => "Commission to Carpenter {$commission->carpenter->name}",
                    ],
                    [
                        'account_id' => $accountId,
                        'debit' => 0,
                        'credit' => $paidAmount,
                        'narration' => "Cash/Bank Payment for Carpenter Commission",
                    ],
                ];

                $voucherService->createVoucher($voucherData, $lines, auth()->id());
            } catch (\Exception $e) {
                \Log::warning("Carpenter commission payment voucher skipped: " . $e->getMessage());
            }

            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Commission payment of Rs. " . number_format($paidAmount, 2) . " recorded successfully.",
                ]);
            }

            return redirect()->back()->with('success', 'Commission payment recorded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to pay commission: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Cancel / Reject Commission.
     */
    public function cancelCommission(Request $request, $id)
    {
        $commission = CarpenterCommission::findOrFail($id);
        $commission->status = 'cancelled';
        $commission->notes = trim($commission->notes . ' [Cancelled: ' . $request->input('reason', 'Cancelled by user') . ']');
        $commission->save();

        if ($commission->sale) {
            $commission->sale->carpenter_commission_approved = 0;
            $commission->sale->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Commission cancelled successfully.',
        ]);
    }

    /**
     * Update Carpenter Inactivity Threshold Days.
     */
    public function updateInactivitySettings(Request $request)
    {
        $request->validate([
            'threshold_days' => 'required|integer|min:1|max:365',
        ]);

        \App\Models\SystemSetting::updateOrCreate(
            ['key' => 'carpenter_inactivity_days'],
            [
                'value' => (string)$request->threshold_days,
                'type' => 'integer',
                'group' => 'carpenters',
                'label' => 'Carpenter Inactivity Reminder Threshold (Days)',
                'description' => 'Number of days without sales after which active carpenters trigger reminder notifications to staff and admin.'
            ]
        );

        \Illuminate\Support\Facades\Cache::forget('setting_carpenter_inactivity_days');
        \Illuminate\Support\Facades\Cache::forget('carpenter_inactivity_check');

        // Immediately check and notify
        \App\Models\Carpenter::checkInactivityAndNotify();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Inactivity reminder threshold updated to {$request->threshold_days} days.",
                'threshold_days' => (int)$request->threshold_days,
            ]);
        }

        return redirect()->back()->with('success', "Inactivity reminder threshold updated to {$request->threshold_days} days.");
    }

    /**
     * Prepare data for Carpenter Commission Receipt & Thermal Slip.
     */
    protected function prepareCommissionReceiptData($id)
    {
        $commission = CarpenterCommission::with([
            'carpenter',
            'sale.customer_relation',
            'sale.items.product.brand',
            'sale.returns.items.product',
            'approver',
            'payer',
            'paymentAccount'
        ])->findOrFail($id);

        $sale = $commission->sale;
        $itemsBreakdown = [];
        $totalSoldQty = 0;
        $totalItemsCommission = 0;

        if ($sale && $sale->items) {
            $isPercentage = ($commission->commission_type === 'percentage');
            $commRate = (float)$commission->commission_rate;
            $saleTotal = (float)($commission->sale_amount > 0 ? $commission->sale_amount : ($sale->total_net > 0 ? $sale->total_net : 1));

            foreach ($sale->items as $item) {
                // Determine item unit price (the price it was sold at to customer)
                $unitPrice = (!empty($item->price_per_piece) && (float)$item->price_per_piece > 0)
                    ? (float)$item->price_per_piece
                    : (float)($item->price ?? 0);

                $qty = (float)($item->total_pieces ?? $item->qty ?? 1);
                $totalSoldQty += $qty;

                // Line total
                $discount = (float)($item->discount_amount ?? 0);
                $lineTotal = ($item->total > 0) ? (float)$item->total : max(0, ($qty * $unitPrice) - $discount);

                // Commission for this line item
                if ($isPercentage) {
                    $itemCommRate = $commRate;
                    $itemCommAmount = round($lineTotal * ($commRate / 100), 2);
                } else {
                    // Fixed commission prorated across products
                    $itemCommRate = ($saleTotal > 0) ? round(((float)$commission->commission_amount / $saleTotal) * 100, 2) : 0;
                    $itemCommAmount = round(($lineTotal / max(1, $saleTotal)) * (float)$commission->commission_amount, 2);
                }
                $totalItemsCommission += $itemCommAmount;

                // Decode variant if available
                $variantInfo = '';
                if (!empty($item->color)) {
                    $b64 = base64_decode($item->color, true);
                    $vData = ($b64 !== false) ? json_decode($b64, true) : json_decode($item->color, true);
                    if (is_array($vData)) {
                        $parts = [];
                        if (!empty($vData['name'])) $parts[] = $vData['name'];
                        if (!empty($vData['size']) && $vData['size'] !== '-') $parts[] = 'Size: ' . $vData['size'];
                        if (!empty($vData['color']) && $vData['color'] !== '-') $parts[] = 'Color: ' . $vData['color'];
                        $variantInfo = implode(' | ', $parts);
                    }
                }

                $itemsBreakdown[] = [
                    'item' => $item,
                    'product_name' => $item->product_name ?? ($item->product->item_name ?? 'N/A'),
                    'product_code' => $item->product->item_code ?? ($item->product->product_code ?? ($item->p_code ?? '')),
                    'brand' => $item->product->brand->name ?? '',
                    'variant_info' => $variantInfo,
                    'qty' => $qty,
                    'unit' => $item->uom_name ?? 'Pcs',
                    'unit_price' => $unitPrice,
                    'discount' => $discount,
                    'line_total' => $lineTotal,
                    'commission_rate' => $itemCommRate,
                    'commission_amount' => $itemCommAmount,
                    'net_amount' => max(0, $lineTotal - $itemCommAmount),
                ];
            }
        }

        // Return adjustments (if any return happened for this sale)
        $returnsBreakdown = [];
        $totalReturnDeduction = 0;
        $totalReturnedQty = 0;

        if ($sale && $sale->returns && $sale->returns->count() > 0) {
            foreach ($sale->returns as $ret) {
                $dedAmt = (float)($ret->carpenter_deduction_amount ?? 0);
                if ($dedAmt <= 0 && (float)$ret->carpenter_commission_rate > 0 && (float)$ret->net_amount > 0) {
                    $dedAmt = round(((float)$ret->net_amount / (100 - (float)$ret->carpenter_commission_rate)) * (float)$ret->carpenter_commission_rate, 2);
                }
                $totalReturnDeduction += $dedAmt;

                $retQty = $ret->items ? $ret->items->sum('qty') : 0;
                $totalReturnedQty += $retQty;

                $returnsBreakdown[] = [
                    'return' => $ret,
                    'return_invoice' => $ret->return_invoice,
                    'return_date' => $ret->return_date,
                    'returned_qty' => $retQty,
                    'net_amount' => (float)$ret->net_amount,
                    'rate' => (float)($ret->carpenter_commission_rate ?? $commission->commission_rate),
                    'deduction_amount' => $dedAmt,
                ];
            }
        }

        // Net adjusted commission payable
        $adjustedCommission = max(0, (float)$commission->commission_amount - $totalReturnDeduction);

        $company = [
            'name' => \App\Models\Setting::get('company_name', 'Kent Hardware'),
            'address' => \App\Models\Setting::get('company_address', '99R8+79R, Doctors Colony, Hyderabad'),
            'phone' => \App\Models\Setting::get('company_phone', ''),
        ];

        return compact(
            'commission',
            'sale',
            'itemsBreakdown',
            'totalSoldQty',
            'totalItemsCommission',
            'returnsBreakdown',
            'totalReturnDeduction',
            'totalReturnedQty',
            'adjustedCommission',
            'company'
        );
    }

    /**
     * View / Print Carpenter Commission Receipt (A4 / A5 Voucher style).
     */
    public function commissionReceipt($id)
    {
        $data = $this->prepareCommissionReceiptData($id);
        return view('admin_panel.carpenters.receipt', $data);
    }

    /**
     * View / Print Carpenter Commission Thermal Slip (80mm POS Roll).
     */
    public function commissionThermal($id)
    {
        $data = $this->prepareCommissionReceiptData($id);
        return view('admin_panel.carpenters.thermal', $data);
    }
}
