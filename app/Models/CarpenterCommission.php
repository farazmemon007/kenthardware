<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarpenterCommission extends Model
{
    use HasFactory;

    protected $table = 'carpenter_commissions';

    protected $fillable = [
        'carpenter_id',
        'sale_id',
        'commission_type',
        'commission_rate',
        'sale_amount',
        'commission_amount',
        'return_deduction_amount',
        'net_payable_amount',
        'effective_percentage',
        'status',
        'approved_by',
        'approved_at',
        'paid_by',
        'paid_at',
        'payment_account_id',
        'notes',
    ];

    protected $casts = [
        'commission_rate' => 'decimal:2',
        'sale_amount' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'return_deduction_amount' => 'decimal:2',
        'net_payable_amount' => 'decimal:2',
        'effective_percentage' => 'decimal:4',
        'approved_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function carpenter()
    {
        return $this->belongsTo(Carpenter::class, 'carpenter_id');
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function payer()
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    public function paymentAccount()
    {
        return $this->belongsTo(Account::class, 'payment_account_id');
    }

    /**
     * Get pending return deduction records due for this carpenter.
     */
    public function getPendingReturnDeductions()
    {
        if (!$this->carpenter_id) {
            return collect();
        }

        return \App\Models\SaleReturn::where('carpenter_id', $this->carpenter_id)
            ->where('carpenter_deduction_amount', '>', 0)
            ->where(function($q) {
                $q->where('carpenter_deduction_settled', 0)
                  ->orWhereNull('carpenter_deduction_settled');
            })
            ->whereRaw('carpenter_deduction_amount > COALESCE(carpenter_deduction_settled_amount, 0)')
            ->orderBy('id', 'asc')
            ->get();
    }

    /**
     * Total unsettled return dues for this carpenter.
     */
    public function getPendingReturnDuesTotalAttribute(): float
    {
        if ($this->status === 'paid') {
            return (float)($this->return_deduction_amount ?? 0);
        }

        $returns = $this->getPendingReturnDeductions();
        $total = 0;
        foreach ($returns as $r) {
            $unsettled = (float)$r->carpenter_deduction_amount - (float)($r->carpenter_deduction_settled_amount ?? 0);
            $total += max(0, $unsettled);
        }
        return round($total, 2);
    }

    /**
     * Net payable commission after deducting return dues.
     */
    public function getNetPayableAttribute(): float
    {
        if ($this->status === 'paid') {
            return (float)($this->net_payable_amount > 0 ? $this->net_payable_amount : max(0, $this->commission_amount - ($this->return_deduction_amount ?? 0)));
        }

        $dues = $this->pending_return_dues_total;
        return max(0, round((float)$this->commission_amount - $dues, 2));
    }
}
