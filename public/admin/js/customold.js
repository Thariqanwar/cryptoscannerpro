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
	// $('.drag-true li').arrangeable({
	// 	dragSelector: '.drag-move',
	// });

	$(document).on('click', '.drag-true li .content', function(){
		$(this).next().children().first().toggleClass('show');
		$(this).toggleClass('active')
	});

	//Date Picker
	$('.multi-datepicker').daterangepicker({
		timePicker: false,
		showCustomRangeLabel: true,
	});


	// Dashboard Side menu
	$(window).bind("resize", function () {
		console.log($(this).width())
		
		if ($(this).width() < 992) {
			$('body').removeClass('non-collapse');
			$('body').addClass('sidebar-collapse');
		} else {
			$('body').removeClass('sidebar-collapse');
			$('body').addClass('non-collapse');
		}
	}).trigger('resize');

	$(".menu-min-button").on('click', function(){
		$("body").toggleClass("sidebar-collapse non-collapse");
		$('.sub-menu').hide();
		$('.main-sidebar .side-menu li a').removeClass('active open');
	});

	// Dashboard Side menu
	$(".non-collapse .main-sidebar .side-menu > li > a").on('click', function() {
		$(".sub-menu").slideUp(200);
		if ($(this).parent().hasClass("active")) {
			$(".main-sidebar .side-menu > li").removeClass("active");
			$(".main-sidebar .side-menu > li > a").removeClass("active");
			$(this).parent().removeClass("active");
			$(this).removeClass("active");
		} else {
			$(".main-sidebar .side-menu > li").removeClass("active");
			$(".main-sidebar .side-menu > li > a").removeClass("active");
			$(this).next(".sub-menu").slideDown(200);
			$(this).parent().addClass("active");
			$(this).addClass("active");
		}
	});


	// Theme Change
	$(document).on('click', '.theme-button', function (){
		$('body').toggleClass('dark-theme')
	});


	// Data tabel
	$(document).ready(function() {
		$('.data-table').DataTable();
	});

	// Drag and Resize 
	$(function () {

		var options = {
			cellHeight: 80,
			verticalMargin: 30,
			float: true,
			resizable: {
				handles: 'e, se, s, sw, w, nw, n, ne'
			},
			draggable: {
				handle: '.draggable-heading',
			},
			acceptWidgets: '.grid-stack-item',
		};
	
		$('.grid-stack').gridstack(options);

	});

	// Minimize Buttone
    $(document).on('click', '.minimize-button', function(){

        $(this).toggleClass('active')
        var minimizeContent = $(this).parent().parent().next().toggleClass('collapse');
        var minimizeWidget = $(this).parent().parent().parent().parent().toggleClass('collapse-content');
        var minimizeResize = $(this).parent().parent().parent().next().toggleClass('d-none');
        
        // if ($('.grid-stack__content').hasClass('collapse')){
		// 	// var minimizeHieght = $(this).parent().parent().parent().parent().attr('data-gs-height', '1');
        // } else {
        //     var maxmizeHieght = $(this).parent().parent().parent().parent().attr('data-gs-height', '4');
        // }

    });


    // Close Button
    $(document).on('click', '.close-button', function(){
        var removeWidget = $(this).parent().parent().parent().parent().remove();
    });

    // Lock Button
    $(document).on('click', '.lock-button', function(){

        $(this).toggleClass('active')
        var lockwidget = $(this).parent().parent().parent().parent().toggleClass('lock-widget');

        if ($(lockwidget).hasClass('lock-widget')){            
            var grid = $('.grid-stack').data('gridstack');
            grid.movable(lockwidget, false);
            grid.resizable(lockwidget, false);
        } else {            
            var grid = $('.grid-stack').data('gridstack');
            grid.movable(lockwidget, true);
            grid.resizable(lockwidget, true);
        }
    });

    
    // Table Col Order Change
    // $(document).ready(function() {
    //     $(".draggable-table").jsdragtable();
    // });

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


	//country selector
	$("#country_selector").countrySelect({
		preferredCountries: ['ca', 'gb', 'us']
	});
	

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