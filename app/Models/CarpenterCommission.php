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
}
