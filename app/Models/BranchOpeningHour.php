<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchOpeningHour extends Model
{
protected $fillable = ['weekday' ,
'start_hour' ,
'end_hour',
'store_branche_id'] ;
}
