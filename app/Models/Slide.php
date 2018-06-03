<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slide extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'id',
        'name',
        'image',
        'order'
    ];

    public function elements(){
        return $this->hasMany('App\Models\SlideElement', 'slide_id', 'id');
    }

    public function getImageAttribute(){
        $base_url = env('APP_URL');
        return $base_url . $this->attributes["image"];
    }
}
