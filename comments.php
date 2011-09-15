			<div id="comments">
<?php
	if ( ! empty($post->post_password) ) :
		if ( post_password_required() ) :
?>
				<div class="nopassword disabled"><?php _e( 'This post is protected. Enter the password to view any comments.', 'erudite' ) ?></div>
			</div><!-- .comments -->
<?php
		return;
	endif;
endif;

if ( ! comments_open() ) {
	echo '<p class="disabled">' . __('Comments are disabled for this post', 'erudite' ) . '</p>';
	echo '</div><!-- .comments -->';
	return;
}

?>
	<h4><?php comments_number(__('No Comments', 'erudite'), __('One Comment', 'erudite'), __('% Comments', 'erudite') );?></h4>
<?php $max_threading = ( ! erdt_get_option('comment_threading') ) ? '&max_depth=1' : ''; // check for theme option to possibly allow threaded comments for those insane enough to really want them ?>
<?php if ( have_comments() ) : // show the comments ?>

	<ul class="commentlist" id="singlecomments">
	<?php wp_list_comments('avatar_size=32&reply_text='.__('Respond to this', 'erudite').'&callback=comments_custom'.$max_threading); ?>
	</ul>
	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>
<?php endif;

if ( 'open' == $post->comment_status ) :

	$req = get_option( 'require_name_email' );
	
	add_filter('comment_form_default_fields', 'erdt_default_fields', 1);
	function erdt_default_fields($fields) {
		$req = get_option( 'require_name_email' );
		$reqtext = $req ? ' <span class="required">*</span>' : '';
		extract( wp_get_current_commenter() );
		return array(
			'author' => '<div class="form-label"><label for="author">' . __( 'Name', 'erudite' ) . '</label>' .  $reqtext . '</div><div class="form-input"><input id="author" name="author" class="text" type="text" value="' . $comment_author . '" size="30" /></div>',
			'email' => '<div class="form-label"><label for="email">' . __( 'Email', 'erudite' ) . '</label>' . $reqtext . '</div><div class="form-input"><input id="email" name="email" class="text" type="text" value="' . $comment_author_email . '" size="30" /></div>',
			'url' => '<div class="form-label"><label for="url">' . __( 'Website', 'erudite' ) . '</label></div><div class="form-input"><input id="url" name="url" class="text" type="text" value="' . $comment_author_url . '" size="30" /></div>'
		);
	}
	
	add_action('comment_form_before_fields', 'erdt_comment_form_before_fields' );
	function erdt_comment_form_before_fields() {
		echo '<div class="user-info">';
	}
	add_action('comment_form_after_fields', 'erdt_comment_form_after_fields' );
	function erdt_comment_form_after_fields() {
		echo "</div>";
	}

	comment_form( array(
		'comment_notes_before' => '<p id="comment-notes">' . __( 'Your email is <em>never</em> shared.', 'erudite' ) . ( $req ? __( 'Required fields are marked <span class="required">*</span>', 'erudite' ) : '' ) . '</p>',
		'comment_notes_after' => ''
	));
endif // REFERENCE: if ( 'open' == $post->comment_status ) ?>

			</div><!-- #comments -->
