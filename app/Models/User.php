<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'users_id'; 
    public $timestamps = false;

    protected $fillable = [
        'users_name',
        'users_email',
        'users_password',
    ];

    protected $hidden = [
        'users_password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'status_del' => 'boolean',
    ];

    public function getAuthIdentifierName()
    {
        return 'users_email';
    }

    public function getAuthPassword()
    {
        return $this->users_password;
    }
    
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

public function getEmailAttribute()
{
    return $this->attributes['users_email'];
}


public function getEmailForPasswordReset()
{
    return $this->users_email;
}

public function setEmailAttribute($value)
{
    $this->attributes['users_email'] = $value;
}

protected static function boot()
{
    parent::boot();

    static::creating(function ($model) {
        unset($model->email); // force ignore 'email' column
    });
}


}

