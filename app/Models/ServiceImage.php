<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceImage extends Model
{
    protected $fillable = [
    'id',
    'store_image_id',
    'service_id'
];

    public function store_image() {
        return $this->hasOne('App\Models\StoreImage', 'id', 'store_image_id');
    }

    public function service() {
        return $this->hasOne('App\Models\Service', 'service_id', 'id');
    }
}
