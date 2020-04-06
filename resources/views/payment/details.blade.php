@extends('layouts.app')

@section('content')
<div class="register-outer">
    <div class="cloud-sky bg-repeat-y coin-slide-rotate"
        style="background-image: url({{asset('crypto/images/top-banner.jpg')}});">


        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">

                    <div class="form-section payment-view">
                        <div class="logo-2">
                            <a href="#">
                                <img src="http://www.cryptoscannerpro.com/crypto/images/csp-logo.png" class="img-fluid"
                                    alt="logo">
                            </a>
                        </div>
                        <div class="details mt-4">
                            <h3> Payment</h3>


                            <div class="payment-header">
                                <div class="flexrow">
                                    <div class="amounts">
                                        <div class="payment_label">
                                            Send this exact amount of:
                                        </div>
                                        <div class="copyable-field amounts-field" id="copy-amounts">
                                            <div class="field-wrap">
                                                <div data-clipboard-target-goal="#copy-amounts .target"
                                                    class="form-item form-type-markup form-item-amounts">
                                                    <span class="target">{{$data['amount']}}</span> LTCT
                                                </div>
                                            </div>
                                         
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="address">
                                        <div class="payment_label"> to Litecoin Testnet Address:</div>
                                        <div class="copyable-field address-field" id="copy-50">
                                            <div class="field-wrap">
                                                <div data-clipboard-target-goal="#copy-50 .target"
                                                    class="form-item form-type-markup form-item-address"> <span
                                                        class="target">{{$data['address']}}</span>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="payment-body">
                                <div class="float-right">
                                    <div id="qr-code">
                                        <img src="{{$data['qrcode_url']}} " class="img-fluid"
                                    alt="logo">
                                    </div>
                                </div>
                                <div class="merchant-data">
                                    <div class="data-row description">
                                        <div class="data-label">Description</div>
                                        <div class="data">Please Note the above transaction address before leaving this page</div>
                                    </div>
                                    <div class="data-row amount">
                                        <div class="data-label">Amount</div>
                                        <div class="data">{{$amount->amount}} USD({{$data['amount']}} LTCT)</div>
                                    </div>
                                    <div class="data-row merchant-name">
                                        <div class="data-label">Merchant Email</div>
                                        <div class="data">{{$email}}</div>
                                    </div>
                                    <div class="data-row site">
                                        <div class="data-label">Site</div>
                                        <div class="data">cryptoscannerpro.com</div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>

                            <div class="form-group clearfix">
                            <a href="https://gocps.net/sfg8udhw19m2p9xbrr7hmr0jpj6l/" class="btn-md btn-theme btn btn-primary login-btn" target ="blank">
                            Payment
                            </a>
                            
                            <a href="{{route('userProfile')}}" class="btn-md btn-theme btn btn-primary login-btn">
                            Home
                            </a>
                            </div>

                            <div class="clearfix"></div>
                            <p style="color:black">Note: It takes arround 1 to 2 hours to activate your account after successfull paymnet</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>



@endsection