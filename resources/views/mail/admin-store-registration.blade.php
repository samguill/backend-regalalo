@extends('layouts.mail')
@section('content')
    <table border="0" cellpadding="30" cellspacing="0" width="600" class="flexibleContainer">
        <tr>
            <td valign="top" width="600" class="flexibleContainerCell">
                <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td valign="middle" class="textContent">

                            <p style="line-height: 28px; color: #393231;">Se ha registrado una nueva tienda, los datos son:</p>
                            <p style="line-height: 28px; color: #393231;">Nombre comercial: {{$store['comercial_name']}}<br>
                            <p style="line-height: 28px; color: #393231;">Razón social: {{$store['business_name']}}<br>
                            <p style="line-height: 28px; color: #393231;">RUC: {{$store['ruc']}}<br>
                            <p style="line-height: 28px; color: #393231;">Contacto comercial: {{$store['name']}}<br>
                            <p style="color: #393231;">Regalalo Perú.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection