<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientWishlist extends Model
{
    protected $fillable = [
        'id',
        'product_id',
        'client_id'
    ];
}
