<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    // Define the fillable attributes
    //
    protected $fillable = [
        'categories_id',
        'products_name',
        'products_description',
        'products_stock',
        'products_image',
        'hover_image',
        'unit_price',
        'orders_price',
        'rating',
        'status_del',
    ];

    // Relationship to the Category model (each product belongs to one category)
    public function category()
    {
        return $this->belongsTo(Category::class, 'categories_id', 'categories_id');
    }

    // Optional: Relationship to CartItems (if each product can appear in many cart items)
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class, 'product_id');
    }

    // Optional: Method to check if the product is in stock
    public function isInStock(): bool
    {
        return $this->stock > 0;
    }
}
