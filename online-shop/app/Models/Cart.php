<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'carts';
    protected $primaryKey = 'cartId';

    protected $fillable = ['user_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'userId');
    }

    public function items()
    {
        return $this->hasMany(CartItem::class, 'cart_id', 'cartId');
    }
}
