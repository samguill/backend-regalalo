<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreImage extends Model
{
    protected $fillable = [
        'id',
        'store_id',
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
