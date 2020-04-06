@extends('layouts.admin')


@section('content') 
<div class="content-wrapper height-100">
  @if(session('success'))
      <div class="alert alert-success">
          {{session('success')}}
      </div>
  @endif
	@if(isset($success))
	
	@endif
  	<h2>IP Log Details </h2>
  	<!-- <p>Suscribed Users List:</p>  
	<a href="{{route('AddNewUser')}}"><button class="btn btn-success"><i class="fas fa-plus-square"></i>Add New</button></a> -->
	<div class="table-responsive">  
  	<table class="table table-bordered mt-2">
  	  <thead>
  	    	<tr>
  	    	  <th>Username</th>
  	    	  <th>Email</th>
  	    	  <th>Ip Address</th>
  	    	  <th>Login Date</th>
  	    	  
  	    	</tr>
  	  </thead>
  	  <tbody>
  	  @foreach($log as $value)	
  		<tr>
  		  	<td>{{$value->logData->name}}</td>
  		  	<td>{{$value->logData->email}}</td>
  		  	<td>{{$value->ip_adress}}</td>
  		  	<td>{{$value->created_at}}</td>
  		  	
  		   
  		</tr>
  		@endforeach
  	   
  	  </tbody>
	  </table>
	</div>
</div>	
@endsection