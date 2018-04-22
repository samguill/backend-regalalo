<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceCharacteristic extends Model
{
    protected $fillable= [
        'name'
    ];

   public function values(){
        return $this->hasMany('App\Models\ServiceCharacteristicValue');
    }
}
