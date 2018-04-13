@extends('layouts.admin')
@section('content')
    <div class="col-md-12  mb-10">
        <div class="card">
            <div class="card-block">
                <div class="card-body">
            <div class="row">
                <div class="col-md-6 col-sm-8 clearfix">
                    <ul class="list-inline links-list pull-left">
                        <li>
                            <?php
                            $dia = date("w");
                            $numerodia = date("j");
                            $mes = date('n');
                            $anio = date("Y");
                            $dias = array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado");
                            $meses = array(1=>"Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
                            ?>
                            <?php echo $dias[$dia]; ?>, <?php echo $numerodia; ?> de <?php echo $meses[$mes]; ?> de <?php echo $anio; ?>
                        </li>
                    </ul>
                </div>

            <hr>
            <ol class="breadcrumb bc-2 hidden-print" >
                <li>
                    <a href="{{ url('') }}"><i class="fa fa-home"></i>Inicio</a>
                </li>
                <li>
                    <a href="{{ url('/inventario') }}">Inventario</a>
                </li>
                <li class="active">
                    <strong>Producto {{$inventory->product->codigo_producto}}</strong>
                </li>
            </ol>
            <br class="hidden-print" />
            <div class="invoice">
                <div class="row">
                    <div class="col-md-12 invoice-left">
                        <h3>CÓDIGO DE PRODUCTO: {{$inventory->product->codigo_producto}}</h3>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3 invoice-left">
                        <h4>Cantidad</h4>
                        {{$inventory->cantidad}}
                    </div>


                    <div class="col-sm-3 invoice-left">
                        <h4>Ubicación</h4>
                        {{$inventory->ubicacion}}
                    </div>
                    <div class="col-sm-3 invoice-left">
                        <h4>Cantidad en mal estado</h4>
                        {{$inventory->cantidad_mal_estado}}
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
                        <th>Órden de compra</th>
                        <th>Guía Proveedor</th>
                        <th>Factura Proveedor</th>
                        <th>Nota de pedido</th>
                        <th>Guía Cliente</th>
                        <th>Boleta Cliente</th>
                        <th>Factura Cliente</th>
                        <th>Fecha de Movimiento</th>
                    </tr>
                </thead>
                @foreach($inventory->movements as $movement)
                    <tr>
                        <td> @if($movement->tipo_movimiento == "I") {{ "Ingreso" }} @else {{ "Egreso" }} @endif</td>
                        <td>{{$movement->cantidad}} </td>
                        <td>@if(isset($movement->order->codigo)){{$movement->order->codigo}}@else - @endif</td>
                        <td>@if($movement->tipo_movimiento == "I") {{$movement->numero_guia}} @else - @endif </td>
                        <td>@if($movement->tipo_movimiento == "I") {{$movement->numero_factura}} @else - @endif </td>
                        <td>{{$movement->numero_notapedido}} </td>
                        <td>@if($movement->tipo_movimiento == "I") - @else {{$movement->numero_guia}} @endif </td>
                        <td>{{$movement->numero_boleta}} </td>
                        <td>@if($movement->tipo_movimiento == "I") @else {{$movement->numero_factura}}  @endif </td>
                        <td>{{ date_format($movement->created_at, 'd/m/Y') }} </td>
                    </tr>
                @endforeach
            </table>
        </div>
            </div>
                </div>
            </div>
        </div>
    </div>
@endsection
