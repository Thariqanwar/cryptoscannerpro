@extends('layouts.admin')


@section('content')
<div class="content-wrapper height-100">
  @if(session('success'))
      <div class="alert alert-success">
          {{session('success')}}
      </div>
  @endif
	@if(isset($success))
		<div class="alert alert-success">
	  		<strong>Success!</strong> {{$success}}
		</div>
	@endif
  	<h2>Update Address</h2>
  	
  {{-- <button id="download" type="button" class="btn btn-success">Download</button> --}}
   <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">

                    <div class="form-section payment-view">
                        
                        <div class="details mt-4">
                            <h3> </h3>

                            <form method="post" action="{{route('EditAddressPost')}}">
                              @csrf
                           <div class="form-group form-box"> 
                            <input id="paybackaddress" type="address" class="input-text form-control " value="{{$edit->address}}" name="address"  required autocomplete=" " placeholder="Your Address">
                           <input type="hidden" name="id" value="{{$edit->id}}">
                            </div>
                             <div class="form-group form-box">
                              <input id="email" type="text" class="input-text form-control " name="city" value="{{$edit->city}}" required autocomplete="email" placeholder="{{ __('city') }}">

                            
                             </div>
                             <div class="form-group form-box">
                              <input id="email" type="text" class="input-text form-control " name="state" value="{{$edit->state}}" required autocomplete="email" placeholder="{{ __('state') }}">

                           
                             </div>
                             <div class="form-group form-box">
                              <input id="email" type="text" class="input-text form-control " name="pincode" value="{{$edit->pincode}}" required autocomplete="email" placeholder="{{ __('pincode') }}">

                           
                             </div>
                             <div class="form-group form-box">
                              <input id="email" type="text" class="input-text form-control " name="phone" value="{{$edit->phone}}" required autocomplete="email" placeholder="{{ __('phone') }}">

                           
                             </div>
                             <div class="form-group clearfix">
                         
                            <button type="submit" class="btn btn-primary login-btn" id="paynow">
                                    {{ __('Update') }}
                                </button>
                               <a href="{{route('BillingAddress')}}"> <button type="button" class="btn btn-primary login-btn" id="paynow">
                                    {{ __('Cancel') }}
                                </button></a>
                            </div>
                    </form>


                            

                            

                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

</div>	
@endsection
@section('script')


@endsection