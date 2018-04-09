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
            $actions['view'] = [];


            $builder->setActions($actions);

       @endphp


            <div class="col-md-12  mb-10">
                <div class="card">
                    <div class="card-block">
                            <div class="card-body">

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

                                        <div class="store-incoming-inventory"
                                             data-products="{{json_encode($products)}}"
                                             data-branches="{{json_encode($branches)}}"
                                             url-incominginventory="{{route('inventory.incominginventory')}}"

                                        ></div>

                                    </div>
                                    <div class="tab-pane fade" id="outgoinginventory" role="tabpanel" aria-labelledby="outgoinginventory-tab">

                                        <div class="store-outgoing-inventory"
                                             data-products="{{json_encode($inventoryproducts)}}"
                                             url-incominginventory="{{route('inventory.outgoinginventory')}}"

                                        ></div>

                                    </div>

                                </div>




                            </div>
                    </div>
                </div>
            </div>


        <div class="col-md-12">
            <div id="{{\App\Utils\ReactComponents::LARAVEL_CRUD_COMPONENT}}" data-url="{{route('inventory.lists')}}" data-settings="{{$builder->get()}}" />
        </div>
    </div>

@endsection
