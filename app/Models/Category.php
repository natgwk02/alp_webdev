<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'categories_id';

    protected $fillable = [
        'categories_name', 'status_del'
    ];
    //
    public function products(): HasMany
    {
        // Ensure this FK on 'products' table is correct
        return $this->hasMany(Product::class, 'categories_id', 'categories_id');
    }
    

}
