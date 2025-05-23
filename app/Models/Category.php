<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    /**
     * The primary key associated with the table.
     * @var string
     */
    protected $primaryKey = 'categories_id';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true; // Or false if needed

    /**
     * The "type" of the auto-incrementing ID.
     *
     *
     * @var string
     */
    // protected $keyType = 'string'; // Uncomment if your key is not an integer

    /**
     * The attributes that are mass assignable.
     * (Add fields like 'categories_name' here - good practice)
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'categories_name',
    ];


    /**
     * Get the products for the category.
     */
    public function products(): HasMany
    {
        // Ensure this FK on 'products' table is correct
        return $this->hasMany(Product::class, 'categories_id', 'categories_id');
    }
}
