@extends('layouts.admin')
@section('content')
    <div class="row">
        @php
            $builder = new \App\Utils\ReactCrudSettingsBuilder();

            $first_nameField = new \App\Utils\ReactCrudField('name');
            $first_nameField->title('Nombres')->required(true)->width(6);
            $builder->addField($first_nameField);

            $emailField = new \App\Utils\ReactCrudField('email');
            $emailField->title('E-mail')->required(true)->width(6);
            $builder->addField($emailField);

            $passwordField = new \App\Utils\ReactCrudField('password');
            $passwordField->title('Contraseña')->show(false)->width(6)->type('password');
            $builder->addField($passwordField);

            $statusField = new \App\Utils\ReactCrudField('status');
            $statusField->fillable(false)->title('Estado')->type('map', [
                ["id"=> '0',"value"=>"Pendiente"],
                ['id'=>'1','value'=>"Activo"],
                ['id'=>"2",'value'=>"Inactivo"]])->default('1');
            $builder->addField($statusField);

            $typeField = new \App\Utils\ReactCrudField('type');
            $typeField->fillable(false)->title('Tipo')->show(false)->default('A');
            $builder->addField($typeField);

            $actions = [];
            $actions["custom"] = [];
            $actions['view'] = [];
            $actions['create'] = [
                'url' => route('users.create')
            ];
            $actions['update'] = [
                'url' => route('users.update')
            ];
            $actions['delete'] = [
                'url' => route('users.delete')
            ];
            $builder->setActions($actions);
        @endphp
        <div class="col-md-12">
            <div id="{{\App\Utils\ReactComponents::LARAVEL_CRUD_COMPONENT}}" data-url="{{route('users.lists')}}" data-settings="{{$builder->get()}}" />
        </div>
    </div>

@endsection
