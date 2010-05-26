jQuery("html").addClass('enhanced');
jQuery(document).ready(function($) {
	
	// wrap hr elements in a div -> ie6&7 compatability
	$("hr").wrap('<div class="hr"></div>').css("display","none");
	
	// settings
	var animLength = 500;
	
	// caching/defining
	var entryHide = $('div.first-post .entry-content');
	var entryHeight = entryHide.children().eq(0).height() + entryHide.children().eq(1).height() + 2*(parseInt( $('div.first-post p').css('marginBottom') ));
	var contentHeight = entryHide.height();
	
	//set .entry-content to height of first paragraph, add view more link
	// only do this if there are more than 2 elements, and the user hasn't disabled in the control panel
	if (entryHide.find("p").size() > 2 && erdt.DisableKeepReading == false) {
		entryHide.css({height: entryHeight}).after("<div class='reveal'><p>"+erdt.More+"</p></div>").addClass("hidden");
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
		entryHide.toggleClass("hidden");
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

});
