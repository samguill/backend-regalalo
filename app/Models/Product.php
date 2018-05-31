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
        'product_characteristic_values',
        'meta_title',
        'meta_description',
        'featured_image',
        'status'
    ];

    public function productimages() {
        return $this->hasMany('App\Models\ProductImage', 'product_id', 'id');
    }

    public function store(){

        return $this->belongsTo('App\Models\Store');
    }

    public function getFeaturedImageAttribute(){
        $base_url = env('APP_URL');
        $featured_image = $this->attributes["featured_image"];
        if($featured_image){
            return $base_url . $this->attributes["featured_image"];
        }
        return $this->attributes["featured_image"];
    }
}
