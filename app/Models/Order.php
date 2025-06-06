<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $primaryKey = 'orders_id';
    public $incrementing = true;

    protected $fillable = [
        'users_id',
        'orders_date',
        'orders_total_price',
        'orders_status',
        'first_name',
        'last_name',
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
        'invoice_number',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'users_id', 'users_id');
    }

    public function items(): HasMany{
        return $this->hasMany(OrderDetail::class, 'orders_id', 'orders_id');
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

    public function getRouteKeyName()
{
    return 'orders_id';
}


}
