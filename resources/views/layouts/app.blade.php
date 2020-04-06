<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
   
     <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/w/bs/dt-1.10.18/datatables.min.css"/>
    
   
   {{--  <script src="{{ asset('bootstrap-3.4.1/js/bootstrap.min.js') }}" ></script> --}}
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
   {{--  <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script> --}}

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    
    {{-- <link href="{{ asset('bootstrap-3.4.1/css/bootstrap.min.css') }}" rel="stylesheet"> --}}
     <link href="{{ asset('fontawesome/css/all.css') }}" rel="stylesheet">

      
     

</head>
<body>
    <div id="app">
        <header class="shadow-sm" style="display: none;">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <nav class="navbar header navbar-expand-md navbar-light">
                            <a class="navbar-brand" href="{{ url('home') }}">
                            {{--  {{ config('app.name', 'Laravel') }} --}}
                            <img class="portal-logo img-fluid" src="{{asset('logo-black-png.png')}}">
                            </a>
                            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                                <span class="navbar-toggler-icon"></span>
                            </button>

                            <div class="collapse navbar-collapse mr-auto" id="navbarSupportedContent">
                                
                                <!-- Right Side Of Navbar -->
                                <ul class="navbar-nav ml-auto">
                                    <!-- Authentication Links -->
                                    @guest
                                        <li class="nav-item">
                                            <a class="nav-link" href="#">{{ __('Feeds') }}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                        </li>
                                    <!--  @if (Route::has('register'))
                                            <li class="nav-item">
                                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                            </li>
                                        @endif -->
                                    @else
                                        <li class="nav-item dropdown">
                                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                                {{ Auth::user()->name }} <span class="caret"></span>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                                <a class="dropdown-item" href="@if (Auth::User()->isAdmin()) {{ route('home') }} @else {{ route('userProfile') }} @endif ">
                                                Dashboard
                                                </a>
                                                @if(Auth::User()->isUser())
                                                    <a class="dropdown-item" href="{{ route('UserFeed') }}">
                                                    News Feeds
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('FeedSettings') }}">
                                                    Feed Settings
                                                    </a>

                                                @endif
                                                @if(Auth::User()->isAdmin())
                                                    <a class="dropdown-item" href="{{ route('AlertLog') }}">
                                                    Alert Log
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('AddFeed') }}">
                                                    RSS Feed
                                                    </a>
                                                @endif
                                                <a class="dropdown-item" href="{{ route('logout') }}"
                                                onclick="event.preventDefault();
                                                                document.getElementById('logout-form').submit();">
                                                    {{ __('Logout') }}
                                                </a>

                                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                    @csrf
                                                </form>
                                            </div>
                                        </li>
                                    @endguest
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </header>
        

        <main>
            @yield('content')
        </main>
    </div>
<script src="{{ asset('js/jquery_3.4.1.min.js') }}" ></script>
<script src="{{ asset('js/custom.js') }}" ></script>
<script src="{{ asset('fontawesome/js/all.js') }}" ></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
@yield('script')

</body>
</html>
