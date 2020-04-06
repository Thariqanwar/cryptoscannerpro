@extends('layouts.app')

@section('content')



<div class="register-outer">
    <div class="cloud-sky bg-repeat-y coin-slide-rotate"
        style="background-image: url({{asset('crypto/images/top-banner.jpg')}});">


        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">

                    <div class="form-section">
                        <div class="logo-2">
                            <a href="#">
                                <img src="http://www.cryptoscannerpro.com/crypto/images/csp-logo.png" class="img-fluid"  alt="logo">
                            </a>
                        </div>
                        <div class="details mt-4">
                            <h3 class="text-center">Register</h3>


                            <form class="form-horizontal" method="POST" action="{{ route('2fa') }}">
                        {{ csrf_field() }}

                        <div class="form-group"> 
                            <div class=" ">
                                <input id="one_time_password" type="number" class="input-text form-control" placeholder="One Time Password" name="one_time_password" required autofocus>
                            </div>
                        </div>

                        <div class=" ">


                            <div class=" "> 
                                <button type="submit" class="btn-theme btn-block login-btn">
                                    Login
                                </button>

                                <a href="{{ route('logout') }}" class="btn-theme btn-block login-btn"
                                                onclick="event.preventDefault();
                                                                document.getElementById('logout-form').submit();" class="list__item--link">
                                    <span class="messages">
                                        Cancel
                                    </span>
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </form>
                    



                            <div class="clearfix"></div>
                        </div>

                    </div>


                </div>
            </div>
        </div>

    </div>
</div>




 
@endsection