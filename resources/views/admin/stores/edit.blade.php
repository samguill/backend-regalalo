@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card-body">
                <div class="accordion" id="accordion">
                    <div class="card mb-20">
                        <button class="btn btn-link text-left" type="button" data-toggle="collapse" data-target="#collapse-datos" aria-expanded="false" aria-controls="collapse-datos">
                            <div class="card-header" id="heading-datos">
                                <h5 class="mb-0">Datos de la tienda</h5>
                            </div>
                        </button>
                        <div id="collapse-datos" class="collapse" aria-labelledby="heading-datos" data-parent="#accordion">
                            <div class="card-body">
                                <div class="update-store-component"
                                     default_data="{{$store}}"
                                     data_update_url="{{route('stores.update')}}"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-20">
                        <button class="btn btn-link text-left" type="button" data-toggle="collapse" data-target="#collapse-representant" aria-expanded="true" aria-controls="collapse-representant">
                            <div class="card-header" id="heading-representant">
                                <h5 class="mb-0">Representantes</h5>
                            </div>
                        </button>
                        <div id="collapse-representant" class="collapse show" aria-labelledby="heading-representant" data-parent="#accordion">
                            <div class="card-body">
                                <div class="update-representant-component"
                                     default_data="{{$store->legal_representatives}}"
                                     data_update_url="{{route('stores.update')}}"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-20">
                        <button class="btn btn-link text-left" type="button" data-toggle="collapse" data-target="#collapse-comercial-contact" aria-expanded="true" aria-controls="collapse-comercial-contact">
                            <div class="card-header" id="heading-comercial-contact">
                                <h5 class="mb-0">Contacto Comercial</h5>
                            </div>
                        </button>
                        <div id="collapse-ccomercial-contact" class="collapse show" aria-labelledby="heading-comercial-contact" data-parent="#accordion">
                            <div class="card-body">
                                <div class="update-comercial-contact-component"
                                     default_data="{{$store->legal_representatives}}"
                                     data_update_url="{{route('stores.update')}}"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-20">
                        <button class="btn btn-link text-left" type="button" data-toggle="collapse" data-target="#collapse-logo" aria-expanded="true" aria-controls="collapse-logo">
                            <div class="card-header" id="heading-logo">
                                <h5 class="mb-0">Imagen / Logo</h5>
                            </div>
                        </button>
                        <div id="collapse-logo" class="collapse show" aria-labelledby="heading-logo" data-parent="#accordion">
                            <div class="card-body">
                                <div class="update-logo-component"
                                     default_data="{{$store->legal_representatives}}"
                                     data_update_url="{{route('stores.update')}}"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection