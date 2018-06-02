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

}
