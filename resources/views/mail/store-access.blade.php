@extends('layouts.mail')
@section('content')
    <table border="0" cellpadding="30" cellspacing="0" width="600" class="flexibleContainer">
        <tr>
            <td valign="top" width="600" class="flexibleContainerCell">
                <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td valign="middle" class="textContent">
                            <p style="color: #393231;">Hola, {{$user->name}}</p>
                            <p style="line-height: 28px; color: #393231;">Te damos la bienvenido a nuestro nuevo portal, tus datos de acceso son:</p>
                            <p style="line-height: 28px; color: #393231;"><b>URL de Acceso:</b> <a href="{{env('APP_URL')}}" target="_blank">regalalo.pe</a><br>
                            <b>Usuario:</b> {{$user->email}}<br>
                            <b>Contraseña:</b> {{$pin}}</p>
                            <br>
                            <p style="color: #393231;">Regalalo Perú.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection