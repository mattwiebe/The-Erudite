<?php
/*
This file is part of THE ERUDITE.

THE ERUDITE is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License v2 as published by the Free Software Foundation.

THE ERUDITE is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! isset( $content_width ) )
	$content_width = 540;

// theme options page
include "library/theme-options.php";

// epigraphs
include_once "library/epigraph.php";

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

add_filter('body_class', 'erudite_body_class');
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

// generic theme option function. set second parameter to TRUE to add content formatting
function get_the_theme_option( $id, $format = false ) {
	global $erdt_options;
	$return = false;
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
	if ( empty($return) ) {
		$return = false;
	}
	return $return;
}

function the_theme_option( $id, $format = false ) {
	echo get_the_theme_option( $id, $format );
}

// filter the_excerpt to give a proper ellipsis.
add_filter('the_excerpt', 'custom_excerpt');
function custom_excerpt($text) {
	return str_replace('[...]', '<span class="excerpt-more">&hellip;</span>', $text);
}

// custom comments loop for wp_list_comments
include "comments_custom.php";


add_action( 'wp_enqueue_scripts', 'erdt_frontend_scripts_and_styles' );
function erdt_frontend_scripts_and_styles() {
	// grab theme version for happier script versioning
	$themes = get_themes();
	$current_theme = get_current_theme();
	$ver = $themes[$current_theme]['Version'];
	$disable_parent = is_child_theme() && 'true' === get_the_theme_option('erdt_disable_parent_css');
	$template_url = trailingslashit(get_template_directory_uri());
	
	if ( ! $disable_parent ) {
		wp_enqueue_style('the-erudite', $template_url.'css/erudite.css');
	}
	if ( is_child_theme() ) {
		wp_enqueue_style('the-erudite-child', get_bloginfo('stylesheet_url') );
	}

	wp_enqueue_script( 'scrollTo', $template_url.'js/jquery.scrollTo-min.js', array('jquery'), '1.4.2', true);
	wp_enqueue_script('erudite', $template_url.'js/common.js', array('jquery', 'scrollTo'), $ver, true);
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
