@extends('layouts.store')
@section('content')
    <div class="row">
       @php

           $sex = array_map(
                        function($item){

                            return [
                                "id" => $item['id'],
                                "value" => $item['value']
                            ];
                        }, App\Utils\ParametersUtil::sex
                    );

               $ages = array_map(
                    function($item){
                        return [
                            "id" => $item,
                            "value" => $item
                        ];
                    }, range(1,80)
                );

                    $builder = new \App\Utils\ReactCrudSettingsBuilder();

                    $skucodeField = new \App\Utils\ReactCrudField('sku_code');
                    $skucodeField->title('Código del servicio')->required(true)->width(6);
                    $builder->addField($skucodeField);

                    $servicenameField = new \App\Utils\ReactCrudField('name');
                    $servicenameField->title('Nombre del servicio')->required(true)->width(6);
                    $builder->addField($servicenameField);

                    $discountField = new \App\Utils\ReactCrudField('discount');
                    $discountField->title('Descuento')->required(false)->show(false)->width(4);
                    $builder->addField($discountField);

                    $priceField = new \App\Utils\ReactCrudField('price');
                    $priceField->title('Precio')->required(false)->show(false)->width(4);
                    $builder->addField($priceField);

                    $productpresentationField = new \App\Utils\ReactCrudField('product_presentation');
                    $productpresentationField->show(false)->type('map', [
                        ['id' => 'unidad', 'value' => 'Unidad'],
                        ['id' => 'par', 'value' => 'Par'],
                        ['id' => 'caja', 'value' => 'Caja'],
                        ['id' => 'docena', 'value' => 'Docena']
                        ])->title('Venta por')->width(4);
                    $builder->addField($productpresentationField);

                     $descriptionField = new \App\Utils\ReactCrudField('description');
                    $descriptionField->title('Descripción')->required(false)->show(false)->width(12);
                    $builder->addField($descriptionField);

                    $ageField = new \App\Utils\ReactCrudField('age');
                    $ageField->title('Edad (Colocar solo un rango)')->type('json', $ages)->required(false)->show(false)->width(6)->renderAs('text');
                    $builder->addField($ageField);

                        $sexField = new \App\Utils\ReactCrudField('sex');
                        $sexField->show(false)->type('map', $sex)->title('¿A quién regalas?')->required(false)->width(4);
                        $builder->addField($sexField);

                    $availabilityField = new \App\Utils\ReactCrudField('availability');
                    $availabilityField->show(false)->type('map', [
                        ['id' => 'D', 'value' => 'Delivery'],
                        ['id' => 'S', 'value' => 'Tienda'],
                        ['id' => 'A', 'value' => 'Todos'],

                        ])->title('Disponibilidad')->width(6);
                    $builder->addField($availabilityField);

                    $experienceField = new \App\Utils\ReactCrudField('experience');
                    $experienceField->fillable()->title('Experiencia')->type('json', $experiences)->show(false)->width(6)->renderAs('text');
                    $builder->addField($experienceField);


                    $actions = [];
                    $actions["custom"] = [];
                    $actions['view'] = [];
                    $actions['create'] = [
                        'url' => route('services.create')
                    ];
                    $actions['update'] = [
                        'url' => route('services.update')
                    ];
                    $actions['delete'] = [
                        'url' => route('services.delete')
                    ];

                    $builder->setActions($actions);
                        $builder->addButton('Carga masiva de servicios', "open_modal('services_charge_modal', 'Carga masiva de servicios')", "btn-info");



       @endphp
        <div class="col-md-12">
            <div id="{{\App\Utils\ReactComponents::LARAVEL_CRUD_COMPONENT}}" data-url="{{route('services.lists')}}" data-settings="{{$builder->get()}}" />
        </div>
    </div>

    <div class="modal fade" id="services_charge_modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <a href="{{ asset('uploads/formats/services_charge.xlsx') }}" target="_blank" class="btn btn-block btn-success">
                        <i class="fa fa-file-excel-o" aria-hidden="true"></i> Descargar formato
                    </a>
                    <form class="mt-20" id="charge_services_form" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="exampleFormControlFile1">Subir archivo excel</label>
                            <input type="file" class="form-control-file" id="excel" name="excel">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="charge_services(this)">Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
@endsection
