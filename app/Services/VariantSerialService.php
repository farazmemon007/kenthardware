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

    /**
     * Clean and format hardware dimension / size strings.
     * Automatically fixes Excel auto-converted date corruptions:
     * - "2026-03-04" -> "3/4\""
     * - "2026-01-02" -> "1/2\""
     * - "2002-01-01" -> "1-1/2\""
     * - "2002-02-01" -> "2-1/2\""
     * - "2004-01-01" -> "1-1/4\""
     * - "2026-05-08" -> "5/8\""
     * - "2026-03-08" -> "3/8\""
     * - "2026-01-04" -> "1/4\""
     * - "1932-05-01" -> "5/32\""
     * - "1964-09-01" -> "9/64\""
     */
    public static function cleanHardwareSize(?string $size): string
    {
        if ($size === null) return '-';
        $size = trim($size);
        if ($size === '' || $size === '.') return '-';

        // Remove escaped quotes e.g. ""10"" AB"" or "10"" AB"
        if (str_starts_with($size, '"') && str_ends_with($size, '"')) {
            $size = trim($size, '"');
        }
        $size = str_replace('""', '"', $size);

        // Check for Excel auto-converted date format YYYY-MM-DD
        if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $size, $matches)) {
            $year  = (int) $matches[1];
            $month = (int) $matches[2];
            $day   = (int) $matches[3];

            if ($year === 1964) {
                return "{$month}/64\"";
            }
            if ($year === 1932) {
                return "{$month}/32\"";
            }
            if ($year === 2002 && $month === 1 && $day === 1) {
                return '1-1/2"';
            }
            if ($year === 2002 && $month === 2 && $day === 1) {
                return '2-1/2"';
            }
            if ($year === 2004 && $month === 1 && $day === 1) {
                return '1-1/4"';
            }
            if ($year >= 2020 && $year <= 2035) {
                return "{$month}/{$day}\"";
            }
        }

        // Check for DD-Mon or Mon-DD format e.g. "04-Mar", "08-May", "4-Mar", "Mar-04"
        $months = [
            'jan' => 1, 'feb' => 2, 'mar' => 3, 'apr' => 4, 'may' => 5, 'jun' => 6,
            'jul' => 7, 'aug' => 8, 'sep' => 9, 'oct' => 10, 'nov' => 11, 'dec' => 12
        ];
        if (preg_match('/^(\d{1,2})-([A-Za-z]{3})(?:-(\d{2,4}))?$/', $size, $m)) {
            $monKey = strtolower($m[2]);
            if (isset($months[$monKey])) {
                $month = $months[$monKey];
                $day = (int) $m[1];
                return "{$month}/{$day}\"";
            }
        } elseif (preg_match('/^([A-Za-z]{3})-(\d{1,2})(?:-(\d{2,4}))?$/', $size, $m)) {
            $monKey = strtolower($m[1]);
            if (isset($months[$monKey])) {
                $month = $months[$monKey];
                $day = (int) $m[2];
                return "{$month}/{$day}\"";
            }
        }

        return $size;
    }

    /**
     * Normalize unit of measure string to match system standard units.
     */
    public static function normalizeUnit(?string $unit): string
    {
        if (empty($unit)) return 'Pcs';
        $u = strtolower(trim($unit));
        return match($u) {
            'qty', 'pcs', 'pc', 'piece', 'pieces' => 'Pcs',
            'feet', 'ft', 'foot'                  => 'Ft',
            'grams', 'gram', 'gm', 'g'            => 'Gm',
            'kg', 'kgs', 'kilogram'               => 'Kg',
            'set', 'sets'                         => 'Set',
            'meter', 'mtr', 'm'                   => 'Meter',
            'carton', 'ctn', 'box'                => 'Carton',
            'dozen', 'doz'                        => 'Dozen',
            'roll', 'rolls'                       => 'Roll',
            'mm'                                  => 'mm',
            'bundle', 'bdl'                       => 'Bundle',
            default                               => ucfirst(trim($unit)),
        };
    }
}
