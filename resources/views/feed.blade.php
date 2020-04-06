@extends('layouts.site')


@section('content')
@php ini_set('memory_limit', '-1'); @endphp
	<!-- Banner HTML Starts Here -->
	<div class="inner-banner feeds-page">
	    <div class="inner-banner-center">
	        <div class="container">
	            <div class="row justify-content-md-center">
	                <div class="col-md-8 col-sm-12  text-center">
	                    <h2>Feed</h2>
	                    <div class="mt-2">
	                        <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.</p>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
	<!-- Banner HTML Ends Here -->
    

        <!-- Feeds List HTML Starts Here -->
        <div class="feed-list ">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="advance-search">
                            <!-- Form Starts Here -->
                            <form class="row equal form-search">
                                {{-- <div class="col-lg-3 col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control multi-datepicker" placeholder="Select Date">
                                    </div>                                  
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="form-group">
                                        <select class="form-control search-input">
                                            <option selected disabled>Select Category</option>
                                            @foreach($feed_list as $feed_each)
                                                <option value="{{$feed_each->id}}">{{$feed_each->name}}</option>
                                            
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="form-group">
                                        <select class="form-control search-input">
                                            <option selected disabled>Select Order By</option>
                                            <option>Newest First</option>
                                            <option>Oldest First</option>
                                            <option>Updated</option>
                                            <option>Name</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="form-group">
                                        <button type="submit" class="button primary-button w-100">Search</button>
                                    </div>                                  
                                </div> --}}
                                <div class="col-lg-3 col-md-6">
                                    <div class="form-group">
                                        <select class="form-control search-input" id="feed_cat">
                                            <option selected value="0">Select Feed Source</option>
                                            @foreach($all_feeds as $feed_each)
                                                <option value="{{$feed_each->name}}">{{$feed_each->name}}</option>
                                            
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="form-group">
                                        <select class="form-control search-input" id="feed_type">
                                            <option selected value="0">Select Feed Type</option>
                                            @foreach($categories as $category)
                                                <option value="{{$category->id}}">{{$category->category_name}}</option>
                                            
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="form-group">
                                        <select class="form-control search-input" id="feed_tag">
                                            <option value="0">Select Tag</option>
                                                @foreach($feed_tags as $tag)
                                                    <option value="{{$tag}}">{{$tag}}</option>
                                            
                                                @endforeach
                                        </select>
                                       
                                    </div>
                                </div>
                                 <div class="col-lg-3 col-md-6">
                                    <div class="form-group">
                                        <select class="form-control search-input" id="time_filter">
                                            <option value="0">Select filter</option>
                                            <option value="lastHr">In the last hour</option>
                                            <option value="last24Hr">In the last 24 hour</option>
                                            <option value="last48Hr">In the last 48 hour</option>
                                            <option value="lastWeek">In the last week</option>   
                                        </select>
                                       
                                    </div>
                                </div>
                                
                                {{-- <div class="col-lg-3 col-md-6">
                                    <div class="form-group">
                                        <button type="button" data-toggle="modal" data-target="#myModal" class="button primary-button w-100">Feed Settings</button>
                                    </div>                                  
                                </div> --}}
                            </form>
                            <!-- Form Ends Here -->
                        </div>
                    </div>

                    <div class="col-12">
                        <ul class="drag-list drag-true news-list">
                            {{-- @foreach($feeds as $feed)   --}}
                                @foreach($feeds as $single_feed)
                                    <li class="c feed-{{$single_feed->feed->className()}} type-{{$single_feed->feed->category}} {{$single_feed->tags()}} {{$single_feed->timeClass()}}">
                                        <a href="{{$single_feed->link}}">
                                            <span class="ago-time">{{$single_feed->time()}}</span>
                                            <h2>{{$single_feed->title}}</h2>
                                            <h5> 
                                                <span><img src="{{asset('admin/images/feeds.png')}}"></span> {{isset($single_feed->feed->name) ? $single_feed->feed->name : ''}} &nbsp; 
                                                <span><img src="{{asset('admin/images/calendar.png')}}"></span>  {{isset($single_feed->pub_date) ? date("d/m/Y ",strtotime($single_feed->pub_date))  : ''}} &nbsp; 
                                                <span><img src="{{asset('admin/images/clock.png')}}"></span>{{isset($single_feed->pub_date) ? date("H:i:s",strtotime($single_feed->pub_date))  : ''}} 
                                            </h5>                                                                       
                                        </a>
                                    </li>
                                @endforeach
                            {{-- @endforeach --}}
                           
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Feeds List HTML Ends Here -->

    
 	   
    
@endsection
@section('scripts')
@parent
<script type="text/javascript">
    /*filter based on the site that came from*/
	$('#feed_cat').on('change', function() {
		 $('.c').not('.feed-').show();
	  //$('.feed-'+this.value).show(); 
        $('#feed_type').val(0);
	   if($('#feed_type').val()>0)
       {
            $('.c').not('.type-'+$('#feed_type').val()).hide();
       }
       if(this.value.indexOf(' ') >= 0)
       {
        var className=this.value.replace(/ /g,"_");
       }
       else
       {
        var className=this.value;
       }
	   $('.c').not('.feed-'+className).hide();
	});

</script>
<script type="text/javascript">
    /*filter based on type of feed (news ,blog,youtube etc)*/
    $('#feed_type').on('change', function() {
         $('.c').not('.type-').show();
      //$('.feed-'+this.value).show();
       $('#feed_cat').val(0); 
        if($('#feed_cat').val()>0)
        {
            $('.c').not('.feed-'+$('#feed_cat').val()).hide();
        }

        $('.c').not('.type-'+this.value).hide();
    });

</script>
<script type="text/javascript">
    {{-- filter based on feed tags --}}
    $('#feed_tag').on('change', function() {
        if(this.value=='0')
        {
            $('.c').not('.feed-').show();
        }
        else
        {
            $('.c').not('.feed-').show();
            var className=this.value;
            console.log(className);
            $('.c').not('.'+className).hide();
        }
    });

</script>
<script type="text/javascript">
    {{-- filter based on time --}}
    $('#time_filter').on('change', function() {
        if(this.value=='lastHr')
        {
            $('.c').not('.feed-').show();
            var className=this.value;
            //console.log(className);
            $('.c').not('.'+className).hide();
        }
        if(this.value=='last24Hr')
        {
            $('.c').not('.feed-').show();
            var className=this.value;
            //console.log(className);
            $('.c').not('.'+className).hide();
            $('.lastHr').show();
           
        }
        if(this.value=='last48Hr')
        {
            $('.c').not('.feed-').show();
            var className=this.value;
            //console.log(className);
            $('.c').not('.'+className).hide();
            $('.lastHr').show();
            $('.last24Hr').show();
           
        }
        if(this.value=='lastWeek')
        {
            $('.c').not('.feed-').show();
            var className=this.value;
            //console.log(className);
            $('.c').not('.'+className).hide();
            $('.lastHr').show();
            $('.last24Hr').show();
            $('.last48Hr').show();
           
        }
        if(this.value=='0')
        {
            $('.c').not('.feed-').show();
            var className=this.value;
        }
         $('.hide').hide(); /*hide feeds not suscribed by user*/
    });

</script>
<script>
    $(document).ready(function(){
      	$.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }); 
      
        $('.add-feed').on("click",function(e){
            e.preventDefault();
            id=$(this).attr('id');
            url='{{ route("AddUserFeedAjax",":slug") }}';
            url = url.replace(':slug', id);
            
            if ($(this).hasClass('fa-minus-circle'))
            {
              $(this).removeClass('fa-minus-circle');
              $(this).addClass('fa-plus');
            }
            else if ($(this).hasClass('fa-plus'))
            {
              $(this).removeClass('fa-plus');
              $(this).addClass('fa-minus-circle'); 
            }
            else
            {

            }
            //alert(id);
            $.ajax({
               url:url,
                
                type: 'GET',
                dataType: 'json',
                success: function(response)
                { 
                	console.log(response);
                	var len = response.length;
					$("#feed_cat").empty();
                	for( var i = 0; i<len; i++){
                	    var id = response[i]['id'];
                	    var name = response[i]['name'];
                	    $("#feed_cat").append("<option class='feed-"+name+"' value='"+name+"'>"+name+"</option>");
					 }
                	               
                }
            });    
        });
          
    });    
</script>    
@endsection
