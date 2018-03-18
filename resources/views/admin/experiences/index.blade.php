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
            $actions['create'] = [
                'url' => route('experiences.create')
            ];
            $actions['update'] = [
                'url' => route('experiences.update')
            ];
            $actions['delete'] = [
                'url' => route('experiences.delete')
            ];

            $builder->setActions($actions);

       @endphp
        <div class="col-md-12">
            <div id="{{\App\Utils\ReactComponents::LARAVEL_CRUD_COMPONENT}}" data-url="{{route('experiences.lists')}}" data-settings="{{$builder->get()}}" />
        </div>
    </div>
@endsection
