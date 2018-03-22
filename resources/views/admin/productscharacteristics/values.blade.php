@extends('layouts.admin')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('productcharacteristics')}}">Home</a></li>
        <li class="breadcrumb-item active">Valores</li>
    </ol>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{$productcharacteristic->name}}</h5>
                    <div class="product-characteristic-values"
                         brancheslist="{{route('productcharacteristic-values.lists')}}"
                         storeid="{{$product_characteristic_id}}"
                         url_create_branch="{{route('productcharacteristic-values.create')}}"
                         url_update_branch="{{route('productcharacteristic-values.update')}}"
                    />
                </div>
            </div>
        </div>
    </div>
@endsection
