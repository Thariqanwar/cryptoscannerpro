@extends('layouts.admin')

@section('header-scripts')
	<!-- <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet"> -->
		<!-- <link href="https://davidstutz.de/bootstrap-multiselect/dist/css/bootstrap-multiselect.css" rel="stylesheet"/> -->

@endsection

@section('content')
	@php ini_set('memory_limit','500M'); @endphp
	<!-- Select sources -->
<div class="modal fade filter-popup" id="filter_source" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">                
            <div class="modal-body">
                <div class="heading">
                    <h3>Select sources</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <svg class="close-icon" viewBox="0 0 24 24">
                            <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"></path>
                            <path d="M0 0h24v24H0z" fill="none"></path>
                        </svg>
                    </button>
                </div>
                <div class="lang-select">
                    <label>Language of sources:</label>
                    <select class="form-control search-input" id="language">
                        <option value="english" >English</option>
                        <option value="turkish" >Turkish</option>
                        <option value="german">German</option>
                        <option value="spanish">Spanish</option>
                    </select>
                </div>
                <div class="tab-wrap">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        	@foreach($categories as $key => $category)
                            	<a class="nav-item nav-link @if($key==0) active @endif" data-id="{{$category->id}}" id="nav-{{$category->category_name}}-tab" data-toggle="tab" href="#nav-{{$category->category_name}}" role="tab" aria-controls="nav-{{$category->category_name}}" aria-selected="true">{{$category->category_name}}</a>
                            @endforeach
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                    	@foreach($categories as $key => $category)
                        <div class="tab-pane fade show @if($key==0) active @endif" id="nav-{{$category->category_name}}" role="tabpanel" aria-labelledby="nav-{{$category->category_name}}-tab">
                            <div class="content">
                                <div class="search-wrap">
                                    <p>Select Websites channels that you want to add to Cryptonews feed</p>
                                    {{-- <div class="search-input">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 12 12">
                                            <path id="search-icon.4645e857" d="M8.576,7.547H8.034l-.192-.185a4.458,4.458,0,1,0-.479.48l.185.192v.542L10.977,12,12,10.978,8.576,7.548Zm-4.116,0a3.083,3.083,0,1,1,2.184-.9A3.083,3.083,0,0,1,4.46,7.547Z" fill="#8496a3"/>
                                        </svg>                                              
                                        <input type="text" class="form-control" placeholder="Type to filter">
                                    </div> --}}
                                </div>
                                <div class="select-item-wrap">
                                    <select multiple="multiple" size="10" name="subscribed-{{$category->id}}" class="dual_filter" title="duallistbox_demo2">
                                    	@foreach($category->feeds as $feeds_data)
                                        	<option @if($feeds_data->isFeedEnabled()) selected @endif value="{{$feeds_data->id}}">{{$feeds_data->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="share-wrap">
                                    <p>Do you know valuable source of information?</p>
                                    <div class="inpur-wrap">
                                        <input type="text" class="form-control" placeholder="Share link with us">
                                        <button type="submit" class="button purple-button">Send</button>
                                    </div>                                    
                                </div>                                
                            </div>
                            <div class="modal-footer">
                                <div  class=" response_message alert alert-success"></div>
                                <button type="button" class="button ash-button" data-dismiss="modal">Close</button>
                                <button type="button" id="{{$category->id}}" class="apply-button button primary-button">Apply</button>
                            </div>
                        </div>                           
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
	<div id="myModal" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">News Feeds</h4>
				</div>
				<div class="modal-body">
						<div class="">
							<div class="row">
								<div class="col-12">
									<div class="table-responsive">

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
												@foreach($all_feeds as $feed)

													<tr>
														<td>{{$feed->name}}</td>
														<td>{{$feed->source}}</td>
														<td>{{$feed->language}}</td>
														<td><a ><button id="{{$feed->id}}" class="add-feed fa @if((Auth::user()->feed_exist($feed->id))) fa-minus-circle @else fa-plus @endif "></button></a></td>
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
				</div>
				<div class="modal-footer">
					<button type="button" class="modal-close btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<div class="content-wrapper height-100">
		<div class="feed-list dashboard">
			
			
				<div class="container-fluid">
					<div class="row">
						<div class="col-12">
							<div class="clearfix">
								
							</div>
							
							<div class="advance-search">
								<form class="row advance-search-form form-search">
									<div class="col">
										<div class="check-box-wrap d-flex align-items-center flex-wrap feed_set">
											@if(isset($categories))
												@foreach($categories as $category)
														<div class="custom-control custom-checkbox custom-control-inline ">
																<input type="checkbox" value="{{$category->id}}" name="type" class="custom-control-input feed_type" id="{{$category->category_name}}">
														    <label class="custom-control-label" for="{{$category->category_name}}">{{$category->category_name}}</label>
														</div>
												@endforeach
											@endif													
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<input type="text" value="" id="tags-input" data-role="tagsinput" placeholder="Search..." />
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<select class="form-control search-input" id="feed_time">
												<option  selected value="0">Select filter</option>
												<option value="1">In the last hour</option>
												<option value="24">In the last 24 hour</option>
												<option value="48">In the last 48 hour</option>
												<option value="7">In the last week</option>		
											</select>			
										</div>
									</div>								

									<div class="col">
										<button type="button" data-toggle="modal" data-target="#filter_source" class="button  plus-btn" title="Minimize Widget">
											<svg xmlns="http://www.w3.org/2000/svg" width="31.5" height="31.5" viewBox="0 0 31.5 31.5">
												<path id="Icon_awesome-plus" data-name="Icon awesome-plus" d="M29.25,14.625H19.125V4.5a2.25,2.25,0,0,0-2.25-2.25h-2.25a2.25,2.25,0,0,0-2.25,2.25V14.625H2.25A2.25,2.25,0,0,0,0,16.875v2.25a2.25,2.25,0,0,0,2.25,2.25H12.375V31.5a2.25,2.25,0,0,0,2.25,2.25h2.25a2.25,2.25,0,0,0,2.25-2.25V21.375H29.25a2.25,2.25,0,0,0,2.25-2.25v-2.25A2.25,2.25,0,0,0,29.25,14.625Z" transform="translate(0 -2.25)">
													
												</path>
											</svg>
									</button>
							</div>

							<div class="col">
								<div class="form-group">
									<div class=option-wrap>
										<div class="notification-switch">
											<input type="checkbox" class=""   id="notification" {{(Auth::user()->notification_sound==true) ? 'checked' : ''}}>
											<label class="" for="notification">
											  <svg xmlns="http://www.w3.org/2000/svg" width="22.493" height="28.132" viewBox="0 0 22.493 28.132">
												  <g id="Icon_ionic-ios-notifications" data-name="Icon ionic-ios-notifications" transform="translate(-6.761 -3.93)">
													  <path id="Path_6" data-name="Path 6" d="M17.993,32.063c2.187,0,3.382-1.547,3.382-3.727H14.6C14.6,30.516,15.8,32.063,17.993,32.063Z" fill="#5a77ff"/>
													  <path id="Path_7" data-name="Path 7" d="M28.969,24.764c-1.083-1.427-3.213-2.264-3.213-8.655,0-6.56-2.9-9.2-5.6-9.83-.253-.063-.436-.148-.436-.415v-.2a1.716,1.716,0,1,0-3.431,0v.2c0,.26-.183.352-.436.415-2.707.64-5.6,3.27-5.6,9.83,0,6.391-2.13,7.221-3.213,8.655A1.4,1.4,0,0,0,8.163,27H27.858A1.4,1.4,0,0,0,28.969,24.764Z" fill="#5a77ff"/>
												  </g>
											  </svg>
											</label>
										</div>		
									</div>
								</div>
							</div>
							
							
							<div class="col-12">

							</div>
								</form><!-- Form Ends Here -->
						</div>
					</div>
					@if(isset($feed_count))
						<input type="hidden" name="feed_count" value="{{ $feed_count }}">
					@endif 
					<audio id="audio" src="" autoplay="false" ></audio> {{-- http://cryptoscannerpro.com/iphone_text_message.wav --}}
					<div class="col-12">
						<span class="search-show-icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="27" height="18" viewBox="0 0 27 18">
								<path id="Icon_material-filter-list" data-name="Icon material-filter-list" d="M15,27h6V24H15ZM4.5,9v3h27V9ZM9,19.5H27v-3H9Z" transform="translate(-4.5 -9)" fill="#fff"/>
							</svg>
						</span>
						<div class="feed-list-box">
							<ul class="drag-list drag-true news-list">
								@if(isset ($message2))	
									<div class="error" >
																
										<label class="success">{{ $message2}}</label> 
										{{-- <button type="button" data-toggle="modal" data-target="filter_source" class="button  plus-btn" title="Minimize Widget">
											<svg xmlns="http://www.w3.org/2000/svg" width="31.5" height="31.5" viewBox="0 0 31.5 31.5">
												<path id="Icon_awesome-plus" data-name="Icon awesome-plus" d="M29.25,14.625H19.125V4.5a2.25,2.25,0,0,0-2.25-2.25h-2.25a2.25,2.25,0,0,0-2.25,2.25V14.625H2.25A2.25,2.25,0,0,0,0,16.875v2.25a2.25,2.25,0,0,0,2.25,2.25H12.375V31.5a2.25,2.25,0,0,0,2.25,2.25h2.25a2.25,2.25,0,0,0,2.25-2.25V21.375H29.25a2.25,2.25,0,0,0,2.25-2.25v-2.25A2.25,2.25,0,0,0,29.25,14.625Z" transform="translate(0 -2.25)">
													
												</path>
											</svg>									
										</button> --}}
										<button type="button" class="button primary-button " data-toggle="modal" data-target="#filter_source">Click here to add feeds</button>
									</div>
								@endif	
								@if(isset($message))
									<li class="c">{{$message}}</li>
								@endif	
								@if(isset($feeds))
									@foreach($feeds as $single_feed)
										<li id="feed-{{$single_feed->id}}" class="">
											<a href="{{$single_feed->link}}" target="_blank">
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
								@endif
							</ul>
						</div>
					</div>
				</div>
			
		</div>
	</div>
@endsection
@section('scripts')
@parent
<!-- <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script src="https://davidstutz.de/bootstrap-multiselect/dist/js/bootstrap-multiselect.js"></script> -->
<script type="text/javascript">
		$('.response_message').hide();
		$('#language1').change(function(){
			$('#language').hide();
			id = $(".nav-item.active").data('id');
			language=$("#language").val();
			console.log(language);
			$.ajax({
			
				data:{'id':id,'language':language},
				dataType:'json',
				type:'post',
				success:function(obj){
					//console.log(data);
					
					$.each(obj.result, function(k, v) {
						if(v.enabled==true)
						{
							enable='selected';

						}else
						{
							enable='';
						}

						$('#bootstrap-duallistbox-selected-list_subscribed-1').append('<option '+enable+' value="'+v.id+'">'+v.name+'</option>');
						
					});
			            
			        var demo2 = $('.dual_filter').bootstrapDualListbox({
			            nonSelectedListLabel: 'Non-selected',
			            selectedListLabel: 'Selected',
			            bootstrap2compatible: true
			        });

			        demo2.trigger('bootstrapDualListbox.refresh' , true);     
				}
			});



		
	});



	    $('#filter_source').on('shown.bs.modal', function () {
	       
	    	/*$.ajax({
	    			url:"{{route('FeedSettingModal')}}",
	    			type: 'POST',
	    			dataType: 'json',
	    			//data:{'tag':tag,'time':time,'type':type,'load':load},
	    			success: function(obj)
	    			{
	    				//$('.drag-list').html('');
	    				$.each(obj.categories, function(k, v) {
	    					console.log(v);
	    					$("input[name=feed_count]").val(v.last_update);
	    					$('.drag-list').append('<li id="feed-'+v.id+'" class=""><a href="'+v.link+'"  target="_blank"><span class="ago-time">'+v.time+'</span><h2>'+v.title+' </h2><h5><span><img src="https://cryptoscannerpro.com/admin/images/feeds.png"></span>'+v.feed_provider+'&nbsp;<span><img src="https://cryptoscannerpro.com/admin/images/calendar.png"></span>'+v.pub_date+'&nbsp;<span><img src="https://cryptoscannerpro.com/admin/images/clock.png"></span>'+v.pub_time+'</h5></a></li>');

	    				});
	    				//localStorage.setItem('load_count', parseInt(localStorage.getItem('load_count'))+50);
	    			},
	    			complete: function(){
	    			        //$('#loader').remove();
	    			      }
	    	});	   */  		
	        var demo2 = $('.dual_filter').bootstrapDualListbox({
	            nonSelectedListLabel: 'Non-selected',
	            selectedListLabel: 'Selected',
	            bootstrap2compatible: true
	        });

	        demo2.trigger('bootstrapDualListbox.refresh' , true);
	    });

	   
	    $('.apply-button').on('click', function () {

	    	category=$(this).attr('id');
	    	values=$(this).parent().parent().find('.dual_filter').val();
	    	$.ajax({
	    			url:"{{route('SaveFeedSetting')}}",
	    			type: 'POST',
	    			dataType: 'json',
	    			data:{'category':category,'values':values},
	    			success: function(obj)
	    			{
	    				$('.response_message').html('Changes Saved');
	    				$('.response_message').show().delay(1000).fadeOut();
	    				
	    				

	    			},
	    			complete: function(){

	    				category=$('#feed_cat').val();
	    				tag=$('#tags-input').val();
	    				time=$('#feed_time').val();
	    				load=localStorage.getItem('load_count');
	    				var type = [];
	    				$("input:checkbox[name=type]:checked").each(function() {
	    					type.push($(this).val());
	    				});
	    				console.log(category+'='+tag+'='+time+'='+type);
	    				$.ajax({
	    					url:"{{route('FeedAjaxTestBlank')}}",
	    					type: 'POST',
	    					dataType: 'json',
	    					data:{'category':category,'tag':tag,'time':time,'type':type,'load':load},
	    					success: function(obj)
	    					{ 
	    						//console.log('ll'+obj.nodata);
	    						var m=obj.message;
	    							//$('.drag-list').hide();
	    						if(typeof(m) != "undefined" && m !== null)
	    						{
	    						console.log('bb'+obj.message);
	    							$('.drag-list').html(m);
	    						}
	    						else if(typeof(obj.nodata) !="undefined" && (obj.nodata) != null)
	    						{
	    							console.log('cc'+obj.nodata);

	    							$('.drag-list').html(obj.nodata);
	    						}
	    						else 
	    						{
	    							$('.drag-list').html("");
	    							$.each(obj.result, function(k, v) {

	    								$("input[name=feed_count]").val(v.last_update);
	    								$('.drag-list').append('<li id="feed-'+v.id+'" class=""><a href="'+v.link+'"  target="_blank"><span class="ago-time">'+v.time+'</span><h2>'+v.title+' </h2><h5><span><img src="https://cryptoscannerpro.com/admin/images/feeds.png"></span>'+v.feed_provider+'&nbsp;<span><img src="https://cryptoscannerpro.com/admin/images/calendar.png"></span>'+v.pub_date+'&nbsp;<span><img src="https://cryptoscannerpro.com/admin/images/clock.png"></span>'+v.pub_time+'</h5></a></li>');
	    							});			
	    						}    
	    					},
	    				});
	    			        
	    			}
	    	});	
	    	
	    	
	    });
	   
	     
	
	$('#notification').on('change', function() {
		if($(this).prop("checked") == true)
		{
			notification=1;
		}
		if($(this).prop("checked") == false)
		{
			notification=0;
		}
		$.ajax({
				url:"{{route('NotificationSoundAjax')}}",
				type: 'POST',
				dataType: 'json',
				data: {'notification':notification},
				success: function(response)
				{
					console.log(response);
				}			
			});
	});

	/*Preloader script start here*/
	localStorage.clear();
	localStorage.setItem('load_count', 100);
	$(window).scroll(function() {
	    if($(window).scrollTop() == $(document).height() - $(window).height()) {
	    	if(!$('#loader').length)
	    	{
				$('.drag-list').append('<li id="loader">Loading..</li>');
			}	
	    	load= parseInt(localStorage.getItem('load_count'))+1;
	    	//load=localStorage.setItem('load_count', parseInt(localStorage.getItem('load_count'))+50);
	     	tag=$('#tags-input').val();
	     	time=$('#feed_time').val();

	     	var type = [];
	     	$("input:checkbox[name=type]:checked").each(function() {
	     			type.push($(this).val());
	     	});
	     	//console.log(tag+'='+time+'='+type);

	     	$.ajax({
	     		url:"{{route('PreLoader')}}",
	     		type: 'POST',
	     		dataType: 'json',
	     		data:{'tag':tag,'time':time,'type':type,'load':load},
	     		success: function(obj)
	     		{
	     			//$('.drag-list').html('');
	     			$.each(obj.result, function(k, v) {
	     				$("input[name=feed_count]").val(v.last_update);
	     				$('.drag-list').append('<li id="feed-'+v.id+'" class=""><a href="'+v.link+'"  target="_blank"><span class="ago-time">'+v.time+'</span><h2>'+v.title+' </h2><h5><span><img src="https://cryptoscannerpro.com/admin/images/feeds.png"></span>'+v.feed_provider+'&nbsp;<span><img src="https://cryptoscannerpro.com/admin/images/calendar.png"></span>'+v.pub_date+'&nbsp;<span><img src="https://cryptoscannerpro.com/admin/images/clock.png"></span>'+v.pub_time+'</h5></a></li>');

	     			});
	     			localStorage.setItem('load_count', parseInt(localStorage.getItem('load_count'))+50);
	     		},
	     		complete: function(){
	     		        $('#loader').remove();
	     		      }
	     	});
	    }
	});	
	/*Preloader script end here*/
	setInterval(function()
	{
		var feed_count=$("input[name=feed_count]").val();
		category=$('#feed_cat').val();
		tag=$('#feed_tag').val();
		time=$('#feed_time').val();			
		var type = [];
		$("input:checkbox[name=type]:checked").each(function() {
				type.push($(this).val());
		});

		$.ajax({
			type:"post",
			url:"{{route('FeedAjax')}}",
			datatype:"json",
			data: {'feed_count':feed_count,'category':category,'tag':tag,'time':time,'type':type},		
			success:function(data)
			{
				obj=JSON.parse(data);
				if(obj.result!='0') /*only if new data present*/
				{
					$.each(obj.result, function(k, v)
					{
						if(v.sound==1)
						{
								$('#audio').attr('src','https://cryptoscannerpro.com/iphone_message.wav');
								var sound = document.getElementById("audio");
								sound.play();
						}		
						$("input[name=feed_count]").val(v.last_update);
						$('.drag-list').prepend('<li  id="feed-'+v.id+'" class="c '+v.time_class+v.tags+v.feed_name+v.feed_category+'"><a href="'+v.link+'"  target="_blank" ><span class="ago-time">'+v.time+'</span><h2>'+v.title+' </h2><h5><span><img src="https://cryptoscannerpro.com/admin/images/feeds.png"></span>'+v.feed_provider+'&nbsp;<span><img src="https://cryptoscannerpro.com/admin/images/calendar.png"></span>'+v.pub_date+'&nbsp;<span><img src="https://cryptoscannerpro.com/admin/images/clock.png"></span>'+v.pub_time+'</h5></a></li>');
					});		
				}	
			}	
		});			
	}, 20000);//time in milliseconds	
	setInterval(function()
	{
		load=localStorage.getItem('load_count');
		category=$('#feed_cat').val();
		tag=$('#tags-input').val();
		time=$('#feed_time').val();
		var type = [];
		$("input:checkbox[name=type]:checked").each(function() {
			type.push($(this).val());
		});
		console.log(category+'='+tag+'='+time+'='+type);
		$.ajax({
			url:"{{route('FeedAjaxTest')}}",
			type: 'POST',
			dataType: 'json',
			data:{'category':category,'tag':tag,'time':time,'type':type,'load':load},
			success: function(obj)
			{
				console.log(obj.message);
				var m=obj.message;
					//$('.drag-list').hide();
				if(typeof(obj.nodata) !="undefined" && (obj.nodata) != null)
				{
					console.log('cc'+obj.nodata);

					$('.drag-list').html('test');
				}
				if(typeof(m) != "undefined" && m !== null)
				{
					
					$('.drag-list').html(m);
				}
				else 
				{
					$('.drag-list').html("");
					$.each(obj.result, function(k, v) {

						$("input[name=feed_count]").val(v.last_update);
						$('.drag-list').append('<li id="feed-'+v.id+'" class=""><a href="'+v.link+'"  target="_blank"><span class="ago-time">'+v.time+'</span><h2>'+v.title+' </h2><h5><span><img src="https://cryptoscannerpro.com/admin/images/feeds.png"></span>'+v.feed_provider+'&nbsp;<span><img src="https://cryptoscannerpro.com/admin/images/calendar.png"></span>'+v.pub_date+'&nbsp;<span><img src="https://cryptoscannerpro.com/admin/images/clock.png"></span>'+v.pub_time+'</h5></a></li>');
					});			
				}    
			}
		});
						
	}, 60000);	

	$('#feed_cat,#feed_tag,#feed_time,input:checkbox[name=type],#tags-input').on('change', function() {
		$('.drag-list').html('Loading..');
		localStorage.clear();
		localStorage.setItem('load_count', 100);
		category=$('#feed_cat').val();
		tag=$('#tags-input').val();
		time=$('#feed_time').val();
		load=localStorage.getItem('load_count');
		var type = [];
		$("input:checkbox[name=type]:checked").each(function() {
			type.push($(this).val());
		});
		console.log(category+'='+tag+'='+time+'='+type);
		$.ajax({
			url:"{{route('FeedAjaxTest')}}",
			type: 'POST',
			dataType: 'json',
			data:{'category':category,'tag':tag,'time':time,'type':type,'load':load},
			success: function(obj)
			{
				console.log(obj.message);
				var m=obj.message;
				if(typeof(m) != "undefined" && m !== null)
				{
					$('.drag-list').html(m);
				}
				else 
				{
					$('.drag-list').html("");
					$.each(obj.result, function(k, v) {

						$("input[name=feed_count]").val(v.last_update);
						$('.drag-list').append('<li id="feed-'+v.id+'" class=""><a href="'+v.link+'"  target="_blank"><span class="ago-time">'+v.time+'</span><h2>'+v.title+' </h2><h5><span><img src="https://cryptoscannerpro.com/admin/images/feeds.png"></span>'+v.feed_provider+'&nbsp;<span><img src="https://cryptoscannerpro.com/admin/images/calendar.png"></span>'+v.pub_date+'&nbsp;<span><img src="https://cryptoscannerpro.com/admin/images/clock.png"></span>'+v.pub_time+'</h5></a></li>');
					});			
				}    
			}
		});
	});
</script>



<script>
	$(document).ready(function(){
		$('.add-feed').on("click",function(e){
			e.preventDefault();
			id=$(this).attr('id');

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
			$.ajax({
				url:"{{route('AddUserFeedAjax')}}",
				type: 'POST',
				dataType: 'json',
				data:{id,id},
				success: function(response)
				{
					//console.log(response);
					var len = response.length;
					$("#feed_cat").empty();
					for( var i = 0; i<len; i++){
						var id = response[i]['id'];
						var name = response[i]['name'];
						$("#feed_cat").append("<option class='feed-"+name+"' value='"+name+"'>"+name+"</option>");		
					}
					$("#feed_cat").trigger("change");
				}	
			});	
		});
	});
	
</script>




<!-- auto complete search -->
<script src="{{asset('admin/js/typeahead.js')}}"></script>
<script src="{{asset('admin/js/bootstrap-tagsinput.js')}}"></script>
<script>
	var countries = new Bloodhound({
	  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
	  queryTokenizer: Bloodhound.tokenizers.whitespace,
	  prefetch: {
		url: "{{asset('admin/data/countries.json')}}",
		filter: function(list) {
		  return $.map(list, function(name) {
			return { name: name }; });
		}
	  }
	});
	countries.initialize();

	$('#tags-input').tagsinput({
	  typeaheadjs: {
		name: 'countries',
		displayKey: 'name',
		valueKey: 'name',
		source: countries.ttAdapter()
	  }
	});
</script>




<script>
		$('.search-show-icon').click(function() {
			$(this).toggleClass('active');
			$('.advance-search').toggleClass("open");
				});





</script>




@endsection
