<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class BlogImage extends Model {

    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'id',
        'image_path'
    ];

    public function getImagePathAttribute(){
        $base_url = env('APP_URL');
        $image_path = $this->attributes["image_path"];
        if($image_path){
            return $base_url . $this->attributes["image_path"];
        }
        return $this->attributes["image_path"];
    }

}
