<!DOCTYPE html>
<html <?php language_attributes() ?> class="no-js">
<head>
	<meta charset="<?php bloginfo('charset') ?>" />
	<title><?php wp_title( '-', true, 'right' ); bloginfo('name'); ?></title>
	<meta name="profile" content="http://gmpg.org/xfn/11" />
	<!--[if lte IE 7 ]> <link rel="stylesheet" href="<?php bloginfo('template_directory') ?>/css/ie7.css" type="text/css"> <![endif]-->
	<!--[if IE 8 ]> <link rel="stylesheet" href="<?php bloginfo('template_directory') ?>/css/ie8.css" type="text/css"> <![endif]-->
	<script>
		var erdt = <?php echo erdt_js_options() ?>;
		(function(H){H.className=H.className.replace(/\bno-js\b/,'js')})(document.documentElement);
	</script>
	<link rel="pingback" href="<?php bloginfo('pingback_url') ?>" />
<?php	if (is_singular() ) { wp_enqueue_script('comment-reply'); } ?>
<?php wp_head() // For plugins ?>
</head>
<body <?php body_class() ?>>

<div id="wrapper" class="hfeed">
	
	<div id="header-wrap">
		<div id="header" role="banner">
			<h1 id="blog-title"><span><a href="<?php echo home_url('/') ?>" title="<?php echo esc_attr( get_bloginfo('name') ) ?>" rel="home"><?php bloginfo('name') ?></a></span></h1>
			<div id="blog-description"><?php bloginfo('description') ?></div>
		</div><!--  #header -->

		<div id="access" role="navigation">
			<div class="skip-link"><a href="#content" title="<?php _e( 'Skip to content', 'erudite' ) ?>"><?php _e( 'Skip to content', 'erudite' ) ?></a></div>
			<?php erdt_globalnav() ?>
		</div><!-- #access -->

	</div><!--  #header-wrap -->