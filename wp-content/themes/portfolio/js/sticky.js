jQuery(document).ready(function() {
	function isScrolledTo(elem) {
		var docViewTop = jQuery(window).scrollTop(); //num of pixels hidden above current screen
		var docViewBottom = docViewTop + jQuery(window).height();

		var elemTop = jQuery(elem).offset().top; //num of pixels above the elem
		var elemBottom = elemTop + jQuery(elem).height();

		return ((elemTop <= docViewTop));
	}

	var catcher = jQuery('#catcher');
	var sticky = jQuery('#sticky');
	

	jQuery(window).scroll(function() {
		var nav_height = jQuery('.main-header').height();
		if(isScrolledTo(sticky)) {
			sticky.css({'position': 'fixed', 'top':'0', 'padding-top': '15px', 'padding-bottom': '15px', 'background': '#fff'});
			jQuery('.main-container').css('padding-top',nav_height + 100);
		} 
		var stopHeight = catcher.offset().top + catcher.height();
		if ( stopHeight > sticky.offset().top) {
			sticky.css({'position': 'relative', 'top':'0', 'padding-top': '50px', 'padding-bottom': '50px', 'background': 'transparent'});
			jQuery('.main-container').css('padding-top','0');
		}
	});
});
