<?php
	if ( 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']) )
		die ( 'Please do not load this page directly. Thanks.' );
?>
			<div id="comments">
<?php
	if ( !empty($post->post_password) ) :
		if ( $_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password ) :
?>
				<div class="nopassword"><?php _e( 'This post is protected. Enter the password to view any comments.', 'erudite' ) ?></div>
			</div><!-- .comments -->
<?php
		return;
	endif;
endif;
?>
	<h4><?php comments_number(__('No Comments', 'erudite'), __('One Comment', 'erudite'), __('% Comments', 'erudite') );?></h4>
<?php if (get_the_theme_option('erdt_comment_threading') == "false" ) {$max_threading = "&max_depth=1"; } // check for theme option to possibly allow threaded comments for those insane enough to really want them ?>
<?php if ( have_comments() ) : // show the comments ?>

	<ul class="commentlist" id="singlecomments">
	<?php wp_list_comments('avatar_size=32&reply_text='.__('Respond to this', 'erudite').'&callback=comments_custom'.$max_threading); ?>
	</ul>
	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>
<?php endif; ?>

<?php if ( 'open' == $post->comment_status ) : ?>
<?php $req = get_option('require_name_email'); // Checks if fields are required. Thanks, Adam. ;-) ?>

				<div id="respond">
					<h3><?php comment_form_title( __( 'Post a Comment', 'erudite' ), __('Respond to %s', 'erudite' ) ); ?></h3>
					
					<div id="cancel-comment-reply"><?php cancel_comment_reply_link(__('Cancel this response', 'erudite')); ?></div>

<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
					<p id="login-req"><?php printf(__('You must be <a href="%s" title="Log in">logged in</a> to post a comment.', 'erudite'),
					get_bloginfo('wpurl') . '/wp-login.php?redirect_to=' . get_permalink() ) ?></p>

<?php else : ?>
					<div class="formcontainer">	
						<form id="commentform" action="<?php bloginfo('wpurl') ?>/wp-comments-post.php" method="post">
<?php comment_id_fields(); ?>

<?php if ( $user_ID ) : ?>
							<p id="login"><?php printf( __( '<span class="loggedin">Logged in as <a href="%1$s" title="Logged in as %2$s">%2$s</a>.</span> <span class="logout"><a href="%3$s" title="Log out of this account">Log out?</a></span>', 'erudite' ),
								get_bloginfo('wpurl') . '/wp-admin/profile.php',
								wp_specialchars( $user_identity, 1 ),
								wp_logout_url(get_permalink())
								) ?></p>

<?php else : ?>

							<p id="comment-notes"><?php _e( 'Your email is <em>never</em> shared.', 'erudite' ) ?> <?php if ($req) _e( 'Required fields are marked <span class="required">*</span>', 'erudite' ) ?></p>

							<div class="user-info">
								<div class="form-label"><label for="author"><?php _e( 'Name', 'erudite' ) ?></label> <?php if ($req) _e( '<span class="required">*</span>', 'erudite' ) ?></div>
								<div class="form-input"><input id="author" name="author" class="text<?php if ($req) echo ' required'; ?>" type="text" value="<?php echo $comment_author ?>" size="30" maxlength="50" tabindex="3" /></div>

								<div class="form-label"><label for="email"><?php _e( 'Email', 'erudite' ) ?></label> <?php if ($req) _e( '<span class="required">*</span>', 'erudite' ) ?></div>
								<div class="form-input"><input id="email" name="email" class="text<?php if ($req) echo ' required'; ?>" type="text" value="<?php echo $comment_author_email ?>" size="30" maxlength="50" tabindex="4" /></div>

								<div class="form-label"><label for="url"><?php _e( 'Website', 'erudite' ) ?></label></div>
								<div class="form-input"><input id="url" name="url" class="text" type="text" value="<?php echo $comment_author_url ?>" size="30" maxlength="50" tabindex="5" /></div>
							</div>

<?php endif // REFERENCE: * if ( $user_ID ) ?>

							<div class="user-comment">
								<div class="form-label"><label for="comment"><?php _e( 'Comment', 'erudite' ) ?></label></div>
								<div class="form-textarea"><textarea id="comment" name="comment" class="text required" cols="45" rows="8" tabindex="6"></textarea></div>

								<div class="form-submit"><input id="submit" name="submit" class="button" type="submit" value="<?php _e( 'Post Comment', 'erudite' ) ?>" tabindex="7" /><input type="hidden" name="comment_post_ID" value="<?php echo $id ?>" /></div>

								<div class="form-option"><?php do_action( 'comment_form', $post->ID ) ?></div>
							</div>

						</form><!-- #commentform -->
					</div><!-- .formcontainer -->
<?php endif // REFERENCE: if ( get_option('comment_registration') && !$user_ID ) ?>

				</div><!-- #respond -->
<?php endif // REFERENCE: if ( 'open' == $post->comment_status ) ?>

			</div><!-- #comments -->
