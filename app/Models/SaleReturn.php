<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleReturn extends Model
{
    use HasFactory;

    protected $table = 'sale_returns';

    protected $fillable = [
        'sale_id',
        'return_invoice',
        'customer_id',
        'warehouse_id',
        'return_date',
        'bill_amount',
        'item_discount',
        'extra_discount',
        'net_amount',
        'paid',
        'balance',
        'remarks',
        'status',
        'carpenter_id',
        'carpenter_commission_rate',
        'carpenter_deduction_amount',
        'carpenter_deduction_settled',
        'carpenter_deduction_settled_amount',
        'carpenter_settled_commission_id',
        'carpenter_settled_at',
    ];

    protected $casts = [
        'return_date' => 'date',
        'bill_amount' => 'decimal:2',
        'item_discount' => 'decimal:2',
        'extra_discount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'paid' => 'decimal:2',
        'balance' => 'decimal:2',
    ];

    // Relationships
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function items()
    {
        return $this->hasMany(SaleReturnItem::class);
    }

    public function carpenter()
    {
        return $this->belongsTo(Carpenter::class, 'carpenter_id');
    }
}
