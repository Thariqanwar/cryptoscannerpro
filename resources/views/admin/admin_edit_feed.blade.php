@extends('layouts.admin')


@section('content')
<div class="content-wrapper height-100">



<div class=" dashboard">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">


                	 




  @if(isset($success))
    <div class="alert alert-success">
        <strong>Success!</strong> {{$success}}
    </div>
  @endif
  @if(session('success'))
      <div class="alert alert-success">
          {{session('success')}}
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


  <div class="admin-controller  ">


                                                
                        <div class="row">
					

                            <div class="col-md-6">
                                <h2>Update Feed</h2>
                                <p>Update details and submit</p>
                            </div>
                            <div class="col-md-6 head-right-btns">
                                <!-- <a href="https://localhost/ajo/cryptoscannerpro/admin/user/list" class="btn btn-label-brand mt-2"> Back </a> -->
                            </div>
                        </div>
 
                        <div class="row"> 
                            <div class="col-12  ">
  
                            <form class="form-horizontal" method="post" action="{{ route('UpdateFeed',$edit_feed->id) }}" >
    @csrf
    <input type="hidden" name="edit_id" value="{{$edit_feed->id}}">
   
   
    <div class="row   ">

			<div class="col-md-3  mt-3">
			<label class="control-label">Name</label>
            <input type="text" name="name" value="{{ (empty(old('name'))) ? $edit_feed->name : old('name')}}  " required class="form-control {{ $errors->has('name') ? 'has-error' : '' }}" id="name" placeholder="Enter Name">
        <span class="text-danger">{{ $errors->first('name') }}</span>
			</div>

            <div class="col-md-3  mt-3">
			<label class="control-label">Source:</label>
            <input type="text" name="source" value="{{ (empty(old('source'))) ? $edit_feed->source : old('source')}} " required class="form-control {{ $errors->has('source') ? 'has-error' : '' }}" id="source" placeholder="Enter source">
        <span class="text-danger">{{ $errors->first('source') }}</span>
			</div>

            <div class="col-md-3  mt-3">
			<label class="control-label">Language</label>
            <div class="form-group {{ $errors->has('lastname') ? 'has-error' : '' }}">
          <select name="language" required class="form-control {{ $errors->has('language') ? 'has-error' : '' }}" id="language">
              
              <option value="english" @if(old('language')=='english') selected @endif 
              @if($edit_feed->language=='english') selected @endif >English</option>

              <option value="english" @if(old('language')=='english') selected @endif
              @if($edit_feed->language=='english') selected @endif >English</option>
              <option value="turkish" @if(old('language')=='turkish') selected @endif
              @if($edit_feed->language=='turkish') selected @endif >Turkish</option>
              <option value="german" @if(old('language')=='german') selected @endif
              @if($edit_feed->language=='german') selected @endif >German</option>
              <option value="spanish" @if(old('language')=='spanish') selected @endif
              @if($edit_feed->language=='spanish') selected @endif >Spanish</option>
           
          </select>
          <span class="text-danger">{{ $errors->first('language') }}</span>
        </div> 
			</div>


            <div class="col-md-3  mt-3">
			<label class="control-label">Category</label>
            <div class="form-group {{ $errors->has('category') ? 'has-error' : '' }}">
          <select name="category" required class="form-control {{ $errors->has('category') ? 'has-error' : '' }}" id="category">
            @foreach($categories as $category)
              <option value="{{$category->id}}" @if(old('category')==$category->id) selected @endif 
                @if($edit_feed->category==$category->id) selected @endif >{{$category->category_name}}</option>
            @endforeach
          </select>
          <span class="text-danger">{{ $errors->first('category') }}</span>
        </div>
           
			</div>

            <div class="col-md-12  mt-3 mb-2">

			<label class="control-label">RSS Feed URL</label>
            <input type="text" name="feed_url" value=" {{ (empty(old('feed_url')))  ? $edit_feed->feed_url  : old('feed_url') }}" required class="form-control {{ $errors->has('feed_url') ? 'has-error' : '' }}" id="feed_url" placeholder="Enter feed url">
          <span class="text-danger">{{ $errors->first('feed_url') }}</span>
           
			</div>

            <div class="col-md-12  mt-3 mb-2">

            <button type="submit" class="button btn-success primary-button mt-2">Update</button>
          <a href="{{route('AddFeed')}}"><button type="button" class="button btn-default primary-button mt-2">Cancel</button></a>
		 
			</div>


    </div>
              



 
  
     
  </form>
					 

                            </div>
 
                        </div>
                    </div>
                    


                    



<div class="admin-controller mt-3">
               <div class="row">
                            <div class="col-md-6">
                                <h2>Feeds List</h2>
                                <p>All feeds</p>
                            </div>
                            <div class="col-md-6 head-right-btns">

                            <div id="userlistInfo">19 Total</div>
 
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
                            <!-- userlist-table -->

                            <div class="table-responsive">  
                            <table id="feedslist-table" class="feedslist-table dataTable no-footer  "  style="width:100%">
        <thead>
            <tr>
              <th data-priority="0">Name</th>
              <th>Feed URL</th>
              <th data-priority="2">Source</th>
              <th data-priority="3">Language</th>
              <th  data-priority="1">Action</th>
              
            </tr>
        </thead>
        <tbody>
        @foreach($feeds as $feed) 
        
        <tr>
            <td>{{$feed->name}}</td>
             <td>{{$feed->feed_url}}</td>
            <td>{{$feed->source}}</td>
            <td>{{$feed->language}}</td>
            <td>

            <div class="dropdown">
                                                    <a data-toggle="dropdown" class="btn btn-sm btn-clean btn-icon btn-icon-md" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-h"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(31px, 29px, 0px);">
                                                        <ul class="kt-nav">
                                                            <!-- <li class=" ">
                                                                <a href="https://localhost/ajo/cryptoscannerpro/admin/user/view/46">
                                                                    <i class="far fa-eye"></i>
                                                                    <span class="kt-nav__link-text">View</span> </a>
                                                            </li> -->
                                                            <li class=" ">
                                                                <a href="{{route('EditFeed',$feed->id)}}">
                                                                    <i class="far fa-edit"></i>
                                                                    <span class="kt-nav__link-text">Edit</span> </a>
                                                            </li>
                                                            <li class=" ">
                  <a href="{{route('DeleteFeed',$feed->id)}}" onclick="return confirm('Are you sure you want to Remove?');">
                                                                    <i class="far fa-trash-alt"></i>
                                                                    <span class="kt-nav__link-text">Delete</span> </a>
                                                            </li>
                                                         
                                                        </ul>
                                                    </div>
                                                </div>
            
       
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
   var table = $('#feedslist-table').DataTable({ 
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
