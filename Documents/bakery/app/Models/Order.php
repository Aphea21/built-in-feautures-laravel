<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'tbl_orders';

    protected $fillable = [
        'customer_id',
        'prepared_by',
        'delivered_by',
        'order_number',
        'order_type',
        'total_amount',
        'status',
        'payment_status',
        'payment_method',
        'shipping_amount',
        'scheduled_delivery',
        'notes'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function preparedBy()
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }

    public function deliveredBy()
    {
        return $this->belongsTo(User::class, 'delivered_by');
    }
}
