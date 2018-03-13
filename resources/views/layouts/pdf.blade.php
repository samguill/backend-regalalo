<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Regalalo | Tu regalo ideal') }}</title>
    <style type="text/css">
        table.table {
            border: 1px solid #1b1e21;
            border-bottom: none;
            border-right: none;
        }

        table.table td{
            border-bottom: 1px solid #1b1e21;
            border-right: 1px solid #1b1e21;
        }

        .title-1 {
            text-transform: uppercase;
            font-size: 28px;
            font-family: Helvetica Neue, Helvetica, Arial;
            font-weight: 500;
        }
        .subtitle-1 {
            text-transform: uppercase;
            font-size: 16px;
            font-family: Helvetica Neue, Helvetica, Arial;
            font-weight: 500;
        }
        .subtitle-2 {
            text-transform: uppercase;
            font-size: 14px;
            font-family: Helvetica Neue, Helvetica, Arial;
            font-weight: 500;
        }
        .gray-text-1 {
            font-family: Helvetica Neue, Helvetica, Arial;
            color: #CCC;
            font-size: 12px;
            text-align: left; margin-right: 10px;
        }

        .gray-text-2 {
            font-family: Helvetica Neue, Helvetica, Arial;
            color: #CCC;
            font-size: 12px;
        }
    </style>
</head>
<body class="app pdf">
    <div class="container">
        @yield('content')
    </div>
</body>
</html>