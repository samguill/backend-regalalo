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
        'phone',
        'site_url',
        'financial_entity',
        'account_type',
        'account_statement_name',
        'bank_account_number',
        'cci_account_number',
        'payme_comerce_id',
        'payme_wallet_id',
        'payme_integration_key',
        'payme_production_key',
        'payme_process_status',
        'analytics_id',
        'status'
    ];

    public function branches(){
        return $this->hasMany('App\Models\StoreBranch', 'store_id', 'id');
    }

}
