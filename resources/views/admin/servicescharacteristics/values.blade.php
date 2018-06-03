@extends('layouts.admin')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('servicecharacteristics')}}">Home</a></li>
        <li class="breadcrumb-item active">Valores</li>
    </ol>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{$servicecharacteristic->name}}</h5>
                    <div class="service-characteristic-values"
                         brancheslist="{{route('servicecharacteristic-values.lists')}}"
                         storeid="{{$service_characteristic_id}}"
                         url_create_branch="{{route('servicecharacteristic-values.create')}}"
                         url_update_branch="{{route('servicecharacteristic-values.update')}}"
                    />
                </div>
            </div>
        </div>
    </div>
@endsection
