<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Crypto Scanner PRO - Design to Assist | More Efficient Trading Decisions</title>

	<!-- Google Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700&display=swap" rel="stylesheet">
	<!-- CSS -->
	<link rel="stylesheet" href="{{asset('crypto/css/styles.css')}}" type="text/css">
	<!-- jQuery -->
	<script src="{{asset('crypto/js/jquery.min.js')}}"></script>

</head>
<body class="pushmenu-push">
	<div class="wrapper"> 

		<!-- Header One HTML Starts Here --> 
		<header>
			<div class="container-fluid"> 
				<div class="row">
					<div class="col-12">
						<div class="menu-row">

							<div class="logo-wrap">
								<a href="{{route('Front')}}">
									<img src="{{asset('crypto/images/csp-logo.png')}}" class="img-fluid" alt="Logo">
								</a>								
							</div>
						

							<div class="menu-right">

								
							<div class="menu-wrap">							
								<div id="nav_list" class="menu-mobile-icon">
									<div id="toggle-icon">
										<span></span>
										<span></span>
										<span></span>
										<span></span>
									</div>
								</div>
								<div class="main-navigation pushmenu pushmenu-left">
									<nav id="nav" class="nav">
										<ul class="main-menu-list">
											<!-- <li><a href="{{route('Front')}}" class="active">Home</a></li>  -->
											<li><a  data-scroll href="#about">About</a></li>
											<li><a  data-scroll href="#contact">Contact </a></li>
											<li><a  data-scroll href="#pricing">Pricing </a></li>
											<li><a  data-scroll href="#blog">Blog </a></li>
										</ul>
									</nav>
								</div>
							</div>


								<div class="button-wrap">
									<a href="{{route('login')}}" class="button primary-button"><i class="fas fa-sign-in-alt"></i> Sign in</a>
									<a href="{{route('register')}}" class="button primary-button"><i class="fas fa-user-plus"></i> Sign Up</a>
									<!-- <div class="dropdown">
										<button class="dropbtn">
											<span class="image-wrap"><img src="{{asset('crypto/images/image-34.jpg')}}" class="img-fluid" alt="image" /></span><span class="name">Liddy Lyddie</span>
										</button>
										<div class="dropdown-content">
											<a href="#">Profile</a>
											<a href="#">Logout</a>
										</div>
									</div> -->
								</div>
							</div>
						
						</div>
					</div>
				</div>
			</div>
		</header>
		{{-- content start here --}}
		@yield('content')
		{{-- content end here --}}
		<!-- Footer HTML Starts Here -->
		<footer class="footer-one">
			<!-- Top Footer HTML Starts Here -->
			<div class="footer-top">
				<div class="container">
					<div class="row justify-content-md-center">
					
					<div class=" col-sm-12  text-center">
						<!-- <h2>Create an account and start your Digital
							Currency Portfolio today!</h2> -->
							<p> CRYPTO SCANNER PRO IS A COMPREHENSIVE TOOL THAT HELPS TO UNDERSTAND FUNDAMENTALS OF TRADING. IT IS NOT INTENDED TO BE TRADING OR INVESTING ADVICE. THE PLATFORM DOES NOT RECOMMEND ANY CRYPTO TO BUY OR SELL,&nbsp; DESIGNED TO ASSIST IN MAKING DECISIONS. THE PLATFORM, ANALYSIS AND MARKET DATA IS PROVIDED ‘AS-IS’ AND WITHOUT WARRANTY. INVEST WISELY. ALWAYS DO COMPREHENSIVE RESEARCH BEFORE TRADING OR INVESTING.</p>
		 
					</div>
					</div>
					 

					<div class="row  mt-3 text-center">
						<div class="col-lg-12   mt-3">

						<ul class="footer-links">
							<li>
								<a href="{{route('terms_of_use')}}">Terms of Use</a> 
							</li>
							<li>
							<a href="{{route('privacy_policy')}}">Privacy Policy</a> 
							</li>
							<li>
							<a href="{{route('cookies_policy')}}">Cookies Policy</a> 	 
							</li>
							<li>
							<a href="{{route('referal_agreement')}}">Referral Agreement</a> 
							</li>
							<li>
							<a href="{{route('disclaimer')}}">Disclaimer</a>  
							</li>
						</ul> 

						</div> 
					</div>

				 
 

					</div>
				</div>
			</div>
			<!-- Top Footer HTML Starts Here -->


 


			<div class="footer-bottom">
			<div class="container ">
				<div class="row">
					<div class="col-12 text-center">  
					<div class="startTyper"></div> 
					</div>
				</div>
			</div>
			
			</div>


		</footer>
		<!-- Footer HTML Ends Here -->


		<a href="#" id="back-to-top" title="Back to top">&uarr;</a>

	</div>
	<!-- Ends Wrapper -->

 

	<!-- Custom Javascript -->
	
	<script src="{{asset('crypto/js/jquery-ui.min.js')}}"></script>
	<script src="{{asset('crypto/js/popper.min.js')}}"></script>
	<script src="{{asset('crypto/js/bootstrap.min.js')}}"></script>
	<script src="{{asset('crypto/js/TweenMax.min.js')}}"></script>
	<script src="{{asset('crypto/js/Draggable.min.js')}}"></script>
	<script src="{{asset('crypto/js/drag-arrange.min.js')}}"></script>
	<script src="{{asset('crypto/js/moment.min.js')}}"></script>
    <script src="{{asset('crypto/js/daterangepicker.min.js')}}"></script>
    <script src="{{asset('crypto/js/jquery.dataTables.min.js')}}"></script>
	<script src="{{asset('crypto/js/dataTables.bootstrap4.min.js')}}"></script>
 
	
	<!-- Custom Script -->
 <script src="{{asset('crypto/js/custom.js')}}"></script>  
		

 <script>
        // alert(windowHeight);
        var a = 0;
        $(window).scroll(function () {
            var oTop = $('#counter').offset().top - window.innerHeight;
            if (a == 0 && $(window).scrollTop() > oTop) { 
				countUp(); 
				a = 1; 
			} 
		});
 
function countUp() { 
	$('.counter-value').each(function () { 
                    var $this = $(this),
                        countTo = $this.attr('data-count');
                    $({
                        countNum: $this.text()
                    }).animate({
                            countNum: countTo
                        },

                        {
                            duration: 500,
                            easing: 'swing',
                            step: function () {
                                $this.text(Math.floor(this.countNum));
                            },
                            complete: function () {
                                $this.text(this.countNum);
                                //alert('finished');
                            }
                        });
				});
} 
// countUp();

	$("#months3").click(function(){
		$('#counter-value1').attr('data-count', '0');
		$('#counter-value2').attr('data-count', '23');
		$('#counter-value3').attr('data-count', '33');
		$('#counter-value4').attr('data-count', '49');
		countUp();
		$("#months12").removeClass('active');
		$("#months3").addClass('active');
		$(".plan-note").html('per month with  <br> quarterly payment');
	});
	$("#months12").click(function(){  
		$('#counter-value1').attr('data-count', '0');
		$('#counter-value2').attr('data-count', '18');
		$('#counter-value3').attr('data-count', '28');
		$('#counter-value4').attr('data-count', '40');
		countUp();
		$("#months3").removeClass('active');
		$("#months12").addClass('active');
		$(".plan-note").html('per month with <br> annual payment');
	});


	</script>
	




 


 

	<!-- Parallax Script -->
	<script>
		; (function ($) {
			$window = $(window);

			$('*[data-type="parallax"]').each(function () {

				var $bgobj = $(this);

				$(window).scroll(function () {

					var yPos = -($window.scrollTop() / $bgobj.data('speed'));
					var coords = '50% ' + yPos + 'px';

					$bgobj.css({ backgroundPosition: coords });

				});
			});
		})(jQuery);
	</script>


  <!-- slick slider Js -->
  <script src="{{asset('crypto/js/slick.js')}}"></script>
	   <script>
	 
		  $(".homeproductslider").slick({
			slidesToShow: 3, 
			dots: false,
			slidesToScroll: 1,
			autoplay: false,
		  autoplaySpeed: 2000,  
		  arrows: true,
	
			responsive: [ 
				{
				  breakpoint: 1200,
				  settings: {
					slidesToShow:3,
					slidesToScroll: 1
				  }
				},
				{
					breakpoint: 992,
					settings: {
					  slidesToShow:2,
					  slidesToScroll: 1
					}
				  },
			   {
					breakpoint: 767,
					settings: {
					  slidesToShow:1,
					  slidesToScroll: 1
					}
				  }
				 
	
			  ]
	
		 }); 
	
	 
	
	   </script>


<script type="text/javascript" src="{{asset('crypto/js/compressed.js')}}"></script>
  <script>
	  $(function() {
	   $(".startTyper").typed({
      strings: ["© Crypto Scanner Pro 2019-2020. All Rights Reserved."],
      typeSpeed: 50, // typing speed
      backDelay: 100, // pause before backspacing
      loop: true, // loop on or off (true or false)
      loopCount: false, // number of loops, false = infinite
    });
  });
</script>
	



	

@yield('scripts')

</body>
</html>