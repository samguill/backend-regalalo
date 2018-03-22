<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable= [
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
}
