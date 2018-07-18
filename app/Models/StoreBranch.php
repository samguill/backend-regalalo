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
    protected $appends = ['open'];

    public function branchopeninghours(){

        return $this->hasMany('App\Models\BranchOpeningHour','store_branche_id', 'id');
    }


    public function getOpenAttribute(){

        $open = false;

        $branchopeninghours = $this->branchopeninghours()
                ->whereRaw('branch_opening_hours.weekday = WEEKDAY(CURDATE())
        AND  (CURTIME() >= branch_opening_hours.start_hour) 
        AND ( CURTIME() <= branch_opening_hours.end_hour)')->get();

        if(count($branchopeninghours)>0){
            $open =true;
        }

        return $open;

    }
}