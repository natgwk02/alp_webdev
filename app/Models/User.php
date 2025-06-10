<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $primaryKey = 'users_id';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $table = 'users';

    protected $fillable = [
        'users_name',
        'users_email',
        'users_password',
        'users_phone',
        'users_address',
        'status_del',
        'role',
    ];

    protected $hidden = [
        'users_password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'status_del' => 'boolean',
    ];
    public function getAuthIdentifier()
    {
        return $this->users_id;
    }

    public function getAuthIdentifierName()
    {
        return 'users_id';
    }

    public function getRedirectRoute()
    {
        return match ($this->role) {
            'admin' => '/dashboard',
            'customer' => '/home',
        };
    }

    public function getAuthPassword()
    {
        return $this->users_password;
    }

    public function getEmailAttribute()
    {
        return $this->users_email;
    }

    public function getNameAttribute()
    {
        return $this->users_name;
    }
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
}
