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
        'delivery'
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
