@extends('layouts.admin')
@section('content')

    <div class="row">
        <div class="col-md-6 col-lg-3">
            <div class="widget-small primary coloured-icon">
                <i class="icon fa fa-shopping-bag fa-3x"></i>
                <div class="info">
                    <h4>Productos</h4>
                    <p><b>{{count($products)}}</b></p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="widget-small primary coloured-icon">
                <i class="icon fa fa-binoculars fa-3x"></i>
                <div class="info">
                    <h4>Servicios</h4>
                    <p><b>{{count($services)}}</b></p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="widget-small primary coloured-icon">
                <i class="icon fa fa-users fa-3x"></i>
                <div class="info">
                    <h4>Clientes</h4>
                    <p><b>{{count($clients)}}</b></p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="widget-small primary coloured-icon">
                <i class="icon fa fa-building-o fa-3x"></i>
                <div class="info">
                    <h4>Tiendas</h4>
                    <p><b>{{count($stores)}}</b></p>
                </div>
            </div>
        </div>
    </div>

@endsection
