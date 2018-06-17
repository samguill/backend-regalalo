@extends('layouts.admin')
@section('content')
    <div class="row">
        @php
            $builder = new \App\Utils\ReactCrudSettingsBuilder();

            $nameField = new \App\Utils\ReactCrudField('product_name');
            $nameField->title('Producto')->required(true)->width(6);
            $builder->addField($nameField);

            $branchField = new \App\Utils\ReactCrudField('branch_name');
            $branchField->title('Ubicación')->required(true)->width(6);
            $builder->addField($branchField);

            $quantityField = new \App\Utils\ReactCrudField('quantity');
            $quantityField->title('Cantidad')->required(true)->width(6);
            $builder->addField($quantityField);


            $actions = [];
            $actions["custom"] = [];
            $actions['custom']=array_merge(
                $actions["custom"],
                    [
                        "edit" => [
                            "link" => true,
                            'url' => route('admin.inventory.movement'),
                            'icon' => "eye",
                            "color" => "#4CAF50",
                            "params" => ['id'],
                            "title" => "Ver movimientos"
                        ]
                    ]);
            $builder->setActions($actions);
            $builder->addButton('Realizar movimiento', "open_modal('inventory_movements_modal', 'Realizar movimiento')", "btn-primary");
        @endphp

        <div class="modal fade" id="inventory_movements_modal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="incominginventory-tab" data-toggle="tab" href="#incominginventory" role="tab" aria-controls="incominginventory" aria-selected="true">Ingreso de inventario</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="outgoinginventory-tab" data-toggle="tab" href="#outgoinginventory" role="tab" aria-controls="outgoinginventory" aria-selected="false">Egreso de inventario</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="incominginventory" role="tabpanel" aria-labelledby="incominginventory-tab">
                                <div class="admin-incoming-inventory"
                                     data-stores="{{json_encode($stores)}}"
                                     url-incominginventory="{{route('admin.inventory.incoming')}}"
                                     url-store-branches="{{route('admin.inventory.store.branches')}}"></div>
                            </div>
                            <div class="tab-pane fade" id="outgoinginventory" role="tabpanel" aria-labelledby="outgoinginventory-tab">
                                <div class="admin-outgoing-inventory"
                                     data-stores="{{json_encode($stores)}}"
                                     url-outgoinginventory="{{route('admin.inventory.outgoing')}}"
                                     url-store-branches="{{route('admin.inventory.store.branches')}}"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div id="{{\App\Utils\ReactComponents::LARAVEL_CRUD_COMPONENT}}" data-url="{{route('admin.inventory.list')}}" data-settings="{{$builder->get()}}" />
        </div>
    </div>

@endsection