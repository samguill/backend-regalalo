@extends('layouts.admin')
@section('content')
    <div class="row">
       @php
            $builder = new \App\Utils\ReactCrudSettingsBuilder();

            $nameField = new \App\Utils\ReactCrudField('name');
            $nameField->title('Nombre de la característica')->required(true)->width(6);
            $builder->addField($nameField);

            $actions = [];
            $actions["custom"] = [];
            $actions['view'] = [];
            $actions['create'] = [
                'url' => route('productcharacteristics.create')
            ];
            $actions['update'] = [
                'url' => route('productcharacteristics.update')
            ];
            $actions['delete'] = [
                'url' => route('productcharacteristics.delete')
            ];

            $actions["custom"]=array_merge(
                $actions["custom"],
                [
                    "branches" => [
                        "link" => true,
                        'url' => route('productcharacteristics.values'),
                        'icon' => "bars",
                        "color" => "#ff9800",
                        "params" => [ 'id' ],
                        "title" => "Valores"
                    ]
                ]
            );

            $builder->setActions($actions);

       @endphp
        <div class="col-md-12">
            <div id="{{\App\Utils\ReactComponents::LARAVEL_CRUD_COMPONENT}}" data-url="{{route('productcharacteristics.lists')}}" data-settings="{{$builder->get()}}" />
        </div>
    </div>
@endsection
