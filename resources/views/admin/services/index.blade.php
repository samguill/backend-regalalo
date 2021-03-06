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
            $skucodeField->title('Código del servicio')->required(true)->width(3);
            $builder->addField($skucodeField);

            $servicenameField = new \App\Utils\ReactCrudField('name');
            $servicenameField->title('Nombre del servicio')->required(true)->width(6);
            $builder->addField($servicenameField);

            $store_idField = new \App\Utils\ReactCrudField('store_id');
            $store_idField->title('Tienda')->required(false)->show(true)->type('map', $stores)->width(3);
            $builder->addField($store_idField);

            $discountField = new \App\Utils\ReactCrudField('discount');
            $discountField->title('Descuento')->required(false)->show(false)->width(4);
            $builder->addField($discountField);

            $priceField = new \App\Utils\ReactCrudField('price');
            $priceField->title('Precio')->required(false)->show(false)->width(4);
            $builder->addField($priceField);

            $availabilityField = new \App\Utils\ReactCrudField('availability');
            $availabilityField->show(false)->type('map', [
                ['id' => 'D', 'value' => 'Delivery'],
                ['id' => 'S', 'value' => 'Tienda'],
                ['id' => 'A', 'value' => 'Todos']
            ])->title('Disponibilidad')->width(4);
            $builder->addField($availabilityField);

            $descriptionField = new \App\Utils\ReactCrudField('description');
            $descriptionField->title('Descripción')->required(false)->type('editor')->show(false)->width(12);
            $builder->addField($descriptionField);

            $minAgeField = new \App\Utils\ReactCrudField('min_age');
            $minAgeField->title('Edad mínima')->type('map', $ages)
                ->required(false)->show(false)
                ->width(4)->renderAs('text');
            $builder->addField($minAgeField);

            $maxAgeField = new \App\Utils\ReactCrudField('max_age');
            $maxAgeField->title('Edad máxima')->type('map', $ages)
                ->required(false)->show(false)
                ->width(4)->renderAs('text');
            $builder->addField($maxAgeField);

            $sexField = new \App\Utils\ReactCrudField('sex');
            $sexField->show(false)->type('map', $sex)->title('¿A quién regalas?')->required(false)->width(4);
            $builder->addField($sexField);



 /*           $experienceField = new \App\Utils\ReactCrudField('experience');
            $experienceField->fillable()->title('Experiencia')->type('json', $experiences)->show(false)->width(6)->renderAs('text');
            $builder->addField($experienceField);
*/

            $actions = [];

            $actions["custom"] = [];

            $actions['custom']=array_merge(
                $actions["custom"],
                [
                    "edit" => [
                        "link" => true,
                        'url' => route('service.edit'),
                        'icon' => "edit",
                        "color" => "#4CAF50",
                        "params" => [ 'id' ],
                        "title" => "Editar"
                    ]
                ]
            );

             $actions['create'] = ['url' => route('service.create')];
            $actions['delete'] = ['url' => route('service.delete')];


            $builder->setActions($actions);
            $builder->addButton('Carga masiva de servicios', "open_modal('services_charge_modal', 'Carga masiva de servicios')", "btn-info");



       @endphp
        <div class="col-md-12">
            <div id="{{\App\Utils\ReactComponents::LARAVEL_CRUD_COMPONENT}}" data-url="{{route('service.lists')}}" data-settings="{{$builder->get()}}" />
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
                    <form class="mt-20" id="charge_services_form_admin" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="exampleFormControlFile1">Seleccione una tienda</label>
                            <select class="form-control" id="store_id">
                                <option value="">Seleccionar...</option>
                                @foreach($stores as $store)
                                    <option value="{{$store["id"]}}">
                                        {{$store["value"]}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlFile1">Subir archivo excel</label>
                            <input type="file" class="form-control-file" id="excel" name="excel">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="charge_services_admin(this)">Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
@endsection
