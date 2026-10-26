<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carpenter extends Model
{
    use HasFactory;

    protected $table = 'carpenters';

    protected $fillable = [
        'name',
        'phone',
        'cnic',
        'address',
        'default_commission_type',
        'default_commission_rate',
        'status',
        'notes',
    ];

    protected $casts = [
        'default_commission_rate' => 'decimal:2',
        'status' => 'integer',
    ];

    public function commissions()
    {
        return $this->hasMany(CarpenterCommission::class, 'carpenter_id');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class, 'carpenter_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function lastSale()
    {
        return $this->hasOne(Sale::class, 'carpenter_id')->latestOfMany();
    }

    public function lastCommission()
    {
        return $this->hasOne(CarpenterCommission::class, 'carpenter_id')->latestOfMany();
    }

    public function getLastActivityDateAttribute()
    {
        $lastSale = $this->lastSale;
        $date = $lastSale ? $lastSale->created_at : $this->created_at;
        return $date ? \Carbon\Carbon::parse($date) : null;
    }

    public function getDaysInactiveAttribute()
    {
        $date = $this->last_activity_date;
        return $date ? (int) $date->diffInDays(now()) : 0;
    }

    /**
     * Scan active carpenters and trigger reminder notifications if inactive past threshold days.
     */
    public static function checkInactivityAndNotify(): array
    {
        $thresholdDays = (int) SystemSetting::get('carpenter_inactivity_days', 7);
        if ($thresholdDays <= 0) {
            $thresholdDays = 7;
        }

        $activeCarpenters = self::active()->with('lastSale')->get();
        $targetUsers = User::pluck('id')->toArray();
        if (empty($targetUsers)) {
            return [];
        }

        $notifiedCarpenters = [];
        $todayStart = now()->startOfDay();

        foreach ($activeCarpenters as $carpenter) {
            $daysInactive = $carpenter->days_inactive;

            if ($daysInactive >= $thresholdDays) {
                // Prevent duplicate notification today for this carpenter
                $alreadyNotifiedToday = SystemNotification::where('type', 'carpenter_inactivity')
                    ->where('source_id', $carpenter->id)
                    ->where('created_at', '>=', $todayStart)
                    ->exists();

                if (!$alreadyNotifiedToday) {
                    $thresholdText = $thresholdDays == 7 ? '1 week' : ($thresholdDays == 15 ? '15 days' : ($thresholdDays == 30 ? '1 month' : "{$thresholdDays} days"));

                    $data = [
                        'title' => "⚠️ Carpenter Inactivity: {$carpenter->name}",
                        'message' => "Carpenter '{$carpenter->name}' has not visited / had no sales for {$daysInactive} days (Alert set for {$thresholdText}).",
                        'type' => 'carpenter_inactivity',
                        'source_id' => $carpenter->id,
                        'source_type' => 'App\Models\Carpenter',
                        'action_url' => route('carpenters.index'),
                        'is_read' => false,
                    ];

                    SystemNotification::createForUsers($targetUsers, $data);
                    $notifiedCarpenters[] = [
                        'carpenter' => $carpenter->name,
                        'days_inactive' => $daysInactive,
                    ];
                }
            }
        }

        return $notifiedCarpenters;
    }
}
