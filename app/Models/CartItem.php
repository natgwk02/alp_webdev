<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = ['cart_id', 'products_id', 'quantity'];

    public function cart()
    {
        // Setiap CartItem milik satu Cart
        return $this->belongsTo(Cart::class, 'cart_id'); 
    }

    public function product()
    {
        // Setiap CartItem milik satu Product
        return $this->belongsTo(Product::class, 'products_id', 'products_id');
    }

}
