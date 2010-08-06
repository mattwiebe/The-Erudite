<?php

// Adds an epigraph

function erdt_epigraph($post_id = null, $return = false ) {
	if ($post_id === null ) {
		global $post;
		$post_id = $post->ID;
	}
	
	$epigraph = get_post_meta($post_id, 'epigraph', true);
	
	if (strlen(trim($epigraph)) > 1 ) {
		
		$epigraph = apply_filters('erdt_formatting', $epigraph);
		$epigraph = "<blockquote class='epigraph'>{$epigraph}</blockquote>";
		
		$cite = get_post_meta($post_id, 'epigraph_citation', true);
		if (strlen($cite) > 1 ) {
			$epigraph = str_replace( '</blockquote>',"\n<cite>&ndash;{$cite}</cite>\n</blockquote>", $epigraph );
		}
		
		if ( ! $return) {
			echo $epigraph;
		}
		return $epigraph;
	}
}

add_action('admin_menu', 'erdt_epigraph_meta_box');
function erdt_epigraph_meta_box() {
	add_meta_box('erdudte-epigraph', __('Epigraph', 'erudite'), 'erdt_epigraph_meta_box_contents', 'post', 'normal', 'high');
}

function erdt_epigraph_meta_box_contents() { 
	global $post; ?>
	<p><label for="epigraph"><?php _e('Epigraph', 'erudite') ?>:</label> <textarea name="epigraph" id="epigraph" rows="3" cols="40" style="width:98%"><?php esc_attr_e( get_post_meta( $post->ID, 'epigraph', true ) ) ?></textarea></p>
	
	<p><label for="epigraph_citation"><?php _e('Citation (optional)', 'erudite') ?>:</label> <input type="text" name="epigraph_citation" value="<?php esc_attr_e( get_post_meta( $post->ID, 'epigraph_citation', true ) ) ?>" id="epigraph_citation"></p>
	
	<?php wp_nonce_field('erdt_epigraph', 'erdt_epigraph_nonce', true, true);
}

add_action('save_post', 'erdt_epigraph_save');
function erdt_epigraph_save($post_id) {
	if ( isset( $_POST['erdt_epigraph_nonce'] ) && wp_verify_nonce( $_POST['erdt_epigraph_nonce'], 'erdt_epigraph' ) ) {
		if ( strlen( $_POST['epigraph'] ) > 0 ) :
			update_post_meta( $post_id, 'epigraph', stripslashes( $_POST['epigraph'] ) );
		else :
			delete_post_meta( $post_id, 'epigraph' );
		endif; 
		if ( strlen( $_POST['epigraph_citation'] ) > 0 ) :
			update_post_meta( $post_id, 'epigraph_citation', stripslashes( $_POST['epigraph_citation'] ) );
		else :
			delete_post_meta( $post_id, 'epigraph_citation' );
		endif;
		
	}
}