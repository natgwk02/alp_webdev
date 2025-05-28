<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $fillable = ['users_id', 'products_id'];
    public function product()
    {
        return $this->belongsTo(Product::class, 'products_id');
    }
}
