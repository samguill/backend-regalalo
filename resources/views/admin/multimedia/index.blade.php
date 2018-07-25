@extends('layouts.store')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="multimedia-images"
                        data-site_url="{{env('APP_URL')}}"
                        data-stores="{{json_encode($stores)}}"
                        data-store-images-url="{{route('admin.multimedia.images.list')}}"
                        data-upload-images-url="{{route('admin.multimedia-upload-files')}}"
                        data-delete-images-url="{{route('admin.multimedia-delete-files')}}"></div>
                </div>
            </div>
        </div>
    </div>

@endsection
