<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
    protected $fillable = ['inventory_id', 'quantity','movement_type'];

    public function order() {
        return $this->hasOne('App\Models\Order', 'id', 'order_id');
    }


}

