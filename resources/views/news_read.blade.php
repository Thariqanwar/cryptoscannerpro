@extends('layouts.site')
@section('content')




<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css" type="text/css">

		        <!-- Banner HTML Starts Here -->
		        <div class="inner-banner alerts-log-page">
		            <div class="inner-banner-center">
		                <div class="container">
		                    <div class="row justify-content-md-center">
		                        <div class="col-md-8 col-sm-12  text-center">
		                            <h2>News</h2>
		                            <div class="mt-2">
		                                <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.</p>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        </div>
		        <!-- Banner HTML Ends Here -->

		     		<!-- faq List HTML Starts Here -->
		<div class="faq-warp">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<h2>{{$feed->title}} </h2>
						<h3>{!!unserialize($feed->description)!!}</h3>
					</div>


				</div>
			</div>
		</div>
		<!-- faq List HTML Ends Here -->
		<script>
		$(function () {
			$('.acc_ctrl').on('click', function (e) {
				e.preventDefault();
				if ($(this).hasClass('active')) {
					$(this).removeClass('active');
					$(this).next()
						.stop()
						.slideUp(300);
				} else {
					$(this).addClass('active');
					$(this).next()
						.stop()
						.slideDown(300);
				}
			});
		});
	</script>
	
    
@endsection		

