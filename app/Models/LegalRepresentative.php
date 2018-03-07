<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LegalRepresentative extends Model
{
    protected $fillable = [
        'id',
        'name',
        'document_number',
        'phone',
        'store_id'
    ];
}
