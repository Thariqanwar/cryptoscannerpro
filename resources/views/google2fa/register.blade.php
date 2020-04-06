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
                            <h3 class="text-center">Set up Google Authenticator</h3>


                            <div class="panel-body" style="text-align: center;">
                                <p>Set up your two factor authentication by scanning the barcode below. Alternatively,
                                    you can use the code <b>{{ $secret }}</b> </p>
                                <div>
                                    <img src="{{ $QR_Image }}" width="200" >
                                </div>
                                <p>You must set up your Google Authenticator app before continuing. You will be unable
                                    to login otherwise</p>
                                <div class="mt-4">
                                    <a href="{{route('completeRegistration')}}" class="btn-md btn-theme btn-block login-btn" style=" width:100% "  >
                                   Complete Registration </a>
                                </div>
                            </div>



                            <div class="clearfix"></div>
                        </div>

                    </div>


                </div>
            </div>
        </div>

    </div>
</div>





@endsection