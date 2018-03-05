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
        'store_id'
    ];
}
