@extends('layouts.store')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="store-images"
                         multimedialist="{{route('store.multimedia-lists')}}"
                         storeid="{{$store_id}}"
                         upload_url="{{route('store.multimedia-upload')}}"
                    />
                </div>
            </div>
        </div>
    </div>

@endsection
