<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        return $this->hasMany(Product::class, 'category_id');
    }
    

}
