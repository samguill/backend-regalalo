@extends('layouts.store')
@section('content')
    <div class="row">
       @php

                    $builder = new \App\Utils\ReactCrudSettingsBuilder();

                    $skucodeField = new \App\Utils\ReactCrudField('sku_code');
                    $skucodeField->title('Código del servicio')->required(true)->width(6);
                    $builder->addField($skucodeField);



                    $actions = [];
                    $actions["custom"] = [];
                    $actions['custom']=array_merge(
                    $actions["custom"],
                      [
                    "edit" => [
                        "link" => true,
                        'url' => route('orders.view'),
                        'icon' => "edit",
                        "color" => "#4CAF50",
                        "params" => [ 'id' ],
                        "title" => "Ver"
                    ]
                                    ]
                            );

                    $builder->setActions($actions);



       @endphp
        <div class="col-md-12">
            <div id="{{\App\Utils\ReactComponents::LARAVEL_CRUD_COMPONENT}}" data-url="{{route('orders.lists')}}" data-settings="{{$builder->get()}}" />
        </div>
    </div>

    <div class="modal fade" id="services_charge_modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <a href="{{ asset('uploads/formats/services_charge.xlsx') }}" target="_blank" class="btn btn-block btn-success">
                        <i class="fa fa-file-excel-o" aria-hidden="true"></i> Descargar formato
                    </a>
                    <form class="mt-20" id="charge_stores_form" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="exampleFormControlFile1">Subir archivo excel</label>
                            <input type="file" class="form-control-file" id="excel" name="excel">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="charge_services(this)">Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
@endsection
