<?php
function comments_custom($comment, $args, $depth) {
$GLOBALS['comment'] = $comment; ?>
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
	<div id="comment-<?php comment_ID(); ?>">
		<div class="comment-meta-wrap">
			<div class="comment-author vcard">
			<?php echo get_avatar($comment,$args['avatar_size']); ?>
			<?php printf( __('<cite class="fn">%s</cite> <span class="says">wrote:</span>', 'erudite'), get_comment_author_link() ) ?>
			</div>
			<?php if ($comment->comment_approved == '0') : ?>
			<em><?php _e('Your comment is awaiting moderation.', 'erudite') ?></em>
			<br />
			<?php endif; ?>
			<div class="comment-meta commentmetadata"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><?php printf(__('%1$s at %2$s'), get_comment_date(), get_comment_time()) ?></a><?php edit_comment_link(__('(Edit)'),' ','') ?></div>
		</div>
		<?php comment_text() ?>
	<div class="reply">
	<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
	</div>
	</div>
<?php } ?>
