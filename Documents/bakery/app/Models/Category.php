<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'tbl_category';

    protected $fillable = [
        'name',
        'slug',
        'image',
        'status'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
