<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComercialContact extends Model
{
    protected $fillable = [
        'id',
        'name',
        'document_number',
        'phone',
        'email',
        'position',
        'store_id'
    ];
}
