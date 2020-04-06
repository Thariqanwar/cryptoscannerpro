@extends('layouts.admin')


@section('content')

	<div class="content-wrapper height-100">
		<div class="row justify-content-center align-items-md-center min-height-90">
			<div class="col-md-6 order-2 order-md-1">
			
			<form class="form-horizontal" method="post" action="{{route('PostuserSettings')}}" >
				@csrf
				
				<input type="hidden" name="user" value="{{Auth::user()->id}}">
				<div class="form-group">
			
					<div class="checkbox">
					  <label><input type="checkbox" @if(Auth::user()->authentication_status==1) checked @endif name="google2fa" value="1">Enable/Disable Google 2FA authentication</label>
					</div>				
				</div>
				<div class="form-group"> 	
					<p class="time-req text-danger mb-2"></p>				
					<button type="submit" class="btn btn-primary">SAVE</button>
					{{-- <a href="{{route('userProfile')}}"><button type="button" class="btn btn-secondary">Cancel</button></a>					 --}}
					@if(session('success'))
						<div class="alert alert-success">
							{{session('success')}}
						</div>
					@endif
				</div>
			</form>
			</div>
			
		</div>
	</div>	

@endsection
@section('scripts')

	<script>
	    $(document).ready(function(){	    		
	    	
	    		
	    });    
	</script>            	
@endsection
