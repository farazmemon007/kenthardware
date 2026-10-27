<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    use HasFactory;

    protected $table = 'product_attributes';

    protected $fillable = [
        'name',
        'display_type',
        'creation_mode',
    ];

    public function values()
    {
        return $this->hasMany(ProductAttributeValue::class, 'product_attribute_id')->orderBy('sequence')->orderBy('id');
    }
}
