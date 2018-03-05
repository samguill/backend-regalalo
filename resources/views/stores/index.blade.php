@extends('layouts.dashboard')
@section('content')
    <div class="row">
       @php
            $builder = new \App\Utils\ReactCrudSettingsBuilder();

            $storenameField = new \App\Utils\ReactCrudField('business_name');
            $storenameField->title('Razón social')->required(true)->width(6);
            $builder->addField($storenameField);

            $storerucField = new \App\Utils\ReactCrudField('ruc');
            $storerucField->title('RUC')->required(true)->width(6);
            $builder->addField($storerucField);

            $legal_addressField = new \App\Utils\ReactCrudField('legal_address');
            $legal_addressField->show(false)->required(true)->title('Domicilio legal')->width(6);
            $builder->addField($legal_addressField);

            $comercial_nameField = new \App\Utils\ReactCrudField('comercial_name');
            $comercial_nameField->show(false)->title('Nombre comercial')->width(6);
            $builder->addField($comercial_nameField);

            $addressField = new \App\Utils\ReactCrudField('address');
            $addressField->show(false)->required(true)->title('Dirección')->width(8);
            $builder->addField($addressField);

            $storephoneField = new \App\Utils\ReactCrudField('phone');
            $storephoneField->title('Teléfono')->required(true)->width(4);
            $builder->addField($storephoneField);

            $storeEmailField = new \App\Utils\ReactCrudField('store_email');
            $storeEmailField->show(false)->required(true)->title('E-mail')->width(6);
            $builder->addField($storeEmailField);

            $site_urlField = new \App\Utils\ReactCrudField('site_url');
            $site_urlField->show(false)->title('URL de la Tienda')->width(6);
            $builder->addField($site_urlField);

            $business_hour_1Field = new \App\Utils\ReactCrudField('business_hour_1');
            $business_hour_1Field->show(false)
                ->title('Horario de atención de Lunes a Viernes')->width(6);
            $builder->addField($business_hour_1Field);

            $business_hour_2Field = new \App\Utils\ReactCrudField('business_hour_2');
            $business_hour_2Field->show(false)
                ->title('Horario de atención Sábados y Domingo')->width(6);
            $builder->addField($business_hour_2Field);

            $financial_entityField = new \App\Utils\ReactCrudField('financial_entity');
            $financial_entityField->show(false)->type('map', [
                ['id' => 'BCP', 'value' => 'BCP'],
                ['id' => 'BBVA', 'value' => 'BBVA'],
                ['id' => 'INTERBANK', 'value' => 'INTERBANK'],
                ['id' => 'SCOTIABANK', 'value' => 'SCOTIABANK'],
                ['id' => 'BANBIF', 'value' => 'BANBIF']
                ])->title('Entidad Financiera')->width(6);
            $builder->addField($financial_entityField);

            $account_typeField = new \App\Utils\ReactCrudField('account_type');
            $account_typeField->show(false)->type('map', [
                ['id' => 'Cuenta de Ahorros', 'value' => 'Cuenta de Ahorros'],
                ['id' => 'Cuenta Corriente', 'value' => 'Cuenta Corriente']
                ])->title('Tipo de cuenta')->width(6);
            $builder->addField($account_typeField);

            $account_statement_nameField = new \App\Utils\ReactCrudField('account_statement_name');
            $account_statement_nameField->show(false)->title('Nombre del Estado de Cuenta');
            $builder->addField($account_statement_nameField);

            $bank_account_numberField = new \App\Utils\ReactCrudField('bank_account_number');
            $bank_account_numberField->show(false)->title('Número de Cuenta Bancaria')->width(6);
            $builder->addField($bank_account_numberField);

            $cci_account_numberField = new \App\Utils\ReactCrudField('cci_account_number');
            $cci_account_numberField->show(false)->title('Código de Cuenta Interbancario (CCI)')->width(6);
            $builder->addField($cci_account_numberField);

            $payme_comerce_idField = new \App\Utils\ReactCrudField('payme_comerce_id');
            $payme_comerce_idField->show(false)->title('PayMe ID Comercio')->width(4);
            $builder->addField($payme_comerce_idField);

            $payme_wallet_idField = new \App\Utils\ReactCrudField('payme_wallet_id');
            $payme_wallet_idField->show(false)->title('PayMe ID Wallet')->width(4);
            $builder->addField($payme_wallet_idField);

            $analytics_idField = new \App\Utils\ReactCrudField('analytics_id');
            $analytics_idField->show(false)->title('Google Analytics ID')->width(4);
            $builder->addField($analytics_idField);

            $actions = [];
            $actions["custom"] = [];
            $actions['view'] = [];
            $actions['create'] = [
                'url' => route('stores.create')
            ];
            $actions['update'] = [
                'url' => route('stores.update')
            ];
            $actions['delete'] = [
                'url' => route('stores.delete')
            ];

            $actions["custom"]=array_merge(
                $actions["custom"],
                [
                    "branches" => [
                        "link" => true,
                        'url' => route('store.branches'),
                        'icon' => "building",
                        "color" => "#ff9800",
                        "params" => [ 'id' ],
                        "title" => "Sucursales"
                    ]
                ]
            );
            $builder->setActions($actions);

       @endphp
        <div class="col-md-12">
            <div id="{{\App\Utils\ReactComponents::LARAVEL_CRUD_COMPONENT}}" data-url="{{route('stores.lists')}}" data-settings="{{$builder->get()}}" />
        </div>
    </div>
@endsection
