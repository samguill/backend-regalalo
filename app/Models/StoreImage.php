<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreImage extends Model
{
    protected $fillable = [
        'id',
        'store_id',
        'image_path'
    ];
}
