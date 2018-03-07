<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreBranch extends Model
{

    protected $fillable = [
        'id',
        'name',
        'latitude',
        'longitude',
        'address',
        'phone',
        'branche_email',
        'business_hour_1',
        'business_hour_2',
        'store_id'
    ];
}