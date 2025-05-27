<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    // Kolom yang bisa diisi melalui mass assignment
    protected $fillable = ['cart_id', 'products_id', 'quantity'];

    /**
     * Relasi Many-to-One antara CartItem dan Cart
     */
    public function cart()
    {
        // Setiap CartItem milik satu Cart
        return $this->belongsTo(Cart::class, 'cart_id');  // Foreign key: 'cart_id'
    }

    /**
     * Relasi Many-to-One antara CartItem dan Product
     */
    public function product()
    {
        // Setiap CartItem milik satu Product
        return $this->belongsTo(Product::class, 'products_id');  // Foreign key: 'product_id'
    }
}
