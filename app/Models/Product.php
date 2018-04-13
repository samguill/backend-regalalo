<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable= [
        'id',
        'name',
        'sku_code',
        'discount',
        'price',
        'product_presentation',
        'description',
        'age',
        'availability',
        'event',
        'interest',
        'store_id',
        'product_characteristic_id',
        'status'
    ];

    public function images() {
        return $this->hasMany('App\Models\ProductImage', 'product_id', 'id');
    }
}
