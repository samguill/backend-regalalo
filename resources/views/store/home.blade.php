@extends('layouts.store')
@section('content')

    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> Escritorio</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">Escritorio</a></li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-6 col-lg-3">
            <div class="widget-small primary coloured-icon"><i class="icon fa fa-users fa-3x"></i>
                <div class="info">
                    <h4>Clientes</h4>
                    <p><b>5</b></p>
                </div>
            </div>
        </div>
    </div>

@endsection
