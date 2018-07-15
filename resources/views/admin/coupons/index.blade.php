@extends('layouts.admin')
@section('content')
    <div class="row">
        @php
            $builder = new \App\Utils\ReactCrudSettingsBuilder();

            $nameField = new \App\Utils\ReactCrudField('service_name');
            $nameField->title('Servicio')->required(true)->width(6);
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
                            'url' => route('admin.coupons.movement'),
                            'icon' => "eye",
                            "color" => "#4CAF50",
                            "params" => ['id'],
                            "title" => "Ver movimientos"
                        ]
                    ]);
            $builder->setActions($actions);
            $builder->addButton('Realizar movimiento', "open_modal('coupons_movements_modal', 'Realizar movimiento')", "btn-primary");
        @endphp

        <div class="modal fade" id="coupons_movements_modal">
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
                                <a class="nav-link active" id="incomingcoupons-tab" data-toggle="tab" href="#incomingcoupons" role="tab" aria-controls="incomingcoupons" aria-selected="true">Ingreso de cupones</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="outgoingcoupons-tab" data-toggle="tab" href="#outgoingcoupons" role="tab" aria-controls="outgoingcoupons" aria-selected="false">Egreso de cupones</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="incomingcoupons" role="tabpanel" aria-labelledby="incomingcoupons-tab">
                                <div class="admin-incoming-coupons"
                                     data-stores="{{json_encode($stores)}}"
                                     url-incomingcoupons="{{route('admin.coupons.incoming')}}"
                                     url-store-branches="{{route('admin.coupons.store.branches')}}"></div>
                            </div>
                            <div class="tab-pane fade" id="outgoingcoupons" role="tabpanel" aria-labelledby="outgoingcoupons-tab">
                                <div class="admin-outgoing-coupons"
                                     data-stores="{{json_encode($stores)}}"
                                     url-outgoingcoupons="{{route('admin.coupons.outgoing')}}"
                                     url-store-branches="{{route('admin.coupons.store.branches')}}"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div id="{{\App\Utils\ReactComponents::LARAVEL_CRUD_COMPONENT}}" data-url="{{route('admin.coupons.list')}}" data-settings="{{$builder->get()}}" />
        </div>
    </div>

@endsection