@extends('layouts.admin')
@section('content')
    <div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">

            <ol class="breadcrumb col-md-12 hidden-print" >
                <li class="breadcrumb-item">
                    <a href="{{ url('') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('orders') }}">Ventas</a>
                </li>
                <li class="breadcrumb-item active">
                    <strong>Orden  N° {{$order->order_code}}</strong>
                </li>
            </ol>


            <br class="hidden-print" />
            <div class="invoice">
                <div class="row">
                    <div class="col-md-12">
                        <h5>Código de Orden: </h5>
                        {{$order->order_code}}
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-4">
                        <h5>Datos del Cliente</h5>
                       <b>Nombres</b>: {{$order->client->first_name}} {{$order->client->last_name}}<br>
                        <b>Correo electrónico</b>: {{$order->client->email}}<br>
                            <b>Teléfono</b>: {{$order->client->phone}}
                    </div>


                    <div class="col-sm-4">
                        <h5>Dirección de envío</h5>
                        {{isset($order->clientdirection->address)? $order->clientdirection->address:''}}</br>
                        <b>{{isset($order->clientdirection->city)?$order->clientdirection->city:''}}</b>


                    </div>

                    <div class="col-sm-4">
                        <h5>Fecha de compra</h5>
                        {{ $order->created_at}}
                    </div>
                </div>

                <hr class="margin" />


                <div class="row">
                    <div class="col-sm-12">
                        <h4>Detalle</h4>
                    </div>
                </div>
            <table class="table">
                <thead>
                    <tr>


                        <th>Producto o Servicio</th>
                        <th>Delivery</th>
                        <th>Cantidad</th>
                        <th>Tienda</th>
                        <th>Sub-Total</th>
                        <th>Total</th>

                    </tr>
                </thead>
                @foreach($order->orderdetails as $orderdetail)
                    <tr>

                        <td>
                            @if(isset($orderdetail->product)){{$orderdetail->product->sku_code}} - {{$orderdetail->product->name}}@endif
                            @if(isset($orderdetail->service)){{$orderdetail->service->sku_code}} - {{$orderdetail->service->name}}@endif
                        </td>
                        <td>@if($order->delivery)
                               <b> Código Tracking</b>: {{$orderdetail->tracking_code}}<br>
                               <b> Precio Delivery</b>: {{number_format( $orderdetail->price_delivery,2)}}<br>
                                <b> Tracking URL:</b> <a href="{{$orderdetail->tracking_url}}"  target="_blank">{{ $orderdetail->tracking_url}} </a><br>
                            @else
                        Recojo en tienda
                            @endif
                        </td>
                        <td>{{$orderdetail->quantity}} </td>
                        <td>{{$orderdetail->branch->name}} </td>
                        <td>{{ number_format($order->sub_total,2)}} </td>
                        <td>{{number_format($order->total,2)}} </td>
                    </tr>
                @endforeach
            </table>
        </div>
            </div>
                </div>

        </div>
    </div>
@endsection
