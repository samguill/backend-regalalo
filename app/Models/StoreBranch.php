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
        'branch_email',
        'store_id'
    ];

    public function branchopeninghours(){

        return $this->hasMany('App\Models\BranchOpeningHour','store_branche_id', 'id');
    }
}