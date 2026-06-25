<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $primaryKey = 'productId';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'discount_id',
        'has_discount',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'categoryId');
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class, 'discount_id', 'discountId');
    }

    public function activePrice()
    {
        return $this->hasOne(Price::class, 'product_id', 'productId')
            ->where('is_active', 1);
    }

    public function audit()
    {
        return $this->hasOne(ProductAudit::class, 'product_id', 'productId');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'product_id', 'productId');
    }
}
