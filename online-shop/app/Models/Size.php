<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $table = 'sizes';
    protected $primaryKey = 'sizeId';
    public $timestamps = false;

    protected $fillable = ['name'];

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'size_id', 'sizeId');
    }
}
