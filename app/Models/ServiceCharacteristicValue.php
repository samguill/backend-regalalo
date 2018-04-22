<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceCharacteristicValue extends Model
{
    protected $fillable = ['key','value','service_characteristic_id'];
}
