<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceApprovalRequest extends Model
{
    use HasFactory;

    protected $table = 'price_approval_requests';

    protected $fillable = [
        'user_id',
        'product_id',
        'variant_key',
        'item_name',
        'sales_desc',
        'qty',
        'regular_price',
        'root_price',
        'requested_price',
        'discount_below_root',
        'status',
        'approved_by',
        'admin_notes',
        'rejection_reason',
        'responded_at',
    ];

    protected $casts = [
        'qty' => 'float',
        'regular_price' => 'float',
        'root_price' => 'float',
        'requested_price' => 'float',
        'discount_below_root' => 'float',
        'responded_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
