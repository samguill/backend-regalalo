@extends('layouts.pdf')
@section('content')

    <div class="row">
        <table width="100%">
            <tr>
                <td>
                    <table>
                        <tr>
                            <td>
                                <p class="gray-text-1" style="">www.<br>alignet<br>.com</p>
                            </td>
                            <td>
                                <p class="gray-text-2">Av. Casimiro Ulloa Nº 333<br>San Antonio, Miraflores<br>Lima 18 - Perú<br>T. 511 610 9500</p>
                            </td>
                        </tr>
                    </table>
                </td>
                <td align="right">
                    <img src="{{ asset('img/logo-payme.png') }}" height="70" />
                </td>
            </tr>
            <tr>
                <td height="10px" colspan="2"></td>
            </tr>
            <tr>
                <td colspan="2">
                    <h1 class="title-1">Solicitud de afiliación</h1>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <p class="subtitle-1">Pasarela de pagos para comercio electronico</p>
                    <p class="subtitle-2">Datos del comercio</p>
                </td>
            </tr>
        </table>

        <table width="100%" class="table" border="0">
            <tr>
                <td bgcolor="#a9a9a9" width="40%">Razón Social:</td>
                <td width="60%">{{ $store->business_name }}</td>
            </tr>
            <tr>
                <td bgcolor="#a9a9a9" width="40%">Nombre Comercial:</td>
                <td width="60%">{{ $store->comercial_name }}</td>
            </tr>
            <tr>
                <td bgcolor="#a9a9a9" width="40%">RUC:</td>
                <td width="60%">{{ $store->ruc }}</td>
            </tr>
            <tr>
                <td bgcolor="#a9a9a9" width="40%">Domicilio Legal:</td>
                <td width="60%">{{ $store->legal_address }}</td>
            </tr>
            <tr>
                <td bgcolor="#a9a9a9" width="40%">Dirección de Facturación:</td>
                <td width="60%">{{ $store->legal_address }}</td>
            </tr>
            <tr>
                <td bgcolor="#a9a9a9" width="40%">Representante Legal:</td>
                <td width="60%">{{ $store->legal_representatives[0]['name'] }}</td>
            </tr>
            <tr>
                <td bgcolor="#a9a9a9" width="40%">Documento de Identidad:</td>
                <td width="60%">{{ $store->legal_representatives[0]['document_number'] }}</td>
            </tr>
            <tr>
                <td bgcolor="#a9a9a9" width="40%">Cargo Representante Legal:</td>
                <td width="60%">{{ $store->legal_representatives[0]['position'] }}</td>
            </tr>
            <tr>
                <td bgcolor="#a9a9a9" width="40%">Giro de LA EMPRESA:</td>
                <td width="60%">{{ $store->business_turn }}</td>
            </tr>
        </table>

        <table border="0" width="100%">
            <tr>
                <td colspan="2">
                    <p class="subtitle-2">Datos del comercio para realizar abonos</p>
                </td>
            </tr>
        </table>

        <table width="100%" class="table" border="0">
            <tr>
                <td bgcolor="#a9a9a9" width="40%">Moneda de procesamiento<br>(S/ o $):</td>
                <td width="60%">S/</td>
            </tr>
            <tr>
                <td bgcolor="#a9a9a9" width="40%">Banco donde se realizarán los<br>depósitos:</td>
                <td width="60%">{{ $store->financial_entity }}</td>
            </tr>
            <tr>
                <td bgcolor="#a9a9a9" width="40%">Nombre que figura en el Estado de Cuenta:</td>
                <td width="60%">{{ $store->account_statement_name }}</td>
            </tr>
            <tr>
                <td bgcolor="#a9a9a9" width="40%">Número de Cuenta Bancaria:</td>
                <td width="60%">{{ $store->bank_account_number }}</td>
            </tr>
            <tr>
                <td bgcolor="#a9a9a9" width="40%">Código de Cuenta Interbancario<br>(CCI, 20 dígitos):</td>
                <td width="60%">{{ $store->cci_account_number }}</td>
            </tr>
        </table>

        <table border="0" width="100%">
            <tr>
                <td colspan="2">
                    <p class="subtitle-2">Parametros para el monitoreo</p>
                </td>
            </tr>
        </table>

        <table width="100%" class="table" border="0">
            <tr>
                <td bgcolor="#a9a9a9" width="40%">Cantidad de transacciones<br>mensuales:</td>
                <td width="60%">{{ $store->monthly_transactions }}</td>
            </tr>
            <tr>
                <td bgcolor="#a9a9a9" width="40%">Importe promedio por transacción:</td>
                <td width="60%">{{ $store->average_amount }}</td>
            </tr>
            <tr>
                <td bgcolor="#a9a9a9" width="40%">Importe máximo por transacción:</td>
                <td width="60%">{{ $store->maximum_amount }}</td>
            </tr>
        </table>

        <table border="0" width="100%">
            <tr>
                <td colspan="2">
                    <p class="subtitle-2">Datos de contacto</p>
                </td>
            </tr>
        </table>

        <table width="100%" class="table" border="0">
            <tr>
                <td bgcolor="#a9a9a9" width="40%">Contacto Comercial:</td>
                <td width="60%">{{ $store->monthly_transactions }}</td>
            </tr>
        </table>

    </div>
@endsection