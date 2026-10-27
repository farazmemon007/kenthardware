<?php

// app/Models/Sale.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'customer_id', 'walkin_name', 'walkin_phone', 'walkin_address', 'reference', 'total_amount_Words', 'total_bill_amount',
        'total_extradiscount', 'total_net', 'cash', 'card', 'change', 'change_account_id',
        'total_items', 'discount_type', 'sale_status', 'invoice_no', 'is_booking',
        'carpenter_id', 'carpenter_commission_type', 'carpenter_commission_rate', 'carpenter_commission_amount', 'carpenter_commission_approved'
    ];

    public function carpenter()
    {
        return $this->belongsTo(Carpenter::class, 'carpenter_id');
    }

    public function carpenterCommission()
    {
        return $this->hasOne(CarpenterCommission::class, 'sale_id');
    }

    public function getCarpenterCommissionEffectivePercentAttribute()
    {
        if ($this->carpenter_commission_type === 'percentage') {
            return (float) ($this->carpenter_commission_rate ?? 0);
        }
        if ($this->total_bill_amount > 0 && (float)$this->carpenter_commission_amount > 0) {
            return round(((float)$this->carpenter_commission_amount / (float)$this->total_bill_amount) * 100, 4);
        }
        return 0;
    }

    public function customer_relation()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function change_account()
    {
        return $this->belongsTo(Account::class, 'change_account_id', 'id');
    }

    public function product_relation()
    {
        return $this->belongsTo(Product::class, 'product', 'id');
    }

    public function getIsWalkinAttribute()
    {
        return empty($this->customer_id) || !empty($this->walkin_name);
    }

    public static function generateInvoiceNo($prefix = null)
    {
        return \App\Models\InvoiceSeries::generateNextNo($prefix);
    }

    /**
     * Find a sale flexibly by invoice number, ID, or short number (e.g. '7', '0007', 'INV-0007', 'inv-7')
     */
    public static function findByInvoiceTerm($term, array $statuses = ['posted', 'post', 'returned'])
    {
        $term = trim($term);
        if ($term === '') {
            return null;
        }

        $baseQuery = self::with(['items.product', 'customer_relation'])
            ->whereIn('sale_status', $statuses);

        // 1. Exact match on invoice_no
        $sale = (clone $baseQuery)->where('invoice_no', $term)->first();
        if ($sale) {
            return $sale;
        }

        // 2. Case-insensitive exact match
        $sale = (clone $baseQuery)->whereRaw('LOWER(invoice_no) = ?', [strtolower($term)])->first();
        if ($sale) {
            return $sale;
        }

        // 3. If input is pure numeric (e.g. "7", "0007", "0022")
        if (ctype_digit($term) || is_numeric($term)) {
            $num = (int)$term;
            if ($num > 0) {
                // Determine active/default invoice series prefix & padding
                $allSeries = \App\Models\InvoiceSeries::all();
                $defaultSeries = $allSeries->where('is_default', 1)->first() ?: $allSeries->first();
                $defaultPrefix = $defaultSeries ? strtoupper(trim($defaultSeries->prefix)) : 'INV';
                $defaultPadding = ($defaultSeries && $defaultSeries->padding) ? (int)$defaultSeries->padding : 4;

                // 3a. Try default series with configured padding (e.g. INV-0007, INV-00007)
                $paddings = array_unique([$defaultPadding, 4, 5, 6]);
                foreach ($paddings as $pad) {
                    $candidate = $defaultPrefix . '-' . str_pad($num, $pad, '0', STR_PAD_LEFT);
                    $sale = (clone $baseQuery)->where('invoice_no', $candidate)->first();
                    if ($sale) {
                        return $sale;
                    }
                }

                // 3b. Try other configured series (e.g. SIN, INVSLE, etc.)
                foreach ($allSeries as $s) {
                    $pref = strtoupper(trim($s->prefix));
                    if ($pref === $defaultPrefix) continue;
                    $pad = (int)($s->padding ?: 4);
                    $candidate = $pref . '-' . str_pad($num, $pad, '0', STR_PAD_LEFT);
                    $sale = (clone $baseQuery)->where('invoice_no', $candidate)->first();
                    if ($sale) {
                        return $sale;
                    }
                }

                // 3c. Try default prefix with regex: ^INV-0*7$
                $sale = (clone $baseQuery)
                    ->where('invoice_no', 'REGEXP', '^' . preg_quote($defaultPrefix, '/') . '-0*' . $num . '$')
                    ->latest('id')
                    ->first();
                if ($sale) {
                    return $sale;
                }

                // 3d. Any invoice ending with -0*num$, e.g. ^[A-Za-z0-9]+-0*7$
                $sale = (clone $baseQuery)
                    ->where('invoice_no', 'REGEXP', '^[A-Za-z0-9]+-0*' . $num . '$')
                    ->latest('id')
                    ->first();
                if ($sale) {
                    return $sale;
                }

                // 3e. Check if primary ID matches directly (last resort)
                $sale = (clone $baseQuery)->where('id', $num)->first();
                if ($sale) {
                    return $sale;
                }
            }
        }

        // 4. If user entered prefix with number, e.g. "inv-7", "inv 7", "inv7", "inv-07", "inv0007"
        if (preg_match('/^([A-Za-z]+)[\s\-_]*(\d+)$/', $term, $matches)) {
            $pref = strtoupper($matches[1]);
            $num = (int)$matches[2];
            if ($num > 0) {
                // Try padding 4, 5, 6
                foreach ([4, 5, 6] as $pad) {
                    $candidate = $pref . '-' . str_pad($num, $pad, '0', STR_PAD_LEFT);
                    $sale = (clone $baseQuery)->where('invoice_no', $candidate)->first();
                    if ($sale) {
                        return $sale;
                    }
                }

                // Try regex for this prefix: ^PREF-0*NUM$
                $sale = (clone $baseQuery)
                    ->where('invoice_no', 'REGEXP', '^' . preg_quote($pref, '/') . '-0*' . $num . '$')
                    ->latest('id')
                    ->first();
                if ($sale) {
                    return $sale;
                }
            }
        }

        return null;
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function journalEntries()
    {
        return $this->morphMany(JournalEntry::class, 'source');
    }

    public function returns()
    {
        return $this->hasMany(SaleReturn::class, 'sale_id');
    }

    /**
     * Check if this sale contains items that need to be purchased from local market
     */
    public function hasMarketShortage()
    {
        if (isset($this->attributes['_has_market_shortage_cached'])) {
            return $this->attributes['_has_market_shortage_cached'];
        }

        $hasShortage = false;
        $items = $this->relationLoaded('items') ? $this->items : $this->items()->with('product')->get();

        foreach ($items as $item) {
            // 1. Manual / Outsourced item has no warehouse stock
            if ($item->is_manual || empty($item->product_id)) {
                $hasShortage = true;
                break;
            }

            $soldPieces = (float)($item->total_pieces > 0 ? $item->total_pieces : $item->qty);
            if ($soldPieces <= 0) {
                continue;
            }

            // 2. If available stock was recorded on the sale item
            if ($item->stock !== null && (float)$item->stock > 0) {
                if ($soldPieces > (float)$item->stock) {
                    $hasShortage = true;
                    break;
                }
                continue;
            }

            // 3. Fallback: Check variant stock or product warehouse stock
            $product = $item->product;
            if (!$product) {
                $hasShortage = true;
                break;
            }

            $liveVariant = null;
            if (!empty($product->color)) {
                $variant = [];
                if (!empty($item->color)) {
                    $b64 = base64_decode($item->color, true);
                    $variant = ($b64 !== false) ? json_decode($b64, true) : json_decode($item->color, true);
                    if (!is_array($variant)) $variant = [];
                }

                $prodVariants = is_string($product->color) ? json_decode($product->color, true) : $product->color;
                if (is_array($prodVariants)) {
                    if (!empty($variant['serial_no'])) {
                        $liveVariant = collect($prodVariants)->firstWhere('serial_no', $variant['serial_no']);
                    }
                    if (!$liveVariant && !empty($variant['barcode'])) {
                        $liveVariant = collect($prodVariants)->firstWhere('barcode', $variant['barcode']);
                    }
                    if (!$liveVariant && !empty($variant['name'])) {
                        $liveVariant = collect($prodVariants)->first(function($v) use ($variant) {
                            $n1 = strtolower(trim($v['name'] ?? ''));
                            $n2 = strtolower(trim($variant['name'] ?? ''));
                            return $n1 === $n2 || ($n1 && $n2 && (str_contains($n1, $n2) || str_contains($n2, $n1)));
                        });
                    }
                    if (!$liveVariant && !empty($variant['size']) && $variant['size'] !== '-') {
                        $liveVariant = collect($prodVariants)->first(function($v) use ($variant) {
                            $s1 = strtolower(trim($v['size'] ?? ''));
                            $s2 = strtolower(trim($variant['size'] ?? ''));
                            return $s1 === $s2 || ($s1 && $s2 && (str_starts_with($s1, $s2) || str_starts_with($s2, $s1)));
                        });
                    }
                    if (!$liveVariant && count($prodVariants) === 1) {
                        $liveVariant = $prodVariants[0];
                    }
                }
            }

            if ($liveVariant && isset($liveVariant['stock'])) {
                $vStock = (float)$liveVariant['stock'];
                if ($vStock <= 0 || $soldPieces > $vStock) {
                    $hasShortage = true;
                    break;
                }
            } else {
                $whStock = $product->relationLoaded('warehouseStocks')
                    ? (float)$product->warehouseStocks->sum('total_pieces')
                    : (float)\App\Models\WarehouseStock::where('product_id', $product->id)->sum('total_pieces');

                if ($whStock < 0 || $whStock == 0 || $whStock < $soldPieces) {
                    $hasShortage = true;
                    break;
                }
            }
        }

        $this->attributes['_has_market_shortage_cached'] = $hasShortage;
        return $hasShortage;
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) \Illuminate\Support\Str::uuid();
            }
            if (!isset($model->is_synced)) {
                $model->is_synced = 0;
            }
        });
    }
}
