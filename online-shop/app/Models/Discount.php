<?php
declare(strict_types=1);
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;
    protected $primaryKey = 'discountId';
    public $timestamps = false;

    protected $fillable = [
        'description',
        'discount_value',
        'type',
        'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
    ];
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
