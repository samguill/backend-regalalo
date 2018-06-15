@extends('layouts.admin')
@section('content')
    <div class="row">
        @php
            $builder = new \App\Utils\ReactCrudSettingsBuilder();

            $titleField = new \App\Utils\ReactCrudField('title');
            $titleField->title('Título')->required(true)->width(12);
            $builder->addField($titleField);

            $descriptiontField = new \App\Utils\ReactCrudField('description');
            $descriptiontField->title('Descripción')->show(false)->width(12)->verbose();
            $builder->addField($descriptiontField);

            $imageField = new \App\Utils\ReactCrudField('featured_image');
            $imageField->title('Imagen destacada')->type('file')->show(false)->width(6);
            $builder->addField($imageField);

            $actions = [];
            $actions["custom"] = [];
            $actions['view'] = [];
            $actions['create'] = [
                'url' => route('blog-categories.create')
            ];
            $actions['update'] = [
                'url' => route('blog-categories.update')
            ];
            $actions['delete'] = [
                'url' => route('blog-categories.delete')
            ];

            $builder->setActions($actions);

        @endphp
        <div class="col-md-12">
            <div id="{{\App\Utils\ReactComponents::LARAVEL_CRUD_COMPONENT}}" data-url="{{route('blog-categories.lists')}}" data-settings="{{$builder->get()}}" />
        </div>
    </div>
@endsection
