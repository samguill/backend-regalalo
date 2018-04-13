<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = [
        'id',
        'store_image_id',
        'product_id'
    ];

    public function image() {
        return $this->hasOne('App\Models\StoreImage', 'id', 'store_image_id');
    }

    public function product() {
        return $this->hasOne('App\Models\Product', 'product_id', 'id');
    }
}
