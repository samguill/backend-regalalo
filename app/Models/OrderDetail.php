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
        'price'

    ];

    public function order()
    {
        return $this->BelongsTo('App\Models\Order', 'order_id', 'id');
    }
}
