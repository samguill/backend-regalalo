@extends('layouts.admin')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{$productcharacteristicvalues->name}}</h5>
                    <div class="product-characteristic-values"
                         data="{{json_encode($productcharacteristicvalues)}}"
                    />
                </div>
            </div>
        </div>
    </div>
@endsection
