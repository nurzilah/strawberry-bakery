<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Cart;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'city',
        'district',
        'postal_code',
        'address_note',
        'delivery_preference',
        'role',
        'google_id',
        'avatar',
        'is_verified',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_verified' => 'boolean',
    ];

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function isSuperadmin()
    {
        return $this->role === 'superadmin';
    }

    public function isAdmin()
    {
        // Admin dashboard access for admin, superadmin, and kasir
        return in_array($this->role, ['admin', 'superadmin', 'kasir']);
    }

    public function isKasir()
    {
        return $this->role === 'kasir';
    }

    public function isCustomer()
    {
        return $this->role === 'customer' || empty($this->role);
    }

    public function isOwner()
    {
        return $this->role === 'owner';
    }

    public function isKurir()
    {
        return $this->role === 'kurir';
    }

    public function courierOrders()
    {
        return $this->hasMany(Order::class, 'courier_id');
    }
}
