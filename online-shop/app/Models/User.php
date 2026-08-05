<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $primaryKey = 'userId';
    public $timestamps    = false;

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

    public function cart()
    {
        return $this->hasOne(Cart::class, 'user_id', 'userId');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id', 'userId');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasRole('admin') && $this->hasVerifiedEmail();
    }
}
