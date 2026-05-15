<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $table = 'products';

    protected $primaryKey = 'id';

    protected $fillable = [
        'proname',
        'cateid',
        'brandid',
        'slug',
        'price',
        'sale_price',
        'stock_quantity',
        'thumbnail',
        'status',
        'description'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'cateid', 'cateid');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brandid', 'id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'id');
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class, 'product_id', 'id');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id', 'id');
    }

    public function getFinalPriceAttribute(): float|int
    {
        $variantFinalPrice = $this->variants()
            ->where('status', true)
            ->where('stock_quantity', '>', 0)
            ->selectRaw('MIN(COALESCE(NULLIF(sale_price, 0), price)) as min_price')
            ->value('min_price');

        if ($variantFinalPrice) {
            return (int) $variantFinalPrice;
        }

        return $this->sale_price && $this->sale_price > 0 && $this->sale_price < $this->price
            ? $this->sale_price
            : $this->price;
    }
}
