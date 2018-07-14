@extends('layouts.mail')
@section('content')
    <table border="0" cellpadding="30" cellspacing="0" width="600" class="flexibleContainer">
        <tr>
            <td valign="top" width="600" class="flexibleContainerCell">
                <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td valign="middle" class="textContent">
                            <p style="color: #393231;">Hola,<br>{{$order->store->comercial_name}} el cliente <b>{{$order->client->first_name}} {{$order->client->last_name}}</b> ha realizado un nuevo pedido.</p>
                            <br>
                            <table border="0" style="color: #393231;">
                                <tbody>
                                <tr>
                                    <td colspan="3">
                                        <p><b>Detalle del pedido</b></p>
                                    </td>
                                </tr>
                                    @php $price_delivery = 0; @endphp
                                    @foreach($order->orderdetails as $orderdetail)
                                        <tr>
                                            <td>
                                                <img src="{{$orderdetail->product->featured_image}}" width="90">
                                            </td>
                                            <td>
                                                @if($orderdetail->product_id)
                                                    <b>{{$orderdetail->product->name}}</b>
                                                @else
                                                    <b>{{$orderdetail->service->name}}</b>
                                                @endif
                                            </td>
                                            <td>
                                                <b>Precio:</b> S/{{$orderdetail->price}}<br>
                                                <b>Cantidad:</b> {{$orderdetail->quantity}}
                                            </td>
                                        </tr>
                                        @if($order->delivery)
                                            <tr>
                                                <td colspan="3">
                                                    <p><b>Detalle de envío</b></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3">
                                                    <b>Dirección de entrega</b><br>
                                                    {{$order->clientdirection->address}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3">
                                                    <b>URL de seguimiento</b><br>
                                                    <a href="{{$orderdetail->tracking_url}}" target="_blank">clic aquí</a>
                                                </td>
                                            </tr>
                                            @php $price_delivery += $orderdetail->price_delivery; @endphp
                                        @endif
                                    @endforeach
                                    <tr>
                                        <td colspan="3">
                                            <p><b>Resumen del pedido</b></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><b style="color: #393231;">Subtotal</b></td>
                                        <td><span style="color: #393231;">S/{{$order->sub_total}}</span></td>
                                    </tr>
                                    @if($order->delivery)
                                        <tr>
                                            <td colspan="2"><b style="color: #393231;">Envío</b></td>
                                            <td><span style="color: #393231;">S/{{$price_delivery}}</span></td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td colspan="2"><b style="color: #393231;">Total</b></td>
                                        <td><span style="color: #393231;">S/{{$order->total}}</span></td>
                                    </tr>
                                </tbody>
                            </table>
                            @if($order->delivery)
                                <p style="color: #393231;"><b>Recuerda tenerlo listo y empacada para el recojo del motorizado de Urbaner</b></p>
                            @else
                                <p style="color: #393231;"><b>Recuerda recuerda tenerlo listo para el recojo del cliente</b></p>
                            @endif
                            <br>
                            <p style="color: #393231;">Regalalo Perú.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection