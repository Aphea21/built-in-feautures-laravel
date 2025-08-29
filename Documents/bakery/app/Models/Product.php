<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'tbl_products';

    protected $fillable = [
        'category_id',
        'name',
        'sku',
        'description',
        'batch_size',
        'price',
        'shelf_life_days',
        'is_active',
        'is_featured',
        'in_stock',
        'on_sale'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function batches()
    {
        return $this->hasMany(Batch::class, 'product_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }
}
