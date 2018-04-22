@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="store-branches"
                         brancheslist="{{route('store.branches-lists-admin')}}"
                         storeid="{{$store_id}}"
                         url_create_branch="{{route('store.branch-create-admin')}}"
                         url_update_branch="{{route('store.branch-update-admin')}}"
                    />
                </div>
            </div>
        </div>
    </div>
@endsection
