<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $table = 'tbl_batches';

    protected $fillable = [
        'product_id',
        'quantity',
        'cost_per_batch',
        'production_date',
        'expiry_date'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function inventory()
    {
        return $this->hasOne(Inventory::class, 'batch_id');
    }
}
