<?php
/*
This file is part of THE ERUDITE.

THE ERUDITE is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License v2 as published by the Free Software Foundation.

THE ERUDITE is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details: http://www.gnu.org/licenses/gpl-2.0.html
*/

// Translate, if applicable
load_theme_textdomain('erudite', get_template_directory() . '/translation');

if ( ! isset( $content_width ) )
	$content_width = 540;

// epigraphs
include_once "library/epigraph.php";

// my own filter for formatting
foreach ( array( 'wptexturize', 'convert_chars', 'wpautop' ) as $filter ) {
	add_filter( 'erdt_formatting', $filter );
}

add_action('init', 'erdt_go_away_page_comments');
function erdt_go_away_page_comments() {
	if ( ! erdt_get_option('allow_page_comments') )
		remove_post_type_support('page', 'comments');
}

// Customizer integration
add_action( 'customize_register', 'erdt_customize_register' );
function erdt_customize_register( $wp_customize ) {
	$wp_customize->get_setting('blogname')->transport='postMessage';
	$wp_customize->get_setting('blogdescription')->transport='postMessage';
	if ( $wp_customize->is_preview() && ! is_admin() ) {
		add_action( 'wp_footer', 'erdt_customize_preview', 21 );
	}
}
function erdt_customize_preview() {
?>
<script>
(function($){
	wp.customize('blogname',function( value ) {
		value.bind(function(to) {
			$('#blog-title a').text( to );
		});
	});
	wp.customize('blogdescription',function( value ) {
		value.bind(function(to) {
			$('#blog-description').text( to );
		});
	});
})(jQuery);
</script>
<?php
}

function erdt_js_options() {
	$js = array(
		'More' => __("<span>↓</span> Keep Reading", "erudite"),
		'Less' => __('<span>↑</span> Put Away', 'erudite' ),
		'Info' => __('&#x2193; Further Information', 'erudite' ),
		'MenuShow' => __('<span>↓</span> Show Menu', 'erudite' ),
		'MenuHide' => __('<span>↑</span> Hide Menu', 'erudite' ),
		'DisableKeepReading' => erdt_get_option( 'keepreading_disable' ),
		'DisableHide' => erdt_get_option( 'hide_disable' )
	);
	return json_encode( $js );
}

// empty titles
add_filter( 'the_title', 'erdt_empty_title' );
function erdt_empty_title( $title ) {
	if ( '' == $title )
		$title = __('[No Title]', 'erudite');

	return $title;
}

add_filter('mce_css', 'erudite_editor_style');
function erudite_editor_style($url) {
	if ( erdt_get_option('editor_style_disable') )
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
	if ( erdt_get_option('color_dark') )
		$classes[] = 'dark';

	// proper first-page class
	if ( is_home() && !is_paged() )
		$classes[] = "first-page";

	// show/hide functionality
	if ( ! erdt_get_option('hide_disable') )
		$classes[] = "hiding";

	// WP-Typography?
	if ( class_exists('wpTypography') )
		$classes[] = 'hypenation';

	return $classes;
}

/*
 * Helper function to return the theme option value. If no value has been saved, it returns $default.
 * Needed because options are saved as serialized strings.
 *
 * This code allows the theme to work without errors if the Options Framework plugin has been disabled.
 */
if ( ! function_exists( 'of_get_option' ) ) {

	add_action( 'admin_notices', 'erdt_prompt_options_framework' );

	function of_get_option($name, $default = false) {
		$optionsframework_settings = get_option('optionsframework');
		// Gets the unique option id
		$option_name = $optionsframework_settings['id'];

		if ( get_option($option_name) ) {
			$options = get_option($option_name);
		}
		else {
			return of_get_option_fallback($name, $default);
		}

		return ( isset($options[$name]) ) ? $options[$name] : $default;
	}

	// If Options Framework hasn't been installed, fall back to defaults
	function of_get_option_fallback($id, $default = false) {
		include_once TEMPLATEPATH . '/options.php';
		$options = optionsframework_options();
		$found_option = array();
		foreach ( $options as $opt ) {
			if ( isset($opt['id'] ) && $opt['id'] === $id ) {
				$found_option = $opt;
				break;
			}
		}
		return isset( $found_option['std'] ) ? $found_option['std'] : $default;
	}
}

function erdt_prompt_options_framework() {
	global $pagenow;
	$pages = array( 'index.php', 'themes.php', 'plugins.php' );

	if ( ! current_user_can( 'install_plugins' ) || ! in_array($pagenow, $pages ) ) {
		return;
	}
	$base = is_multisite() ? network_admin_url('plugin-install.php') : admin_url('plugin-install.php');
	$url = add_query_arg( array(
		'tab' => 'search',
		'type' => 'term',
		's' => 'Options Framework'
	), $base );
	$text = sprintf( __('The Erudite now requires the <a href="%s">Options Framework</a> plugin if you want to change theme options. (If you had previous theme options set, you will need to re-enter them. Some options have been removed.)'), $url );
	echo "<div class='updated'><p>{$text}</p></div>";
}

// generic theme option function. set second parameter to TRUE to add content formatting
function erdt_get_option( $id, $format = false ) {
	$return = of_get_option( $id );

	if ( empty( $return ) ) {
		$return = false;
	}

	if ( $format ) {
		$return = apply_filters( 'erdt_formatting', $return );
	}

	return $return;
}

function erdt_the_option( $id, $format = false ) {
	echo erdt_get_option( $id, $format );
}

// filter the_excerpt to give a proper ellipsis.
add_filter('the_excerpt', 'erdt_custom_excerpt');
function erdt_custom_excerpt($text) {
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
	$disable_parent = is_child_theme() && erdt_get_option('disable_parent_css');
	$template_url = trailingslashit(get_template_directory_uri());

	if ( ! $disable_parent ) {
		wp_enqueue_style('the-erudite', $template_url.'css/erudite.css');
	}
	if ( is_child_theme() ) {
		wp_enqueue_style('the-erudite-child', get_bloginfo('stylesheet_url') );
	}

	wp_enqueue_script('erudite', $template_url.'js/common.js', array('jquery'), $ver, true);
}


if ( function_exists('add_theme_support') ) {
	add_theme_support('automatic-feed-links');
}

if ( function_exists('register_nav_menus') ) {
	register_nav_menus( array('header-menu' => __('Header Menu', 'erudite') ) );
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

	if ( erdt_get_option('category_nav') ) {
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
		esc_attr( sprintf( __( 'Posts by %s', 'erudite' ), get_the_author() ) ),
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

// Runs our code at the end to check that everything needed has loaded
add_action( 'init', 'erdt_widgets_init' );

// Adds filters for the description/meta content in archives.php
add_filter( 'archive_meta', 'wptexturize' );
add_filter( 'archive_meta', 'convert_smilies' );
add_filter( 'archive_meta', 'convert_chars' );
add_filter( 'archive_meta', 'wpautop' );
