@extends('layouts.admin')


@section('content')
<div class="content-wrapper height-100">
	@if(isset($success))
		<div class="alert alert-success">
		  	<strong>Success!</strong> {{$success}}
		</div>
	@endif
	@if(count($errors))

		<div class="alert alert-danger">

			<strong>Whoops!</strong> There were some problems with your input.

			<br/>

			<ul>

				@foreach($errors->all() as $error)

				<li>{{ $error }}</li>

				@endforeach

			</ul>

		</div>

	@endif


	<div class=" dashboard">


        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-md-6">

                    <div class="admin-controller  ">


                                                
                        <div class="row">
					

                            <div class="col-md-6">
                                <h2>New User</h2>
                                <p>Enter user details and submit</p>
                            </div>
                            <div class="col-md-6 head-right-btns">
                                <a href="{{route('UserList')}}" class="btn btn-label-brand mt-2"> Back </a>
                            </div>
                        </div>
 
                        <div class="row"> 
                            <div class="col-12  ">

							<form class="form-horizontal" method="post" action="{{route('PostNewUser')}}" >
		@csrf
		<div class="row   ">
			<div class="col-md-6  mt-3">
			<label class="control-label">Name</label>
			<input type="text" name="name" value="{{ old('name') }}" required class="form-control {{ $errors->has('name') ? 'has-error' : '' }}" id="name" placeholder="Enter Name">
				<span class="text-danger">{{ $errors->first('name') }}</span>
			</div>

			<div class="col-md-6  mt-3">
			<label class="control-label">Email</label>
			<input type="email" name="email" {{ old('email') }} required class="form-control {{ $errors->has('email') ? 'has-error' : '' }}" id="email" placeholder="Enter email">
	  	    <span class="text-danger">{{ $errors->first('email') }}</span>
			</div>

			<div class="col-md-6  mt-3">
			<label class="control-label">Password</label>
			<input type="text" name="password" {{ old('password') }} required class="form-control {{ $errors->has('password') ? 'has-error' : '' }}" id="pwd" placeholder="Generate password" readonly="true">
	 	    <span class="text-danger">{{ $errors->first('password') }}</span>
	 	    <button type="button" id="generate">Generate</button>
			</div>

			<div class="col-md-6  mt-3">
			<label class="control-label">Subscription period</label>
			<div class="form-group {{ $errors->has('lastname') ? 'has-error' : '' }}">
	 	      <select name="subscription_period" required class="form-control {{ $errors->has('subscription_period') ? 'has-error' : '' }}" id="subscription_period">
	 	      	@foreach($sub_periods as $sub_period) 
	 	        	<option value="{{$sub_period->id}}" @if(old('subscription_period')==$sub_period->id) selected @endif>{{$sub_period->text}}</option>
	 	        @endforeach
	 	      </select>
	 	      <span class="text-danger">{{ $errors->first('subscription_period') }}</span>
	 	    </div>
			
			
			</div>

			
			{{-- <div class="form-group {{ $errors->has('lastname') ? 'has-error' : '' }}">
	 	   <label class="control-label col-sm-2" for="username">Telegram Username</label>
	  	  <div class="col-sm-10">
	  	    <input type="text" name="username" class="form-control" id="username" placeholder="Enter Telegram Username">
	  	  </div>
		 </div> --}}

		 <div class="col-md-12  mt-3 mb-2">
		 <button type="submit" class="button btn-default primary-button">Submit</button>
	  	    <a href="{{route('UserList')}}"><button type="button" class="button btn-default primary-button">Cancel</button></a>
			
			</div>
		 


		 
		</div>
	 
 
  
	</form>


					 

                            </div>
 
                        </div>
                    </div>








                </div>
            </div>

        </div>
    </div>





	 

</div>	
@endsection
@section('scripts')

	<script>
	    $(document).ready(function(){
			$.ajaxSetup({
	          headers: {
	            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	          }
	        });	
	        $('#generate').click(function(e){
	        	e.preventDefault();
	        	$.ajax({
	        	    url: "{{route('GeneratePassword')}}",
	        	    type: 'post',
	        	    success: function(password)
	        	    { 
	        	    	$('#pwd').val(password);
	        	    }
	        	});    
	        });
	        // AJAX request
	    });    
	</script>            	
@endsection
