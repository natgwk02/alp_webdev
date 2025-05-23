<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Nama tabel (opsional jika kamu memang pakai tabel bernama 'users')
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
     * Kolom login yang digunakan oleh Auth::attempt()
     */
    public function getAuthIdentifierName()
    {
        return 'users_email';
    }

    /**
     * Kolom password yang digunakan oleh Auth
     */
    public function getAuthPassword()
    {
        return $this->users_password;
    }

    /**
     * Kolom yang tidak boleh ditampilkan (misal saat toArray atau JSON)
     */
    protected $hidden = [
        'users_password',
        'remember_token',
    ];

    /**
     * Cast field ke tipe tertentu (optional)
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'status_del' => 'boolean',
    ];

    
}
