<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offer extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'id',
        'title',
        'slug',
        'description',
        'order',
        'image',
        'status'
    ];

    public function offerdetails(){
        return $this->hasMany('App\Models\OfferDetail', 'offer_id', 'id');
    }

    public function getImageAttribute(){
        $base_url = env('APP_URL');
        $image = $this->attributes["image"];
        if($image){
            return $base_url . $this->attributes["image"];
        }
        return $this->attributes["image"];
    }
}
