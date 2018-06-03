@extends('layouts.admin')
@section('content')
    <div class="row">
        @php
            $builder = new \App\Utils\ReactCrudSettingsBuilder();

            $questionField = new \App\Utils\ReactCrudField('question');
            $questionField->title('Pregunta')->required(true)->width(12);
            $builder->addField($questionField);

            $answerField = new \App\Utils\ReactCrudField('answer');
            $answerField->title('Respuesta')->required(true)->verbose()->width(12);
            $builder->addField($answerField);

            $actions = [];
            $actions["custom"] = [];
            $actions['view'] = [];
            $actions['create'] = [
                'url' => route('faq.create')
            ];
            $actions['update'] = [
                'url' => route('faq.update')
            ];
            $actions['delete'] = [
                'url' => route('faq.delete')
            ];
            $builder->setActions($actions);
        @endphp
        <div class="col-md-12">
            <div id="{{\App\Utils\ReactComponents::LARAVEL_CRUD_COMPONENT}}" data-url="{{route('faq.lists')}}" data-settings="{{$builder->get()}}" />
        </div>
    </div>

@endsection
