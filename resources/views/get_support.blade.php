@extends('layouts.site')
@section('content')




<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css" type="text/css">

		        <!-- Banner HTML Starts Here -->
		        <div class="inner-banner alerts-log-page">
		            <div class="inner-banner-center">
		                <div class="container">
		                    <div class="row justify-content-md-center">
		                        <div class="col-md-8 col-sm-12  text-center">
		                            <h2>HOW CAN WE HELP YOU?</h2>
		                            <div class="mt-2">
		                                <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.</p>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        </div>
		        <!-- Banner HTML Ends Here -->

						<!-- refer  HTML Starts Here -->
		<div class="refer-warp">
			<div class="container">
				<div class="row justify-content-center">

					<div class="col-md-10 col-lg-7">
						<h2 class="text-left">Contact form</h2>
						<p>Below you will find contact form. </p>

						<div class="contact-form">

							<div class="row">
								<div class="col-md-12 mt-3">
									<label class="control-label" for="textinput">Full name</label>
									<input name="textinput" type="text" placeholder="Name" class="form-control">
								</div>
								<div class="col-md-6 mt-3">
									<label class="control-label" for="textinput">Phone</label>
									<input name="textinput" type="text" placeholder="Phone"
										class="form-control  phone-format">
								</div>
								<div class="col-md-6 mt-3">
									<label class="control-label" for="textinput">Email</label>
									<input name="textinput" type="email" placeholder="Email" class="form-control">
								</div>

								<div class="col-md-6 mt-3">
									<label class="control-label" for="textinput">Help for </label>
									<select name="Category" class="form-control" aria-required="true" aria-invalid="false">
										<option value="Help with account">Help with account</option>
										<option value="Help with Cryptonews">Help with Cryptonews</option>
										<option value="Help with Cryptoscanner">Help with Cryptoscanner</option>
										<option value="Promotions">Promotions</option>
										<option value="Development suggestions">Development suggestions</option>
										<option value="Business Partnership">Business Partnership</option>
										<option value="Other">Other</option>
									</select>
								</div>

								<div class="col-md-6 mt-3">
									<label class="control-label" for="textinput">Subject</label>
									<input name="textinput" type="text" placeholder="Subject" class="form-control">
								</div>

								<div class="col-md-12 mt-3">
									<label class="control-label" for="textinput">Message </label>
									<textarea class="form-control" placeholder="Write Message "></textarea>
								</div>
								<div class="col-md-12 mt-3">
									<input type="submit" class="form-control contact-sub btn trs" value="Send Email">
								</div>
							</div>


						</div>






					</div>


				</div>
			</div>
		</div>
		<!-- refer HTML Ends Here -->
 

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

