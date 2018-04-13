<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'inventory';

    protected $fillable = ['product_id', 'quantity', 'store_branche_id'];

    public function branch(){
        return $this->hasONe('App\Models\StoreBranch', 'id', 'store_branche_id');
    }


    public function product(){
        return $this->hasONe('App\Models\Product', 'id', 'product_id');
    }

    public function movements(){

        return $this->hasMany('App\Models\InventoryMovement', 'inventory_id' , 'id');
    }

}
