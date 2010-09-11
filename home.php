<?php get_header() ?>

	<div id="container">
		<div id="content" role="main">

<?php 
$first = 0; //set a counter to help add post classes below ?>
<?php while ( have_posts() ) : the_post() ?>
<?php $first++; 
if ($first == 1 && !is_paged() ) { //this is for the first post on the homepage only ?>
	<?php erdt_epigraph() ?>
	<div id="post-<?php the_ID() ?>" <?php post_class("first-post") ?>>
		<h2 class="entry-title"><a href="<?php the_permalink() ?>" title="<?php printf( __('Permalink to %s', 'erudite'), the_title_attribute('echo=0') ) ?>" rel="bookmark"><?php the_title() ?></a></h2>
		<div class="entry-content">

<?php the_content( __( 'Read More <span class="meta-nav">&rarr;</span>', 'erudite' ) );

} else { //subsequent posts on the homepage, all is_paged() posts as well ?>
	<?php $home_pager = ( ! is_paged() ) ? "home-post home-post-".$first : ''; //add home-post classes on front page only ?>
	<div id="post-<?php the_ID() ?>" <?php post_class($home_pager); ?>>
		<h3 class="entry-title"><a href="<?php the_permalink() ?>" title="<?php printf( __('Permalink to %s', 'erudite'), the_title_attribute('echo=0') ) ?>" rel="bookmark"><?php the_title() ?></a></h3>
		<div class="entry-content">
<?php the_excerpt(); ?>
			<p class="more-link"><a href="<?php the_permalink() ?>" title="<?php printf(__('Keep reading &lsquo;%s&rsquo;', 'erudite' ), the_title_attribute('echo=0') ) ?>"><?php _e('Read More <span class="meta-nav">&rarr;</span>', 'erudite' ) ?></a></p>
<?php } ?>
			<hr />
				<?php wp_link_pages('before=<p class="page-link">' . __( 'Pages:', 'erudite' ) . '&after=</p>&link_before=<span>&link_after=</span>') ?>
				</div>
				<div class="entry-meta">
					<span class="entry-date"><abbr class="published" title="<?php the_time('Y-m-d\TH:i:sO') ?>"><?php unset($previousday); printf( __( '%1$s &#8211; %2$s', 'erudite' ), the_date( '', '', '', false ), get_the_time() ) ?></abbr></span>
					<span class="meta-sep">|</span>
					<span class="author vcard"><?php printf( __( 'By %s', 'erudite' ), erdt_get_author_posts_link() ) ?></span>
					<span class="meta-sep">|</span>
					<span class="cat-links"><?php printf( __( 'Posted in %s', 'erudite' ), get_the_category_list(', ') ) ?></span>
					<span class="meta-sep">|</span>
					<?php the_tags( __( '<span class="tag-links">Tagged ', 'erudite' ), ", ", "</span>\n\t\t\t\t\t<span class=\"meta-sep\">|</span>\n" ) ?>
<?php edit_post_link( __( 'Edit', 'erudite' ), "\t\t\t\t\t<span class=\"edit-link\">", "</span>\n\t\t\t\t\t<span class=\"meta-sep\">|</span>\n" ) ?>
					<span class="comments-link"><?php comments_popup_link( __( 'Comments (0)', 'erudite' ), __( 'Comments (1)', 'erudite' ), __( 'Comments (%)', 'erudite' ) ) ?></span>
				</div>
			</div><!-- .post -->

<?php comments_template() ?>

<?php endwhile; ?>

			<div id="nav-below" class="navigation">
				<div class="nav-previous"><?php next_posts_link(__( '<span class="meta-nav">&larr;</span> Older posts', 'erudite' )) ?></div>
				<div class="nav-next"><?php previous_posts_link(__( 'Newer posts <span class="meta-nav">&rarr;</span>', 'erudite' )) ?></div>
			</div>

		</div><!-- #content -->
	</div><!-- #container -->

<?php get_sidebar() ?>
<?php get_footer() ?>