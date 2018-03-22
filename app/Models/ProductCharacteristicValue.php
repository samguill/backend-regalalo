<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCharacteristicValue extends Model
{
    protected $fillable = ['key','value','product_characteristic_id'];
}
