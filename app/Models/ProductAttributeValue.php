<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttributeValue extends Model
{
    use HasFactory;

    protected $table = 'product_attribute_values';

    protected $fillable = [
        'product_attribute_id',
        'value',
        'color_hex',
        'image',
        'extra_price',
        'is_custom',
        'sequence',
    ];

    protected $casts = [
        'extra_price' => 'decimal:2',
        'is_custom' => 'boolean',
        'sequence' => 'integer',
    ];

    public function attribute()
    {
        return $this->belongsTo(ProductAttribute::class, 'product_attribute_id');
    }
}
