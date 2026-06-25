<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatables
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $primaryKey = 'userId';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public $timestamps = false;

    public function cart()
    {
        return $this->hasOne(Cart::class, 'user_id', 'userId');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id', 'userId');
    }
}
