@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card-body">
                <div class="accordion" id="accordion">
                    <div class="card">
                        <button class="btn btn-link text-left" type="button" data-toggle="collapse" data-target="#collapse-datos" aria-expanded="true" aria-controls="collapse-datos">
                            <div class="card-header" id="heading-datos">
                                <h5 class="mb-0">Datos de la tienda</h5>
                            </div>
                        </button>
                        <div id="collapse-datos" class="collapse show" aria-labelledby="heading-datos" data-parent="#accordion">
                            <div class="card-body">
                                <div class="update-store-component"
                                     default_data="{{$store}}"
                                     data_update_url="{{route('stores.update')}}" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection