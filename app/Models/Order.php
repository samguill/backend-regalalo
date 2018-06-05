<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'id',
        'order_code',
        'status',
        'total',
        'sub_total',
        'client_id',
        'store_id',
        'delivery',
        'client_direction_id'
    ];


    public function orderdetails()
    {
        return $this->hasMany('App\Models\OrderDetail', 'order_id', 'id');
    }

    public function store()
    {
        return $this->BelongsTo('App\Models\Store', 'store_id', 'id');
    }

    public function clientdirection(){
        return $this->hasOne('App\Models\ClientDirection', 'id', 'client_direction_id');
    }
}
