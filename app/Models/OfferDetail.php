<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OfferDetail extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'offer_id',
        'product_id',
        'service_id'
    ];

    public function offer(){
        return $this->BelongsTo('App\Models\Offer', 'offer_id', 'id');
    }

    public function product(){
        return $this->hasOne('App\Models\Product', 'id', 'product_id');
    }

    public function service(){
        return $this->hasOne('App\Models\Service', 'id', 'service_id');
    }

}
