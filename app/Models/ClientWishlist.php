<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientWishlist extends Model
{
    protected $fillable = [
        'id',
        'product_id',
        'service_id',
        'client_id'
    ];

    public function product(){
        return $this->hasONe('App\Models\Product', 'id', 'product_id');
    }

    public function service(){
        return $this->hasONe('App\Models\Service', 'id', 'service_id');
    }
    public function client(){
        return $this->hasONe('App\Models\Client', 'id', 'client_id');
    }
}
