@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card-body">
                <div class="accordion" id="accordion">
                    <div class="card mb-20">
                        <button class="btn btn-link text-left" type="button" data-toggle="collapse" data-target="#collapse-product" aria-expanded="false" aria-controls="collapse-product">
                            <div class="card-header" id="heading-product">
                                <h5 class="mb-0 d-inline-block">Datos del servicio</h5>
                                <i class="fa fa-angle-down float-lg-right" style="margin-right: 10px;" aria-hidden="false"></i>
                            </div>
                        </button>
                        <div id="collapse-product" class="collapse" aria-labelledby="heading-product" data-parent="#accordion">
                            <div class="card-body">
                                <div class="update-service-component"
                                     data_sex="{{json_encode($sex)}}"
                                     data_ages="{{json_encode($ages)}}"
                                     data_experiences="{{json_encode($experiences)}}"
                                     data_product_characteristics="{{json_encode($service_characteristics)}}"
                                     default_data="{{$service}}"
                                     data_update_url="{{route('services.update')}}"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-20">
                        <button class="btn btn-link text-left" type="button" data-toggle="collapse" data-target="#collapse-featured" aria-expanded="false" aria-controls="collapse-featured">
                            <div class="card-header" id="heading-featured">
                                <h5 class="mb-0 d-inline-block">Imagen destacada</h5>
                                <i class="fa fa-angle-down float-lg-right" style="margin-right: 10px;" aria-hidden="true"></i>
                            </div>
                        </button>
                        <div id="collapse-featured" class="collapse" aria-labelledby="heading-featured" data-parent="#accordion">
                            <div class="card-body">
                                <div class="featured-image-service-component"
                                     service_id="{{$service->id}}"
                                     featured_image="{{$service->featured_image}}"
                                     data_upload_url="{{route('service.upload.featured_image')}}"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-20">
                        <button class="btn btn-link text-left" type="button" data-toggle="collapse" data-target="#collapse-representant" aria-expanded="false" aria-controls="collapse-representant">
                            <div class="card-header" id="heading-representant">
                                <h5 class="mb-0 d-inline-block">Fotos del servicio</h5>
                                <i class="fa fa-angle-down float-lg-right" style="margin-right: 10px;" aria-hidden="true"></i>
                            </div>
                        </button>
                        <div id="collapse-representant" class="collapse" aria-labelledby="heading-representant" data-parent="#accordion">
                            <div class="card-body">
                                <div class="service-images-component"
                                     data_store_images="{{$store_images}}"
                                     data_service_id="{{$service->id}}"
                                     data_service_images="{{$service->serviceimages}}"
                                     data_add_image_url="{{route('service.images.add')}}"
                                     data_delete_image_url="{{route('service.images.delete')}}"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection