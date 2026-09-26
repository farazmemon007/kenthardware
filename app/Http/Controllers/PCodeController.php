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

        return response()->json([
            'status'  => 'success',
            'message' => 'P-Code mapping successfully updated.',
            'mapping' => $savedMapping,
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
