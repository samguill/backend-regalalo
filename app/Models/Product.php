<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable= [
        'id',
        'name',
        'slug',
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
        'featured_image',
        'status'
    ];

    public function productimages() {
        return $this->hasMany('App\Models\ProductImage', 'product_id', 'id');
    }
}
