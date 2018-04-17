<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = ['service_id', 'quantity', 'store_branche_id'];

    public function branch(){
        return $this->hasONe('App\Models\StoreBranch', 'id', 'store_branche_id');
    }

    public function service(){
        return $this->hasONe('App\Models\Service', 'id', 'service_id');
    }

    public function movements(){

        return $this->hasMany('App\Models\CouponMovement', 'coupon_id' , 'id');
    }
}
