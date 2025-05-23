<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    // Define the fillable attributes
    protected $fillable = [
        'name', 'price', 'stock', 'description', 'weight', 'category',
        'image', 'storage_temp', 'featured', 'status', 'category_id'
    ];

    // Relationship to the Category model (each product belongs to one category)
    public function product_category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
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
