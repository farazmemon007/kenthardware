<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'base_unit',
        'unit_type',
        'uom_data'
    ];

    protected $casts = [
        'uom_data' => 'array'
    ];

    public function getBaseUnit(): string
    {
        return !empty($this->base_unit) ? $this->base_unit : $this->name;
    }

    public function getRelatedUnits(): array
    {
        if (is_array($this->uom_data) && !empty($this->uom_data['units']) && is_array($this->uom_data['units'])) {
            return $this->uom_data['units'];
        }
        return [];
    }

    public function hasMultipleUnits(): bool
    {
        return !empty($this->getRelatedUnits());
    }

    public function getFormattedMultipleUnits(): string
    {
        $units = $this->getRelatedUnits();
        if (empty($units)) {
            return $this->getBaseUnit();
        }

        $base = $this->getBaseUnit();
        $parts = [];
        foreach ($units as $u) {
            $uName = $u['name'] ?? '';
            $uRatio = $u['ratio'] ?? 1;
            if ($uName) {
                $parts[] = "1 {$uName} = {$uRatio} {$base}";
            }
        }

        return !empty($parts) ? implode(', ', $parts) : $base;
    }
}
