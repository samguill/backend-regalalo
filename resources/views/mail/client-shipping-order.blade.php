@extends('layouts.mail')
@section('content')
    <table border="0" cellpadding="30" cellspacing="0" width="600" class="flexibleContainer">
        <tr>
            <td valign="top" width="600" class="flexibleContainerCell">
                <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td valign="middle" class="textContent">
                            <p style="color: #393231;">Hola, {{$order->client->first_name}}</p>
                            <p style="color: #393231;"><b>¡Muchas gracias por tu compra!</b></p>
                            <p style="color: #393231;">La tienda {{$order->store->comercial_name}} empezará a preparar tu pedido.</p>
                            <br>
                            <table border="0">
                                <tbody>
                                    <tr>
                                        <td colspan="3">
                                            <p style="color: #393231;"><b>Detalle del pedido</b></p>
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
                                                    <p style="color: #393231;"><b>Detalle de envío</b></p>
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
                                            <p style="color: #393231;"><b>Resumen del pedido</b></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><b>Subtotal</b></td>
                                        <td>S/{{$order->sub_total}}</td>
                                    </tr>
                                    @if($order->delivery)
                                        <tr>
                                            <td colspan="2"><b>Envío</b></td>
                                            <td>S/{{$price_delivery}}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td colspan="2"><b>Total</b></td>
                                        <td>S/{{$order->total}}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <br>
                            <p style="color: #393231;"><b>¿Necesitas ayuda?</b><br>
                            Consulta nuestras preguntas frecuentes dando clic <a href="https://regalalo.pe/faq" target="_blank">aquí</a></p>
                            <br>
                            <p style="color: #393231;">Regalalo Perú.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection