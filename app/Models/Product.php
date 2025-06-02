<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Product extends Model
{
    use HasFactory;
    // Define the fillable attributes
    //
    protected $table = 'products';
    protected $primaryKey = 'products_id';


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
        'calories',
        'protein',
        'fat',
        'status_del',
        'low_stock_threshold'

    ];

    // Relationship to the Category model (each product belongs to one category)
    public function category()
    {
    return $this->belongsTo(Category::class, 'categories_id');
    }

    // Optional: Relationship to CartItems (if each product can appear in many cart items)
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class, 'products_id');
    }

    // Optional: Method to check if the product is in stock
    public function isInStock(): bool
    {
        return $this->stock > 0;
    }

    /**
     * Get the product's status based on stock.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function status(): Attribute
{
    return Attribute::make(
        get: function ($value) {
            $stock = $this->products_stock;
            $threshold = $this->low_stock_threshold ?? 10; // Default threshold if not set
            
            if ($stock <= 0) {
                return 'Out of Stock';
            } elseif ($stock <= $threshold) {
                return 'Low Stock';
            } else {
                return 'In Stock';
            }
        }
    );
}
    

}
