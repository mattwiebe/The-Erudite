<?php

// Theme options shamelessly stolen from the Thematic Framework, which itself adapted from "A Theme Tip For WordPress Theme Authors"
// http://literalbarrage.org/blog/archives/2007/05/03/a-theme-tip-for-wordpress-theme-authors/


global $erdt_nonce, $erdt_themename, $erdt_shortname, $erdt_options, $erdt_;
$erdt_nonce = 'erdt_nonce';
$erdt_themename = 'The Erudite';
$erdt_shortname = 'erdt_';

// Create theme options
$erdt_options = array (
	array(
		'name' => __('Dark Colour Scheme','erudite'),
		'desc' => __('For all the dark colour lovers out there','erudite'),
		'id' => $erdt_shortname.'color_dark',
		'std' => 'false',
		'type' => 'checkbox'
	),
	array(
		'name' => __('Use Categories in Menu','erudite'),
		'desc' => __('Use categories rather than pages for your top menu. Caution: this only works with a small number of categories','erudite'),
		'id' => $erdt_shortname.'category_nav',
		'std' => 'false',
		'type' => 'checkbox'
	),
	array(
		'name' => __('Footer &lsquo;About&rsquo; Blurb','erudite'),
		'desc' => __('The following text will appear in the footer for &lsquo;About&lsquo;. <br /><em>Note:</em> This will only appear if you have not enabled a widget for <strong>Erudite Widget 1</strong>','erudite'),
		'id' => $erdt_shortname.'footer_about',
		'std' => sprintf(__("This is an example of an ‘About’ blurb. I’m sure that the author of this blog is highly intelligent, witty and even <em>possibly</em> socially competent.\n\nIt can be modified from this theme‘s <strong>options panel</strong> in the admin area (Appearance –> %s Options)", 'erudite'), $erdt_themename),
		'type' => 'textarea',
		'options' => array( 'rows' => '6', 'cols' => '70')
	),
	array(
		'name' => __('Disable &lsquo;Keep Reading&rsquo;','erudite'),
		'desc' => __('Turns off the dynamic <em>Keep Reading</em> / <em>Put Away</em> functionality on the homepage if it doesn\'t suit your fancy','erudite'),
		'id' => $erdt_shortname.'keepreading_disable',
		'std' => 'false',
		'type' => 'checkbox'
	),
	array(
		'name' => __('Disable Header/Footer Hiding','erudite'),
		'desc' => __('Turns off the dynamic show/hide functionality of the header and footer','erudite'),
		'id' => $erdt_shortname.'hide_disable',
		'std' => 'false',
		'type' => 'checkbox'
	),
	array(
		'name' => __('Enable Page Comments','erudite'),
		'desc' => __('WordPress thinks you should have comments on pages, which The Erudite thinks is a misunderstanding of what pages are. Check this to disagree and allow comments on pages.','erudite'),
		'id' => $erdt_shortname.'allow_page_comments',
		'std' => false,
		'type' => 'checkbox'
	),
	array(
		'name' => __('Disable footer credit','erudite'),
		'desc' => __('Removes the link to the theme\'s author in the footer. Leave it in to say &ldquo;thanks for the hard work.&rdquo;','erudite'),
		'id' => $erdt_shortname.'credit_disable',
		'std' => 'false',
		'type' => 'checkbox'
	),
	array(
		'name' => __('Enable comment threading','erudite'),
		'desc' => __("Allow comment threading. (Global <a href='options-discussion.php' title='Discussion settings'>discussion settings</a> have no effect unless this is checked first)<br /> This is <strong>off</strong> by default because threaded comments are rubbish. Turn on at your peril, and don't go more than 2 levels deep.",'erudite'),
		'id' => $erdt_shortname.'comment_threading',
		'std' => 'false',
		'type' => 'checkbox'
	),
	array(
		'name' => __('Disable editor style','erudite'),
		'desc' => __('Turn off the Visual editor styling','erudite'),
		'id' => $erdt_shortname.'editor_style_disable',
		'std' => 'false',
		'type' => 'checkbox'
	)
);

if ( is_child_theme() ) {
	$erdt_options[] = array(
		'name' => __('Disable parent style','erudite'),
		'desc' => __('Don&rsquo;t include the parent theme&rsquo;s CSS. My own CSS will suffice.','erudite'),
		'id' => $erdt_shortname.'disable_parent_css',
		'std' => 'false',
		'type' => 'checkbox'
	);
}


$erdt_options = apply_filters('erdt_options', $erdt_options);

add_action('admin_menu' , 'erdt_add_admin');
function erdt_add_admin() {
    add_theme_page($erdt_themename.' '.__('Options', 'erudite'), $erdt_themename.' '.__('Options', 'erudite'), 'edit_themes', basename(__FILE__), 'erdt_admin');
}

add_action( 'load-appearance_page_theme-options', 'erdt_maybe_save' );
function erdt_maybe_save() {
	global $erdt_themename, $erdt_shortname, $erdt_options, $erdt_nonce;

	if ( wp_verify_nonce($_POST[$erdt_nonce], $erdt_nonce ) && isset($_REQUEST['action']) ) {

		if ( 'save' == $_REQUEST['action'] ) {
			foreach ( $erdt_options as $opt ) {
				if ( isset( $_REQUEST[ $opt['id'] ] ) ) {
					$cleaned_value = erdt_validate_option($_REQUEST[ $opt['id'] ], $opt );
					update_option( $opt['id'], $_REQUEST[ $opt['id'] ]  );
				}
				else {
					delete_option( $opt['id'] );
				}
			}
			$_REQUEST['saved'] = 1;
		}
		else if ( 'reset' == $_REQUEST['action'] ) {
			foreach ($erdt_options as $value) {
				delete_option( $value['id'] );
			}
			$_REQUEST['reset'] = 1;
		}
	}
}

function erdt_validate_option($value, $option) {
	$clean = null;
	switch ( $value['type'] ) {
		case 'text':
			$clean = (string) strip_tags( $value );
			break;
		case 'textarea':
			$clean = wp_filter_post_kses($value);
			break;
		case 'checkbox':
			$clean = ( $value === 'on' ) ? 'on' : false;
			break;
		case 'select':
		case 'radio':
			$clean = ( in_array( $value, array_keys($option['options']) ) ) ? $value : false;
			break;
		default:
			break;
	}
	return $clean;
}

function erdt_admin() {
	global $erdt_themename, $erdt_shortname, $erdt_options, $erdt_nonce;

	if ( isset( $_REQUEST['saved'] ) ) echo '<div id="message" class="updated fade"><p><strong>'.$erdt_themename.' '.__('settings saved.','erudite').'</strong></p></div>';
	if ( isset( $_REQUEST['reset'] ) ) echo '<div id="message" class="updated fade"><p><strong>'.$erdt_themename.' '.__('settings reset.','erudite').'</strong></p></div>';

?>
<div class="wrap">
<?php if ( function_exists('screen_icon') ) screen_icon(); ?>
<h2><?php echo $erdt_themename . ' '; _e('Options', 'erudite'); ?></h2>

<form method="post" action="">

<table class="form-table">

<?php foreach ($erdt_options as $value) {
	// Save some undefined indices
	if ( ! isset( $value['std'] ) ) {
		$value['std'] = '';
	}
	?>
	<tr>
		<th scope="row"><?php echo $value['name']; ?>:</th>
		<td>
<?php

	switch ( $value['type'] ) {
		case 'text': ?>
			<input name="<?php echo $value['id'] ?>" id="<?php echo $value['id'] ?>" type="text" value="<?php echo esc_attr( get_option( $value['id'], $value['std'] ) ) ?>" />
			<?php if ( isset( $value['desc'] ) ) {
				echo $value['desc'];
			}
		break;

		case 'select': ?>
			<select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
				<?php
				$saved = get_option( $value['id'], $value['std'] );
				foreach ( $value['options'] as $option ) : ?>
				<option<?php selected($saved, $option); ?>><?php echo $option; ?></option>
				<?php endforeach; ?>
			</select>
		<?php
		break;

		case 'textarea':
			$ta_options = $value['options']; ?>
			<?php if ( isset( $value['desc'] ) ) {
				echo $value['desc'];
			} ?>
			<textarea name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" cols="<?php echo $ta_options['cols']; ?>" rows="<?php echo $ta_options['rows']; ?>"><?php
				echo esc_textarea( get_option($value['id'], $value['std'] ) );
			?></textarea>
		<?php
		break;

		case "radio":
			$radio_setting = get_option($value['id'], $value['std']);
			foreach ( $value['options'] as $key => $option ) : ?>
				<input type="radio" name="<?php echo $value['id']; ?>" value="<?php echo $key; ?>" <?php checked($radio_setting, $key) ?> /> <?php echo $option; ?><br />
			<?php endforeach;

		break;

		case "checkbox": ?>
			<label><input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" <?php checked( get_option($value['id'] ), 'on' ) ?> />
			<?php if ( isset( $value['desc'] ) ) {
				echo $value['desc'];
			} ?></label>
			<?php
		break;

		default:

		break;
	}
?>
		</td>
	</tr>
<?php

} // end foreach
?>

</table>

<p class="submit">
<input name="save" type="submit" value="<?php _e('Save changes','erudite'); ?>" class="button-primary" />
<input type="hidden" name="action" value="save" />
<?php wp_nonce_field($erdt_nonce, $erdt_nonce, false); ?>
</p>
</form>
<form method="post" action="">
<p class="submit">
<input name="reset" type="submit" value="<?php _e('Reset','erudite'); ?>" />
<input type="hidden" name="action" value="reset" />
<?php wp_nonce_field($erdt_nonce, $erdt_nonce, false); ?>
</p>
</form>
</div>

<?php
}


?>
