@extends('layouts.store')
@section('content')
    <div class="row">
        @php
            $roductcharacteristics = App\Utils\ParametersUtil::getProductCharacteristics();
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
            $skucodeField->title('SKU')->required(true)->width(3);
            $builder->addField($skucodeField);

            $productnameField = new \App\Utils\ReactCrudField('name');
            $productnameField->title('Nombre del producto')->required(true)->width(6);
            $builder->addField($productnameField);

            $store_idField = new \App\Utils\ReactCrudField('store_id');
            $store_idField->title('Tienda')->required(false)->show(true)->type('map', $stores)->width(3);
            $builder->addField($store_idField);

            $discountField = new \App\Utils\ReactCrudField('discount');
            $discountField->title('Descuento')->required(false)->show(false)->width(4);
            $builder->addField($discountField);

            $priceField = new \App\Utils\ReactCrudField('price');
            $priceField->title('Precio')->required(false)->show(true)->width(4);
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
            $descriptionField->title('Descripción')->type('editor')->show(false)->width(12);
            $builder->addField($descriptionField);

            $minAgeField = new \App\Utils\ReactCrudField('min_age');
            $minAgeField->title('Edad mínima')->type('map', $ages)
                ->required(false)->show(false)
                ->width(2)->renderAs('text');
            $builder->addField($minAgeField);

            $maxAgeField = new \App\Utils\ReactCrudField('max_age');
            $maxAgeField->title('Edad máxima')->type('map', $ages)
                ->required(false)->show(false)
                ->width(2)->renderAs('text');
            $builder->addField($maxAgeField);

            $sexField = new \App\Utils\ReactCrudField('sex');
            $sexField->show(false)->type('map', $sex)->title('¿A quién regalas?')->required(false)->width(4);
            $builder->addField($sexField);

            $availabilityField = new \App\Utils\ReactCrudField('availability');
            $availabilityField->show(false)->type('map', [
                ['id' => 'D', 'value' => 'Delivery'],
                ['id' => 'S', 'value' => 'Tienda'],
                ['id' => 'A', 'value' => 'Todos'],
            ])->title('Disponibilidad')->width(4)->renderAs('text');
            $builder->addField($availabilityField);

            $eventField = new \App\Utils\ReactCrudField('event');
            $eventField->fillable()->title('Ocasión')
                ->type('json', $events)->show(false)
                ->width(6)->renderAs('text');
            $builder->addField($eventField);

            $interestField = new \App\Utils\ReactCrudField('interest');
            $interestField->fillable()->title('Interés')
                ->type('json', $interests)->show(false)
                ->width(6)->renderAs('text');
            $builder->addField($interestField);

            $actions = [];
            $actions["custom"] = [];
            $actions['view'] = [];
            $actions['create'] = [
                'url' => route('product.create')
            ];
            //$actions['update'] = ['url' => route('products.update')];
            $actions['custom']=array_merge(
                $actions["custom"],
                [
                    "edit" => [
                        "link" => true,
                        'url' => route('product.edit'),
                        'icon' => "edit",
                        "color" => "#4CAF50",
                        "params" => [ 'id' ],
                        "title" => "Editar"
                    ]
                ]
            );
            $actions['delete'] = ['url' => route('product.delete')];
            $builder->setActions($actions);
            $builder->addButton('Carga masiva de productos', "open_modal('products_admin_charge_modal', 'Carga masiva de productos')", "btn-info");

       @endphp
        <div class="col-md-12">
            <div id="{{\App\Utils\ReactComponents::LARAVEL_CRUD_COMPONENT}}" data-url="{{route('product.lists')}}" data-settings="{{$builder->get()}}" />
        </div>
    </div>


    <div class="modal fade" id="products_admin_charge_modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <a href="{{ asset('uploads/formats/products_charge.xlsx') }}" target="_blank" class="btn btn-block btn-success">
                        <i class="fa fa-file-excel-o" aria-hidden="true"></i> Descargar formato
                    </a>
                    <form class="mt-20" id="charge_products_form_admin" enctype="multipart/form-data">
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
                    <button type="button" class="btn btn-primary" onclick="charge_products_admin(this)">Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
@endsection
