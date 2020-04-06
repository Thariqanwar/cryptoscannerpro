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
  	<h2>Payment details</h2>
  	
	  <div style="float: right;padding: 10px;" >  <a href="{{ route('BillingAddress') }}"><button id="download" type="button" class="btn btn-success" >Billing Address</button></a></div>
  {{-- <button id="download" type="button" class="btn btn-success">Download</button> --}}
	<div class="table-responsive">  
  	<table id="log_table" class="table table-bordered mt-2">
  	  <thead>
  	    	<tr>
  	    	  <th>User</th>
            <th>email</th>
            <th>payment_status</th>
  	    	  <th>txn_id</th>
            <th>start_time</th>
            <th>expire_time</th>
            <th>amount</th>
            <th>payment_address</th>
            <th>payback_address</th>
            <th>subscription_type</th>
            <th>subscription_period</th>
            <th>coin_type</th>
            
  	    	  <th>Created Date</th>
            <th>Created Time</th>
  	    	</tr>
  	  </thead>
  	  <tbody>
  	  @foreach($payments as $payment)	
  		<tr>
  		  	<td>{{$payment->user->name}}</td>
          <td>{{$payment->email}}</td>
          <td>{{($payment->payment_status==100) ? 'Completed' : 'Processing'}}</td>
          <td>{{$payment->txn_id}}</td>
          <td>{{date('d-m-Y',$payment->start_time)}}</td>
          <td>{{date('d-m-Y',$payment->expire_time)}}</td>
          <td>{{$payment->amount}}</td>
          <td>{{$payment->payment_address}}</td>
          <td>{{$payment->payback_address}}</td>
          <td>{{$payment->user_category->category_type}}</td>
          <td>{{$payment->period->text}}</td>
          <td>{{$payment->coin_type}}</td>
         
  		  	<td>{{date_format($payment->created_at,'d-m-Y')}}</td>
          <td>{{date_format($payment->created_at,'H:i:s a')}}</td>
  		   	
  		</tr>
  		@endforeach
  	   
  	  </tbody>
	  </table>
	</div>
</div>	
@endsection
@section('script')


@endsection