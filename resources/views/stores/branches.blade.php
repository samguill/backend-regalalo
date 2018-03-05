@extends('layouts.dashboard')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="store-branches" brancheslist="{{route('store.branches-lists')}}" storeid="{{$store_id}}" />
                </div>
            </div>
        </div>
    </div>
@endsection
