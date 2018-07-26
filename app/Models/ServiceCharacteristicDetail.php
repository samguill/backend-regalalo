<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceCharacteristicDetail extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        "id",
        "service_id",
        'service_characteristic_id',
        'service_characteristic_values'
    ];

    public function characteristic(){
        return $this->hasOne("App\Models\ServiceCharacteristic", "id", "service_characteristic_id");
    }
}
