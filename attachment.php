<?php get_header() ?>

	<div id="container">
		<div id="content" role="main">

<?php the_post() ?>

			<h2 class="page-title"><a href="<?php echo get_permalink($post->post_parent) ?>" title="<?php printf( __( 'Return to %s', 'erudite' ), esc_attr( get_the_title($post->post_parent) ) ) ?>" rev="attachment"><?php echo get_the_title($post->post_parent) ?></a></h2>

			<div id="post-<?php the_ID() ?>" <?php post_class() ?>>
				<h3 class="entry-title"><?php the_title() ?></h3>
				<div class="entry-content">
					<div class="entry-attachment"><a href="<?php echo wp_get_attachment_url($post->ID) ?>" title="<?php echo esc_attr( get_the_title($post->ID) ) ?>" rel="attachment"><?php echo basename($post->guid) ?></a></div>
					<div class="entry-caption"><?php if ( !empty($post->post_excerpt) ) the_excerpt() ?></div>
<?php the_content() ?>

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

		</div><!-- #content -->
	</div><!-- #container -->

<?php get_sidebar() ?>
<?php get_footer() ?>