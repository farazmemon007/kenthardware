<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CycleCount extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'count_date' => 'date',
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'total_variance_value' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(CycleCountItem::class, 'cycle_count_id');
    }

    public function location()
    {
        return $this->belongsTo(StorageLocation::class, 'location_id');
    }

    public function counter()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function approvedByUser()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    /**
     * Formatted duration string e.g. "34m 12s" or "1h 05m 20s"
     */
    public function getFormattedDurationAttribute()
    {
        $seconds = $this->duration_seconds;
        if (!$seconds && $this->started_at && $this->submitted_at) {
            $seconds = $this->started_at->diffInSeconds($this->submitted_at);
        }

        if ($seconds === null && !$this->started_at) {
            return '—';
        }
        if ($seconds === 0 || $seconds < 60) {
            return ($seconds > 0 ? $seconds . 's' : '< 1m');
        }

        $h = floor($seconds / 3600);
        $m = floor(($seconds % 3600) / 60);
        $s = $seconds % 60;

        if ($h > 0) {
            return sprintf('%dh %02dm %02ds', $h, $m, $s);
        }
        return sprintf('%dm %02ds', $m, $s);
    }

    /**
     * Calculation of progress percent
     */
    public function getProgressPercentAttribute()
    {
        if ($this->total_items <= 0) return 0;
        return min(100, (int) round(($this->entered_items / $this->total_items) * 100));
    }
}
