<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CycleCountItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'system_qty' => 'decimal:2',
        'counted_qty' => 'decimal:2',
        'difference' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'variance_value' => 'decimal:2',
        'is_entered' => 'boolean',
        'entered_at' => 'datetime',
    ];

    public function cycleCount()
    {
        return $this->belongsTo(CycleCount::class, 'cycle_count_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
