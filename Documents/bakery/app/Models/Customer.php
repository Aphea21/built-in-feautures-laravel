<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'tbl_customers';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'street_address',
        'city',
        'state',
        'zip_code',
        'country',
        'is_registered'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }
}
