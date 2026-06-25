<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAudit extends Model
{
    protected $table = 'product_audit';
    protected $primaryKey = 'auditId';
    public $timestamps = false;

    protected $fillable = ['product_id', 'quantity'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'productId');
    }
}
