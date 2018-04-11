<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [

        'code',
        'discount',
        'status',
        'store_id',
        'subtotal',
        'total',

    ];


    public function orderdetail()
    {
        return $this->hasMany('App\Models\OrderDetail', 'order_id', 'id');
    }

    public function store()
    {
        return $this->BelongsTo('App\Models\Store', 'store_id', 'id');
    }
}
