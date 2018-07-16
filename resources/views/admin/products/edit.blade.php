@extends('layouts.admin')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('product')}}">  Productos</a></li>
        <li class="breadcrumb-item active">Editar</li>
    </ol>
    <div class="row">
        <div class="col-md-12">
            <div class="card-body">
                <div class="accordion" id="accordion">
                    <div class="card mb-20">
                        <button class="btn btn-link text-left" type="button" data-toggle="collapse" data-target="#collapse-product" aria-expanded="false" aria-controls="collapse-product">
                            <div class="card-header" id="heading-product">
                                <h5 class="mb-0 d-inline-block">Datos del producto</h5>
                                <i class="fa fa-angle-down float-lg-right" style="margin-right: 10px;" aria-hidden="false"></i>
                            </div>
                        </button>
                        <div id="collapse-product" class="collapse" aria-labelledby="heading-product" data-parent="#accordion">
                            <div class="card-body">
                                <div class="update-product-component"
                                     data_sex="{{json_encode($sex)}}"
                                     data_ages="{{json_encode($ages)}}"
                                     data_events="{{json_encode($events)}}"
                                     data_interests="{{json_encode($interests)}}"
                                     default_data="{{$product}}"
                                     data-brands="{{json_encode($brands)}}"
                                     data_update_url="{{route('product.update')}}"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-20">
                        <button class="btn btn-link text-left" type="button" data-toggle="collapse" data-target="#collapse-characteristics" aria-expanded="true" aria-controls="collapse-characteristics">
                            <div class="card-header" id="heading-characteristics">
                                <h5 class="mb-0 d-inline-block">Características del producto</h5>
                                <i class="fa fa-angle-down float-lg-right" style="margin-right: 10px;" aria-hidden="false"></i>
                            </div>
                        </button>
                        <div id="collapse-characteristics" class="collapse show" aria-labelledby="heading-characteristics" data-parent="#accordion">
                            <div class="card-body">
                                <div class="characteristics-product-component"
                                    product_id="{{$product->id}}"
                                    data_product_characteristics_detail="{{json_encode($product->productcharacteristicsdetail)}}"
                                    data_product_characteristics="{{json_encode($product_characteristics)}}"
                                    data_update_url="{{route('product.characteristics_update')}}"
                                    data_delete_url="{{route('product.characteristics_delete')}}"
                                    data_store_url="{{route('product.characteristics_store')}}"></div>
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
                                <div class="featured-image-product-component"
                                     site_url="{{env('APP_URL')}}"
                                     product_id="{{$product->id}}"
                                     featured_image="{{$product->featured_image}}"
                                     data_upload_url="{{route('single.product.upload.featured_image')}}"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-20">
                        <button class="btn btn-link text-left" type="button" data-toggle="collapse" data-target="#collapse-representant" aria-expanded="false" aria-controls="collapse-representant">
                            <div class="card-header" id="heading-representant">
                                <h5 class="mb-0 d-inline-block">Fotos del producto</h5>
                                <i class="fa fa-angle-down float-lg-right" style="margin-right: 10px;" aria-hidden="true"></i>
                            </div>
                        </button>
                        <div id="collapse-representant" class="collapse" aria-labelledby="heading-representant" data-parent="#accordion">
                            <div class="card-body">
                                <div class="product-images-component"
                                     site_url="{{env('APP_URL')}}"
                                     data_store_images="{{$store_images}}"
                                     data_product_id="{{$product->id}}"
                                     data_product_images="{{$product->productimages}}"
                                     data_add_image_url="{{route('product.images.add')}}"
                                     data_delete_image_url="{{route('product.images.delete')}}"></div>
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
                                <div class="product-seo-component"
                                     data_update_url="{{route('product.update_seo')}}"
                                     product_id="{{$product->id}}"
                                     meta_title="{{$product->meta_title}}"
                                     meta_description="{{$product->meta_description}}"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection