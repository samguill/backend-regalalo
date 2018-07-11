@extends('layouts.mail')
@section('content')
    <table border="0" cellpadding="30" cellspacing="0" width="600" class="flexibleContainer">
        <tr>
            <td valign="top" width="600" class="flexibleContainerCell">
                <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td valign="middle" class="textContent">
                            <p style="color: #393231;">¡Hola {{$client->first_name}}!:</p>
                            <p style="line-height: 28px; color: #393231;">¡BIENVENIDO A REGÁLALO!</p>
                            <p style="line-height: 28px; color: #393231;">Para todo el equipo de Regalalo es una gran alegría darte la bienvenida a nuestra plataforma, por ello te queremos dar las gracias por confiar en nosotros.<br>
                            <p style="color: #393231;">Regalalo Perú.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection