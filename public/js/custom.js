jQuery(function($) {'use strict';

	// min height 
	
	$(document).ready(function() {

		function setHeight() {
			var windowHeight = $(window).height();
				$('.min-height-100').css('min-height', windowHeight);
				$('.min-height-90').css('min-height', windowHeight-72);
			}
		setHeight();
		$(window).resize(function() {
			setHeight();
		});
	});
	// Docuement ready end
});