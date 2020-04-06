@extends('layouts.admin')


@section('content')
	<div class="content-wrapper height-100">


trai

	    <!-- Feeds List HTML Starts Here -->
	    <div class="feed-list dashboard">
	        <div class="container-fluid">
	            <div class="row">
	                <div class="col-12">
	                    <div class="advance-search">
	                        <!-- Form Starts Here -->
	                        <form class="row equal form-search">
	                            <div class="col-lg-3 col-md-6">
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
	                            </div>
	                        </form>
	                        <!-- Form Ends Here -->
	                    </div>
	                </div>

	                <div class="col-12">
	                    <ul class="drag-list drag-true news-list">
	                    	@foreach($feeds as $feed)
	                        <li>
                                <a href="{{$feed->link}}">
                                    <span class="ago-time">17min</span>
                                    <h2>{{$feed->title}}</h2>
                                    <h5> 
                                        <span><img src="{{asset('admin/images/feeds.png')}}"></span> {{isset($feed->feed->name) ? $feed->feed->name : ''}} &nbsp; 
                                        <span><img src="{{asset('admin/images/calendar.png')}}"></span>  {{isset($feed->pub_date) ? $feed->pub_date : ''}} &nbsp; 
                                        <span><img src="{{asset('admin/images/clock.png')}}"></span>08:29:46 +0000
                                    </h5>
                                </a>
	                        </li>
	                       	@endforeach	                       
	                    </ul>
	                </div>
	            </div>
	        </div>
	    </div>
	    <!-- Feeds List HTML Ends Here -->

	</div>
 	   

@endsection
