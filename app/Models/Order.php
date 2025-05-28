<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    //
   use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'users_id',
        'orders_date',
        'orders_total_price',
        'orders_status',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'city',
        'zip',
        'country',
        'payment_method',
        'payment_status',
        'subtotal',
        'shipping_fee',
        'tax',
        'voucher_discount',
        'total',
        'notes',
        'status_del',
    ];

    protected $casts = [
        'orders_date' => 'datetime',
        'orders_total_price' => 'float',
        'total' => 'float',
        'subtotal' => 'float',
        'shipping_fee' => 'float',
        'tax' => 'float',
        'voucher_discount' => 'float',
        'status_del' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime', 
    ];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }

    protected function statusBadgeClass(): Attribute
    {
        return Attribute::make(
            get: fn () => match (strtolower($this->orders_status)) {
                'processing' => 'bg-warning text-dark',
                'shipped' => 'bg-info',
                'delivered' => 'bg-success',
                'cancelled' => 'bg-danger',
                'pending' => 'bg-secondary',
                default => 'bg-light text-dark',
            }
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'users_id');
    }

}
