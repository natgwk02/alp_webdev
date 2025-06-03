<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $table = 'order_details';
    protected $primaryKey = 'order_details_id';

    protected $fillable = [
        'orders_id',
        'products_id',
        'order_details_quantity',
        'price',
        'total',
        'status_del',
    ];

     const UPDATED_AT = null;

    protected $casts = [
        'status_del' => 'boolean',

    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'orders_id', 'orders_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'products_id', 'products_id');
    }


}
