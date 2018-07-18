<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'service_id',
        'quantity',
        'price',
        'price_delivery',
        'tracking_url',
        'tracking_code',
        'store_branche_id',
        'igv'
    ];

    public function order() {
        return $this->BelongsTo('App\Models\Order', 'order_id', 'id');
    }

    public function product(){
        return $this->hasOne('App\Models\Product', 'id', 'product_id');
    }

    public function service(){
        return $this->hasOne('App\Models\Service', 'id', 'service_id');
    }

    public function branch(){
        return $this->hasOne('App\Models\StoreBranch', 'id', 'store_branche_id');
    }

    public function characteristic(){
        return $this->belongsTo("App\Models\OrderDetailCharacteristic", 'order_detail_id', 'id');
    }
}
