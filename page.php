<?php get_header() ?>

	<div id="container">
		<div id="content" role="main">

<?php the_post() ?>

			<div id="post-<?php the_ID() ?>" <?php post_class() ?>>
				<h2 class="entry-title"><?php the_title() ?></h2>
				<div class="entry-content">
<?php the_content() ?>

<?php wp_link_pages('before=<p class="page-link">' . __( 'Pages:', 'erudite' ) . '&after=</p>&link_before=<span>&link_after=</span>') ?>

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