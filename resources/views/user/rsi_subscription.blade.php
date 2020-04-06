@extends('layouts.admin')


@section('content')

	<div class="content-wrapper height-100">
		<div class="row justify-content-center align-items-md-center min-height-90">
			<div class="col-md-6 order-2 order-md-1">
			
			<form class="form-horizontal" method="post" action="{{route('PostRsiSubscription')}}" >
				@csrf
				
				<input type="hidden" name="user" value="{{Auth::user()->id}}">
				<div class="form-group">
			
					<div class="form-group">
						<h4 class="mb-4">RSI Bullish Divergance</h4>						
					
						<div class="option-wrap">
							@foreach($time_periods as $key => $time_period) 
							<div class="full-width">   
							<div class="form-check onoffswitch options">
								<input class="onoffswitch-checkbox" type="checkbox" id="defaultCheck{{$key+1}}" name=time_frame[] required value="{{$time_period->id}}" {{(in_array($time_period->id,$subscribe)) ? 'checked' : ''}} data-toggle="toggle">
								<label class="form-check-label onoffswitch-label" for="defaultCheck{{$key+1}}">								 
									<span class="onoffswitch-inner"></span>
									<span class="onoffswitch-switch"></span>
								</label>
								</div>							 
								<p>{{$time_period->text}}</p>
								</div>
							@endforeach							
						</div>						
					</div>				
				</div>
				<div class="form-group"> 	
					<p class="time-req text-danger mb-2"></p>				
					<button type="submit" class="btn btn-primary">Submit</button>
					{{-- <a href="{{route('userProfile')}}"><button type="button" class="btn btn-secondary">Cancel</button></a>					 --}}
					@if(session('success'))
						<div class="alert alert-success">
							{{session('success')}}
						</div>
					@endif
				</div>
			</form>
			</div>
			<div class="col-md-6 img-col order-1 order-md-2">
				<img src="{{asset('bg-2.png')}}" class="img-fluid right-img" alt="">
			</div>
		</div>
	</div>	

@endsection
@section('scripts')

	<script>
	    $(document).ready(function(){	    		
	    	
	    		var requiredCheckboxes = $('.options :checkbox[required]');
	    			if(requiredCheckboxes.is(':checked')) 
	    			{
	    			    requiredCheckboxes.removeAttr('required');
	    			    $('.time-req').html('');
	    			} 
	    			else 
	    			{

	    			    requiredCheckboxes.attr('required', 'required');
	    			    $('.time-req').html('Select a time frame');
	    			}
	    		    requiredCheckboxes.change(function(){
	    		        if(requiredCheckboxes.is(':checked')) 
	    		        {
	    		            requiredCheckboxes.removeAttr('required');
	    		            $('.time-req').html('');
	    		        } 
	    		        else 
	    		        {

	    		            requiredCheckboxes.attr('required', 'required');
	    		            $('.time-req').html('Select a time frame');
	    		        }
	    		    });
	     	
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
	        $('#subscription_period').change(function(e){
	        	var data = $('form').serialize();
	        	e.preventDefault();
	        	$.ajax({
	        	    url: "{{route('GetTimeFrames')}}",
	        	    type: 'post',
	        	    data: data,
	        	    success: function(timeframes)
	        	    { 
	        	    	$("input[name='time_frame']").each(function(e){
	        	    	    if($(this).val() == 1){
	        	    	        $(this).attr("checked", "checked");
	        	    	    }
	        	    	});
	        	    	console.log(timeframes);
	        	    }
	        	});    
	        });

	    });    
	</script>            	
@endsection
