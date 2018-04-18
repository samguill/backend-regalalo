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
            <div class="widget-small primary coloured-icon">
                <i class="icon fa fa-shopping-bag fa-3x"></i>
                <div class="info">
                    <h4>Productos</h4>
                    <p><b>16</b></p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="widget-small primary coloured-icon">
                <i class="icon fa fa-binoculars fa-3x"></i>
                <div class="info">
                    <h4>Servicios</h4>
                    <p><b>12</b></p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="widget-small primary coloured-icon">
                <i class="icon fa fa-sitemap fa-3x"></i>
                <div class="info">
                    <h4>Sucursales</h4>
                    <p><b>4</b></p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="widget-small primary coloured-icon">
                <i class="icon fa fa-paper-plane fa-3x"></i>
                <div class="info">
                    <h4>Ventas</h4>
                    <p><b>19</b></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title">Ventas mensuales</h3>
                <div class="embed-responsive embed-responsive-16by9">
                    <canvas class="embed-responsive-item" id="lineChartDemo"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection
