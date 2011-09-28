<?php

/**
* A unique identifier is defined to store the options in the database and reference them from the theme.
* By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
* If the identifier changes, it'll appear as if the options have been reset.
*
*/

function optionsframework_option_name() {

	// This gets the theme name from the stylesheet (lowercase and without spaces)
	$themename = get_theme_data(TEMPLATEPATH . '/style.css');
	$themename = $themename['Name'];
	$themename = preg_replace("/\W/", "", strtolower($themename) );

	$optionsframework_settings = get_option('optionsframework');
	$optionsframework_settings['id'] = $themename;
	update_option('optionsframework', $optionsframework_settings);
}

function optionsframework_options() {
	$options = array (
		array(
			'name' => __( 'The Erudite Settings', 'erudite' ),
			'type' => 'heading'
		),
		array(
			'name' => __('Dark Colour Scheme','erudite'),
			'desc' => __('For all the dark colour lovers out there','erudite'),
			'id' => 'color_dark',
			'type' => 'checkbox',
			'std' => false
		),
		array(
			'name' => __('Footer &lsquo;About&rsquo; Blurb','erudite'),
			'desc' => __('The following text will appear in the footer for &lsquo;About&lsquo;. <br /><em>Note:</em> This will only appear if you have not enabled a widget for <strong>Erudite Widget 1</strong>','erudite'),
			'id' => 'footer_about',
			'std' => sprintf(__("This is an example of an ‘About’ blurb. I’m sure that the author of this blog is highly intelligent, witty and even <em>possibly</em> socially competent.\n\nIt can be modified from this theme‘s <strong>options panel</strong> in the admin area (Appearance → Theme Options)", 'erudite') ),
			'type' => 'textarea',
			'options' => array( 'rows' => '6', 'cols' => '70')
		),
		array(
			'name' => __('Disable &lsquo;Keep Reading&rsquo;','erudite'),
			'desc' => __('Turns off the dynamic <em>Keep Reading</em> / <em>Put Away</em> functionality on the homepage if it doesn\'t suit your fancy','erudite'),
			'id' => 'keepreading_disable',
			'std' => false,
			'type' => 'checkbox'
		),
		array(
			'name' => __('Disable Header/Footer Hiding','erudite'),
			'desc' => __('Turns off the dynamic show/hide functionality of the header and footer','erudite'),
			'id' => 'hide_disable',
			'std' => false,
			'type' => 'checkbox'
		),
		array(
			'name' => __('Enable Page Comments','erudite'),
			'desc' => __('WordPress thinks you should have comments on pages, which The Erudite thinks is a misunderstanding of what pages are. Check this to disagree and allow comments on pages.','erudite'),
			'id' => 'allow_page_comments',
			'std' => false,
			'type' => 'checkbox'
		),
		array(
			'name' => __('Disable footer credit','erudite'),
			'desc' => __('Removes the link to the theme\'s author in the footer. Leave it in to say &ldquo;thanks for the hard work.&rdquo;','erudite'),
			'id' => 'credit_disable',
			'std' => false,
			'type' => 'checkbox'
		),
		array(
			'name' => __('Enable comment threading','erudite'),
			'desc' => __("Allow comment threading. (Global <a href='options-discussion.php' title='Discussion settings'>discussion settings</a> have no effect unless this is checked first)<br /> This is <strong>off</strong> by default because threaded comments are rubbish. Turn on at your peril, and don't go more than 2 levels deep.",'erudite'),
			'id' => 'comment_threading',
			'std' => false,
			'type' => 'checkbox'
		),
		array(
			'name' => __('Disable editor style','erudite'),
			'desc' => __('Turn off the Visual editor styling','erudite'),
			'id' => 'editor_style_disable',
			'std' => false,
			'type' => 'checkbox'
		)
	);

	if ( is_child_theme() ) {
		$options[] = array(
			'name' => __('Disable parent style','erudite'),
			'desc' => __('Don&rsquo;t include the parent theme&rsquo;s CSS. My own CSS will suffice.','erudite'),
			'id' => 'disable_parent_css',
			'std' => false,
			'type' => 'checkbox'
		);
	}

	return apply_filters('erdt_options', $options);
}