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
                        'url' => route('coupons.movements'),
                        'icon' => "eye",
                        "color" => "#4CAF50",
                        "params" => [ 'id' ],
                        "title" => "Ver movimientos"
                    ]
                                    ]
                            );



            $builder->setActions($actions);

       @endphp


            <div class="col-md-12  mb-10">
                <div class="card">
                    <div class="card-block">
                            <div class="card-body">

                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="incomingcoupons-tab" data-toggle="tab" href="#incomingcoupons" role="tab" aria-controls="incomingcoupons" aria-selected="true">Ingreso de cupos de servicios</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="outgoingcoupons-tab" data-toggle="tab" href="#outgoingcoupons" role="tab" aria-controls="outgoingcoupons" aria-selected="false">Egreso de cupos de servicios</a>
                                    </li>

                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="incomingcoupons" role="tabpanel" aria-labelledby="incomingcoupons-tab">

                                        <div class="store-incoming-coupons"
                                             data-services="{{json_encode($services)}}"
                                             data-branches="{{json_encode($branches)}}"
                                             url-incomingcoupons="{{route('coupons.incomingcoupons')}}"

                                        ></div>

                                    </div>
                                    <div class="tab-pane fade" id="outgoingcoupons" role="tabpanel" aria-labelledby="outgoingcoupons-tab">

                                        <div class="store-outgoing-coupons"
                                             data-services="{{json_encode($couponservices)}}"
                                             url-outgoingcoupons="{{route('coupons.outgoingcoupons')}}"

                                        ></div>

                                    </div>

                                </div>




                            </div>
                    </div>
                </div>
            </div>


        <div class="col-md-12">
            <div id="{{\App\Utils\ReactComponents::LARAVEL_CRUD_COMPONENT}}" data-url="{{route('coupons.lists')}}" data-settings="{{$builder->get()}}" />
        </div>
    </div>

@endsection
