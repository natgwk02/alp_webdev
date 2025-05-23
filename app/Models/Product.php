<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
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
        'status_del'
    ];

    public function product_category():BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
