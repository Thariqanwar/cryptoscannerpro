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
                                <img src="http://www.cryptoscannerpro.com/crypto/images/csp-logo.png" class="img-fluid" alt="logo">
                            </a>
                        </div>
                        <div class="details mt-4">
                            <h3>Sign into your account</h3>

                            <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group form-box">
                        <input id="name" type="text" class="input-text form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="{{ __('Name') }}" autofocus>

@error('name')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
@enderror
                                </div> 
                                <div class="form-group form-box">
                                <input id="email" type="email" class="input-text form-control  @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="{{ __('E-Mail Address') }}">

@error('email')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
@enderror
                                </div>

                                <div class="form-group form-box">
                                <input id="password" type="password" placeholder="{{ __('Password') }}" class="input-text form-control  @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

@error('password')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
@enderror
                                </div>

                                <div class="form-group form-box">
                                <input id="password-confirm" type="password" class="input-text form-control" name="password_confirmation" required autocomplete="new-password" placeholder="{{ __('Confirm Password') }}">
                                </div>

                                <div class="row">
                                    <div class="col-sm-6   ">
                                        <div class="custom-control custom-checkbox custom-control-inline form-group ">
                                            <input type="checkbox" value="1" name="type" class="custom-control-input feed_type" id="News">
                                        <label class="custom-control-label" for="News"> Remember me</label>
                                    </div>
                                    </div>
                                    <div class="col-sm-6  form-group ">
                                        <!-- <a href="#" class="forgot-password">Forgot Password</a> -->
                                    </div>   
                                </div>


                

                

            
                 
 
                            <div class="form-group clearfix">
                            <button type="submit" class="btn-md btn-theme btn-block login-btn">
                            {{ __('Register') }}
                            </button> 
                            </div> 
                    </form>
 
                            <div class="clearfix"></div>
                        </div>
                        <p class="outer-text">Already a member? <a href="{{route('login')}}" class="thembo"> Login here  </a></p>
                    </div>

 
                </div>
            </div>
        </div>

    </div> 
        </div>


 
@endsection
