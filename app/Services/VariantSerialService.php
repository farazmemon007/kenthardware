<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Str;

class VariantSerialService
{
    /**
     * Generate a clean uppercase prefix from a product name.
     * E.g. "Cut Screw" -> "CS", "Wood Screw" -> "WS", "Hinges" -> "HIN"
     */
    public static function generatePrefix(string $productName): string
    {
        $clean = trim(preg_replace('/[^a-zA-Z0-9\s]/', '', $productName));
        if (empty($clean)) {
            return 'VAR';
        }

        $words = preg_split('/\s+/', $clean);

        if (count($words) >= 2) {
            $prefix = '';
            foreach ($words as $w) {
                if (!empty($w)) {
                    $prefix .= strtoupper($w[0]);
                }
                if (strlen($prefix) >= 4) break;
            }
            return $prefix ?: 'VAR';
        } else {
            // Single word: take first 3 characters
            $single = strtoupper($words[0]);
            return strlen($single) >= 3 ? substr($single, 0, 3) : str_pad($single, 3, 'X');
        }
    }

    /**
     * Generate unique serial numbers for a list of variants of a product.
     * Format: {PREFIX}-{0001}, {PREFIX}-{0002}...
     *
     * @param string $productName
     * @param array $variants
     * @return array
     */
    public static function assignSerialNumbers(string $productName, array $variants): array
    {
        $prefix = self::generatePrefix($productName);

        $index = 1;
        foreach ($variants as &$variant) {
            if (empty($variant['serial_no'])) {
                $variant['serial_no'] = sprintf('%s-%04d', $prefix, $index);
            }
            $index++;
        }

        return $variants;
    }

    /**
     * Backfill serial numbers for all existing products that have variants in `color` JSON.
     *
     * @return int Number of products updated
     */
    public static function backfillExistingProducts(): int
    {
        $products = Product::whereNotNull('color')->get();
        $updatedCount = 0;

        foreach ($products as $p) {
            if (empty($p->color)) continue;

            $parsed = is_string($p->color) ? json_decode($p->color, true) : $p->color;
            if (!is_array($parsed) || count($parsed) === 0) continue;

            // Check if it's an array of variant objects
            if (isset($parsed[0]) && is_array($parsed[0]) && (isset($parsed[0]['name']) || isset($parsed[0]['size']))) {
                $prefix = self::generatePrefix($p->item_name);
                $changed = false;
                $idx = 1;

                foreach ($parsed as &$v) {
                    if (empty($v['serial_no'])) {
                        $v['serial_no'] = sprintf('%s-%04d', $prefix, $idx);
                        $changed = true;
                    }
                    $idx++;
                }

                if ($changed) {
                    $p->color = json_encode($parsed);
                    $p->save();
                    $updatedCount++;
                }
            }
        }

        return $updatedCount;
    }
}
