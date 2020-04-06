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
                            <h3>{{ __('Make Payment') }}</h3>

                            
                            <form method="POST" action="{{ route('payment') }}">
                        @csrf
                        <input id="subPeriod" name= "subPeriod" type="hidden" value="{{ $subPeriod }}">
                        <input id="subType" name= "subType" type="hidden" value="{{ $subType }}">

                        <div class="form-group form-box">
<input id="email" type="email" class="input-text form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="{{ __('E-Mail') }}">

@error('email')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
@enderror
 </div>

 
 <div class="form-group form-box"> 
 <label for="currency" class=" col-form-label ">{{ __('Currency') }}</label>
                            <select class="input-text form-control @error('currency') is-invalid @enderror" name="currency" id ="currency"> 
                               <option value="{{ $currency }}">{{ $currency }}</option>   
                            </select>

@error('currency')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
@enderror
 </div>
         <div class="form-group form-box"> 
<input id="paybackaddress" type="address" class="input-text form-control " name="paybackaddress"  required autocomplete=" " placeholder="Your payment Address">
 
 </div>
                 
                       
                        <div class="form-group form-box row">

                            <label for="name" class="col-md-4 col-form-label ">{{ __('Total Amount') }}</label> 
                             <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $amount }}" readonly>
                            </div>
                        </div>

                      
                        <div class="form-group clearfix">
                         
                            <button type="submit" class="btn btn-primary login-btn" id="paynow">
                                    {{ __('PAY NOW') }}
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
        </div>


 
@endsection

<!-- @section('script')
<script>
	$(document).ready(function(){
		$('#paynow').on("click",function(e){
            // $("#paynow").attr("disabled", true);
            var subPeriod = document.getElementById('subPeriod').value;
            var subType = document.getElementById('subType').value;
			$.ajax({
				url:"{{route('payment')}}",
				type: 'POST',
				dataType: 'json',
                data: {"_token": "{{ csrf_token() }}",
                "subPeriod" : subPeriod,"subType" : subType },
				success: function(result)
				{
					console.log(result);
                    var address = result.address;
                    var txn_id = result.txn_id;
                    var amount = result.amount;
                    
                    // $("#show-address").append("<label for='payment-address' class='col-md-4 col-form-label text-md-right'>Payment Address</label><div class='col-md-6'><input id='payment-address' type='text' class='form-control' name='payment-address' value='"+address+"' readonly></div>");
                    // $("#show-address").append("<label for='txn-id' class='col-md-4 col-form-label text-md-right'>Transaction ID</label><div class='col-md-6'><input id='txn-id' type='text' class='form-control' name='txn-id' value='"+txn_id+"' readonly></div>");

					$.ajax({
                    type: "POST",
                    url: "{{route('paymentSave')}}",
                    data: {"_token": "{{ csrf_token() }}",
                            "txn_id" : txn_id,
                            "payment_address" :address,
                            "subPeriod" : subPeriod,
                            "subType" : subType,
                            "amount"  : amount,
                            },
                    success: function (data) {
                        console.log(data);
                    }
                    });
				}	
			});	
		});
	});
</script>
@endsection -->
