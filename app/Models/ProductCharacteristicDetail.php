<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCharacteristicDetail extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        "id",
        "product_id",
        'product_characteristic_id',
        'product_characteristic_values'
    ];

    public function characteristic(){
        return $this->hasOne("App\Models\ProductCharacteristic", "id", "product_characteristic_id");
    }
}
