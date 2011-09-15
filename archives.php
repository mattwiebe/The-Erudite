<?php
/*
Template Name: Archives Page
*/
?>
<?php get_header() ?>

	<div id="container">
		<div id="content" role="main">

<?php the_post() ?>

			<div id="post-<?php the_ID() ?>" <?php post_class() ?>>
				<h2 class="entry-title"><?php the_title() ?></h2>
				<div class="entry-content">
<?php the_content() ?>

					<ul id="archives-page" class="xoxo">
						<li id="category-archives">
							<h3><?php _e( 'Archives by Category', 'erudite' ) ?></h3>
							<ul>
								<?php wp_list_categories('optioncount=1&title_li=&show_count=1') ?> 
							</ul>
						</li>
						<li id="monthly-archives">
							<h3><?php _e( 'Archives by Month', 'erudite' ) ?></h3>
							<ul>
								<?php wp_get_archives('type=monthly&show_post_count=1') ?>
							</ul>
						</li>
					</ul>
<?php edit_post_link( __( 'Edit', 'erudite' ), '<span class="edit-link">', '</span>' ) ?>

				</div>
			</div><!-- .post -->

<?php if ( post_type_supports('page', 'comments') ) {
	comments_template();
}  ?>

		</div><!-- #content -->
	</div><!-- #container -->

<?php get_sidebar() ?>
<?php get_footer() ?>