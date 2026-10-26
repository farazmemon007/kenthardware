<?php

namespace App\Http\Controllers;

use App\Models\PriceApprovalRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PriceApprovalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Staff submits a request to sell an item below Root / Floor Price
     */
    public function requestApproval(Request $request)
    {
        try {
            // Clean/Extract integer product_id if compound ID was provided (e.g. "13|variant|...")
            if ($request->has('product_id')) {
                $rawPid = $request->input('product_id');
                if (is_string($rawPid) && str_contains($rawPid, '|')) {
                    $parts = explode('|', $rawPid);
                    $rawPid = $parts[0];
                }
                $request->merge([
                    'product_id' => is_numeric($rawPid) ? (int)$rawPid : null
                ]);
            }

            $validated = $request->validate([
                'product_id'      => 'nullable|integer',
                'variant_key'     => 'nullable|string',
                'item_name'       => 'required|string',
                'sales_desc'      => 'nullable|string',
                'qty'             => 'required|numeric|min:0.001',
                'regular_price'   => 'required|numeric',
                'root_price'      => 'required|numeric',
                'requested_price' => 'required|numeric',
            ]);

            $user = Auth::user();
            $rootPrice = (float) $validated['root_price'];
            $reqPrice = (float) $validated['requested_price'];

            // If user is Super Admin or Admin, they don't need approval
            if ($user && $user->isSuperAdmin()) {
                return response()->json([
                    'success'       => true,
                    'is_admin'      => true,
                    'status'        => 'approved',
                    'message'       => 'Admin price override applied directly.',
                    'request_id'    => null,
                ]);
            }

            $discountBelowRoot = max(0, $rootPrice - $reqPrice);

            $approval = PriceApprovalRequest::create([
                'user_id'             => $user->id,
                'product_id'          => $validated['product_id'] ?? null,
                'variant_key'         => $validated['variant_key'] ?? null,
                'item_name'           => $validated['item_name'],
                'sales_desc'          => $validated['sales_desc'] ?? null,
                'qty'                 => $validated['qty'],
                'regular_price'       => $validated['regular_price'],
                'root_price'          => $rootPrice,
                'requested_price'     => $reqPrice,
                'discount_below_root' => $discountBelowRoot,
                'status'              => 'pending',
            ]);

            // Notify all Super Admins & Admins via system_notifications
            try {
                $admins = User::all()->filter(fn($u) => $u->isSuperAdmin());
                $staffName = $user->name ?: 'Staff Employee';
                $formattedPrice = number_format($reqPrice, 2);
                $formattedRoot = number_format($rootPrice, 2);
                $notifTitle = "Price Approval Required";
                $notifMsg = "{$staffName} requested to sell '{$validated['item_name']}' at Rs. {$formattedPrice} (Below Root Price Rs. {$formattedRoot})";

                foreach ($admins as $admin) {
                    DB::table('system_notifications')->insert([
                        'user_id'     => $admin->id,
                        'title'       => $notifTitle,
                        'message'     => $notifMsg,
                        'type'        => 'warning',
                        'source_id'   => $approval->id,
                        'source_type' => 'PriceApprovalRequest',
                        'action_url'  => 'javascript:window.openPriceApprovalModal(' . $approval->id . ')',
                        'is_read'     => false,
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ]);
                }
            } catch (\Exception $ne) {
                Log::warning('Failed inserting admin notification: ' . $ne->getMessage());
            }

            return response()->json([
                'success'    => true,
                'is_admin'   => false,
                'status'     => 'pending',
                'request_id' => $approval->id,
                'message'    => 'Price approval request sent to Admin. Waiting for decision...',
            ]);

        } catch (\Exception $e) {
            Log::error('Price approval request error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create approval request: ' . $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Staff polls to check if request was approved or rejected
     */
    public function checkStatus($id)
    {
        $approval = PriceApprovalRequest::with(['approver', 'user'])->find($id);

        if (!$approval) {
            return response()->json([
                'success' => false,
                'message' => 'Request not found',
            ], 404);
        }

        return response()->json([
            'success'          => true,
            'id'               => $approval->id,
            'status'           => $approval->status,
            'requested_price'  => $approval->requested_price,
            'qty'              => $approval->qty,
            'item_name'        => $approval->item_name,
            'approver_name'    => $approval->approver ? $approval->approver->name : 'Admin',
            'responded_at'     => $approval->responded_at ? $approval->responded_at->format('h:i A') : null,
            'rejection_reason' => $approval->rejection_reason,
            'admin_notes'      => $approval->admin_notes,
        ]);
    }

    /**
     * Staff cancels their pending request
     */
    public function cancelRequest($id)
    {
        $approval = PriceApprovalRequest::where('id', $id)
            ->where(function ($q) {
                $q->where('user_id', Auth::id())
                  ->orWhereRaw('? = 1', [Auth::user() && Auth::user()->isSuperAdmin() ? 1 : 0]);
            })
            ->first();

        if ($approval && $approval->status === 'pending') {
            $approval->status = 'cancelled';
            $approval->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Request cancelled successfully',
        ]);
    }

    /**
     * Admin fetches full details of a specific price approval request
     */
    public function getRequestDetails($id)
    {
        $user = Auth::user();
        if (!$user || !$user->isSuperAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access',
            ], 403);
        }

        $req = PriceApprovalRequest::with(['user', 'product', 'approver'])->find($id);
        if (!$req) {
            return response()->json([
                'success' => false,
                'message' => 'Price approval request not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'request' => [
                'id'                  => $req->id,
                'status'              => $req->status,
                'staff_id'            => $req->user_id,
                'staff_name'          => $req->user ? $req->user->name : 'Staff Employee',
                'staff_email'         => $req->user ? $req->user->email : '',
                'product_id'          => $req->product_id,
                'variant_key'         => $req->variant_key,
                'item_name'           => $req->item_name,
                'sales_desc'          => $req->sales_desc,
                'qty'                 => $req->qty,
                'regular_price'       => $req->regular_price,
                'root_price'          => $req->root_price,
                'requested_price'     => $req->requested_price,
                'discount_below_root' => $req->discount_below_root,
                'created_at_time'     => $req->created_at ? $req->created_at->format('h:i A') : '',
                'created_at_human'    => $req->created_at ? $req->created_at->diffForHumans() : '',
                'approver_name'       => $req->approver ? $req->approver->name : null,
                'admin_notes'         => $req->admin_notes,
                'rejection_reason'    => $req->rejection_reason,
                'responded_at'        => $req->responded_at ? $req->responded_at->format('h:i A') : null,
            ]
        ]);
    }

    /**
     * Admin polls for pending approval requests
     */
    public function getPendingForAdmin()
    {
        $user = Auth::user();
        if (!$user || !$user->isSuperAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access',
            ], 403);
        }

        $pending = PriceApprovalRequest::with(['user', 'product'])
            ->where('status', 'pending')
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($req) {
                return [
                    'id'                  => $req->id,
                    'staff_id'            => $req->user_id,
                    'staff_name'          => $req->user ? $req->user->name : 'Staff Employee',
                    'staff_email'         => $req->user ? $req->user->email : '',
                    'product_id'          => $req->product_id,
                    'variant_key'         => $req->variant_key,
                    'item_name'           => $req->item_name,
                    'sales_desc'          => $req->sales_desc,
                    'qty'                 => $req->qty,
                    'regular_price'       => $req->regular_price,
                    'root_price'          => $req->root_price,
                    'requested_price'     => $req->requested_price,
                    'discount_below_root' => $req->discount_below_root,
                    'created_at_time'     => $req->created_at ? $req->created_at->format('h:i A') : '',
                    'created_at_human'    => $req->created_at ? $req->created_at->diffForHumans() : '',
                ];
            });

        return response()->json([
            'success'  => true,
            'count'    => $pending->count(),
            'requests' => $pending,
        ]);
    }

    /**
     * Admin approves or rejects the request
     */
    public function respondApproval(Request $request, $id)
    {
        $admin = Auth::user();
        if (!$admin || !$admin->isSuperAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: Only Admin / Super Admin can approve or reject price requests.',
            ], 403);
        }

        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'reason' => 'nullable|string',
            'notes'  => 'nullable|string',
        ]);

        $approval = PriceApprovalRequest::findOrFail($id);

        if ($approval->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => "This request has already been {$approval->status}.",
            ]);
        }

        $isApproved = ($validated['action'] === 'approve');
        $approval->status = $isApproved ? 'approved' : 'rejected';
        $approval->approved_by = $admin->id;
        $approval->admin_notes = $validated['notes'] ?? null;
        $approval->rejection_reason = $validated['reason'] ?? null;
        $approval->responded_at = now();
        $approval->save();

        // Create notification for the staff member
        try {
            $formattedPrice = number_format($approval->requested_price, 2);
            $actionWord = $isApproved ? 'Approved' : 'Rejected';
            $notifTitle = "Price Request {$actionWord}";
            $notifMsg = "Admin {$admin->name} has {$actionWord} your request to sell '{$approval->item_name}' at Rs. {$formattedPrice}.";

            DB::table('system_notifications')->insert([
                'user_id'     => $approval->user_id,
                'title'       => $notifTitle,
                'message'     => $notifMsg,
                'type'        => $isApproved ? 'success' : 'danger',
                'source_id'   => $approval->id,
                'source_type' => 'PriceApprovalResponse',
                'action_url'  => null,
                'is_read'     => false,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        } catch (\Exception $ne) {
            Log::warning('Failed inserting staff notification: ' . $ne->getMessage());
        }

        return response()->json([
            'success' => true,
            'status'  => $approval->status,
            'message' => "Price request {$approval->status} successfully.",
        ]);
    }
}
