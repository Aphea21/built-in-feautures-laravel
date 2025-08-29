<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'tbl_inventory';

    protected $fillable = [
        'batch_id',
        'stock',
        'low_stock_threshold'
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }
}
