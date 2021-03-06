@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card-body">
                <div class="accordion" id="accordion">
                    <div class="card mb-20">
                        <button class="btn btn-link text-left" type="button" data-toggle="collapse" data-target="#collapse-datos" aria-expanded="false" aria-controls="collapse-datos">
                            <div class="card-header" id="heading-datos">
                                <h5 class="mb-0 d-inline-block">Datos de la tienda</h5>
                                <i class="fa fa-angle-down float-lg-right" style="margin-right: 10px;" aria-hidden="true"></i>
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
                        <button class="btn btn-link text-left" type="button" data-toggle="collapse" data-target="#collapse-representant" aria-expanded="false" aria-controls="collapse-representant">
                            <div class="card-header" id="heading-representant">
                                <h5 class="mb-0 d-inline-block">Representantes</h5>
                                <i class="fa fa-angle-down float-lg-right" style="margin-right: 10px;" aria-hidden="true"></i>
                            </div>
                        </button>
                        <div id="collapse-representant" class="collapse" aria-labelledby="heading-representant" data-parent="#accordion">
                            <div class="card-body">
                                <div class="representantive-store-component"
                                     default_data="{{$store->legal_representatives}}"
                                     store_id="{{$store->id}}"
                                     data_update_url="{{route('store.legal-representative-update')}}"
                                     data_create_url="{{route('store.legal-representative-create')}}"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-20">
                        <button class="btn btn-link text-left" type="button" data-toggle="collapse" data-target="#collapse-comercial-contact" aria-expanded="false" aria-controls="collapse-comercial-contact">
                            <div class="card-header" id="heading-comercial-contact">
                                <h5 class="mb-0 d-inline-block">Contacto Comercial</h5>
                                <i class="fa fa-angle-down float-lg-right" style="margin-right: 10px;" aria-hidden="true"></i>
                            </div>
                        </button>
                        <div id="collapse-comercial-contact" class="collapse" aria-labelledby="heading-comercial-contact" data-parent="#accordion">
                            <div class="card-body">
                                <div class="comercial-contact-edit-component"
                                     store_id="{{$store->id}}"
                                     default_data="{{$store->comercial_contact}}"
                                     data_update_url="{{route('store.comercial-contact-update')}}"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-20">
                        <button class="btn btn-link text-left" type="button" data-toggle="collapse" data-target="#collapse-logo" aria-expanded="false" aria-controls="collapse-logo">
                            <div class="card-header" id="heading-logo">
                                <h5 class="mb-0 d-inline-block">Imagen / Logo</h5>
                                <i class="fa fa-angle-down float-lg-right" style="margin-right: 10px;" aria-hidden="true"></i>
                            </div>
                        </button>
                        <div id="collapse-logo" class="collapse" aria-labelledby="heading-logo" data-parent="#accordion">
                            <div class="card-body">
                                <div class="logo-store-component"
                                     store_id="{{$store->id}}"
                                     logo_store="{{$store->logo_store}}"
                                     data_upload_url="{{route('stores.upload-logo')}}"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-20">
                        <button class="btn btn-link text-left" type="button" data-toggle="collapse" data-target="#collapse-accesos" aria-expanded="false" aria-controls="collapse-accesos">
                            <div class="card-header" id="heading-accesos">
                                <h5 class="mb-0 d-inline-block">Datos de acceso</h5>
                                <i class="fa fa-angle-down float-lg-right" style="margin-right: 10px;" aria-hidden="true"></i>
                            </div>
                        </button>
                        <div id="collapse-accesos" class="collapse" aria-labelledby="heading-accesos" data-parent="#accordion">
                            <div class="card-body">
                                <div class="store-access-component"
                                     store_id="{{$store->id}}"
                                     user_data="{{$store->user}}"
                                     data_update_url="{{route('stores.update-access')}}"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-20">
                        <button class="btn btn-link text-left" type="button" data-toggle="collapse" data-target="#collapse-posicionamiento" aria-expanded="false" aria-controls="collapse-posicionamiento">
                            <div class="card-header" id="heading-posicionamiento">
                                <h5 class="mb-0 d-inline-block">Datos de posicionamiento (SEO)</h5>
                                <i class="fa fa-angle-down float-lg-right" style="margin-right: 10px;" aria-hidden="true"></i>
                            </div>
                        </button>
                        <div id="collapse-posicionamiento" class="collapse" aria-labelledby="heading-posicionamiento" data-parent="#accordion">
                            <div class="card-body">
                                <div class="store-seo-component"
                                     store_id="{{$store->id}}"
                                     meta_title="{{$store->meta_title}}"
                                     meta_description="{{$store->meta_description}}"
                                     data_update_url="{{route('stores.update')}}"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection