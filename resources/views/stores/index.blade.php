@extends('layouts.dashboard')
@section('content')
    <div class="row">
       @php
            $builder = new \App\Utils\ReactCrudSettingsBuilder();

            $storenameField = new \App\Utils\ReactCrudField('business_name');
            $storenameField->title('Razón social');
            $builder->addField($storenameField);

            $storerucField = new \App\Utils\ReactCrudField('ruc');
            $storerucField->title('RUC');
            $builder->addField($storerucField);

            $storephoneField = new \App\Utils\ReactCrudField('phone');
            $storephoneField->title('Teléfono');
            $builder->addField($storephoneField);

            $storeEmailField = new \App\Utils\ReactCrudField('store_email');
            $storeEmailField->show(false)->title('E-mail');
            $builder->addField($storeEmailField);

            $legal_addressField = new \App\Utils\ReactCrudField('legal_address');
            $legal_addressField->show(false)->title('Domicilio legal');
            $builder->addField($legal_addressField);


            $actions = [];
            $actions['view'] = [];
            $actions['update'] = [];
            $actions['delete'] = [];
            $builder->setActions($actions);

       @endphp
        <div class="col-md-12">
            <div id="{{\App\Utils\ReactComponents::LARAVEL_CRUD_COMPONENT}}" data-url="{{route('stores.lists')}}" data-settings="{{$builder->get()}}" />
        </div>
    </div>
@endsection
