@extends('layouts.admin')
@section('content')
    <div class="row">
        @php
            $builder = new \App\Utils\ReactCrudSettingsBuilder();

            $nameField = new \App\Utils\ReactCrudField('name');
            $nameField->title('Nombre de la marca')->required(true)->width(6);
            $builder->addField($nameField);

            $imageField = new \App\Utils\ReactCrudField('image');
            $imageField->title('Logo')->type('file')->show(false)->width(6);
            $builder->addField($imageField);

            $actions = [];
            $actions["custom"] = [];
            $actions['view'] = [];
            $actions['create'] = [
                'url' => route('brands.create')
            ];
            $actions['update'] = [
                'url' => route('brands.update')
            ];
            $actions['delete'] = [
                'url' => route('brands.delete')
            ];

            $builder->setActions($actions);

        @endphp
        <div class="col-md-12">
            <div id="{{\App\Utils\ReactComponents::LARAVEL_CRUD_COMPONENT}}" data-url="{{route('brands.lists')}}" data-settings="{{$builder->get()}}" />
        </div>
    </div>
@endsection
