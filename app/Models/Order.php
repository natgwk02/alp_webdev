<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    //
   use HasFactory;

    protected $table = 'orders'; 

    protected $fillable = [
        'users_id',
        'order_number',
        'subtotal',
        'shipping_fee',
        'tax',
        'voucher_discount',
        'total',
        'payment_method',
        'payment_status',
        'status',
        'created_at',
        'updated_at',
    ];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }

}
