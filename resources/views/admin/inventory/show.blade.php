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
                            <a href="{{ route('admin.inventory') }}">Inventario</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong>Producto {{$inventory->product->name}}</strong>
                        </li>
                    </ol>


                    <br class="hidden-print" />
                    <div class="invoice">
                        <div class="row">
                            <div class="col-md-12 invoice-left">
                                <h3>PRODUCTO: {{$inventory->product->name}}</h3>
                                <p>SKU: {{$inventory->product->sku_code}}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3 invoice-left">
                                <h4>Cantidad</h4>
                                {{$inventory->quantity}}
                            </div>


                            <div class="col-sm-3 invoice-left">
                                <h4>Ubicación</h4>
                                {{$inventory->branch->name}}
                            </div>

                            <div class="col-sm-3 invoice-left">
                                <h4>Fecha de Ingreso</h4>
                                {{ date_format($inventory->created_at, 'd/m/Y')}}
                            </div>
                        </div>

                        <hr class="margin" />
                        <div class="row">
                            <div class="col-sm-12">
                                <h3>Movimientos de Producto</h3>
                            </div>
                        </div>
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Tipo de Movimiento</th>
                                <th>Cantidad</th>
                                <th>Órden</th>
                                <th>Fecha de Movimiento</th>
                            </tr>
                            </thead>
                            @foreach($inventory->movements as $movement)
                                <tr>
                                    <td> {{($movement->movement_type == "I") ? "Ingreso"  : "Egreso" }} </td>
                                    <td>{{$movement->quantity}} </td>
                                    <td>@if(isset($movement->order->order_code)){{$movement->order->order_code}}@else - @endif</td>
                                    <td>{{ date_format($movement->created_at, 'd/m/Y') }} </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
