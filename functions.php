<?php
/*
This file is part of THE ERUDITE.

THE ERUDITE is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License v2 as published by the Free Software Foundation.

THE ERUDITE is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details: http://www.gnu.org/licenses/gpl-2.0.html
*/

// I hate for it to come to this, but wp-themes.com preview is AWFUL for my theme.
// Redirect to my demo site
if(false !== strpos(site_url(), 'http://wp-themes.com')) {
	wp_redirect('http://erudite.somadesign.ca/');
	exit();
}

if ( ! isset( $content_width ) )
	$content_width = 540;

// theme options page
include "library/theme-options.php";

// epigraphs
include_once "library/epigraph.php";

// 3.0 compat. Because WP.org themes directory are a pain in the ass.
if ( version_compare( $wp_version, '3.0', '<' ) )
	require_once('library/3.0.compat.php');

// my own filter for formatting
foreach ( array( 'wptexturize', 'convert_chars', 'wpautop' ) as $filter ) {
	add_filter( 'erdt_formatting', $filter );
}

add_action('init', 'erdt_go_away_page_comments');
function erdt_go_away_page_comments() {
	if ( ! get_the_theme_option('erdt_allow_page_comments') )
		remove_post_type_support('page', 'comments');
}

// empty titles
add_filter( 'the_title', 'erdt_empty_title' );
function erdt_empty_title( $title ) {
	if ( '' == $title )
		$title = __('[No Title]', 'erudite');
	
	return $title;
}

// style visual editor
add_filter('mce_css', 'erudite_editor_style');
function erudite_editor_style($url) {
	if ( get_the_theme_option('erdt_editor_style_disable') != 'false' )
		return $url;

	if ( !empty($url) )
	  $url .= ',';

	// Change the path here if using sub-directory
	$url .= trailingslashit( get_template_directory_uri() ) . 'css/editor-style.css';

	return $url;
}


function erudite_body_class($classes) {
	// for theme colors
	if ( get_the_theme_option('erdt_color_dark') === 'true' )
		$classes[] = 'dark';

	// proper first-page class
	if ( is_home() && !is_paged() )
		$classes[] = "first-page";

	// show/hide functionality
	if ( get_the_theme_option("erdt_hide_disable") == "false" )
		$classes[] = "hiding";

	// WP-Typography?
	if ( class_exists('wpTypography') )
		$classes[] = 'hypenation';
	
	return $classes;
}

add_filter('body_class', 'erudite_body_class');


// generic theme option function. set second parameter to TRUE to add content formatting
function get_the_theme_option( $id, $format = false ) {
	global $erdt_options;
	
	foreach ($erdt_options as $value) {
		
		if (get_option( $value['id'] ) === false) { 
			$$value['id'] = $value['std']; 
		} 
		else { 
			$$value['id'] = get_option( $value['id'] ); 
		}
	}
	$return = stripslashes($$id);
	if ($format) {
		$return = apply_filters( 'erdt_formatting', $return );
	}
	return $return;
}

function the_theme_option( $id, $format = false ) {
	echo get_the_theme_option( $id, $format );
}


// body_class future compatibility for WP 2.8
if (!function_exists("body_class") ) {
	function body_class($extra_classes='') {
		if ($extra_classes != '' ) { $extra_classes = " " . $extra_classes; } // add a preceding space for extra classes
		echo "class='" . erdt_body_class(false) . $extra_classes . "'";
	}
}



// filter the_excerpt to give a proper ellipsis.
function custom_excerpt($text) {
	return str_replace('[...]', '<span class="excerpt-more">&hellip;</span>', $text);
}
add_filter('the_excerpt', 'custom_excerpt');

// custom comments loop for wp_list_comments
include "comments_custom.php";

// grab theme version for happier script versioning
$themes = get_themes();
$current_theme = get_current_theme();
$ct->version = $themes[$current_theme]['Version'];

if (!is_admin() ) { //only load on non-admin pages
	wp_enqueue_script('scrollTo',get_bloginfo('template_directory').'/js/jquery.scrollTo-min.js',array('jquery'),'1.4.2', true);
	wp_enqueue_script('erudite',get_bloginfo('template_directory').'/js/common.js',array('jquery', 'scrollTo'),$ct->version, true);
}

if ( function_exists('add_theme_support') ) {
	add_theme_support('automatic-feed-links');
}

if ( function_exists('register_nav_menus') ) {
	register_nav_menus( array('header-menu' => __('Header Menu') ) );
}

// call wp_nav_menu, but fallback to old_erdt_globalnav otherwise
function erdt_globalnav() {
	if ( function_exists('wp_nav_menu') ) {
		$menu = wp_nav_menu(array(
			'theme_location' => 'header-menu',
			'fallback_cb' => 'old_erdt_globalnav',
			'container' => 'ul',
			'echo' => false
		));
		$menu = str_replace( array( "\r", "\n", "\t" ), '', trim($menu) );
		
		if ( ! empty( $menu) ) {
			echo '<div id="menu">' . $menu . "</div>\n";
		}
	}
	else {
		old_erdt_globalnav();
	}
}
// Produces a list of pages in the header without whitespace
function old_erdt_globalnav() {
	
	if (get_the_theme_option('erdt_category_nav') === 'true') {
		$menu = wp_list_categories('title_li=&echo=0');
	} else {
		$menu = wp_list_pages('title_li=&sort_column=menu_order&echo=0');
	}
	
	$menu = str_replace( array( "\r", "\n", "\t" ), '', trim($menu) );
	$menu = '<ul>' . $menu . '</ul>';
	echo '<div id="menu">' . $menu . "</div>\n";
}

add_filter('the_content', 'erdt_hr_helper', 0);
function erdt_hr_helper($content) {
	return str_replace('<hr />', "<hr />\n\n", $content);
}

function erdt_get_author_posts_link() {
	global $authordata;
	return sprintf(
		'<a href="%1$s" title="%2$s">%3$s</a>',
		get_author_posts_url( $authordata->ID, $authordata->user_nicename ),
		esc_attr( sprintf( __( 'Posts by %s' ), get_the_author() ) ),
		get_the_author()
	);
}

// Generates semantic classes for BODY element
function erdt_body_class( $print = true ) {
	global $wp_query, $current_user;

	// Applies the time- and date-based classes (below) to BODY element
	erdt_date_classes( time(), $c );

	// Generic semantic classes for what type of content is displayed
	is_front_page()  ? $c[] = 'home'       : null; // For the front page, if set
	is_home()        ? $c[] = 'blog'       : null; // For the blog posts page, if set
	is_archive()     ? $c[] = 'archive'    : null;
	is_date()        ? $c[] = 'date'       : null;
	is_search()      ? $c[] = 'search'     : null;
	is_paged()       ? $c[] = 'paged'      : null;
	is_attachment()  ? $c[] = 'attachment' : null;
	is_404()         ? $c[] = 'error404'     : null; // CSS does not allow a digit as first character

	// Special classes for BODY element when a single post
	if ( is_single() ) {
		$postID = $wp_query->post->ID;
		the_post();

		// Adds 'single' class and class with the post ID
		$c[] = 'single postid-' . $postID;

		// Adds classes for the month, day, and hour when the post was published
		if ( isset( $wp_query->post->post_date ) )
			erdt_date_classes( mysql2date( 'U', $wp_query->post->post_date ), $c, 's-' );

		// Adds category classes for each category on single posts
		if ( $cats = get_the_category() )
			foreach ( $cats as $cat )
				$c[] = 's-category-' . $cat->slug;

		// Adds tag classes for each tags on single posts
		if ( $tags = get_the_tags() )
			foreach ( $tags as $tag )
				$c[] = 's-tag-' . $tag->slug;

		// Adds MIME-specific classes for attachments
		if ( is_attachment() ) {
			$mime_type = get_post_mime_type();
			$mime_prefix = array( 'application/', 'image/', 'text/', 'audio/', 'video/', 'music/' );
				$c[] = 'attachmentid-' . $postID . ' attachment-' . str_replace( $mime_prefix, "", "$mime_type" );
		}

		// Adds author class for the post author
		$c[] = 's-author-' . sanitize_title_with_dashes(strtolower(get_the_author_login()));
		rewind_posts();
	}

	// Author name classes for BODY on author archives
	elseif ( is_author() ) {
		$author = $wp_query->get_queried_object();
		$c[] = 'author';
		$c[] = 'author-' . $author->user_nicename;
	}

	// Category name classes for BODY on category archvies
	elseif ( is_category() ) {
		$cat = $wp_query->get_queried_object();
		$c[] = 'category';
		$c[] = 'category-' . $cat->slug;
	}

	// Tag name classes for BODY on tag archives
	elseif ( is_tag() ) {
		$tags = $wp_query->get_queried_object();
		$c[] = 'tag';
		$c[] = 'tag-' . $tags->slug;
	}

	// Page author for BODY on 'pages'
	elseif ( is_page() ) {
		$pageID = $wp_query->post->ID;
		$page_children = wp_list_pages("child_of=$pageID&echo=0");
		the_post();
		$c[] = 'page pageid-' . $pageID;
		$c[] = 'page-author-' . sanitize_title_with_dashes(strtolower(get_the_author('login')));
		// Checks to see if the page has children and/or is a child page; props to Adam
		if ( $page_children )
			$c[] = 'page-parent';
		if ( $wp_query->post->post_parent )
			$c[] = 'page-child parent-pageid-' . $wp_query->post->post_parent;
		if ( is_page_template() ) // Hat tip to Ian, themeshaper.com
			$c[] = 'page-template page-template-' . str_replace( '.php', '-php', get_post_meta( $pageID, '_wp_page_template', true ) );
		rewind_posts();
	}

	// Search classes for results or no results
	elseif ( is_search() ) {
		the_post();
		if ( have_posts() ) {
			$c[] = 'search-results';
		} else {
			$c[] = 'search-no-results';
		}
		rewind_posts();
	}

	// For when a visitor is logged in while browsing
	if ( $current_user->ID )
		$c[] = 'logged-in';

	// Paged classes; for 'page X' classes of index, single, etc.
	if ( ( ( $page = $wp_query->get('paged') ) || ( $page = $wp_query->get('page') ) ) && $page > 1 ) {
		$c[] = 'paged-' . $page;
		if ( is_single() ) {
			$c[] = 'single-paged-' . $page;
		} elseif ( is_page() ) {
			$c[] = 'page-paged-' . $page;
		} elseif ( is_category() ) {
			$c[] = 'category-paged-' . $page;
		} elseif ( is_tag() ) {
			$c[] = 'tag-paged-' . $page;
		} elseif ( is_date() ) {
			$c[] = 'date-paged-' . $page;
		} elseif ( is_author() ) {
			$c[] = 'author-paged-' . $page;
		} elseif ( is_search() ) {
			$c[] = 'search-paged-' . $page;
		}
	}

	// Separates classes with a single space, collates classes for BODY
	$c = join( ' ', apply_filters( 'body_class',  $c ) ); // Available filter: body_class

	// And tada!
	return $print ? print($c) : $c;
}

// Define the num val for 'alt' classes (in post DIV and comment LI)
$erdt_post_alt = 1;

// Generates time- and date-based classes for BODY, post DIVs, and comment LIs; relative to GMT (UTC)
function erdt_date_classes( $t, &$c, $p = '' ) {
	$t = $t + ( get_option('gmt_offset') * 3600 );
	$c[] = $p . 'y' . gmdate( 'Y', $t ); // Year
	$c[] = $p . 'm' . gmdate( 'm', $t ); // Month
	$c[] = $p . 'd' . gmdate( 'd', $t ); // Day
	$c[] = $p . 'h' . gmdate( 'H', $t ); // Hour
}

// For category lists on category archives: Returns other categories except the current one (redundant)
function erdt_cats_meow($glue) {
	$current_cat = single_cat_title( '', false );
	$separator = "\n";
	$cats = explode( $separator, get_the_category_list($separator) );
	foreach ( $cats as $i => $str ) {
		if ( strstr( $str, ">$current_cat<" ) ) {
			unset($cats[$i]);
			break;
		}
	}
	if ( empty($cats) )
		return false;

	return trim(join( $glue, $cats ));
}

// For tag lists on tag archives: Returns other tags except the current one (redundant)
function erdt_tag_ur_it($glue) {
	$current_tag = single_tag_title( '', '',  false );
	$separator = "\n";
	$tags = explode( $separator, get_the_tag_list( "", "$separator", "" ) );
	foreach ( $tags as $i => $str ) {
		if ( strstr( $str, ">$current_tag<" ) ) {
			unset($tags[$i]);
			break;
		}
	}
	if ( empty($tags) )
		return false;

	return trim(join( $glue, $tags ));
}

// Produces an avatar image with the hCard-compliant photo class
function erdt_commenter_link() {
	$commenter = get_comment_author_link();
	if ( ereg( '<a[^>]* class=[^>]+>', $commenter ) ) {
		$commenter = ereg_replace( '(<a[^>]* class=[\'"]?)', '\\1url ' , $commenter );
	} else {
		$commenter = ereg_replace( '(<a )/', '\\1class="url "' , $commenter );
	}
	$avatar_email = get_comment_author_email();
	$avatar_size = apply_filters( 'avatar_size', '32' ); // Available filter: avatar_size
	$avatar = str_replace( "class='avatar", "class='photo avatar", get_avatar( $avatar_email, $avatar_size ) );
	echo $avatar . ' <span class="fn n">' . $commenter . '</span>';
}

// Function to filter the default gallery shortcode
function erdt_gallery($attr) {
	global $post;
	if ( isset($attr['orderby']) ) {
		$attr['orderby'] = sanitize_sql_orderby($attr['orderby']);
		if ( !$attr['orderby'] )
			unset($attr['orderby']);
	}

	extract(shortcode_atts( array(
		'orderby'    => 'menu_order ASC, ID ASC',
		'id'         => $post->ID,
		'itemtag'    => 'dl',
		'icontag'    => 'dt',
		'captiontag' => 'dd',
		'columns'    => 3,
		'size'       => 'thumbnail',
	), $attr ));

	$id           =  intval($id);
	$orderby      =  addslashes($orderby);
	$attachments  =  get_children("post_parent=$id&post_type=attachment&post_mime_type=image&orderby={$orderby}");

	if ( empty($attachments) )
		return null;

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $id => $attachment )
			$output .= wp_get_attachment_link( $id, $size, true ) . "\n";
		return $output;
	}

	$listtag     =  tag_escape($listtag);
	$itemtag     =  tag_escape($itemtag);
	$captiontag  =  tag_escape($captiontag);
	$columns     =  intval($columns);
	$itemwidth   =  $columns > 0 ? floor(100/$columns) : 100;

	$output = apply_filters( 'gallery_style', "\n" . '<div class="gallery">', 9 ); // Available filter: gallery_style

	foreach ( $attachments as $id => $attachment ) {
		$img_lnk = get_attachment_link($id);
		$img_src = wp_get_attachment_image_src( $id, $size );
		$img_src = $img_src[0];
		$img_alt = $attachment->post_excerpt;
		if ( $img_alt == null )
			$img_alt = $attachment->post_title;
		$img_rel = apply_filters( 'gallery_img_rel', 'attachment' ); // Available filter: gallery_img_rel
		$img_class = apply_filters( 'gallery_img_class', 'gallery-image' ); // Available filter: gallery_img_class

		$output  .=  "\n\t" . '<' . $itemtag . ' class="gallery-item gallery-columns-' . $columns .'">';
		$output  .=  "\n\t\t" . '<' . $icontag . ' class="gallery-icon"><a href="' . $img_lnk . '" title="' . $img_alt . '" rel="' . $img_rel . '"><img src="' . $img_src . '" alt="' . $img_alt . '" class="' . $img_class . ' attachment-' . $size . '" /></a></' . $icontag . '>';

		if ( $captiontag && trim($attachment->post_excerpt) ) {
			$output .= "\n\t\t" . '<' . $captiontag . ' class="gallery-caption">' . $attachment->post_excerpt . '</' . $captiontag . '>';
		}

		$output .= "\n\t" . '</' . $itemtag . '>';
		if ( $columns > 0 && ++$i % $columns == 0 )
			$output .= "\n</div>\n" . '<div class="gallery">';
	}
	$output .= "\n</div>\n";

	return $output;
}


// Widgets plugin: intializes the plugin after the widgets above have passed snuff
function erdt_widgets_init() {
	if ( !function_exists('register_sidebars') )
		return;

	// Formats the Sandbox widgets, adding readability-improving whitespace
	$p = array(
		'name'			 =>   "Erudite Footer %d",
		'before_widget'  =>   "\n\t\t\t" . '<li id="%1$s" class="widget %2$s">',
		'after_widget'   =>   "\n\t\t\t</li>\n",
		'before_title'   =>   "\n\t\t\t\t". '<h3 class="widgettitle">',
		'after_title'    =>   "</h3>\n"
	);

	// Table for how many? Two? This way, please.
	register_sidebars( 3, $p );
}

// Translate, if applicable
load_theme_textdomain('erudite', get_template_directory() . '/translation');

// Runs our code at the end to check that everything needed has loaded
add_action( 'init', 'erdt_widgets_init' );

// Adds filters for the description/meta content in archives.php
add_filter( 'archive_meta', 'wptexturize' );
add_filter( 'archive_meta', 'convert_smilies' );
add_filter( 'archive_meta', 'convert_chars' );
add_filter( 'archive_meta', 'wpautop' );

// Remember: the Sandbox is for play.
?>