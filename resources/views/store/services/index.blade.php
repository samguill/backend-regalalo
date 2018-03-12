@extends('layouts.store')
@section('content')
    <div class="row">
       @php
            $builder = new \App\Utils\ReactCrudSettingsBuilder();

            $skucodeField = new \App\Utils\ReactCrudField('sku_code');
            $skucodeField->title('Código del servicio')->required(true)->width(6);
            $builder->addField($skucodeField);

            $productnameField = new \App\Utils\ReactCrudField('name');
            $productnameField->title('Nombre del servicio')->required(true)->width(6);
            $builder->addField($productnameField);

            $discountField = new \App\Utils\ReactCrudField('discount');
            $discountField->title('Descuento')->required(false)->show(false)->width(4);
            $builder->addField($discountField);

            $priceField = new \App\Utils\ReactCrudField('price');
            $priceField->title('Precio')->required(false)->show(false)->width(4);
            $builder->addField($priceField);

            $productpresentationField = new \App\Utils\ReactCrudField('product_presentation');
            $productpresentationField->show(false)->type('map', [
                ['id' => 'unidad', 'value' => 'Unidad'],
                ['id' => 'par', 'value' => 'Par'],
                ['id' => 'caja', 'value' => 'Caja'],
                ['id' => 'docena', 'value' => 'Docena']
                ])->title('Venta por')->width(4);
            $builder->addField($productpresentationField);

             $descriptionField = new \App\Utils\ReactCrudField('description');
            $descriptionField->title('Descripción')->required(false)->show(false)->width(12);
            $builder->addField($descriptionField);

            $ageField = new \App\Utils\ReactCrudField('age');
            $ageField->title('Edad')->required(false)->show(false)->width(6);
            $builder->addField($ageField);

            $availabilityField = new \App\Utils\ReactCrudField('availability');
            $availabilityField->show(false)->type('map', [
                ['id' => 'D', 'value' => 'Delivery'],
                ['id' => 'S', 'value' => 'Tienda'],
                ['id' => 'A', 'value' => 'Todos'],

                ])->title('Disponibilidad')->width(6);
            $builder->addField($availabilityField);

            $eventField = new \App\Utils\ReactCrudField('event');
            $eventField->title('Ocasión')->required(false)->show(false)->width(6);
            $builder->addField($eventField);

              $interestField = new \App\Utils\ReactCrudField('interest');
            $interestField->title('Interés')->required(false)->show(false)->width(6);
            $builder->addField($interestField);


            $actions = [];
            $actions["custom"] = [];
            $actions['view'] = [];
            $actions['create'] = [
                'url' => route('services.create')
            ];
            $actions['update'] = [
                'url' => route('services.update')
            ];
            $actions['delete'] = [
                'url' => route('services.delete')
            ];

            $builder->setActions($actions);

       @endphp
        <div class="col-md-12">
            <div id="{{\App\Utils\ReactComponents::LARAVEL_CRUD_COMPONENT}}" data-url="{{route('services.lists')}}" data-settings="{{$builder->get()}}" />
        </div>
    </div>
@endsection
