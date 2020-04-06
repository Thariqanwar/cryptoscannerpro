@extends('layouts.admin')
@section('content')
<div class="content-wrapper height-100">
    <div class=" dashboard">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="admin-controller">
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

 


                        <div class="row">
                            <div class="col-md-6">
                                <h2>Users List</h2>
                                <p>Suscribed Users List:</p>
                            </div>
                            <div class="col-md-6 head-right-btns">

                            <div id="userlistInfo">
                            </div>
 
                            <div class="kt-subheader__search mt-2">
                            <input type="text" id="search-userlist" class="btn  " placeholder="Search..." > 
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



                           
                                <a href="{{route('AddNewUser')}}" class="btn btn-label-brand mt-2"> Add User </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mt-2">

 
 

                              <table class="userlist-table" id="userlist-table">
                                    <thead>
                                        <tr>
                                         
                                            <th class="no-sort" width="17"> <!--<input name="select_all" value="1" type="checkbox"> -->
                                            <div class="custom-control custom-checkbox custom-control-inline ">
                                                    <input type="checkbox" value="1" name="select_all"
                                                        class="custom-control-input feed_type" id="selectall">
                                                    <label class="custom-control-label" for="selectall"> </label>
                                                </div>
                                            
                                            </th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Subscription Type</th>  
                                            <th>Ship Date</th>  
                                           {{--  <th>Company</th> --}}
                                            <th>Status</th>
                                          
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $user)
                                        <tr> 
                                            <td  >
                                            <div class="custom-control custom-checkbox custom-control-inline ">
                                                    <input type="checkbox" value="{{$user->id}}" name="type[]"
                                                        class="custom-control-input feed_type selectedId" id="selectall{{$user->id}}">
                                                    <label class="custom-control-label" for="selectall{{$user->id}}"> </label>
                                                </div>
                                             
                                            </td>
                                            <td>
                                              
                                                    <div class="kt-user-card-v2">
                                                        <div class="kt-user-card-v2__pic">
                                                            <div class="kt-badge  blue {{($user->subscription_details) ? substr($user->subscription_details->category_type,0,1) : ''}}">{{substr($user->name,0,1)}}</div>
                                                        </div>
                                                        <div class="kt-user-card-v2__details">
                                                          <b><a class="kt-user-card-v2__name" href="{{route('ViewUser',$user->id)}}">{{$user->name}}</a></b>  
                                                             <span class="kt-user-card-v2__desc">{{($user->subscription_details) ? $user->subscription_details->category_type : ''}}  </span>  
                                                        </div>
                                                    </div>
                                               
                                                </td>
                                            <td>{{$user->email}}</td>
                                            <td>
                                            <!-- <span class="kt-font-bold kt-font-danger">* Online</span> -->
                                            <span class="kt-font-bold kt-font-primary {{($user->subscription_details) ? substr($user->subscription_details->category_type,0,1) : ''}}"> {{($user->subscription_details) ? $user->subscription_details->category_type : ''}}  </span>
                                            <!-- <span class="kt-font-bold kt-font-success">* Direct</span> -->
                                            </td> 
                                            <td>{{($user->sub_period) ? $user->sub_period->text : ''}}</td>
                                            
                                            {{-- <td>
                                            
                                            <div class="kt-user-card-v2">
                                                        <div class="kt-user-card-v2__pic">
                                                            <div class="kt-badge  green">H</div>
                                                        </div>
                                                        <div class="kt-user-card-v2__details"> <a
                                                                class="kt-user-card-v2__name" href="#">Kessler and Sons</a> <span
                                                                class="kt-user-card-v2__desc">Angular, React</span> </div>
                                                    </div>
                                               
                                            </td> --}}
                                            <td>
                                            <!-- <span class="btn btn-bold btn-sm btn-font-sm  btn-label-warning">Canceled</span>
                                            <span class="btn btn-bold btn-sm btn-font-sm  btn-label-brand">Pending</span>
                                            <span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">Delivered</span> -->
                                            <span class="btn btn-bold btn-sm btn-font-sm  btn-label-{{($user->status==false) ? 'danger' : 'success'}}">{{($user->status==false) ? 'blocked' : 'active'}}</span>
                                            <!-- {{($user->status==false) ? 'blocked' : 'active'}} -->
                                            
                                            </td>
                                 
                                            <td>
                                                <div class="dropdown">
                                                    <a data-toggle="dropdown"
                                                        class="btn btn-sm btn-clean btn-icon btn-icon-md">
                                                        <i class="fas fa-ellipsis-h"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <ul class="kt-nav">
                                                            <li class=" ">
                                                                <a href="{{route('ViewUser',$user->id)}}">
                                                                    <i class="far fa-eye"></i>
                                                                    <span class="kt-nav__link-text">View</span> </a>
                                                            </li>
                                                            <li class=" ">
                                                                <a href="{{route('UserEdit',['id' => $user->id])}}">
                                                                    <i class="far fa-edit"></i>
                                                                    <span class="kt-nav__link-text">Edit</span> </a>
                                                            </li>
                                                            <li class=" ">
                                                                <a onclick="return confirm('Are you sure you want to delete this item?')" href="{{route('DeleteUser',['id' => $user->id])}}">
                                                                    <i class="far fa-trash-alt"></i>
                                                                    <span class="kt-nav__link-text">Delete</span> </a>
                                                            </li>
                                                            <li class=" ">
                                                                <a class="kt-nav__link" href="#">
                                                                    <i class="fas fa-file-excel"></i>
                                                                    <span class="kt-nav__link-text">Export</span> </a>
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



<script src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/select/1.1.2/js/dataTables.select.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.0.2/js/dataTables.responsive.min.js"></script>

 
  
<script>  

//
// Updates "Select all" control in a data table
//
function updateDataTableSelectAllCtrl(table){
   var $table             = table.table().node();
   var $chkbox_all        = $('tbody input[type="checkbox"]', $table);
   var $chkbox_checked    = $('tbody input[type="checkbox"]:checked', $table);
   var chkbox_select_all  = $('thead input[name="select_all"]', $table).get(0);

   // If none of the checkboxes are checked
   if($chkbox_checked.length === 0){
      chkbox_select_all.checked = false;
      if('indeterminate' in chkbox_select_all){
         chkbox_select_all.indeterminate = false;
      }

   // If all of the checkboxes are checked
   } else if ($chkbox_checked.length === $chkbox_all.length){
      chkbox_select_all.checked = true;
      if('indeterminate' in chkbox_select_all){
         chkbox_select_all.indeterminate = false;
      }

   // If some of the checkboxes are checked
   } else {
      chkbox_select_all.checked = true;
      if('indeterminate' in chkbox_select_all){
         chkbox_select_all.indeterminate = true;
      }
   }
}

$(document).ready(function (){
   // Array holding selected row IDs
   var rows_selected = [];
   var table = $('#userlist-table').DataTable({ 
    'order': [4, 'asc'],
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




 

$('#selectall').click(function() {
    $('.selectedId').prop('checked', this.checked);
});
$('.selectedId').change(function() {
    var check = ($('.selectedId').filter(":checked").length == $('.selectedId').length);
    $('#selectall').prop("checked", check);
});






</script>
@endsection