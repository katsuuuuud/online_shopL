<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'orderId';
    public $timestamps = false;

    protected $fillable = [
        'created_at',
        'amount',
        'customer_id',
        'status',
        'address',
    ];

    protected $casts = [
        'created_at' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id', 'userId');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'orderId');
    }
}
