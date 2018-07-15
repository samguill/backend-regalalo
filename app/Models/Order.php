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
        'client_direction_id',
        'created_at'
    ];

    protected $appends = ['client_name','store_name', 'order_type'];

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

    public function client(){
        return $this->belongsTo('App\Models\Client');
    }

    public function getClientNameAttribute()
    {
        return $this->client->first_name.' '.$this->client->last_name;
    }

    public function getStoreNameAttribute()
    {
        return $this->store->comercial_name;
    }

    public function getOrderTypeAttribute()
    {
        //TODO: En el futuro se podrían solicitar más productos o servicios en una sola orden. Quedaría pendiente discriminarlo de otra forma
        $orderdetail = $this->orderdetails()->first();
        if($orderdetail->product_id != null){
            return 'product';
        }else{

            return 'service';
        }
    }



}
