<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StorageLocation extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creater_id');
    }

    /**
     * Get clean display name e.g., "Rack A-1 (Warehouse 1)" or "Shelf 3 (Shop 1)"
     */
    public function getFullDisplayNameAttribute()
    {
        if ($this->type === 'warehouse_rack') {
            $parentName = $this->warehouse ? $this->warehouse->warehouse_name : 'Warehouse';
            $code = $this->code ? " [{$this->code}]" : '';
            return "Rack: {$this->name}{$code} ({$parentName})";
        } else {
            $parentName = $this->branch ? $this->branch->name : 'Shop';
            $code = $this->code ? " [{$this->code}]" : '';
            return "Shelf: {$this->name}{$code} ({$parentName})";
        }
    }
}
