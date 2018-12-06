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

    <!-- Fonts -->
    <!-- <link rel="dns-prefetch" href="https://fonts.gstatic.com"> -->
    <!-- <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css"> -->

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel" id="menu">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <!-- {{ config('app.name', 'Laravel') }} -->
                    <img alt="Enlight" src="../img/logo_header.png">
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
                        <!-- Authentication Links -->
                            <li class="nav-item">
                                <a class="nav-link" href="">Para tu casa</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="">Para tu negocio</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="">Nosotros</a>
                            </li>
                        @guest
                            <!-- <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            <li class="nav-item">
                                @if (Route::has('register'))
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                @endif
                            </li> -->
                        @else
                            <!-- <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li> -->
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <div class="cintillo"></div>   
        <div class="submenu"></div>
        
        @yield('content')
    </div>
    <script src="/js/enlight.js"></script>
    <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBSZF6bTP5Gd9o8jYFrDpFFzGovhEoXK5M&callback=myMap"></script> -->
</body>
</html>
