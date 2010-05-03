<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes() ?>>
<head profile="http://gmpg.org/xfn/11">
	<title><?php wp_title( '-', true, 'right' ); echo wp_specialchars( get_bloginfo('name'), 1 ) ?></title>
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
// ]]>

	</script>
<?php	if (is_singular() ) { wp_enqueue_script('comment-reply'); } ?>
<?php wp_head() // For plugins ?>
	<link rel="alternate" type="application/rss+xml" href="<?php bloginfo('rss2_url') ?>" title="<?php printf( __( '%s latest posts', 'erudite' ), wp_specialchars( get_bloginfo('name'), 1 ) ) ?>" />
	<link rel="alternate" type="application/rss+xml" href="<?php bloginfo('comments_rss2_url') ?>" title="<?php printf( __( '%s latest comments', 'erudite' ), wp_specialchars( get_bloginfo('name'), 1 ) ) ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url') ?>" />
</head>
<body <?php body_class() ?>>

<div id="wrapper" class="hfeed">
	
	<div id="header-wrap">
		<div id="header" role="banner">
			<h1 id="blog-title"><span><a href="<?php bloginfo('home') ?>/" title="<?php echo wp_specialchars( get_bloginfo('name'), 1 ) ?>" rel="home"><?php bloginfo('name') ?></a></span></h1>
			<div id="blog-description"><?php bloginfo('description') ?></div>
		</div><!--  #header -->

		<div id="access" role="navigation">
			<div class="skip-link"><a href="#content" title="<?php _e( 'Skip to content', 'erudite' ) ?>"><?php _e( 'Skip to content', 'erudite' ) ?></a></div>
			<?php sandbox_globalnav() ?>
		</div><!-- #access -->

	</div><!--  #header-wrap -->