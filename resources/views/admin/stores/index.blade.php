@extends('layouts.admin')
@section('content')
    <div class="row">
       @php
            $builder = new \App\Utils\ReactCrudSettingsBuilder();

            $storenameField = new \App\Utils\ReactCrudField('business_name');
            $storenameField->title('Razón social')->fillable()->show(true)->required(true)->width(6);
            $builder->addField($storenameField);

            $storerucField = new \App\Utils\ReactCrudField('ruc');
            $storerucField->title('RUC')->required(true)->width(6);
            $builder->addField($storerucField);

            $legal_addressField = new \App\Utils\ReactCrudField('legal_address');
            $legal_addressField->show(false)->fillable(false)->required(true)->title('Domicilio legal')->width(6);
            $builder->addField($legal_addressField);

            $comercial_nameField = new \App\Utils\ReactCrudField('comercial_name');
            $comercial_nameField->show(false)->title('Nombre comercial')->width(6);
            $builder->addField($comercial_nameField);

            $storephoneField = new \App\Utils\ReactCrudField('phone');
            $storephoneField->title('Teléfono')->required(true)->width(6);
            $builder->addField($storephoneField);

            $statusField = new \App\Utils\ReactCrudField('status');
            $statusField->fillable()->title('Estado')->type('map', [
                ["id"=> '0',"value"=>"Pendiente"],
                ['id'=>'1','value'=>"Activo"],
                ['id'=>"2",'value'=>"Inactivo"]])->title("Estado")->default('0')->width(4);
            $builder->addField($statusField);

            $statusField = new \App\Utils\ReactCrudField('payme_process_status');
            $statusField->fillable(false)->title('PayMe')->type('map', [
                ["id"=> '0',"value"=>"Pendiente"],
                ['id'=>'1','value'=>"Integración"],
                ['id'=>"2",'value'=>"Producción"]])->title("PayMe")->default('0');
            $builder->addField($statusField);

            $site_urlField = new \App\Utils\ReactCrudField('site_url');
            $site_urlField->show(false)->title('URL de la Tienda')->width(6);
            $builder->addField($site_urlField);

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

            $actions["custom"]=array_merge(
                $actions["custom"],
                [
                    "activate" => [
                        "link" => false,
                        'url' => route('stores.generate_user'),
                        'icon' => "user-plus",
                        "color" => "#3F51B5",
                        "params" => [ 'id' ],
                        "condition" => [
                            "field" => "status",
                            "operator" => "in",
                            "value" => [0]
                        ],
                        "title" => "Generar usuario"
                    ]
                ]
            );

            $actions["custom"]=array_merge(
                $actions["custom"],
                [
                    "payme" => [
                        "link" => true,
                        "new_tab" => true,
                        'url' => route('stores.payme_doc'),
                        'icon' => "file-pdf-o",
                        "color" => "#009688",
                        "params" => [ 'id' ],
                        "title" => "PayMe"
                    ]
                ]
            );
            $builder->setActions($actions);
            $builder->addButton('Carga masiva', "open_modal('stores_charge_modal', 'Carga masiva de tiendas')", "btn-info");
       @endphp
        <div class="col-md-12">
            <div id="{{\App\Utils\ReactComponents::LARAVEL_CRUD_COMPONENT}}" data-url="{{route('stores.lists')}}" data-settings="{{$builder->get()}}" />
        </div>
    </div>

    <div class="modal fade" id="stores_charge_modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <a href="{{ asset('uploads/formats/stores_charge.xlsx') }}" target="_blank" class="btn btn-block btn-success">
                        <i class="fa fa-file-excel-o" aria-hidden="true"></i> Descargar formato
                    </a>
                    <form class="mt-20" id="charge_stores_form" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="exampleFormControlFile1">Subir archivo excel</label>
                            <input type="file" class="form-control-file" id="excel" name="excel">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="charge_stores(this)">Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

@endsection
