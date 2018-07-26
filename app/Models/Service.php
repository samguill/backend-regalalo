<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

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
        'service_characteristic_id',
        'service_characteristic_values',
        'featured_image',
        'is_featured',
        'meta_title',
        'meta_description',
        'status'];

    protected $appends = ['discount_price'];

    public function serviceimages() {
        return $this->hasMany('App\Models\ServiceImage');
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

    public function getDiscountPriceAttribute()
    {
        $discount_price = 0;

        if($this->discount>0){
            $discount_price =  $this->price*(1-$this->discount/100);
        }
        return $discount_price;
    }

    public function servicecharacteristic(){
        return $this->hasOne("App\Models\ServiceCharacteristic", "id", "service_characteristic_id");
    }

    public function servicecharacteristicsdetail(){
        return $this->hasMany('App\Models\ServiceCharacteristicDetail', 'service_id', 'id');
    }
}
