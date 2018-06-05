@extends('layouts.admin')
@section('content')
    <div class="row">
        @php
            $builder = new \App\Utils\ReactCrudSettingsBuilder();

            $titleField = new \App\Utils\ReactCrudField('title');
            $titleField->title('Título de página')->required(true)->fillable(true)->width(12);
            $builder->addField($titleField);

            $contentField = new \App\Utils\ReactCrudField('content');
            $contentField->title('Contenido')->type('editor')->required(true)->show(false)->width(12);
            $builder->addField($contentField);

            $positionField = new \App\Utils\ReactCrudField('position');
            $positionField ->fillable(true)->required(true)->title('Posición')->type('map', [
                ["id"=> 'footer-servicio-cliente',"value"=>"Footer - Servicio al cliente"],
                ['id'=>'footer-informes','value'=>"Footer - Informes"],
                ['id'=>"footer-venta-compra",'value'=>"Footer - Venta y compra"]]);
            $builder->addField($positionField );

            $actions = [];
            $actions["custom"] = [];
            $actions['view'] = [];
            $actions['create'] = [
                'url' => route('pages.create')
            ];
            $actions['update'] = [
                'url' => route('pages.update')
            ];
            $actions['delete'] = [
                'url' => route('pages.delete')
            ];
            $builder->setActions($actions);
        @endphp
        <div class="col-md-12">
            <div id="{{\App\Utils\ReactComponents::LARAVEL_CRUD_COMPONENT}}" data-url="{{route('pages.lists')}}" data-settings="{{$builder->get()}}" />
        </div>
    </div>

@endsection
