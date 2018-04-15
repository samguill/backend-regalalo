@extends('layouts.admin')
@section('content')
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
                                     data_product_characteristics="{{json_encode($product_characteristics)}}"
                                     default_data="{{$product}}"
                                     data_update_url="{{route('products.update')}}"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-20">
                        <button class="btn btn-link text-left" type="button" data-toggle="collapse" data-target="#collapse-representant" aria-expanded="true" aria-controls="collapse-representant">
                            <div class="card-header" id="heading-representant">
                                <h5 class="mb-0 d-inline-block">Fotos del producto</h5>
                                <i class="fa fa-angle-down float-lg-right" style="margin-right: 10px;" aria-hidden="true"></i>
                            </div>
                        </button>
                        <div id="collapse-representant" class="collapse show" aria-labelledby="heading-representant" data-parent="#accordion">
                            <div class="card-body">
                                <div class="product-images-component"
                                     data_store_images="{{$store_images}}"
                                     data_product_id="{{$product->id}}"
                                     data_product_images="{{$product->productimages}}"
                                     data_add_image_url="{{route('product.images.add')}}"
                                     data_delete_image_url="{{route('product.images.delete')}}"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection