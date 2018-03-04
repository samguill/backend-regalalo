@extends('layouts.dashboard')
@section('content')
    <div class="row">
       @php
            $builder = new \App\Utils\ReactCrudSettingsBuilder();

            $storenameField = new \App\Utils\ReactCrudField('business_name');
            $storenameField->title('Nombre');
            $builder->addField($storenameField);

            $storerucField = new \App\Utils\ReactCrudField('ruc');
            $storerucField->title('RUC');
            $builder->addField($storerucField);

            $storephoneField = new \App\Utils\ReactCrudField('phone');
            $storephoneField->title('Teléfono');
            $builder->addField($storephoneField);

       @endphp
        <div class="col-md-12">
            <div id="{{\App\Utils\ReactComponents::LARAVEL_CRUD_COMPONENT}}" data-url="{{route('stores.lists')}}" data-settings="{{$builder->get()}}" />
        </div>
    </div>
@endsection
