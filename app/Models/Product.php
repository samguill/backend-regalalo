<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

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
        'brand_id',
        'meta_title',
        'meta_description',
        'featured_image',
        'is_featured',
        'status'
    ];

    protected $appends = ['discount_price'];
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

    public function getDiscountPriceAttribute(){
        $discount_price = 0;
        if($this->discount>0){
          $discount_price =  $this->price*(1-$this->discount/100);
        }
        return $discount_price;
    }

    public function productcharacteristic(){
        return $this->hasOne("App\Models\ProductCharacteristic", "id", "product_characteristic_id");
    }

    public function productcharacteristicsdetail(){
        return $this->hasMany('App\Models\ProductCharacteristicDetail', 'product_id', 'id');
    }

    public function brand(){
        return $this->hasOne("App\Models\Brand", "id", "brand_id");
    }

}
