<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'productId';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'discount_id',
        'has_variant',
    ];

    protected $casts = [
        'has_variant' => 'boolean',
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

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'product_id', 'productId');
    }

    public function colors()
    {
        return $this->hasMany(ProductColor::class, 'product_id', 'productId');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tags', 'product_id', 'tag_id', 'productId', 'tagId');
    }
}
