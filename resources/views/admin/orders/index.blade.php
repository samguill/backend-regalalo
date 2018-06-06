@extends('layouts.store')
@section('content')
    <div class="row">
       @php

                    $builder = new \App\Utils\ReactCrudSettingsBuilder();

                    $codeField = new \App\Utils\ReactCrudField('order_code');
                    $codeField ->title('Código de la orden')->width(6);
                    $builder->addField($codeField);

                    $storenameField = new \App\Utils\ReactCrudField('store_name');
                    $storenameField  ->title('Tienda')->width(6);
                    $builder->addField($storenameField );

                    $clientnameField = new \App\Utils\ReactCrudField('client_name');
                    $clientnameField  ->title('Cliente')->width(6);
                    $builder->addField($clientnameField );

                    $subtotalField = new \App\Utils\ReactCrudField('sub_total');
                    $subtotalField  ->title('Sub-Total')->width(6);
                    $builder->addField($subtotalField );

                    $totalField = new \App\Utils\ReactCrudField('total');
                    $totalField ->title('Total')->width(6);
                    $builder->addField($totalField);



                    $actions = [];
                    $actions["custom"] = [];
                    $actions['custom']=array_merge(
                    $actions["custom"],
                      [
                    "edit" => [
                        "link" => true,
                        'url' => route('order.show'),
                        'icon' => "eye",
                        "color" => "#4CAF50",
                        "params" => [ 'id' ],
                        "title" => "Ver"
                    ]
                                    ]
                            );

                    $builder->setActions($actions);



       @endphp
        <div class="col-md-12">
            <div id="{{\App\Utils\ReactComponents::LARAVEL_CRUD_COMPONENT}}" data-url="{{route('order.lists')}}" data-settings="{{$builder->get()}}" />
        </div>
    </div>
@endsection
