<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductColor extends Model
{
    protected $table = 'product_colors';
    protected $primaryKey = 'productColorId';
    public $timestamps = false;

    protected $fillable = ['product_id', 'color_id', 'image_path'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'productId');
    }

    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id', 'colorId');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_color_id', 'productColorId');
    }
}
