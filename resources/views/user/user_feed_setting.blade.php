@extends('layouts.user')


@section('content')

  <div class="content-wrapper height-100">
    <div class="row">
      <div class="col-12">
        <div class="table-responsive">  
          <h2>Available Feeds</h2>
          <table id="datatable" class="table table-bordered mt-2">
            <thead>
                <tr>
                  <th>Name</th>
                  <th>Source</th>
                  <th>Language</th>
                  <th>Action</th>
                  
                </tr>
            </thead>
            <tbody>
            @foreach($feeds as $feed) 
            
            <tr>
                <td>{{$feed->name}}</td>
                <td>{{$feed->source}}</td>
                <td>{{$feed->language}}</td>
                <td><a href="{{route('AddUserFeed',$feed->id)}}" ><button id="{{$feed->id}}" class=" fa @if((Auth::user()->feed_exist($feed->id))) fa-minus-circle @else fa-plus  @endif "></button></a></td>
            </tr>
            
            @endforeach
            </tbody>
          </table>
        </div>

        @if(isset($success))
          <div class="text-success">
              <strong>Success!</strong> {{$success}}
          </div>
        @endif
        @if(session('success'))
            <div class="text-success">
                {{session('success')}}
            </div>
        @endif
        @if(count($errors))
          <div class="text-danger">
            <strong>Whoops!</strong> There were some problems with your input.
            <br/>
            <ul>
              @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif
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
          $('.add-feed').click(function(e){
            e.preventDefault();
            id=$(this).attr('id');
            url='{{ route("AddUserFeed",":slug") }}';
            url = url.replace(':slug', id);
            if ($(this).hasClass('fa-plus'))
            {
              $(this).removeClass("fa-plus");
            }
            else
            {
              $(this).removeClass("fa-minus");
            }


            //alert(id);
            $.ajax({
               url:url,
                
                type: 'GET',
                
                success: function(data)
                { 

                }
            });    
          });
          // AJAX request
      });    
  </script>             
@endsection
