<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    //
    protected $fillable = [
        'name', 'price', 'stock', 'description', 'weight', 'category',
        'image', 'storage_temp', 'featured', 'status'
    ];

    public function product_category():BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
