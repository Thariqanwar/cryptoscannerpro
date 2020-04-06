@extends('layouts.admin')


@section('content')

			<!-- The Modal -->
			{{-- <div id="myModal" class="modal" style="height: 500px;width:800px;">

			  <!-- The Close Button -->
			  <span class="close">&times;</span>

			  Modal Content (The Image)
			  <img class="modal-content" id="img01">

			  <!-- Modal Caption (Image Text) -->
			  <div id="caption"></div>
			</div> --}}
			<div id="myModal" class="modal " role="dialog">
			  <div class="modal-dialog">

			    <!-- Modal content-->
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal">&times;</button>
			        <h4 class="modal-title">Modal Header</h4>
			      </div>
			      <div class="modal-body">
			        <img width="100%" id="img01">
			        <div id="caption"></div>
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-default close" data-dismiss="modal">Close</button>
			      </div>
			    </div>

			  </div>
			</div>
		
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
			  	<h2>Alerts</h2>
			  	
				{{-- <a href="{{route('AlertLogTable') }}"><button class="btn btn-success">Export Excel</button></a> --}}
			  	{{-- <button id="download" type="button" class="btn btn-success">Download</button> --}}
				<div class="table-responsive">  
				  	<table id="datatable" class="table table-bordered">
				  	  	<thead>
				  	    	<tr>
				  	    	  	<th>Pairs</th>
				  	    	  	<th>Time Frame</th>
				            	<th>Category</th>
				  	    	  	
				            	<th>Triggered price</th>
				            	<th>Image</th>
				  	    	  	<th>Created Date</th>
				            	<th>Created Time</th>
				  	    	</tr>
				  	  	</thead>
				  	  	<tbody>
					  	  	@foreach($alerts as $alert)	
					  		<tr>
					  		  	<td>{{$alert->coin}}</td>
					  		  	<td>{{$alert->time_interval}}</td>
					          	 <td>{{($alert->signal) ? $alert->signal->short_text : ''}}</td>
					  		  
					          	<td>{{$alert->price_2}}</td>
					          	<td>
					          		@if(file_exists(public_path('/telegram/'.$alert->id.'.jpg') ))
					          			{{-- <img height="20px" width="20px" src='{{asset("/telegram/$alert->id")}}.jpg'>  --}}
					          			<img class="myImg"  height="20px" width="20px" src='{{asset("/telegram/$alert->id")}}.jpg' alt="{{$alert->category.'-'.$alert->coin.'-'.$alert->time_interval}}" >
					          		@endif
					          	</td>
					  		  	<td>{{date_format($alert->created_at,'d-m-Y')}}</td>
					          	<td>{{date_format($alert->created_at,'H:i:s a')}}</td>
					  		</tr>
					  		@endforeach
				  	   
				  	  	</tbody>
					</table>
				</div>
			</div>	
	

@endsection
@section('scripts')
	<script type="text/javascript">
		// Get the modal
		$(".myImg").click(function(){
			 $("#myModal").show();
			$("#img01").attr('src',$(this).attr('src'));
			 $("#caption").html($(this).attr('alt'));
			$('.modal-title').html($(this).attr('alt'));
			

		// When the user clicks on <span> (x), close the modal
				$(".close").click(function(){
					 $("#myModal").hide();
				});
		
		});
		
	</script>
	          	
@endsection
