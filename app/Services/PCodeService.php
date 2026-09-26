<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class PCodeService
{
    /**
     * Default digit-to-alphabet mapping:
     * 1 => A, 2 => B, 3 => C, 4 => D, 5 => E,
     * 6 => F, 7 => G, 8 => H, 9 => I, 0 => J
     */
    public static function getDefaultMapping(): array
    {
        return [
            '1' => 'A',
            '2' => 'B',
            '3' => 'C',
            '4' => 'D',
            '5' => 'E',
            '6' => 'F',
            '7' => 'G',
            '8' => 'H',
            '9' => 'I',
            '0' => 'J',
        ];
    }

    /**
     * Get the active mapping from settings, falling back to default.
     */
    public static function getMapping(): array
    {
        $cacheKey = 'setting_pcode_mapping';

        return Cache::remember($cacheKey, 3600, function () {
            $setting = Setting::where('key', 'pcode_mapping')->first();
            $default = self::getDefaultMapping();

            if (!$setting || empty($setting->value)) {
                return $default;
            }

            $saved = is_array($setting->value) ? $setting->value : json_decode($setting->value, true);

            if (!is_array($saved)) {
                return $default;
            }

            // Ensure all digits 1..9, 0 are present
            return array_merge($default, $saved);
        });
    }

    /**
     * Save/update the digit-to-alphabet mapping.
     */
    public static function saveMapping(array $inputMapping): array
    {
        $default = self::getDefaultMapping();
        $cleaned = [];

        // Order: 1, 2, 3, 4, 5, 6, 7, 8, 9, 0
        $orderedKeys = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '0'];

        foreach ($orderedKeys as $key) {
            $val = isset($inputMapping[$key]) ? trim((string)$inputMapping[$key]) : ($default[$key] ?? '');
            $val = strtoupper(substr($val, 0, 1));
            $cleaned[$key] = $val !== '' ? $val : ($default[$key] ?? '');
        }

        Setting::updateOrCreate(
            ['key' => 'pcode_mapping'],
            [
                'value'       => json_encode($cleaned),
                'type'        => 'json',
                'group'       => 'general',
                'label'       => 'P-Code Cipher Mapping',
                'description' => 'Maps digits 1-9 and 0 to confidential alphabet letters for secret pricing code',
            ]
        );

        Cache::forget('setting_pcode_mapping');

        return $cleaned;
    }

    /**
     * Convert an amount/number to its P-Code string.
     * E.g. 250 -> 'BEJ', 1234567890 -> 'ABCDEFGHIJ'
     */
    public static function encode($amount): string
    {
        if ($amount === null || $amount === '') {
            return '';
        }

        // Clean string from currency symbols, commas or whitespace
        $str = preg_replace('/[^\d\.]/', '', (string)$amount);
        if ($str === '') {
            return '';
        }

        // Normalize numeric value
        $num = (float)$str;
        if ($num == 0 && $str === '0') {
            $mapping = self::getMapping();
            return $mapping['0'] ?? 'J';
        }

        // Check if integer (no non-zero decimals)
        if (floor($num) == $num) {
            $str = (string)(int)round($num);
        } else {
            // Trim unnecessary trailing zeros if float
            $str = rtrim(rtrim(sprintf('%.2f', $num), '0'), '.');
        }

        $mapping = self::getMapping();
        $output = '';

        for ($i = 0; $i < strlen($str); $i++) {
            $ch = $str[$i];
            if (isset($mapping[$ch])) {
                $output .= $mapping[$ch];
            } else {
                $output .= $ch; // Preserve decimal '.' or other delimiters
            }
        }

        return $output;
    }

    /**
     * Reverse a P-Code string back to its numeric value.
     */
    public static function decode(string $pcode): string
    {
        if (empty($pcode)) {
            return '';
        }

        $mapping = self::getMapping();
        $reversed = array_flip($mapping);

        $output = '';
        $pcodeUpper = strtoupper($pcode);

        for ($i = 0; $i < strlen($pcodeUpper); $i++) {
            $ch = $pcodeUpper[$i];
            if (isset($reversed[$ch])) {
                $output .= $reversed[$ch];
            } else {
                $output .= $ch;
            }
        }

        return $output;
    }
}
