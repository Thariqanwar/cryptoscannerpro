jQuery(function($) {'use strict';

	// min height
	$(document).ready(function() {
		function setHeight() {
			var windowHeight = $(window).height();
				$('.height-100').css('min-height', windowHeight);
				$('.height-100').css('min-height', windowHeight-64);
			}
		setHeight();
		$(window).resize(function() {
			setHeight();
		});
	});


	// Header
	$(window).load(function(){
		$("header").width($(window).width());
	});

	$(window).resize(function(){
		$("header").width($(window).width());
	});

	$(document).ready(function() {
		$('#nav_list').click(function() {
			/*$(this).toggleClass('active');*/
			$('#toggle-icon').toggleClass("open");
			$('.pushmenu-push').toggleClass('pushmenu-push-toright');
			$('.pushmenu-left').toggleClass('pushmenu-open');
		});
	});

	// Single menu
function scrollToSection(event) {
	event.preventDefault();
	var $section = $($(this).attr('href')); 
	$('html, body').animate({
	  scrollTop: $section.offset().top
	}, 500);
  }
  $('[data-scroll]').on('click', scrollToSection);

  

	// click Scroll
					
	$('.btm_go').click(function(){
		$('html, body').animate({
			scrollTop: $(".intro_scroll").offset().top
		}, 1000);
	});


	// Banner Effect

	$("#container").mousemove(function(e) {
		parallaxIt(e, "#content_banner", -60);
		parallaxIt(e, "#banner_img", -30);
		parallaxIt(e, ".btm_go", -100);
	});

	function parallaxIt(e, target, movement) {
	var $this = $("#container");
	var relX = e.pageX - $this.offset().left;
	var relY = e.pageY - $this.offset().top;

	TweenMax.to(target, 1, {
		x: (relX - $this.width() / 2) / $this.width() * movement,
		y: (relY - $this.height() / 2) / $this.height() * movement
	});
	}


	// Video popup

	$(document).ready(function(){
		var url = $("#video_id").attr('src');
		
		$("#video_id").attr('src', '');
		
		$("#video-popup").on('shown.bs.modal', function(){
			$("#video_id").attr('src', url);
		});
		
		$("#video-popup").on('hide.bs.modal', function(){
			$("#video_id").attr('src', '');
		});
	});


	//Feeds dragable
	$('.drag-list li').arrangeable({
		dragSelector: '.drag-move',
	});

	$(document).on('click', '.drag-list li .content', function(){
		$(this).next().children().first().toggleClass('show');
		$(this).toggleClass('active')
	});

	//Date Picker
	$('.multi-datepicker').daterangepicker({
		timePicker: true,
		showCustomRangeLabel: true,
	});


	// Dashboard Side menu
	$(window).bind("resize", function () {
		console.log($(this).width())
		if ($(this).width() < 992) {
			$('body').addClass('sidebar-collapse')
		} else {
			$('body').removeClass('sidebar-collapse')
		}
	}).trigger('resize');

	$(".menu-min-button").on('click', function(){
		$("body").toggleClass("sidebar-collapse");
		$('.sub-menu').hide();
		$('.main-sidebar .side-menu li a').removeClass('active open');
	});

	// Dashboard Side menu
	$(window).bind("resize", function () {
		console.log($(this).width())
		$(".main-sidebar .side-menu > li > a").on('click',function () {
			// $("ul.sub-menu").hide();
			// $("ul.sub-sub-menu").hide();
			$(".main-sidebar .side-menu > li > a").removeClass('active open');
			$(this).addClass('active open');
			$("ul.sub-menu").hide();
			$(this).next().slideToggle(400)
		});
	
	}).trigger('resize');	


	// Notification	
	var historyIcon = document.querySelector('.histroy-icon');
	var dropdownHistory = document.querySelector('.dropdown-histroy');
	var helpIcon = document.querySelector('.help-icon');
	var dropdownHepl = document.querySelector('.dropdown-help');
	var timeDelay = [1000,2000,3000,4000,5000,6000];
	
    function histroyCheck(event){
		var isClickInside = historyIcon.contains(event.target);
		if (isClickInside) {
			dropdownHistory.classList.toggle('d-none');
		} else {
			dropdownHistory.classList.add('d-none');
			clearInterval(helpInterval);
			helpInterval = setInterval((timeDelay));
		}
	}
	var helpInterval = setInterval((timeDelay));
	window.addEventListener('click', histroyCheck);
	
	function helpCheck(event){
		var isClickInside = helpIcon.contains(event.target);
		if (isClickInside) {
			dropdownHepl.classList.toggle('d-none');
		} else {
			dropdownHepl.classList.add('d-none');
			clearInterval(helpInterval);
			helpInterval = setInterval((timeDelay));
		}
	}
	var helpInterval = setInterval((timeDelay));
	window.addEventListener('click', helpCheck);

	//back to top
	if ($('#back-to-top').length) {
		var scrollTrigger = 100, // px
			backToTop = function () {
				var scrollTop = $(window).scrollTop();
				if (scrollTop > scrollTrigger) {
					$('#back-to-top').addClass('show');
				} else {
					$('#back-to-top').removeClass('show');
				}
			};
		backToTop();
		$(window).on('scroll', function () {
			backToTop();
		});
		$('#back-to-top').on('click', function (e) {
			e.preventDefault();
			$('html,body').animate({
				scrollTop: 0
			}, 700);
		});
	}
	
});

 	 
$(window).scroll(function() {    
	  // alert('hi');
		var scroll = $(window).scrollTop();
	
		if (scroll >= 180) {
			$("header").addClass("stick");
		} else {
			$("header").removeClass("stick");
		}
	});
 
	
