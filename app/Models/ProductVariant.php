<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'size',
        'price',
        'sale_price',
        'stock_quantity',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function getFinalPriceAttribute(): float|int
    {
        return $this->sale_price && $this->sale_price > 0 && $this->sale_price < $this->price
            ? $this->sale_price
            : ($this->price ?? 0);
    }
}
