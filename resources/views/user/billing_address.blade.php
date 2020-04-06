@extends('layouts.admin')


@section('content')
<div class="content-wrapper height-100">


<div class=" dashboard">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

              

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
  {{-- <button id="download" type="button" class="btn btn-success">Download</button> --}}





  <div class="admin-controller  ">

  <div class="row">
					

                            <div class="col-md-6">
                                <h2>Billing Address</h2>
                                <p>Enter user details and submit</p>
                            </div>
                            <div class="col-md-6 head-right-btns">
                                <!-- <a href="https://localhost/ajo/cryptoscannerpro/admin/user/list" class="btn btn-label-brand mt-2"> Back </a> -->
                            </div>
                        </div>



  <form method="post" action="{{route('AddAddressPost')}}">
                              @csrf

    <div class="row   ">

			<div class="col-md-3  mt-3">
			<label class="control-label">Address</label>
            <input id="paybackaddress" type="address" class="input-text form-control " name="address"  required autocomplete=" " placeholder="Your Address">
             </div>

            <div class="col-md-3  mt-3">
			<label class="control-label">Email</label>
            <input id="email" type="text" class="input-text form-control " name="city" value="" required autocomplete="email" placeholder="{{ __('city') }}">
            </div>

            <div class="col-md-3  mt-3">
			<label class="control-label">State</label>
            <input id="email" type="text" class="input-text form-control " name="state" value="" required autocomplete="email" placeholder="{{ __('state') }}">
        </div>

        <div class="col-md-3  mt-3">
			<label class="control-label">Pincode</label>
            <input id="email" type="text" class="input-text form-control " name="pincode" value="" required autocomplete="email" placeholder="{{ __('pincode') }}">

             </div>

        <div class="col-md-3  mt-3">
			<label class="control-label">Phone</label>
            <input id="email" type="text" class="input-text form-control " name="phone" value="" required autocomplete="email" placeholder="{{ __('phone') }}">

             </div>

             <div class="col-md-12  mt-3 mb-2">
             <button type="submit" class="button btn-primary primary-button login-btn" id="paynow">
                                    {{ __('Add') }}
                                </button>
          <!-- <a href="https://cryptoscannerpro.com/admin/feed"><button type="button" class="button  btn-default primary-button mt-2">Cancel</button></a> -->
		 
		 
			</div>
 
 

 
		</div>

                          
                          
                             
                            
                         
                    </form>

                    </div>


                    <div class="admin-controller mt-3">
               <div class="row">
                            <div class="col-md-6">
                                <h2>Billing List</h2>
                                <p>All Billing</p>
                            </div>
                            <div class="col-md-6 head-right-btns">

                            <div id="userlistInfo">154 Total</div>
 
                            <div class="kt-subheader__search mt-2">
                            <input type="text" id="search-userlist" class="btn  " placeholder="Search..."> 
                            <span class="kt-input-icon__icon kt-input-icon__icon--right">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"></rect>
        <path d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
        <path d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z" fill="#000000" fill-rule="nonzero"></path>
    </g>
</svg>                                    <!--<i class="flaticon2-search-1"></i>-->
                                </span>
                            </span>
                        </div>



                           
                                <!-- <a href="https://localhost/ajo/cryptoscannerpro/admin/user/new" class="btn btn-label-brand mt-2"> Add User </a> -->
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mt-2">

                            <div class="table-responsive">  
  	<table id="billingdatatable" class="feedslist-table dataTable no-footer  "  style="width:100%">
  	  <thead>
  	    	<tr>
  	    	  
            <th>Name</th>
  	    	  <th>Address</th>
            <th>State</th>
            <th>city</th>
            <th>Pincode</th>
            <th>Phone</th>
            <th>Action</th>
           
  	    	</tr>
  	  </thead>
  	  <tbody>
  	   @foreach($address as $value)	 
  		<tr>
  		  	<td>{{$value->name}}</td>
  		   	<td>{{$value->address}}</td>
          <td>{{$value->state}}</td>
          <td>{{$value->city}}</td>
          <td>{{$value->pincode}}</td>
          <td>{{$value->phone}}</td>
          <td>
          
          <div class="dropdown">
                                                    <a data-toggle="dropdown" class="btn btn-sm btn-clean btn-icon btn-icon-md">
                                                        <i class="fas fa-ellipsis-h"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <ul class="kt-nav">
                                                            
                                                            <li class=" ">
                                                                <a href="{{route('EditAddress',$value->id)}}">
                                                                    <i class="far fa-edit"></i>
                                                                    <span class="kt-nav__link-text">Edit</span> </a>
                                                            </li>
                                                            <li class=" ">
                                                                <a   href="{{route('DeleteAddress',$value->id)}}" data-id="{{$value->id}}" id="delete" onclick="return confirm('Are you sure  to Remove?');">
                                                                    <i class="far fa-trash-alt"></i>
                                                                    <span class="kt-nav__link-text">Delete</span> </a>
                                                            </li>
                                                            
                                                        </ul>
                                                    </div>
                                                </div>
          
 
          </td>
  		</tr>
  		@endforeach
  	   
  	  </tbody>
	  </table>
	</div>
                      

 
                            </div>
                        </div>
                    </div>





  







</div>
</div>
</div>
</div>



	
</div>	


<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
<script src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/select/1.1.2/js/dataTables.select.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.0.2/js/dataTables.responsive.min.js"></script>

 

<script>   
$(document).ready(function (){
   // Array holding selected row IDs
   var rows_selected = [];
   var table = $('#billingdatatable').DataTable({ 
    'order': [4, 'asc'],
    responsive: true, 
      'columnDefs': [ { orderable: false, targets: [0] } ],
     'sDom': 'Rfrtlip' ,
     "language": {
    "lengthMenu": '<select>'+
      '<option value="10">10</option>'+
      '<option value="20">20</option>'+
      '<option value="30">30</option>'+
      '<option value="40">40</option>'+
      '<option value="50">50</option>'+
      '<option value="-1">All</option>'+
      '</select>'
  },
      'rowCallback': function(row, data, dataIndex){
         // Get row ID
         var rowId = data[0];

         // If row ID is in the list of selected row IDs
         if($.inArray(rowId, rows_selected) !== -1){
            $(row).find('input[type="checkbox"]').prop('checked', true);
            $(row).addClass('selected');
         }
      }
   });

   $('#search-userlist').on('keyup',function(){
      table.search($(this).val()).draw() ;
});
var info = table.page.info();
 
$('#userlistInfo').html(
    info.recordsTotal+' Total'
);

 
 
   // Handle table draw event
   table.on('draw', function(){
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(table);
   });
    
   // Handle form submission event 
   $('#frm-example').on('submit', function(e){
      var form = this;

      // Iterate over all selected checkboxes
      $.each(rows_selected, function(index, rowId){
         // Create a hidden element 
         $(form).append(
             $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'id[]')
                .val(rowId)
         );
      });

      // FOR DEMONSTRATION ONLY     
      
      // Output form data to a console     
      $('#example-console').text($(form).serialize());
      console.log("Form submission", $(form).serialize());
       
      // Remove added elements
      $('input[name="id\[\]"]', form).remove();
       
      // Prevent actual form submission
      e.preventDefault();
   });
});
 

</script>


@endsection
@section('script')


@endsection