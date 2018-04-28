<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{

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
        'payme_process_status',
        'analytics_id',
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

}
