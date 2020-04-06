
  	<h2>Alerts Logs</h2>
  	
	 <a href="{{route('downloadExcel','xlsx') }}"><button class="btn btn-success">Download Excel xlsx</button></a>
 
	<div class="table-responsive">  
  	<table id="log_table" class="table table-bordered mt-2">
  	  <thead>
  	    	<tr>
  	    	  <th>Pairs</th>
  	    	  <th>Time Frame</th>
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
{{-- <script type="text/javascript">
  window.location.href = "http://cryptoscannerpro.com/admin/downloadexcel/xls";
</script> --}}