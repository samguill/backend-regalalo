@extends('layouts.admin')
@section('content')
    <div class="row">
        @php
            $builder = new \App\Utils\ReactCrudSettingsBuilder();

            $first_nameField = new \App\Utils\ReactCrudField('first_name');
            $first_nameField->title('Nombres')->required(true)->width(6);
            $builder->addField($first_nameField);

            $last_nameField = new \App\Utils\ReactCrudField('last_name');
            $last_nameField->title('Apellidos')->required(true)->width(6);
            $builder->addField($last_nameField);

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
                ['id'=>"2",'value'=>"Inactivo"]])->title("Estado")->default('1');
            $builder->addField($statusField);

            $actions = [];
            $actions["custom"] = [];
            $actions['view'] = [];
            $actions['create'] = [
                'url' => route('clients.create')
            ];
            $actions['update'] = [
                'url' => route('clients.update')
            ];
            $actions['delete'] = [
                'url' => route('clients.delete')
            ];
            $builder->setActions($actions);
        @endphp
        <div class="col-md-12">
            <div id="{{\App\Utils\ReactComponents::LARAVEL_CRUD_COMPONENT}}" data-url="{{route('clients.lists')}}" data-settings="{{$builder->get()}}" />
        </div>
    </div>

@endsection
