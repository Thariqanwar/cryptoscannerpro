@extends('layouts.site')
@section('content')




<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css" type="text/css">

		        <!-- Banner HTML Starts Here -->
		        <div class="inner-banner alerts-log-page">
		            <div class="inner-banner-center">
		                <div class="container">
		                    <div class="row justify-content-md-center">
		                        <div class="col-md-8 col-sm-12  text-center">
		                            <h2>FAQ</h2>
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
						<h2>How can we help? </h2>

						<div class="search-box">

							<div class="input-group">
								<input type="text" class="search-field" placeholder="Search...">
								<span class="input-group-btn">
									<button class="btn search-btn" type="button">
										<i class="fas fa-search"></i>
									</button>
								</span>
							</div>
						</div>

						<h3>Select your issue category</h3>

						<ul class="issue-category-list">
							<li>
								<a href="#">
									<span class="issue-category-icon">
										<i class="fas fa-balance-scale"></i>	
									</span>
									Billing

								</a>
							</li>
							<li>
								<a href="#">
									<span class="issue-category-icon">
										<i class="fab fa-buffer"></i>
									</span>
									Data

								</a>
							</li>
							<li>
								<a href="#">
									<span class="issue-category-icon">
										<i class="fas fa-chart-line"></i>
									</span>
									Chart
								</a>
							</li>
							<li>
								<a href="#">
									<span class="issue-category-icon">
										<i class="fas fa-draw-polygon"></i>
									</span>
									Trading

								</a>
							</li>
							<li>
								<a href="#">
									<span class="issue-category-icon">
										<i class="fas fa-transgender-alt"></i>
									</span>
									Alerts

								</a>
							</li>
							<li>
								<a href="#">
									<span class="issue-category-icon">
										<i class="fas fa-code-branch"></i>	
									</span>
									Pine Script 
								</a>
							</li>
							<li>
								<a href="#">
									<span class="issue-category-icon">
										<i class="fab fa-acquisitions-incorporated"></i>	
									</span>
									Screener

								</a>
							</li>
							<li>
								<a href="#">
									<span class="issue-category-icon">
										<i class="fas fa-balance-scale"></i>	
									</span>
									Social network 
								</a>
							</li>
							<li>
								<a href="#">
									<span class="issue-category-icon">
										<i class="fas fa-list-ul"></i>
									</span>
									Watchlist 
								</a>
							</li>
							<li>
								<a href="#">
									<span class="issue-category-icon">
										<i class="fab fa-app-store"></i>
									</span>
									Mobile apps 
								</a>
							</li>
						</ul>


						<h3 class="mt-5">Popular questions</h3>


				 



						<div class="faq-warp-in">
							<ul class="acc">
								<li>
									<button class="acc_ctrl">
										<h4>I’ve lost my chart drawings</h4>
									</button>
									<div class="acc_panel">
										<p>Drawings are often lost when one chart layout is overridden by a different version of the same layout. This is a very common case and typically happens when you use the same chart layout in multiple tabs or on several devices. Note that this may happen even when you use different symbols. The changes that you apply to an active chart layout are not automatically synced across open chart layouts or different versions of the same layout. <br>

											Here a scenario where drawings are lost. <br>
											
											You've opened a chart layout through a link — https://www.tradingview.com/chart/<id> on your computer & smartphone simultaneously.<br>
											You've drawn some trend lines on the chart for NYSE:IBM ticker symbol and saved the layout.<br>
											You then decided to look up FX:EURUSD ticker symbol and switched to it while using the same chart layout.<br>
											Afterward, you either saved your chart layout manually on the smartphone or an autosave was triggered. <br>
											If you now decide to refresh your chart layout page then you won't be able to find the trend lines that you drew earlier as your chart layout was overridden the moment it was saved on your smartphone. 
											<br>
											Here is what we recommend to avoid this from happening. <br>
											
											Don't work with the same chart layout on several devices or in several browser tabs simultaneously. 
											Don't refresh the chart layout page (restart the mobile app) when you continue working with it on a different device or in a different browser tab.
											Turn autosave off in the chart layout menu.</p>
									</div>
								</li>
								<li>
									<button class="acc_ctrl">
										<h4>I want to access Extended Hours data</h4>
									</button>
									<div class="acc_panel">
										<p>To get access to Extended Hours data, you must have either a PRO+ or a Premium subscription to TradingView. If you have one of those, you can display the Extended Hours data on intraday charts either by clicking on the Ext button in the bottom right corner or in the Symboltab in the chart settings. There you can also change or remove the tint that highlights Extended Hours data on the chart by default.
											<br>
											Please note that not all exchanges have Extended Hours data. If no Extended Hours data for the symbol is available, the Ext button will not be shown in the bottom right corner of the chart.
											<br>
											However, the Extended Hours option in the Chart settings will still be present, but it will not change anything, if the Extended Hours data for that symbol is not available.</p>
									</div>
								</li>
								<li>
									<button class="acc_ctrl">
										<h4>I forgot my username/password and would like to restore access to TradingView</h4>
									</button>
									<div class="acc_panel">
										<p>If you don't remember your username or password try getting it from the browser.
<br>
											If that doesn't help click the Forgot password or can’t sign in button in the sign in dialog.</p>
									</div>
								</li>
							</ul>

						</div>


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

