@extends('layouts.app')
@yield('page-js-script')
@section('content')
<form method="POST" action="{{route('makePayment')}}">
    @csrf
    <input type="hidden"  name="subPeriod">
    <input type="hidden"  name="subType">

</form>
<section class="perfect-plan" id="pricing" >
    <div class="container">
        <div class="row justify-content-md-center" id="counter">
            <div class="col-md-8 col-sm-12  text-center">
                <h2>How Much Does It Cost?</h2>
                <span class="uvc-headings-line"></span> 
                <div class="mt-1">
                    <p class="big-text">All free users can enjoy 1 basic smart visual signal</p> 
                    <button class="month repeat-btn active" id="months3" data-month='1' >3 Month </button>
                    <button class="month repeat-btn" id="months12" data-month='2' >12 month</button>
                </div>
            </div>
        </div>  

        <div class="row "> 
            <div class="col-lg-3 col-sm-6 mt-3">
                <div class="price-box green">
                    <h4>Free</h4>
                    <div class="price-box-cont">
                        <div class="monthly-price">
                            <span class="price">
                                <span aria-hidden="true"><sup>$</sup><span class="price__number"><span id="counter-value1" class="counter-value"  data-count="0">0</span></span></span></span>
                            <span class="text-minor" aria-hidden="true">
                                / Month 
                            </span>
                        </div>
                        <p class="plan-note">per month with  <br> quarterly payment</p> 
                        <hr>
                        <ul class="gw-go-body">
                            <li  >
                                <div class="gw-go-body-cell">
                                    <i class="fa fa-asterisk"></i>
                                    News Feed
                                </div>
                            </li>
                            <li class="active">
                                <div>
                                    <i class="fa fa-asterisk"></i>
                                    Basic Smart Signals
                                </div>
                            </li>
                            <li class=" ">
                                <div class=" ">
                                    <i class="fa fa-asterisk"></i> 
                                    Comprehensive Smart Signals
                                </div>
                            </li>
                            <li>
                                <div class=" ">
                                    <i class="fa fa-asterisk"></i>
                                    Screener PRO
                                </div>
                            </li>
                            <li>
                                <div>
                                    <i class="fa fa-asterisk"></i>
                                    Smart Trade
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="button-wrap mt-3">
                        <a href="#" data-sub_type='4' class="subscribe-btn button primary-button ">SIGN UP</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6 mt-3">
                <div class="price-box blue">
                    <h4>Basic</h4>
                    <div class="price-box-cont">
                    <div class="monthly-price">
                            <span class="price">
                                <span aria-hidden="true"><sup>$</sup><span class="price__number"><span class="counter-value" id="counter-value2" data-count="23">0</span></span></span></span>
                            <span class="text-minor" aria-hidden="true">
                                / Month
                            </span>
                        </div>
                        <p class="plan-note">per month with  <br> quarterly payment</p> 
                        <hr>
                        <ul class="gw-go-body">
                            <li  class="active" >
                                <div class="gw-go-body-cell">
                                    <i class="fa fa-asterisk"></i>
                                    News Feed</div>
                            </li>
                            <li class="active">
                                <div>
                                    <i class="fa fa-asterisk"></i>
                                    Basic Smart Signals
                                </div>
                            </li>
                            <li class=" ">
                                <div class=" ">
                                    <i class="fa fa-asterisk"></i> 
                                    Comprehensive Smart Signals
                                </div>
                            </li>
                            <li>
                                <div class=" ">
                                    <i class="fa fa-asterisk"></i>
                                    Screener PRO
                                </div>
                            </li>
                            <li>
                                <div>
                                    <i class="fa fa-asterisk"></i>
                                    Smart Trade
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="button-wrap mt-3">
                        <a href="#"  data-sub_type='3' class="subscribe-btn button primary-button ">Subscribe</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6 mt-3">
                <div class="price-box yellow">
                    <h4>Advanced</h4>
                    <div class="price-box-cont">
                    <div class="monthly-price">
                            <span class="price">
                                <span aria-hidden="true"><sup>$</sup><span class="price__number"><span class="counter-value" id="counter-value3" data-count="33">0</span></span></span></span>
                            <span class="text-minor" aria-hidden="true">
                                / Month
                            </span>
                        </div>
                        <p class="plan-note">per month with  <br> quarterly payment</p> 
                        <hr>
                        <ul class="gw-go-body">
                        <li  class="active" >
                                <div class="gw-go-body-cell">
                                    <i class="fa fa-asterisk"></i>
                                    News Feed</div>
                            </li>
                            <li class="active">
                                <div>
                                    <i class="fa fa-asterisk"></i>
                                    Basic Smart Signals
                                </div>
                            </li>
                            <li  class="active">
                                <div class=" ">
                                    <i class="fa fa-asterisk"></i> 
                                    Comprehensive Smart Signals
                                </div>
                            </li>
                            <li class="active">
                                <div class=" ">
                                    <i class="fa fa-asterisk"></i>
                                    Screener PRO
                         </div>
                            </li>
                            <li>
                                <div>
                                    <i class="fa fa-asterisk"></i>
                                    Smart Trade
                                </div>
                            </li>
                            
                        </ul>


                    </div>

                    <div class="button-wrap mt-3">
                        <a href="#"  data-sub_type='2' class="subscribe-btn button primary-button ">Subscribe</a>
                    </div>
                </div>


            </div>

            <div class="col-lg-3 col-sm-6 mt-3">
                <div class="price-box red">
                    <h4>Professional</h4>
                    <div class="price-box-cont">
                    <div class="monthly-price">
                            <span class="price">
                                <span aria-hidden="true"><sup>$</sup><span class="price__number"><span class="counter-value" id="counter-value4" data-count="49">0</span></span></span></span>
                            <span class="text-minor" aria-hidden="true">
                                / Month
                            </span>
                        </div>

                        <p class="plan-note">per month with  <br> quarterly payment</p> 

                        <hr>
                        <ul class="gw-go-body">
                        <li  class="active" >
                                <div class="gw-go-body-cell">
                                    <i class="fa fa-asterisk"></i>
                                    News Feed</div>
                            </li>
                            <li class="active">
                                <div><i class="fa fa-asterisk"></i>
                                Basic Smart Signals</div>
                            </li>
                            <li  class="active">
                                <div class=" ">
                                    <i class="fa fa-asterisk"></i> 
                                    Comprehensive Smart Signals
                                </div>
                            </li>
                            <li class="active">
                                <div class=" ">
                                    <i class="fa fa-asterisk"></i>
                                    Screener PRO
                                </div>
                            </li>
                            <li class="active">
                                <div>
                                    <i class="fa fa-asterisk"></i>
                                    Smart Trade
                                </div>
                            </li>
                            
                        </ul>

                    </div>

                    <div class="button-wrap mt-3">
                        <a href="#"  data-sub_type='1' class="subscribe-btn button primary-button ">Subscribe</a>
                    </div>
                </div>


            </div>
        </div>
    </div>
</section>
<!-- <div class="register-outer">
    <div class="cloud-sky bg-repeat-y coin-slide-rotate"
        style="background-image: url({{asset('crypto/images/top-banner.jpg')}});">
      
    
           <div class="container">
            <div class="row justify-content-center"> 
                <div class="col-md-12">

                    <div class="form-section ">
                        <div class="logo-2">
                            <a href="#">
                                <img src="http://www.cryptoscannerpro.com/crypto/images/csp-logo.png" class="img-fluid" alt="logo">
                            </a>
                        </div>
                        <div class="details mt-4">
                            <h3>{{ __('Subscribe') }}</h3>

                            <form method="GET" action="{{ route('UserSubcribe') }}">
                        @csrf

                        
                        <div class="form-group form-box">
                        <label for="text" class=" col-form-label  ">{{ __('Subscription Type') }}</label>

                        <select class="form-control m-bot15 input-text" name="type" id ="type">
                               @if($types->count() > 0)
                              @foreach($types as $type)
                               <option value="{{$type->id}}">{{$type->category_type}}</option>
                              @endForeach
                              @else
                               No Record Found
                                @endif   
                            </select>

                            </div>

                            <div class="form-group form-box">

                            <label for="text" class=" col-form-label ">{{ __('Subscription Period') }}</label>
                            <select class="form-control m-bot15 input-text" name="period" id ="period">
                               @if($periods->count() > 0)
                              @foreach($periods as $period)
                               <option value="{{$period->id}}">{{$period->text}}</option>
                              @endForeach
                              @else
                               No Record Found
                                @endif   
                            </select>

                            </div>
              

                            <div class="form-group clearfix">
                            <button type="submit" id = "button" class="btn-md btn-theme btn btn-primary login-btn">
                            <p id ="buttonText" style ="color:white">{{ __('Subscribe') }}</p>
                            </button> 
                            </div>  
                    </form> 
                            <div class="clearfix"></div>
                        </div>
                       
                    </div>

 
                </div>
            </div>
        </div>

    </div> 
</div> -->



 
@endsection

@section('script')
<script type="text/javascript">
    $(document).ready(function() {

        $("#type").change(function () {
            if(this.value == 4)
            {
                $("#buttonText").text('Register Now');
                // $("#period").prop("disabled", true);
            }
            else {
                $("#buttonText").text('Subscribe');
                // $("#period").prop("disabled", false);
            }
        });
        $(".subscribe-btn").click(function () {
            month=$('.month,.active').data('month');
            sub_type=$(this).data('sub_type');
            $('input[name="subPeriod"]').val(month);
            $('input[name="subType"]').val(sub_type);
            console.log(month+'-'+sub_type);
            $("form").submit();
            
        });    

        // $('#subscribe').click(function(){
        //     var periods = [];
        //     var types = [];
        //     var p,t;
        // $.each($("#type option:selected"), function(){            
        //     types.push($(this).val());
        // });
        // t = types.join(", ");
        
        // $.each($("#period option:selected"), function(){            
        //     periods.push($(this).val());
        // });

        // p = periods.join(", ");

        // document.getElementById("amount").value = "1000";

        // });
    });
</script>
@endsection