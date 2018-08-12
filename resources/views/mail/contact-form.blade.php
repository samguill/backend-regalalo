@extends('layouts.mail')
@section('content')
    <table border="0" cellpadding="30" cellspacing="0" width="600" class="flexibleContainer">
        <tr>
            <td valign="top" width="600" class="flexibleContainerCell">
                <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td valign="middle" class="textContent">
                            <p style="line-height: 28px; color: #393231;">Haz recibido y mensaje desde el formulario de contacto:</p>
                            <p style="line-height: 28px; color: #393231;"><b>Nombres y Apellidos:</b> {{$data["name"]}}<br>
                            <p style="line-height: 28px; color: #393231;"><b>E-mail:</b> {{$data["email"]}}</p>
                            <p style="color: #393231;"><b>Consulta:</b></p>
                            <p style="color: #393231;">{{$data["message"]}}</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection