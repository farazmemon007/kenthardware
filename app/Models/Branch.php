<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shelves()
    {
        return $this->hasMany(StorageLocation::class, 'branch_id')->where('type', 'shop_shelf');
    }
}
