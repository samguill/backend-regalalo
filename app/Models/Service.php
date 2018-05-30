<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable= [
        'name',
        'sku_code',
        'discount',
        'price',
        'description',
        'age',
        'availability',
        'experience',
        'store_id',
        'featured_image',
        'meta_title',
        'meta_description',
        'status'];


    public function serviceimages() {
        return $this->hasMany('App\Models\ServiceImage');
    }

    public function store(){

        return $this->belongsTo('App\Models\Store');
    }
}
