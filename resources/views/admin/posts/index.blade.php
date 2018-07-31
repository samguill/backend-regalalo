@extends('layouts.admin')
@section('content')
    <div class="row">
        @php
            $builder = new \App\Utils\ReactCrudSettingsBuilder();

            $titleField = new \App\Utils\ReactCrudField('title');
            $titleField->title('Título')->required(true)->width(8);
            $builder->addField($titleField);

            $titleField = new \App\Utils\ReactCrudField('category_id');
            $titleField->title('Categoría')->type('map', $categories)->required(true)->width(4);
            $builder->addField($titleField);

            $contentField = new \App\Utils\ReactCrudField('content');
            $contentField->title('Descripción')->type('editor')->show(false)->width(12);
            $builder->addField($contentField);

            $contentField = new \App\Utils\ReactCrudField('summary');
            $contentField->title('Resúmen')->show(false)->width(12)->verbose();
            $builder->addField($contentField);

            $authorField = new \App\Utils\ReactCrudField('author');
            $authorField->title('Autor')->width(12);
            $builder->addField($authorField);

            $imageField = new \App\Utils\ReactCrudField('featured_image');
            $imageField->title('Imagen destacada')->type('file')->show(false)->width(6);
            $builder->addField($imageField);

            $meta_titleField = new \App\Utils\ReactCrudField('meta_title');
            $meta_titleField->title('Meta title')->show(false)->width(12);
            $builder->addField($meta_titleField);

            $meta_descriptionField = new \App\Utils\ReactCrudField('meta_description');
            $meta_descriptionField->title('Meta description')->show(false)->width(12)->verbose();
            $builder->addField($meta_descriptionField);

            $meta_keywordsField = new \App\Utils\ReactCrudField('meta_keywords');
            $meta_keywordsField->title('Meta keywords')->show(false)->width(12)->verbose();
            $builder->addField($meta_keywordsField);

            $actions = [];
            $actions["custom"] = [];
            $actions['view'] = [];
            $actions['create'] = [
                'url' => route('posts.create')
            ];
            $actions['update'] = [
                'url' => route('posts.update')
            ];
            $actions['delete'] = [
                'url' => route('posts.delete')
            ];

            $builder->setActions($actions);

        @endphp
        <div class="col-md-12">
            <div id="{{\App\Utils\ReactComponents::LARAVEL_CRUD_COMPONENT}}" data-url="{{route('posts.lists')}}" data-settings="{{$builder->get()}}" />
        </div>
    </div>
@endsection
