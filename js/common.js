jQuery(document).ready(function($) {
	
	// wrap hr elements in a div -> ie6&7 compatability
	$("hr").wrap('<div class="hr"></div>').css("display","none");
	
	// settings
	var animLength = 500;
	
	// caching/defining
	var entryHide = $('div.first-post .entry-content');
	var entryHeight = entryHide.children().eq(0).height() + entryHide.children().eq(1).height() + 2*(parseInt( $('div.first-post p').css('marginBottom'),10 ));
	var contentHeight = entryHide.height();
	
	//set .entry-content to height of first paragraph, add view more link
	// only do this if there are more than 2 elements, and the user hasn't disabled in the control panel
	if (entryHide.find("p").size() > 2 && erdt.DisableKeepReading == false) {
		entryHide.css({height: entryHeight}).after("<div class='reveal'><p>"+erdt.More+"</p></div>").addClass("shortened");
	}

	//show/hide animation
	$('div.reveal p').click(function() {
		if ($(this).hasClass('hide') ) { // hide it
			$(this).removeClass('hide').html(erdt.More);
			entryHide.animate({height: entryHeight}, animLength);
			$.scrollTo("#content", animLength);
		} else { // show it
			$(this).addClass('hide').html(erdt.Less);
			entryHide.animate({height: contentHeight}, animLength);
		}
		entryHide.toggleClass("shortened");
	});

// header & footer show/hide functionality
	if (!erdt.DisableHide){

		// create menu show/hide link
		$("#header-wrap").after("<div id='menu-toggle'><div class='closed'>"+erdt.MenuShow+"</div></div>");
	
		// menu toggler
		$("#menu-toggle div").click(function() {
			if ($(this).hasClass("closed") ) {
				$(this).html(erdt.MenuHide);
			} else {
				$(this).html(erdt.MenuShow);
			}
			$(this).toggleClass("closed");
			$("#access").slideToggle(animLength);
		});

		// insert footer toggle
		$("#footer-wrap").before("<div id='foot-toggle'><div><span>"+erdt.Info+"</span></div></div>");
	
		// toggle the footer
		$("#foot-toggle span").click(function() {
			var footerAnimLength, footer = $("#footer-wrap");
			
			if ( footer.is(":visible") ) {
				footerAnimLength = animLength;
			}
			else {
				footerAnimLength = 0;
			}
			
			$("#footer-wrap").slideToggle(footerAnimLength, function(){
				$.scrollTo("#foot-toggle",animLength);
			});
			
		});

	}

	// pullquotes autogeneration
	$("body.single .entry-content, div.first-post .entry-content").each(function(i) {
		var erdtPullQuote = $(this).find(".pullquote");
		if (erdtPullQuote.size() > 0) {
			var erdtPqTop = erdtPullQuote.position().top;
			if (erdtPqTop < 240) { erdtPqTop = 252; } // make sure it clears the .entry-meta area
			if (entryHide.size() > 0 && erdtPqTop < entryHeight ) {erdtPqTop = entryHeight + 72; } //on first-post, make sure it's below the fold
			$(this).append("<div class='pullquote-display'><p></p></div>").find(".pullquote-display p").html(erdtPullQuote.eq(0).html() ).parent().css({top: erdtPqTop +"px"});
		};
	});
	

	// set a group of elements to equal height.
	$.fn.equalHeights = function() {
			var currentTallest = 0;
			$(this).each(function(i){
				if ($(this).height() > currentTallest) { currentTallest = $(this).height(); }
			});
			// for ie6, set height since min-height isn't supported
			if ($.browser.msie && $.browser.version == 6.0) { $(this).css({'height': currentTallest}); }
			$(this).css({'min-height': currentTallest}); 

		return this;
	};
	
	// set to equal heights
	$(".home-post .entry-content").equalHeights();
	

	
	// blockquote enhancemnet for browsers that don't support ::before and ::after pseudo-classes
	$(".entry-content blockquote").append("<span class='before quote'>&ldquo;</span><span class='after quote'>&rdquo;</span>");
	
	// IE6 doesn't play well with the blockquote enhancement, nor do ie6-specific stylesheets work. using deprecated $.browser
	if ($.browser.msie && $.browser.version == 6.0) {$("blockquote .quote").remove(); }
	
	// IE8 strange behavior. have to add a top-level css declaration or else the margin-bottom for .first-post collapses.
	if ($.browser.msie && $.browser.version == 8) {$("body").css('padding', 0);  }
	
	// click the header to turn typography grid on or off. uncomment the line below if you want to see it.
	//$('#header-wrap').click(function() {$('body').toggleClass('baseline');});
	
	// cache body
	var body = $("body");
	// Font Stacks
	body.fontunstack( "constantia,'hoefler text','palatino linotype',serif" );
	
	// Browsers, because
	if ( $.browser.msie ) {
		body.addClass("ie");
	}
	else if ( $.browser.webkit || $.browser.safari ) {
		body.addClass("webkit");
	}
	else if ( $.browser.mozilla ) {
		body.addClass("mozilla");
	}
	// opera doesn't play because nobody cares.

});

/*
* Font UnStack 0.1
*
* Developed by Phil Oye
* Copyright (c) 2009 Phil Oye, http://philoye.com/
*
* Licensed under the MIT license:
* http://www.opensource.org/licenses/mit-license.php
*
*/

(function($){$.fn.fontunstack=function(defaults,opts){$.fontunstack.init(defaults,opts,this);};$.fontunstack={options:{class_prefix:"set-in-"},init:function(stack,opts,elems){var elems=elems||"body";$.extend(this.options,opts);if(this.options.class_prefix==""){this.options.class_prefix="set-in-";}
if(typeof stack=="string"){stack=stack.match(/[^'",;\s][^'",;]*/g)||[];}
this.analyzeStack(stack,elems);},analyzeStack:function(stack,elems){var generics=["monospace","sans-serif","serif","cursive","fantasy"];var baseline=generics[0];var num_fonts=stack.length;var last_resort=stack[num_fonts-1];if($.inArray(last_resort,generics)){stack.push(baseline);num_fonts++;}
if(last_resort==baseline){baseline=generics[1];};for(var i=0;i<num_fonts-1;i++){font=stack[i];if($.fontunstack.testFont(font,baseline)){var re=new RegExp("\\b"+this.options.class_prefix+".*?\\b","g");$(elems).get(0).className=$(elems).get(0).className.replace(re,"");safe_font_name=encodeURIComponent(font.replace(/[\s\-.!~*'()"]/g,"").toLowerCase());$(elems).addClass(this.options.class_prefix+safe_font_name);break;}}},testFont:function(requested_font,baseline_font){var span=$('<span id="font_tester" style="font-family:'+baseline_font+'; font-size:144px;position:absolute;left:-10000px;top:-10000px;visibility:hidden;">mmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmml</span>');$("body").prepend(span);var baseline_width=span.width();span.css("font-family",requested_font+","+baseline_font);var requested_width=span.width();span.remove();return(requested_width!=baseline_width);}};})(jQuery);