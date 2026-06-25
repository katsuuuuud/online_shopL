<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $table = 'prices';
    protected $primaryKey = 'priceauditId';
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'price',
        'currency',
        'is_active',
        'date',
    ];

    protected $casts = [
        'price'     => 'decimal:2',
        'is_active' => 'boolean',
        'date'      => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'productId');
    }
}
