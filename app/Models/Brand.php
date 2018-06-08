<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'id',
        'name',
        'slug',
        'image',
        'order'
    ];

    public function getImageAttribute(){
        $base_url = env('APP_URL');
        $logo = $this->attributes["image"];
        if($logo){
            return $base_url . $this->attributes["image"];
        }
        return $this->attributes["image"];
    }
}
