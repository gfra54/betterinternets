jQuery.fn.exists = function(callback) {
  var args = [].slice.call(arguments, 1);
  if (this.length) {
    callback.call(this, args);
  }
  return this;
};

/*----------------------------------------------------
/* Scroll to top
/*--------------------------------------------------*/
jQuery(document).ready(function($) {
	//move-to-top arrow
	jQuery("body").prepend("<div id='move-to-top' class='animate '><i class='fa fa-angle-up'></i></div>");
	var scrollDes = 'html,body';  
	/*Opera does a strange thing if we use 'html' and 'body' together so my solution is to do the UA sniffing thing*/
	if(navigator.userAgent.match(/opera/i)){
		scrollDes = 'html';
	}
	//show ,hide
	jQuery(window).scroll(function () {
		if (jQuery(this).scrollTop() > 160) {
			jQuery('#move-to-top').addClass('filling').removeClass('hiding');
		} else {
			jQuery('#move-to-top').removeClass('filling').addClass('hiding');
		}
	});
	// scroll to top when click 
	jQuery('#move-to-top').click(function () {
		jQuery(scrollDes).animate({ 
			scrollTop: 0
		},{
			duration :500
		});
	});
});

/*----------------------------------------------------
/* Smooth Scrolling for Anchor Tag like #comments
/*--------------------------------------------------*/
jQuery(document).ready(function($) {
  $('a[href*="#comments"]:not([href="#comments"])').click(function() {
    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') 
        || location.hostname == this.hostname) {

      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
      if (target.length) {
        $('html,body').animate({
          scrollTop: target.offset().top
        }, 1000);
        return false;
      }
    }
  });
});

/*----------------------------------------------------
/* Responsive Navigation
/*--------------------------------------------------*/
if (mts_customscript.responsive && mts_customscript.nav_menu != 'none') {
    jQuery(document).ready(function($){
        // merge if two menus exist
        if (mts_customscript.nav_menu == 'both') {
            var menu_wrapper = $('.secondary-navigation')
                .clone().attr('class', 'mobile-menu secondary').wrap('<div id="mobile-menu-wrapper" />')
                .parent().hide()
                .appendTo('body');
            $('.primary-navigation').clone().attr('class', 'mobile-menu primary').appendTo(menu_wrapper).find('.mts-cart').remove();
        } else {
            var menu_wrapper = $('.'+mts_customscript.nav_menu+'-navigation')
                .clone().attr('class', 'mobile-menu ' + mts_customscript.nav_menu)
                .wrap('<div id="mobile-menu-wrapper" />').parent().hide()
                .appendTo('body');
        }
    
        $('.toggle-mobile-menu').click(function(e) {
            e.preventDefault();
            e.stopPropagation();
            $('#mobile-menu-wrapper').show();
            $('body').toggleClass('mobile-menu-active');
        });
        
        // prevent propagation of scroll event to parent
        $(document).on('DOMMouseScroll mousewheel', '#mobile-menu-wrapper', function(ev) {
            var $this = $(this),
                scrollTop = this.scrollTop,
                scrollHeight = this.scrollHeight,
                height = $this.height(),
                delta = (ev.type == 'DOMMouseScroll' ?
                    ev.originalEvent.detail * -40 :
                    ev.originalEvent.wheelDelta),
                up = delta > 0;
        
            var prevent = function() {
                ev.stopPropagation();
                ev.preventDefault();
                ev.returnValue = false;
                return false;
            }
        
            if (!up && -delta > scrollHeight - height - scrollTop) {
                // Scrolling down, but this will take us past the bottom.
                $this.scrollTop(scrollHeight);
                return prevent();
            } else if (up && delta > scrollTop) {
                // Scrolling up, but this will take us past the top.
                $this.scrollTop(0);
                return prevent();
            }
        });
    }).click(function() {
        jQuery('body').removeClass('mobile-menu-active');
    });
}
/*----------------------------------------------------
/*  Dropdown menu
/* ------------------------------------------------- */
jQuery(document).ready(function($) { 
	$('#navigation ul.sub-menu, #navigation ul.children').hide(); // hides the submenus in mobile menu too
	$('#navigation li').hover( 
		function() {
			$(this).children('ul.sub-menu, ul.children').slideDown('fast');
		}, 
		function() {
			$(this).children('ul.sub-menu, ul.children').hide();
		}
	);
});

/*----------------------------------------------------
/* Social button scripts
/*---------------------------------------------------*/
jQuery(document).ready(function($){
	(function(d, s) {
	  var js, fjs = d.getElementsByTagName(s)[0], load = function(url, id) {
		if (d.getElementById(id)) {return;}
		js = d.createElement(s); js.src = url; js.id = id;
		fjs.parentNode.insertBefore(js, fjs);
	  };
	jQuery('span.facebookbtn, .facebook_like').exists(function() {
	  load('//connect.facebook.net/en_US/all.js#xfbml=1', 'fbjssdk');
	});
	jQuery('span.gplusbtn').exists(function() {
	  load('https://apis.google.com/js/plusone.js', 'gplus1js');
	});
	jQuery('span.twitterbtn').exists(function() {
	  load('//platform.twitter.com/widgets.js', 'tweetjs');
	});
	jQuery('span.linkedinbtn').exists(function() {
	  load('//platform.linkedin.com/in.js', 'linkedinjs');
	});
	jQuery('span.pinbtn').exists(function() {
	  load('//assets.pinterest.com/js/pinit.js', 'pinterestjs');
	});
	jQuery('span.stumblebtn').exists(function() {
	  load('//platform.stumbleupon.com/1/widgets.js', 'stumbleuponjs');
	});
	}(document, 'script'));
});

/*----------------------------------------------------
/* Portfolio
/*---------------------------------------------------*/
(function ($, window, document, undefined) {
    "use strict";
    var pluginName = "ajaxPortfolio",
        defaults = {};

    function Plugin(element, options) {
        this.element = $(element);
        this.settings = $.extend({}, defaults, options);
        this.init();
    }
    Plugin.prototype = {
        init: function () {
            var obj = this;
            this.grid = this.element.find('.portfolio-grid-container'),
            this.items = this.grid.children();
            if (this.items.length < 1) return false; //If no items was found then exit
            this.ajaxDiv = this.element.find('div.ajax-container'),
            this.filter = this.element.find('.sort_by_cat'),
            this.loader = this.element.find('.portfolio-loader'),
            this.triggers = this.items.find('.project-load'),
            this.closeBtn = this.ajaxDiv.find('.close-ajax-container'),
            this.nextBtn = this.ajaxDiv.find('.next-ajax-container'),
            this.prevBtn = this.ajaxDiv.find('.prev-ajax-container'),
            this.api = {},
            this.id = null,
            this.win = $(window),
            this.current = 0,
            this.breakpointT = 989,
            this.breakpointP = 767,
            this.columns = this.grid.data('columns'),
            this.prevNextClicked = false,
            this.real_col = this.columns;
            this.loader.fadeIn();
            if (this.items.length == 1) {
                this.nextBtn.remove();
                this.prevBtn.remove();
            }
            this.grid.waitForImages(function() {
                obj.loader.fadeOut();
                obj.set_width();
                obj.relayout();
                obj.bind_handler();
            });
        },
        relayout: function () {
            if (this.grid.hasClass('.isotope'))
                this.grid.isotope('reLayout');
            else
                this.grid.isotope().fadeTo(500, 1);
        },
        bind_handler: function () {
            var obj = this; // Temp instance of this object
            // Bind the filters with isotope
            obj.filter.find('a').click(function () {
                obj.grid.find('.project-load').removeClass('active');
                obj.grid.removeClass('grid-open');
                obj.close_project(true);
                obj.filter.find('a').removeClass('active_sort');
                $(this).addClass('active_sort');
                var selector = $(this).attr('data-filter');
                obj.grid.isotope({
                    filter: selector
                });
                return false;
            });
            
            //obj.triggers.on('click', function(){
            obj.grid.on('click', '.project-load', function(){
                
                var clicked = $(this),
                    clickedParent = clicked.parent();

                obj.current = clickedParent.index();
                if (mts_customscript.portfolio_expand == "1") {
                    if(clicked.hasClass('active'))
                        return false;
                    
                    obj.close_project(obj.prevNextClicked);
                    
                    obj.grid.find('.project-load').removeClass('active');
                    clicked.addClass('active');
                    obj.grid.addClass('grid-open');
                    obj.loader.fadeIn();
                    
                    obj.id = clicked.data('post-id');
                    clickedParent.addClass('loading-project');

                    obj.load_project();
                    obj.prevNextClicked = false;

                } else {
                    window.location.href = clicked.data('permalink');
                }
                return false;
                
            });
            
            obj.nextBtn.on('click', function(){
                obj.triggers = obj.element.find('.project-load'); 
                obj.prevNextClicked = true;
                if(obj.current == obj.triggers.length-1) {
                    obj.triggers.eq(0).trigger('click');
                    return false;
                }
                else {
                    obj.triggers.eq(obj.current + 1).trigger('click');
                    return false;
                }
                    
            });
            
            obj.prevBtn.on('click', function(){
                obj.triggers = obj.element.find('.project-load'); 
                obj.prevNextClicked = true;
                if(obj.current == 0) {
                    obj.triggers.eq(obj.triggers.length-1).trigger('click');
                    return false;
                }
                else {
                    obj.triggers.eq(obj.current - 1).trigger('click');
                    return false;
                }
                    
            });
            
            // Bind close button
            obj.closeBtn.on('click', function(){
                obj.close_project(true);
                obj.triggers.removeClass('active');
                obj.grid.removeClass('grid-open');
                return false;
            });
            
        },
        // Function to close the ajax container div
        close_project: function(collapse){
            var obj = this, // Temp instance of this object
                video = obj.ajaxDiv.find('iframe'),
                src = video.attr('src'),
                project = obj.ajaxDiv.find('.ajax_project'),
                newH = project.outerHeight(),
                height = obj.ajaxDiv.height();
            
            obj.ajaxDiv.find('video, audio').each(function() {
                this.player.pause()
            });

            video.attr( 'src', '' );
            //video.attr( 'src', src );

            obj.ajaxDiv.removeClass('active');

            var new_height = height;
            if (collapse) {
                new_height = 0;
                obj.ajaxDiv.removeClass('expanded');
            }
            
            if(height > 0) {
                obj.ajaxDiv.css('height', newH+'px').animate({
                    height: new_height,
                    opacity: 0
                }, 400);
            }
            else {
                obj.ajaxDiv.animate({
                    height: new_height,
                    opacity: 0
                }, 400);
            }
            
        },      
        load_project: function(){
            var obj = this;
            $.post(mts_customscript.ajaxurl, {
                action  : 'get_portfolio',
                id      : obj.id
            }, function (response) {
                obj.ajaxDiv.find('.ajax_project').remove();
                obj.ajaxDiv.append(response).addClass('active expanded');
                obj.project_factory();
                $('.loading-project').removeClass('loading-project');
            }); 
        },
        project_factory:function(){
            var obj = this,
                project = this.ajaxDiv.find('.ajax_project'),
                slider = project.find('.project_flexslider'),
                gallery = project.find('.gallery-inner');
            
            /*obj.ajaxDiv.css({
                'display': 'block',
                opacity: 0,
                height:0
            });*/
            
            project.waitForImages(function() {
                $('html:not(:animated),body:not(:animated)').animate({ scrollTop: obj.ajaxDiv.offset().top - 32 }, 500);
                slider.flexslider({
                    selector: ".project_slides > li",
                    smoothHeight: true,
                    animation: 'slide',
                    animationLoop: false,
                    video: true,
                    controlNav: false,
                    slideshow: false,
                    before: function(slideshow){
                        var ul = slideshow.container ? slideshow.container : '';
                        if(ul.length)
                            ul.parent().removeClass('animating-height');
                    },
                    after: function(slideshow){
                        var index = slideshow.currentSlide,
                            ul = slideshow.container ? slideshow.container : '';
                            if(ul.length) {
                                var isgal = ul.find('> li').eq(index).find(' > div ').hasClass('gallery-block');
                                if(isgal )
                                    ul.parent().addClass('animating-height');
                            }
                    },
                    start: function(slideshow){
                        var index = slideshow.currentSlide,
                            ul = slideshow.container ? slideshow.container : '';
                            if(ul.length) {
                                var isgal = ul.find('> li').eq(index).find(' > div ').hasClass('gallery-block');
                                if(isgal )
                                    ul.parent().addClass('animating-height');
                            }
                            
                    }
                    
                });
                obj.loader.fadeOut(function(){
                    var newH = project.outerHeight();
                    obj.ajaxDiv.animate({
                        opacity: 1,
                        height:newH
                    }, 400, function(){
                        obj.ajaxDiv.css({height:'auto'});
                    });
                });
                
            });
            
        },
        set_width: function () {
            this.real_col = this.columns;
            return true;

            var gridW = this.grid.width(),
                winW = this.win.width();
            if (winW > this.breakpointP && winW <= this.breakpointT)
                this.real_col = 2;
            else if (winW <= this.breakpointP)
                this.real_col = 1;
            else
                this.real_col = this.columns;
            console.log('real_col = '+this.real_col);
            console.log('outerWidth = '+Math.floor(gridW / this.real_col));
            this.items.outerWidth(Math.floor(gridW / this.real_col));
        }
    };
    $.fn[pluginName] = function (options) {
        return this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName, new Plugin(this, options));
            }
        });
    };
})(jQuery, window, document);
jQuery(document).ready(function ($) {
    $('.portfolio-grid').ajaxPortfolio();
});
