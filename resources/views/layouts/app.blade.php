<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Calculadora 3.0') }}</title>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel" id="menu">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img alt="Enlight" src="{{ asset('img/logo_header.png') }}">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                    </ul>
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="https://www.enlight.mx/">Para tu casa</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="https://www.enlight.mx/negocio/">Para tu negocio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="https://www.enlight.mx/empresa">Nosotros</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="cintillo"></div>   
        <div class="submenu"></div>
        @yield('content')
    </div>
</body>
</html>
