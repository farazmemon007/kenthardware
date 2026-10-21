<?php

namespace App\Services;

use App\Models\Product;
use App\Models\SaleItem;
use App\Models\StorageLocation;
use App\Models\CycleCount;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CycleCountClassificationService
{
    /**
     * Run full ABC-FSN-XYZ classification on all active products.
     */
    public function classifyAllProducts($daysBack = 90)
    {
        $startDate = Carbon::now()->subDays($daysBack)->startOfDay();
        $now = Carbon::now();

        // 1. Fetch sales aggregated per product over the period
        $salesData = SaleItem::select(
                'product_id',
                DB::raw('SUM(qty) as total_qty'),
                DB::raw('COUNT(DISTINCT DATE(created_at)) as distinct_sale_days'),
                DB::raw('MAX(created_at) as last_sale_at')
            )
            ->where('created_at', '>=', $startDate)
            ->groupBy('product_id')
            ->get()
            ->keyBy('product_id');

        // 2. Fetch weekly sales distribution (12 weeks) for XYZ analysis
        $weeklySales = [];
        for ($w = 0; $w < 12; $w++) {
            $wStart = Carbon::now()->subWeeks($w + 1)->startOfWeek();
            $wEnd = Carbon::now()->subWeeks($w)->startOfWeek();

            $weekRows = SaleItem::select('product_id', DB::raw('SUM(qty) as week_qty'))
                ->whereBetween('created_at', [$wStart, $wEnd])
                ->groupBy('product_id')
                ->pluck('week_qty', 'product_id')
                ->toArray();

            foreach ($weekRows as $pId => $qty) {
                if (!isset($weeklySales[$pId])) {
                    $weeklySales[$pId] = array_fill(0, 12, 0);
                }
                $weeklySales[$pId][$w] = (float)$qty;
            }
        }

        $products = Product::where('is_active', true)->get();

        // Step A: Calculate Consumption Value for ABC Analysis
        $productValues = [];
        $totalConsumptionValue = 0.0;

        foreach ($products as $product) {
            $cost = (float)($product->purchase_price_per_piece ?: ($product->purchase_price_per_box ?: 0));
            $sData = $salesData->get($product->id);
            $qtySold = $sData ? (float)$sData->total_qty : 0.0;
            $consumptionValue = $cost * $qtySold;

            $productValues[$product->id] = [
                'product'           => $product,
                'cost'              => $cost,
                'qty_sold'          => $qtySold,
                'consumption_value' => $consumptionValue,
                'sale_days'         => $sData ? (int)$sData->distinct_sale_days : 0,
                'last_sale_at'      => $sData ? $sData->last_sale_at : null,
            ];

            $totalConsumptionValue += $consumptionValue;
        }

        // Sort products by consumption value descending
        uasort($productValues, function ($a, $b) {
            return $b['consumption_value'] <=> $a['consumption_value'];
        });

        // Compute ABC classes based on cumulative value
        $cumulativeValue = 0.0;
        foreach ($productValues as $pId => &$item) {
            $cumulativeValue += $item['consumption_value'];
            $cumulativePercent = $totalConsumptionValue > 0 ? ($cumulativeValue / $totalConsumptionValue) * 100 : 100;

            if ($item['consumption_value'] > 0 && $cumulativePercent <= 80) {
                $item['abc'] = 'A';
            } elseif ($item['consumption_value'] > 0 && $cumulativePercent <= 95) {
                $item['abc'] = 'B';
            } else {
                $item['abc'] = 'C';
            }

            // Step B: FSN Analysis (Movement Frequency)
            $qty = $item['qty_sold'];
            $daysWithSales = $item['sale_days'];

            if ($qty <= 0 || !$item['last_sale_at'] || Carbon::parse($item['last_sale_at'])->diffInDays($now) >= 60) {
                $item['fsn'] = 'N'; // Non-moving
            } elseif ($daysWithSales >= 12 || $qty >= 30) {
                $item['fsn'] = 'F'; // Fast moving
            } else {
                $item['fsn'] = 'S'; // Slow moving
            }

            // Step C: XYZ Analysis (Demand Variability using Standard Deviation & CV)
            $wArray = $weeklySales[$pId] ?? array_fill(0, 12, 0);
            $n = count($wArray);
            $mean = array_sum($wArray) / $n;

            if ($mean <= 0 || $item['fsn'] === 'N') {
                $item['xyz'] = 'Z';
                $item['cv'] = 999.0;
            } else {
                $sumSquares = 0.0;
                foreach ($wArray as $wQty) {
                    $sumSquares += pow($wQty - $mean, 2);
                }
                $stdDev = sqrt($sumSquares / $n);
                $cv = $stdDev / $mean;
                $item['cv'] = $cv;

                if ($cv < 0.5) {
                    $item['xyz'] = 'X'; // Predictable
                } elseif ($cv <= 1.0) {
                    $item['xyz'] = 'Y'; // Moderate
                } else {
                    $item['xyz'] = 'Z'; // Erratic
                }
            }

            // Step D: Integrated Matrix -> Risk Priority Score (1 to 5) & Audit Frequency
            $matrix = $this->determineMatrixAuditFrequency($item['abc'], $item['fsn'], $item['xyz']);
            $item['risk_score'] = $matrix['risk_score'];
            $item['audit_frequency_days'] = $matrix['frequency_days'];
            $item['risk_level'] = $matrix['risk_level'];

            // Save to product model
            $product = $item['product'];
            $product->abc_class = $item['abc'];
            $product->fsn_class = $item['fsn'];
            $product->xyz_class = $item['xyz'];
            $product->risk_priority_score = $item['risk_score'];
            $product->cycle_count_class = $item['abc'];
            if ($product->cycle_count_frequency_mode !== 'custom') {
                $product->cycle_count_days = $item['audit_frequency_days'];
                if (!$product->next_count_date || $product->next_count_date < $now->toDateString()) {
                    $product->next_count_date = Carbon::today()->addDays($item['audit_frequency_days'])->toDateString();
                }
            }
            $product->save();
        }

        return $productValues;
    }

    /**
     * Map ABC-FSN-XYZ combination to Risk Level and Audit Frequency
     */
    public function determineMatrixAuditFrequency($abc, $fsn, $xyz)
    {
        if ($fsn === 'N') {
            return [
                'risk_level'     => 'Dead Check',
                'risk_score'     => 2,
                'frequency_days' => 30, // Monthly physical check
                'frequency_label'=> 'Monthly (Dead Check)',
            ];
        }

        if ($abc === 'A') {
            if ($fsn === 'F') {
                return [
                    'risk_level'     => 'Critical',
                    'risk_score'     => 5,
                    'frequency_days' => 2, // Daily / Alternate days
                    'frequency_label'=> 'Daily / Alternate Days',
                ];
            } else {
                return [
                    'risk_level'     => 'High',
                    'risk_score'     => 4,
                    'frequency_days' => 7, // Weekly
                    'frequency_label'=> 'Weekly',
                ];
            }
        } elseif ($abc === 'B') {
            if ($fsn === 'F') {
                return [
                    'risk_level'     => 'High',
                    'risk_score'     => 4,
                    'frequency_days' => 7, // Weekly
                    'frequency_label'=> 'Weekly',
                ];
            } else {
                return [
                    'risk_level'     => 'Medium',
                    'risk_score'     => 3,
                    'frequency_days' => 15, // Bi-Weekly
                    'frequency_label'=> 'Bi-Weekly (15 Days)',
                ];
            }
        } else { // 'C'
            if ($fsn === 'F') {
                return [
                    'risk_level'     => 'Medium',
                    'risk_score'     => 3,
                    'frequency_days' => 30, // Monthly
                    'frequency_label'=> 'Monthly',
                ];
            } else {
                return [
                    'risk_level'     => 'Low',
                    'risk_score'     => 1,
                    'frequency_days' => 90, // Quarterly
                    'frequency_label'=> 'Quarterly (90 Days)',
                ];
            }
        }
    }

    /**
     * Compute Location Prioritization for the Assign Screen (Image 1)
     */
    public function getLocationAuditPriorities()
    {
        $locations = StorageLocation::with(['warehouse', 'branch'])->get();
        $openCounts = CycleCount::whereIn('status', ['not_started', 'started', 'submitted', 'draft'])
            ->get()
            ->keyBy('location_id');

        $result = [];
        $now = Carbon::now();

        foreach ($locations as $loc) {
            // Count items belonging to this location
            $locName = strtolower(trim($loc->name));
            $locCode = $loc->code ? strtolower(trim($loc->code)) : '';

            $locVariantsCount = 0;
            $maxRiskScore = 1;

            $allActiveProducts = Product::where('is_active', true)->with(['warehouseStocks', 'category'])->get();
            $productsInLoc = collect();

            foreach ($allActiveProducts as $prod) {
                $hasVariantInLoc = false;
                if ($prod->color) {
                    $variants = is_string($prod->color) ? json_decode($prod->color, true) : $prod->color;
                    if (is_array($variants)) {
                        foreach ($variants as $v) {
                            $vl = strtolower(trim($v['location'] ?? ''));
                            if ($vl && ($vl === $locName || ($locCode && $vl === $locCode) || str_contains($vl, $locName) || ($locCode && str_contains($vl, $locCode)))) {
                                $locVariantsCount++;
                                $hasVariantInLoc = true;
                            }
                        }
                    }
                }

                // Check warehouse stocks
                $hasWsInLoc = false;
                foreach ($prod->warehouseStocks as $ws) {
                    if ($ws->storage_location_id == $loc->id) {
                        $hasWsInLoc = true;
                    } elseif ($loc->warehouse_id && $ws->warehouse_id == $loc->warehouse_id) {
                        $rem = strtolower(trim($ws->remarks ?? ''));
                        if ($rem && ($rem === $locName || ($locCode && $rem === $locCode) || str_contains($rem, $locName) || ($locCode && str_contains($rem, $locCode)))) {
                            $hasWsInLoc = true;
                        }
                    }
                }

                if ($hasVariantInLoc || $hasWsInLoc) {
                    $productsInLoc->push($prod);
                    $rScore = $prod->risk_priority_score ?: 2;
                    if ($rScore > $maxRiskScore) {
                        $maxRiskScore = $rScore;
                    }
                }
            }

            // Total countable items for this location
            $itemCount = $locVariantsCount;
            if ($itemCount === 0) {
                $itemCount = $productsInLoc->count();
            }
            if ($itemCount === 0) {
                // If no specific variants tagged, check if warehouse has items
                if ($loc->warehouse_id) {
                    $itemCount = DB::table('warehouse_stocks')
                        ->where('warehouse_id', $loc->warehouse_id)
                        ->where('total_pieces', '>', 0)
                        ->count();
                } elseif ($loc->branch_id) {
                    $itemCount = DB::table('stocks')
                        ->where('branch_id', $loc->branch_id)
                        ->where('qty', '>', 0)
                        ->count();
                }
            }

            // Days since last count
            $lastCountedAt = $loc->last_counted_at;
            $daysAgo = null;
            $lastCountedText = 'Never';
            if ($lastCountedAt) {
                $daysAgo = Carbon::parse($lastCountedAt)->diffInDays($now);
                $lastCountedText = $daysAgo . ' days ago';
            }

            // Max risk score of products in this location
            $maxRiskScore = $productsInLoc->max('risk_priority_score') ?: $maxRiskScore;

            // Determine Priority Badge (High / Med / Low)
            if ($daysAgo === null || $daysAgo >= 30 || $maxRiskScore >= 4) {
                $priority = 'High';
                $priorityClass = 'badge-danger';
            } elseif ($daysAgo >= 15 || $maxRiskScore == 3) {
                $priority = 'Med';
                $priorityClass = 'badge-warning';
            } else {
                $priority = 'Low';
                $priorityClass = 'badge-info';
            }

            // Open Count Status
            $openCount = $openCounts->get($loc->id);
            $openCountStatus = 'None';
            $openCountNumber = null;
            if ($openCount) {
                $openCountStatus = ucfirst(str_replace('_', ' ', $openCount->status));
                $openCountNumber = $openCount->count_number;
            }

            $zone = $loc->zone_or_aisle ?: ($loc->warehouse ? $loc->warehouse->warehouse_name : ($loc->branch ? $loc->branch->name : 'General'));

            $result[] = [
                'id'                 => $loc->id,
                'name'               => $loc->name,
                'code'               => $loc->code,
                'type'               => $loc->type,
                'display_name'       => $loc->full_display_name,
                'zone'               => $zone,
                'items_count'        => $itemCount,
                'last_counted_text'  => $lastCountedText,
                'days_ago'           => $daysAgo,
                'priority'           => $priority,
                'priority_class'     => $priorityClass,
                'open_count_status'  => $openCountStatus,
                'open_count_number'  => $openCountNumber,
                'has_open_count'     => $openCount !== null,
            ];
        }

        return $result;
    }

    /**
     * Generate Next Count Number e.g. C-0412
     */
    public function generateCountNumber($offset = 0)
    {
        $lastCount = CycleCount::withTrashed()->orderBy('id', 'desc')->first();
        $nextNum = $lastCount ? ($lastCount->id + 412 + $offset) : (412 + $offset);
        return sprintf('C-%04d', $nextNum);
    }
}
