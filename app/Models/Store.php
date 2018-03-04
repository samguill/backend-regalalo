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
        'address',
        'phone',
        'store_email',
        'site_url',
        'start_business_hour',
        'end_business_hour',
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
        'status'
    ];



}
