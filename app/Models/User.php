<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Nama tabel (opsional jika memang pakai tabel bernama 'users')
    protected $table = 'users';

    /**
     * Kolom yang boleh diisi massal
     */
    protected $fillable = [
        'users_name',
        'users_email',
        'users_password',
        'users_phone',
        'users_address',
        'status_del',
        'role',
    ];

    /**
     * Kolom yang tidak boleh ditampilkan (misal saat toArray atau JSON)
     */
    protected $hidden = [
        'users_password',
        'remember_token',
    ];

    /**
     * Cast field ke tipe tertentu
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'status_del' => 'boolean',
    ];
    public function getAuthIdentifier()
{
    return $this->users_id;
}

    /**
     * Get the name of the unique identifier for the user.
     * Untuk mengubah kolom identifier default (email) ke users_email
     */
    public function getAuthIdentifierName()
    {
        return 'users_id';
    }

    public function getRedirectRoute()
{
    return match ($this->role) {
        'admin' => '/dashboard',
        default => '/home',
    };
}

    /**
     * Get the password for the user.
     * Untuk mengubah kolom password default ke users_password
     */
    public function getAuthPassword()
    {
        return $this->users_password;
    }

    /**
     * Accessor untuk kompatibilitas dengan sistem yang mengharapkan 'email'
     */
    public function getEmailAttribute()
    {
        return $this->users_email;
    }

    /**
     * Accessor untuk kompatibilitas dengan sistem yang mengharapkan 'name'
     */
    public function getNameAttribute()
    {
        return $this->users_name;
    }


}
