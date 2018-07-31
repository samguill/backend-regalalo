<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        "title",
        "slug",
        "content",
        "summary",
        "author",
        "featured_image",
        'user_id',
        'category_id',
        'meta_title',
        'meta_description',
        'meta_keywords'
    ];

    public function user(){
        return $this->hasOne("App\Models\User", "id", "user_id");
    }

    public function category(){
        return $this->hasOne("App\Models\Category", "id", "category_id");
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
