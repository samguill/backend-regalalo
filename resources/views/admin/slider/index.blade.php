@extends('layouts.admin')
@section('content')
    <div class="row">
        @php
            $builder = new \App\Utils\ReactCrudSettingsBuilder();

            $titleField = new \App\Utils\ReactCrudField('name');
            $titleField->title('Título')->required(true)->width(8);
            $builder->addField($titleField);

            $orderField = new \App\Utils\ReactCrudField('order');
            $orderField->title('Orden')->width(4);
            $builder->addField($orderField);

            $tagsField = new \App\Utils\ReactCrudField('tags');
            $tagsField->title('Etiquetas')->type('json', $tags)->width(12)->renderAs('text')->show(false);
            $builder->addField($tagsField);

            $imageField = new \App\Utils\ReactCrudField('image');
            $imageField->title('Imagen')->type('file')->show(false)->width(12);
            $builder->addField($imageField);

            $actions = [];
            $actions["custom"] = [];
            $actions['view'] = [];
            $actions['create'] = [
                'url' => route('admin.slider.create')
            ];
            $actions['update'] = [
                'url' => route('admin.slider.update')
            ];
            $actions['delete'] = [
                'url' => route('admin.slider.delete')
            ];

            $builder->setActions($actions);

        @endphp
        <div class="col-md-12">
            <div id="{{\App\Utils\ReactComponents::LARAVEL_CRUD_COMPONENT}}" data-url="{{route('admin.slider.list')}}" data-settings="{{$builder->get()}}" />
        </div>
    </div>
@endsection
