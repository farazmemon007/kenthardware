<?php

namespace App\Http\Controllers;

use App\Services\PCodeService;
use Illuminate\Http\Request;

class PCodeController extends Controller
{
    /**
     * Get current P-Code mapping JSON.
     */
    public function getMapping()
    {
        return response()->json([
            'status'  => 'success',
            'mapping' => PCodeService::getMapping(),
        ]);
    }

    /**
     * Save/update P-Code mapping.
     */
    public function updateMapping(Request $request)
    {
        $mapping = $request->input('mapping', []);

        if (!is_array($mapping) || empty($mapping)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid mapping data provided.',
            ], 422);
        }

        $savedMapping = PCodeService::saveMapping($mapping);
        $syncedCount = PCodeService::syncAllProductsPCodes();

        return response()->json([
            'status'       => 'success',
            'message'      => "P-Code mapping saved and {$syncedCount} product(s) synchronized successfully.",
            'mapping'      => $savedMapping,
            'synced_count' => $syncedCount,
        ]);
    }

    /**
     * Explicit endpoint to synchronize all products P-Codes with the active mapping.
     */
    public function syncAllProducts(Request $request)
    {
        $syncedCount = PCodeService::syncAllProductsPCodes();

        return response()->json([
            'status'       => 'success',
            'message'      => "Successfully synchronized P-Codes for {$syncedCount} product(s) and their variants.",
            'synced_count' => $syncedCount,
        ]);
    }

    /**
     * Convert an amount to P-Code via AJAX.
     */
    public function encodeAjax(Request $request)
    {
        $amount = $request->input('amount');
        $pcode = PCodeService::encode($amount);

        return response()->json([
            'status' => 'success',
            'amount' => $amount,
            'pcode'  => $pcode,
        ]);
    }
}
