<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes() ?> class="no-js">
<head profile="http://gmpg.org/xfn/11">
	<title><?php wp_title( '-', true, 'right' ); bloginfo('name'); ?></title>
	<meta http-equiv="content-type" content="<?php bloginfo('html_type') ?>; charset=<?php bloginfo('charset') ?>" />
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory') ?>/css/erudite.css" />
	<!--[if lte IE 6]> <link rel="stylesheet" href="<?php bloginfo('template_directory') ?>/css/ie6.css" type="text/css"> 
	<style type="text/css" media="screen">
		.hr {behavior: url(<?php bloginfo('template_directory') ?>/library/iepngfix.htc); }
	</style> <![endif]-->
	<!--[if lte IE 7 ]> <link rel="stylesheet" href="<?php bloginfo('template_directory') ?>/css/ie7.css" type="text/css"> <![endif]-->
	<!--[if gte IE 8 ]> <link rel="stylesheet" href="<?php bloginfo('template_directory') ?>/css/ie8.css" type="text/css"> <![endif]-->
	<script type="text/javascript">
// <![CDATA[
		var erdt = {
			More: '<?php _e("<span>↓</span> Keep Reading", "erudite" ) ?>',
			Less: '<?php _e("<span>↑</span> Put Away", "erudite" ) ?>',
			Info: '<?php _e("&#x2193; Further Information", "erudite" ) ?>',
			MenuShow: '<?php _e("<span>↓</span> Show Menu", "erudite" ) ?>',
			MenuHide: '<?php _e("<span>↑</span> Hide Menu", "erudite" ) ?>',
			DisableKeepReading: <?php the_theme_option("erdt_keepreading_disable") ?>,
			DisableHide: <?php the_theme_option("erdt_hide_disable") ?> 
		};
		(function(H){H.className=H.className.replace(/\bno-js\b/,'js')})(document.documentElement)
// ]]>
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