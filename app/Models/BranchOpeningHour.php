<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BranchOpeningHour extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = ['weekday',
        'start_hour',
        'end_hour',
        'store_branche_id'];

    protected $appends = ['day'];

    public function getDayAttribute(){

        switch ($this->weekday) {
            case 0:
                return 'Lunes';
                break;
            case 1:
                return 'Martes';
                break;
            case 2:
                return 'Miércoles';
                break;
            case 3:
                return 'Jueves';
                break;
            case 4:
                return 'Viernes';
                break;
            case 5:
                return 'Sábado';
                break;
            case 6:
                return 'Domingo';
                break;
            default:
                return "-";
        }

    }
}
