<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        "title",
        "slug",
        "description",
        "featured_image",
        'user_id'
    ];

    public function user(){
        return $this->hasOne("App\Models\User", "user_id", "id");
    }

    public function getFeaturedImageAttribute(){
        $base_url = env('APP_URL');
        $logo = $this->attributes["featured_image"];
        if($logo){
            return $base_url . $this->attributes["featured_image"];
        }
        return $this->attributes["featured_image"];
    }
}
