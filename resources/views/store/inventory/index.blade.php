@extends('layouts.admin')
@section('content')
    <div class="row">
       @php
            $builder = new \App\Utils\ReactCrudSettingsBuilder();

            $nameField = new \App\Utils\ReactCrudField('name');
            $nameField->title('Nombre de la experiencia')->required(true)->width(6);
            $builder->addField($nameField);

            $descriptionField = new \App\Utils\ReactCrudField('description');
            $descriptionField->title('Descripción')->required(true)->width(6);
            $builder->addField($descriptionField);


            $actions = [];
            $actions["custom"] = [];
            $actions['view'] = [];

            $builder->addButton("Registrar ingreso","open_modal('incoming_inventory', 'Ingreso de un producto a inventario')","btn-info");
            $builder->addButton("Registrar egreso","open_modal('family_modal', 'Egreso de un producto a inventario')","btn-info");
            $builder->advancedSearch();

            $builder->setActions($actions);

       @endphp
        <div class="col-md-12">
            <div id="{{\App\Utils\ReactComponents::LARAVEL_CRUD_COMPONENT}}" data-url="{{route('inventory.lists')}}" data-settings="{{$builder->get()}}" />
        </div>
    </div>


    <div class="modal fade" id="incoming_inventory">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="store-incoming-inventory"
                         data-products="{{json_encode($products)}}"

                    ></div>
                </div>
                <div class="modal-footer">
                   <!-- <button type="button" class="btn btn-primary" onclick="charge_products(this)">Aceptar</button>-->
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
@endsection
