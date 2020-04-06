<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Crypto Scanner Pro</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700&display=swap" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="{{asset('admin/css/styles.css')}}" type="text/css">
    <link href="{{ asset('fontawesome/css/all.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <!-- jQuery -->
    <script src="{{asset('admin/js/jquery.min.js')}}"></script>



    @yield('header-scripts')
</head>

<body>
    <div class="wrapper">

        <header class="dashboard">
            <div class="left">
                <h4 class="logo-full">Crypto Scanner Pro</h4>
                <h4 class="logo-sm">CSP</h4>
                <a href="#" class="menu-min-button">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="menu-icon">
                        <path fill="currentColor"
                            d="M442 114H6a6 6 0 0 1-6-6V84a6 6 0 0 1 6-6h436a6 6 0 0 1 6 6v24a6 6 0 0 1-6 6zm0 160H6a6 6 0 0 1-6-6v-24a6 6 0 0 1 6-6h436a6 6 0 0 1 6 6v24a6 6 0 0 1-6 6zm0 160H6a6 6 0 0 1-6-6v-24a6 6 0 0 1 6-6h436a6 6 0 0 1 6 6v24a6 6 0 0 1-6 6z"
                            class=""></path>
                    </svg>
                </a>
            </div>
            <div class="right">

            <div class="user-fixed-panel">
              
                    {{-- @if( Route::currentRouteName()=='userProfile') --}}
                    <section class="user-fixed-panel-view">
                        <div class="user-fixed-metric"><a class="user-fixed-metric-url" title="Market"
                                href="#">
                                <div class="user-fixed-left">
                                    <p class="user-fixed-title">TotalCap USD</p>
                                    <div class="percent user-fixed-percent-value" style="color:#f04a38">
                                   <i class="fas fa-caret-down"></i> 0.9%</div>
                                </div>
                                <div class="user-fixed-right">
                                    <div class="panel-total-value totalmcap">{{$totalcapusd}}</div>
                                </div>
                            </a></div>
                        <div class="user-fixed-metric"><a class="user-fixed-metric-url" title="Market"
                                href="#">
                                <div class="user-fixed-left">
                                    <p class="user-fixed-title">Volume 24h</p>
                                    <div class="percent user-fixed-percent-value" style="color:#f04a38">
                                    <i class="fas fa-caret-down"></i> 9.3%</div>
                                </div>
                                <div class="user-fixed-right">
                                    <div class="panel-total-value total24hr">{{$total24hr}}</div>
                                </div>
                            </a></div>
                            {{-- {{eth_price}}
                            {{eth_price_change}}%
                            {{btc_price}}
                            {{btc_price_change}}% --}}
                  
                        <div class="user-fixed-metric"><a class="user-fixed-metric-url" title="Market"
                                href="#">
                                <div class="user-fixed-left">
                                    <p class="user-fixed-title">ETH price</p>
                                    <div class="percent user-fixed-percent-value eth_price">{{$eth_price}}</div>
                                </div>
                                <div class="user-fixed-right">
                                    <div class="percent panel-total-value eth_price_change"  @if($btc_price_change
                                     < 0 ) style="color:#f04a38" @else style="color:#58ca6a" @endif>  @if($btc_price_change
                                     < 0 ) <i class="fas fa-caret-down"></i> @else <i class="fas fa-caret-up"></i> @endif{{$eth_price_change}}%</div>
                                </div>
                            </a></div>
                        <div class="user-fixed-metric"><a class="user-fixed-metric-url" title="Market"
                                href="#">
                                <div class="user-fixed-left">
                                    <p class="user-fixed-title">BTC price</p>
                                    <div class="percent user-fixed-percent-value btc_price">{{$btc_price}}</div>
                                </div>
                                <div class="user-fixed-right">
                                    <div class="percent panel-total-value btc_price_change "  @if($btc_price_change
                                     < 0 ) style="color:#f04a38" @else style="color:#58ca6a" @endif >@if($btc_price_change
                                     < 0 ) <i class="fas fa-caret-down"></i> @else <i class="fas fa-caret-up"></i> @endif {{$btc_price_change}}%</div>
                                </div>
                            </a></div>
                    </section>
                    {{-- @endif --}}

                </div>



                <!-- <div class="search-box"> 
                <input type="text" class="form-control" name="" id="" placeholder="Search">
            </div> -->
                <div class="right-content">

                    <div class="header__dropdown">
                        <div class="histroy-icon small-icon">
                            <button type="button" class="dropbtn">
                                <img src="{{asset('/admin/images/alarm.png')}}" alt="icon" />
                            </button>
                            <span class="notification-number bell-number"></span>
                            <ul class="notification__list notification dropdown dropdown-histroy d-none">
                                <h3 class="notification__list__name">Notifications</h3>
                                <li class="list__item">
                                    <a href="#" class="list__item--link">
                                        <span class="messages">
                                            <b>AMBBTC </b> Lorem Ipsum is simply dummy text of the printing and
                                            industry.
                                        </span>
                                    </a>
                                </li>
                                <li class="list__item">
                                    <a href="#" class="list__item--link">
                                        <span class="messages">
                                            <b>AMBBTC </b> Lorem Ipsum is simply dummy text of the printing and
                                            industry.
                                        </span>
                                    </a>
                                </li>
                                <li class="list__item">
                                    <a href="#" class="list__item--link">
                                        <span class="messages">
                                            <b>AMBBTC </b> Lorem Ipsum is simply dummy text of the printing and
                                            industry.
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="language-selector">
                        <input id="country_selector" type="text">
                        <!-- <label for="country_selector" style="display:none;">Select a country here...</label> -->


                    </div>


                    <div class="header__dropdown">
                        <div class="help-icon small-icon">
                            <button type="button" class="dropbtn">
                                <span class="name">{{ Auth::user()->name }}</span>
                                <span class="img-wrap">
                                    <img src="{{asset('/admin/images/avatar.png')}}" class="img-fluid" alt="icon" />
                                </span>
                            </button>
                            <ul class="notification__list profile dropdown dropdown-help d-none">
                                <h3 class="notification__list__expire">
                                    Suscription wiil expire in 14 days
                                </h3>
                                <li class="list__item">
                                    <a href="@if (Auth::User()->isAdmin()) {{ route('home') }} @else {{ route('userProfile') }} @endif "
                                        class="list__item--link">
                                        <span class="messages">
                                            Profile
                                        </span>
                                    </a>
                                </li>
                                <li class="list__item">
                                    <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                                document.getElementById('logout-form').submit();"
                                        class="list__item--link">
                                        <span class="messages">
                                            Log Out
                                        </span>
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="header__dropdown">
                        <div class="theme-icon small-icon">
                            <button type="button" class="dropbtn theme-button" title="Theme Change">
                                <svg viewBox="0 0 512 512" class="theme-icon">
                                    <path fill="currentColor"
                                        d="M448.964 365.617C348.188 384.809 255.14 307.765 255.14 205.419c0-58.893 31.561-112.832 82.574-141.862 25.83-14.7 19.333-53.859-10.015-59.28A258.114 258.114 0 0 0 280.947 0c-141.334 0-256 114.546-256 256 0 141.334 114.547 256 256 256 78.931 0 151.079-35.924 198.85-94.783 18.846-23.22-1.706-57.149-30.833-51.6zM280.947 480c-123.712 0-224-100.288-224-224s100.288-224 224-224c13.984 0 27.665 1.294 40.94 3.745-58.972 33.56-98.747 96.969-98.747 169.674 0 122.606 111.613 214.523 231.81 191.632C413.881 447.653 351.196 480 280.947 480z"
                                        class=""></path>
                                </svg>
                            </button>
                        </div>
                    </div>


                </div>
            </div>
        </header>

        <aside class="main-sidebar">
            <ul class="side-menu">
                <li>
                    <a href="{{route('login')}}" class="active">
                        <i class="fab fa-buffer"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fab fa-audible"></i>
                        <span>Smart Trade</span> <span class="menu-arrow"><svg xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 192 512">
                                <path fill="currentColor"
                                    d="M166.9 264.5l-117.8 116c-4.7 4.7-12.3 4.7-17 0l-7.1-7.1c-4.7-4.7-4.7-12.3 0-17L127.3 256 25.1 155.6c-4.7-4.7-4.7-12.3 0-17l7.1-7.1c4.7-4.7 12.3-4.7 17 0l117.8 116c4.6 4.7 4.6 12.3-.1 17z"
                                    class=""></path>
                            </svg></span>
                    </a>
                    <ul class="sub-menu">
                        <li><a href="#">Trade</a></li>
                        <li><a href="#">Trade</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#">
                        <i class="fab fa-blackberry"></i>
                        <span>Smart Signals</span> <span class="menu-arrow"><svg xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 192 512">
                                <path fill="currentColor"
                                    d="M166.9 264.5l-117.8 116c-4.7 4.7-12.3 4.7-17 0l-7.1-7.1c-4.7-4.7-4.7-12.3 0-17L127.3 256 25.1 155.6c-4.7-4.7-4.7-12.3 0-17l7.1-7.1c4.7-4.7 12.3-4.7 17 0l117.8 116c4.6 4.7 4.6 12.3-.1 17z"
                                    class=""></path>
                            </svg></span>
                    </a>
                    <ul class="sub-menu">
                        <li><a href="#">Trade</a></li>
                        <li><a href="#">Trade</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#">
                        <i class="fab fa-codepen"></i>
                        <span>Crypto Scanner PRO</span> <span class="menu-arrow"><svg xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 192 512">
                                <path fill="currentColor"
                                    d="M166.9 264.5l-117.8 116c-4.7 4.7-12.3 4.7-17 0l-7.1-7.1c-4.7-4.7-4.7-12.3 0-17L127.3 256 25.1 155.6c-4.7-4.7-4.7-12.3 0-17l7.1-7.1c4.7-4.7 12.3-4.7 17 0l117.8 116c4.6 4.7 4.6 12.3-.1 17z"
                                    class=""></path>
                            </svg></span>
                    </a>
                    <ul class="sub-menu">
                        <li><a href="#">Trade</a></li>
                        <li><a href="#">Trade</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-coins"></i>
                        <span>News Feed</span> <span class="menu-arrow"><svg xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 192 512">
                                <path fill="currentColor"
                                    d="M166.9 264.5l-117.8 116c-4.7 4.7-12.3 4.7-17 0l-7.1-7.1c-4.7-4.7-4.7-12.3 0-17L127.3 256 25.1 155.6c-4.7-4.7-4.7-12.3 0-17l7.1-7.1c4.7-4.7 12.3-4.7 17 0l117.8 116c4.6 4.7 4.6 12.3-.1 17z"
                                    class=""></path>
                            </svg></span>
                    </a>
                    <ul class="sub-menu">
                        @if(Auth::User()->isAdmin())
                        <li>
                            <a href="{{route('AddFeed')}}">

                                <span>Feeds</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('listBlog')}}">

                                <span>Blog</span>
                            </a>
                            <a href="{{route('AddBlogFeedUrl')}}">

                                <span>Create Blog Feed URL</span>
                            </a>
                        </li>
                        @else
                        <li>
                            <a href="{{route('UserFeed')}}">
                                <span>Feeds</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @if(Auth::User()->isAdmin())
                <li>
                    <a href="{{route('UserFunctionalities')}}">
                        <i class="fas fa-chart-bar"></i>
                        <span>User Functionalities</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('SubscriptionAmount')}}">
                        <i class="fas fa-chart-bar"></i>
                        <span>Price settings</span>
                    </a>
                </li>

                <li>
                    <a href="{{route('AlertLog')}}">
                        <i class="fas fa-chart-bar"></i>
                        <span>Admin Alert Log</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('IpLog')}}">
                        <i class="fas fa-chart-bar"></i>
                        <span>Ip Log</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('PaymentDetails')}}">
                        <i class="fas fa-coins"></i>
                        <span>Payment Details</span>
                    </a>
                </li>
                @endif
                @if(Auth::user()->subscription_type!=4 && Auth::user()->isUser())
                <li>
                    <a href="{{route('UserPaymentDetails')}}">
                        <i class="fas fa-coins"></i>
                        <span>Payment Details</span>
                    </a>
                </li>
                @endif
                <li>
                    <a href="#">
                        <i class="fas fa-coins"></i>
                        <span>Portfolio</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('BillingAddress') }}">
                        <i class="fas fa-coins"></i>
                        <span>Billing Address</span>
                    </a>
                </li>
                @if(Auth::user()->isUser())
                <li>
                    <a href="#">
                        <i class="fas fa-cubes"></i>
                        <span>Subscription</span> <span class="menu-arrow"><svg xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 192 512">
                                <path fill="currentColor"
                                    d="M166.9 264.5l-117.8 116c-4.7 4.7-12.3 4.7-17 0l-7.1-7.1c-4.7-4.7-4.7-12.3 0-17L127.3 256 25.1 155.6c-4.7-4.7-4.7-12.3 0-17l7.1-7.1c4.7-4.7 12.3-4.7 17 0l117.8 116c4.6 4.7 4.6 12.3-.1 17z"
                                    class=""></path>
                            </svg></span>
                    </a>
                    <ul class="sub-menu">
                        <li><a href="{{route('UserSubcribe')}}">Subscribe</a></li>
                        <li><a href="#">Trade</a></li>
                    </ul>
                </li>
                <li>
                    <a href="{{route('settings')}}">
                        <i class="fas fa-cubes"></i>
                        <span>Settings</span> <span class="menu-arrow"></span>
                    </a>
                    
                </li>
                @endif
                <li>
                    <a href="#">
                        <i class="fas fa-chart-bar"></i>
                        <span>FAQ(AKA Blog)</span> <span class="menu-arrow"><svg xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 192 512">
                                <path fill="currentColor"
                                    d="M166.9 264.5l-117.8 116c-4.7 4.7-12.3 4.7-17 0l-7.1-7.1c-4.7-4.7-4.7-12.3 0-17L127.3 256 25.1 155.6c-4.7-4.7-4.7-12.3 0-17l7.1-7.1c4.7-4.7 12.3-4.7 17 0l117.8 116c4.6 4.7 4.6 12.3-.1 17z"
                                    class=""></path>
                            </svg></span>
                    </a>
                    <ul class="sub-menu">
                        <li><a href="#">Trade</a></li>
                        <li><a href="#">Trade</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#">
                        <i class="fab fa-dropbox"></i>
                        <span>Get Support</span> <span class="menu-arrow"><svg xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 192 512">
                                <path fill="currentColor"
                                    d="M166.9 264.5l-117.8 116c-4.7 4.7-12.3 4.7-17 0l-7.1-7.1c-4.7-4.7-4.7-12.3 0-17L127.3 256 25.1 155.6c-4.7-4.7-4.7-12.3 0-17l7.1-7.1c4.7-4.7 12.3-4.7 17 0l117.8 116c4.6 4.7 4.6 12.3-.1 17z"
                                    class=""></path>
                            </svg></span>
                    </a>
                    <ul class="sub-menu">
                        <li><a href="#">Trade</a></li>
                        <li><a href="#">Trade</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-dolly"></i>
                        <span>Referals</span> <span class="menu-arrow"><svg xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 192 512">
                                <path fill="currentColor"
                                    d="M166.9 264.5l-117.8 116c-4.7 4.7-12.3 4.7-17 0l-7.1-7.1c-4.7-4.7-4.7-12.3 0-17L127.3 256 25.1 155.6c-4.7-4.7-4.7-12.3 0-17l7.1-7.1c4.7-4.7 12.3-4.7 17 0l117.8 116c4.6 4.7 4.6 12.3-.1 17z"
                                    class=""></path>
                            </svg></span>
                    </a>
                    <ul class="sub-menu">
                        <li><a href="#">Trade</a></li>
                        <li><a href="#">Trade</a></li>
                    </ul>
                </li>



            </ul>
        </aside>
        {{-- content start here --}}
        @yield('content')
        {{-- content end here --}}
    </div>

    <!-- Custom Javascript -->
    <script src="{{asset('admin/js/popper.min.js')}}"></script>
    <script src="{{asset('admin/js/bootstrap.min.js')}}"></script>
    <!-- Draggable Plugin -->
    <script src="{{asset('admin/js/TweenMax.min.js')}}"></script>
    <script src="{{asset('admin/js/Draggable.min.js')}}"></script>
    <script src="{{asset('admin/js/drag-arrange.min.js')}}"></script>
    <!-- Date Range Picker -->
    <script src="{{asset('admin/js/moment.min.js')}}"></script>
    <script src="{{asset('admin/js/daterangepicker.min.js')}}"></script>
    <!-- Data Table -->
    <script src="{{asset('admin/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('admin/js/dataTables.bootstrap4.min.js')}}"></script>
    <!-- Grid Stack -->
    <script src="{{asset('admin/js/jquery-ui.min.js')}}"></script>
    <script src="{{asset('admin/js/lodash.min.js')}}"></script>
    <script src="{{asset('admin/js/gridstack.min.js')}}"></script>
    <script src="{{asset('admin/js/gridstack.jQueryUI.js')}}"></script>
    <!-- Draggable Table -->
    <script src="{{asset('admin/js/jsdragtable.js')}}"></script>
    <!-- Country Select -->
    <script src="{{asset('admin/js/countrySelect.js')}}"></script>
    <!-- multiselect  Script -->

    <script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>
    <script src="{{asset('admin/js/jquery.bootstrap-duallistbox.js')}}"></script>
    <!-- Custom Script -->

    <script src="{{asset('admin/js/custom.js')}}"></script>

    {{-- <script src="https://code.jquery.com/jquery-3.3.1.js"></script>--}}
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.ckeditor.com/4.13.0/standard/ckeditor.js"></script>

    <script type="text/javascript">
    $.ajaxSetup({

        headers: {

            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    </script>
    

    @yield('scripts')



    <script type="text/javascript">
    $(document).ready(function() {
        var table = $('#datatable').DataTable({
            "pageLength": 50
        });
        $('#search-category').on('keyup', function() {

            table
                .column(0)
                .search(this.value)
                .draw();

        });
        $('#search-date2').on('keyup', function() {

            table
                .column(1)
                .search(this.value)
                .draw();

        });

    });
    </script>


</body>

</html>