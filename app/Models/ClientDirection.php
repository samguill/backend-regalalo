<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientDirection extends Model
{
    protected $fillable = [
        'id',
        'name',
        'city',
        'address',
        'client_id'
    ];
}