@extends('layouts.admin')
@section('content')
    <div class="row">
        @php
            $builder = new \App\Utils\ReactCrudSettingsBuilder();

            $keyField = new \App\Utils\ReactCrudField('key');
            $keyField->title('Palabra clave')->required(true)->width(12);
            $builder->addField($keyField);

            $actions = [];
            $actions["custom"] = [];
            $actions['view'] = [];
            $actions['create'] = [
                'url' => route('admin.tags.create')
            ];
            $actions['update'] = [
                'url' => route('admin.tags.update')
            ];
            $actions['delete'] = [
                'url' => route('admin.tags.delete')
            ];

            $builder->setActions($actions);

        @endphp
        <div class="col-md-12">
            <div id="{{\App\Utils\ReactComponents::LARAVEL_CRUD_COMPONENT}}" data-url="{{route('admin.tags.list')}}" data-settings="{{$builder->get()}}" />
        </div>
    </div>
@endsection
