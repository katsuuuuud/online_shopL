<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $table = 'product_variants';
    protected $primaryKey = 'variantId';
    public $timestamps = false;

    protected $fillable = ['product_color_id', 'size_id', 'sku'];

    public function productColor()
    {
        return $this->belongsTo(ProductColor::class, 'product_color_id', 'productColorId');
    }

    public function size()
    {
        return $this->belongsTo(Size::class, 'size_id', 'sizeId');
    }

    public function stock()
    {
        return $this->hasOne(Stock::class, 'variant_id', 'variantId');
    }
}
