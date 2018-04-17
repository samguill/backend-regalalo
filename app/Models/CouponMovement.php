<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponMovement extends Model
{
    protected $fillable = ['coupon_id', 'quantity', 'movement_type'];

    public function order() {
        return $this->hasOne('App\Models\Order', 'id', 'order_id');
    }

}
