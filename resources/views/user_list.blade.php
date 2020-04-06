@extends('layouts.app')


@section('content')
<div class="container">
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
  	<h2>Users List</h2>
  	<p>Suscribed Users List:</p>  
	<a href="{{route('AddNewUser')}}"><button class="btn btn-success"><i class="fas fa-plus-square"></i>Add New</button></a>
	<div class="table-responsive">  
  	<table class="table table-bordered mt-2">
  	  <thead>
  	    	<tr>
  	    	  <th>Firstname</th>
  	    	  <th>Email</th>
  	    	  <th>Subscription Period</th>
  	    	  <th>Password</th>
  	    	  <th>Status</th>
  	    	  <th>Subscription start</th>
  	    	  <th>Subscription end</th>
  	    	  <th>Action</th>
  	    	</tr>
  	  </thead>
  	  <tbody>
  	  @foreach($users as $user)	
  		<tr>
  		  	<td>{{$user->name}}</td>
  		  	<td>{{$user->email}}</td>
  		  	<td>{{$user->sub_period->text}}</td>
  		  	<td>{{$user->password_string}}</td>
  		  	<td>{{($user->status==false) ? 'blocked' : 'active'}}</td>
  		  	<td>{{$user->subscription_start}}</td>
  		   	<td>{{$user->subscription_end}}</td>
  		   	<td>
			<p class="no-wrap">
				<a href="{{route('UserEdit',['id' => $user->id])}}"><button class="btn btn-xs btn-primary"><i class="fas fa-pencil-alt"></i></button></a>
				 <a href="{{route('DeleteUser',['id' => $user->id])}}" onclick="return confirm('Are you sure you want to delete this item?');"><button class="btn btn-xs btn-danger"><i class="fas fa-trash"></i></button></a>
			</p>
		</td>
  		</tr>
  		@endforeach
  	   
  	  </tbody>
	  </table>
	</div>
</div>	
@endsection