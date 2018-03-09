@extends('layouts.pdf')
@section('content')

    <div class="row">
        <table>
            <tr>
                <td>
                    {{ $store->business_name  }}
                </td>
            </tr>
            <tr>
                <td>
                    <h1>Contrato de Integración con PayMe</h1>
                </td>
            </tr>
        </table>
    </div>
@endsection