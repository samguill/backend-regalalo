@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="store-branches"
                         brancheslist="{{route('store.branches-lists')}}"
                         storeid="{{$store_id}}"
                         url_create_branch="{{route('store.branch-create')}}"
                         url_update_branch="{{route('store.branch-update')}}"
                    />
                </div>
            </div>
        </div>
    </div>
@endsection
