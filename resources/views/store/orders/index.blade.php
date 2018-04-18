@extends('layouts.store')
@section('content')
    <div class="row">
       @php

                    $builder = new \App\Utils\ReactCrudSettingsBuilder();

                    $codeField = new \App\Utils\ReactCrudField('order_code');
                    $codeField ->title('Código de la orden')->width(6);
                    $builder->addField($codeField);

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
                        'url' => route('orders.view'),
                        'icon' => "edit",
                        "color" => "#4CAF50",
                        "params" => [ 'id' ],
                        "title" => "Ver"
                    ]
                                    ]
                            );

                    $builder->setActions($actions);



       @endphp
        <div class="col-md-12">
            <div id="{{\App\Utils\ReactComponents::LARAVEL_CRUD_COMPONENT}}" data-url="{{route('orders.lists')}}" data-settings="{{$builder->get()}}" />
        </div>
    </div>
@endsection
