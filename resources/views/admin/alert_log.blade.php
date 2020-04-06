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
  	<h2>Alerts Log</h2>
  	
	 <a href="{{route('AlertLogTable') }}"><button class="btn btn-success">Export Excel</button></a>
  {{-- <button id="download" type="button" class="btn btn-success">Download</button> --}}
	<div class="table-responsive">  
  	<table id="log_table" class="table table-bordered mt-2">
  	  <thead>
  	    	<tr>
  	    	  <th>Pairs</th>
  	    	  <th>Time Frame</th>
            <th>Category</th>
  	    	  <th>Activated price</th>
            <th>Triggered price</th>
  	    	  <th>Created Date</th>
            <th>Created Time</th>
  	    	</tr>
  	  </thead>
  	  <tbody>
  	  @foreach($alerts as $alert)	
  		<tr>
  		  	<td>{{$alert->coin}}</td>
  		  	<td>{{$alert->time_interval}}</td>
          <td>{{$alert->category}}</td>
  		  	<td>{{$alert->price}}</td>
          <td>{{$alert->price_2}}</td>
  		  	<td>{{date_format($alert->created_at,'d-m-Y')}}</td>
          <td>{{date_format($alert->created_at,'H:i:s a')}}</td>
  		   	
  		</tr>
  		@endforeach
  	   
  	  </tbody>
	  </table>
	</div>
</div>	
@endsection
@section('script')


@endsection