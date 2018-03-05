<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}" type="image/vnd.microsoft.icon" />

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body class="app sidebar-mini rtl">
    @component('layouts.header')@endcomponent
    @component('layouts.sidebar')@endcomponent

    <main class="app-content">
        @yield('content')
    </main>

<!-- Scripts -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBJ0XvtnWS6I60xHgZ7u_rRc8aGFzBYEXQ&libraries=places" async defer></script>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
