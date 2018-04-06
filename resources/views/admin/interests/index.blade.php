@extends('layouts.admin')
@section('content')
    <div class="row">
       @php
            $builder = new \App\Utils\ReactCrudSettingsBuilder();

            $nameField = new \App\Utils\ReactCrudField('name');
            $nameField->title('Nombre del interés')->required(true)->width(6);
            $builder->addField($nameField);

            $descriptionField = new \App\Utils\ReactCrudField('description');
            $descriptionField->title('Descripción')->required(true)->width(6);
            $builder->addField($descriptionField);

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

            $builder->setActions($actions);

       @endphp
        <div class="col-md-12">
            <div id="{{\App\Utils\ReactComponents::LARAVEL_CRUD_COMPONENT}}" data-url="{{route('interests.lists')}}" data-settings="{{$builder->get()}}" />
        </div>
    </div>
@endsection
