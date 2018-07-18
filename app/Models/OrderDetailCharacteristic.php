<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetailCharacteristic extends Model
{
    protected $fillable = [
        "order_detail_id",
        "product_id",
        "service_id",
        "characteristic",
        "characteristic_value"
    ];

    public function orderdetail(){
        return $this->hasONe('App\Models\OrderDetail', 'id', 'order_detail_id');
    }
}
