<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'business_name',
        'ruc',
        'legal_address',
        'comercial_name',
        'slug',
        'phone',
        'site_url',
        'financial_entity',
        'account_type',
        'account_statement_name',
        'bank_account_number',
        'cci_account_number',
        'business_turn',
        'monthly_transactions',
        'average_amount',
        'maximum_amount',
        'payme_comerce_id',
        'payme_wallet_id',
        'payme_acquirer_id',
        'payme_wallet_password',
        'payme_gateway_password',
        'payme_process_status',
        'analytics_id',
        'meta_title',
        'meta_description',
        'logo_store',
        'user_id',
        'status'
    ];

    public function branches(){
        return $this->hasMany('App\Models\StoreBranch', 'store_id', 'id');
    }

    public function comercial_contact(){
        return $this->hasOne('\App\Models\ComercialContact', 'store_id', 'id');
    }

    public function legal_representatives(){
        return $this->hasMany('App\Models\LegalRepresentative', 'store_id', 'id');
    }

    public function getLogoStoreAttribute(){
        $base_url = env('APP_URL');
        $logo = $this->attributes["logo_store"];
        if($logo){
            return $base_url . $this->attributes["logo_store"];
        }
        return $this->attributes["logo_store"];
    }

    public function user(){
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

}
