<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $table = 'colors';
    protected $primaryKey = 'colorId';
    public $timestamps = false;

    protected $fillable = ['name', 'hex_code'];

    public function productColors()
    {
        return $this->hasMany(ProductColor::class, 'color_id', 'colorId');
    }
}
