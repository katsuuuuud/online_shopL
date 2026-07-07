<?php
declare(strict_types=1);
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    protected $table = 'carts';
    protected $primaryKey = 'cartId';

    protected $fillable = ['user_id'];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'userId');
    }

    public function items():HasMany
    {
        return $this->hasMany(CartItem::class, 'cart_id', 'cartId');
    }
}
