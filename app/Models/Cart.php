<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    // Kolom yang bisa diisi secara mass-assignment
    protected $fillable = ['users_id'];

    /**
     * Relasi One-to-Many antara Cart dan CartItem
     */
    public function items()
    {
        // Menghubungkan Cart dengan banyak CartItem
        // Laravel akan secara otomatis menganggap foreign key pada CartItem adalah 'cart_id'
        return $this->hasMany(CartItem::class, 'cart_id'); // Pastikan 'cart_id' adalah foreign key di tabel cart_items
    }

    /**
     * Relasi Many-to-One antara Cart dan User
     */
    public function user()
    {
        // Menghubungkan Cart ke satu User
        // Laravel akan secara otomatis menggunakan 'user_id' sebagai foreign key
        return $this->belongsTo(User::class, 'users_id'); 
    }
}
