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
                                <img src="http://www.cryptoscannerpro.com/crypto/images/csp-logo.png" class="img-fluid"
                                    alt="logo">
                            </a>
                        </div>
                        <div class="details mt-4">
                            <h3>{{ __('Login') }}</h3>


                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <div class="form-group row">
                                    <!-- <label for="email" class="col-12 col-form-label">{{ __('E-Mail Address') }}</label> -->

                                    <div class="col-12">
                                        <input id="email" type="email"
                                            class="input-text form-control @error('email') is-invalid @enderror"
                                            name="email" value="{{ old('email') }}" required autocomplete="email"
                                            placeholder="{{ __('E-Mail Address') }}" autofocus>

                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <!-- <label for="password" class="col-12 col-form-label">{{ __('Password') }}</label> -->

                                    <div class="col-12">
                                        <input id="password" type="password"
                                            class="input-text form-control @error('password') is-invalid @enderror"
                                            name="password" placeholder="{{ __('Password') }}" required
                                            autocomplete="current-password">

                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                
                                <div class="row">
                                        <div class="col-sm-6   ">
                                        <div class="custom-control custom-checkbox custom-control-inline form-group ">
                                            <input class="form-check-input custom-control-input feed_type"
                                                type="checkbox" name="remember" id="remember"
                                                {{ old('remember') ? 'checked' : '' }}>

                                            <label class="custom-control-label" for="remember">
                                                {{ __('Remember Me') }}
                                            </label>
                                        </div>

                                        </div>
                                        <div class="col-sm-6  form-group ">

                                        @if (Route::has('password.request'))
                                        <a class="btn btn-link forgot-password" href="{{ route('password.request') }}">
                                            {{ __('Forgot Password?') }}
                                        </a>
                                        @endif
                                        
                                     </div>
                                    </div>



                           

                                <div class="form-group row mb-0">
                                    <div class="col-12 mb-3">
                                        <button type="submit" class="btn-md btn-theme btn-block login-btn">
                                            {{ __('Login') }}
                                        </button>

                                    </div>
                                  
                                </div>
                            </form>
 
                          
                        </div>
                        <div class="clearfix"></div>

<p class="outer-text">Don't have an account? <a href="{{route('register')}}" class="thembo"> Register here</a></p>

                    </div>


                </div>
            </div>
        </div>

    </div>
</div>




@endsection