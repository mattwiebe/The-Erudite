<?php

// Theme options shamelessly stolen from the Thematic Framework, which itself adapted from "A Theme Tip For WordPress Theme Authors"
// http://literalbarrage.org/blog/archives/2007/05/03/a-theme-tip-for-wordpress-theme-authors/

$themename = 'The Erudite';
$shortname = 'erdt_';
global $erdt_nonce;
$erdt_nonce = 'erdt_nonce';

// Create theme options
global $erdt_options;
$erdt_options = array (

	array(	
		'name' => __('Dark Colour Scheme','erudite'),
		'desc' => __('For all the dark colour lovers out there','erudite'),
		'id' => $shortname.'color_dark',
		'std' => 'false',
		'type' => 'checkbox'
	),
	array(
		'name' => __('Use Categories in Menu','erudite'),
		'desc' => __('Use categories rather than pages for your top menu. Caution: this only works with a small number of categories','erudite'),
		'id' => $shortname.'category_nav',
		'std' => 'false',
		'type' => 'checkbox'
	),
	array(
		'name' => __('Footer &lsquo;About&rsquo; Blurb','erudite'),
		'desc' => __('The following text will appear in the footer for &lsquo;About&lsquo;. <br /><em>Note:</em> This will only appear if you have not enabled a widget for <strong>Erudite Widget 1</strong>','erudite'),
		'id' => $shortname.'footer_about',
		'std' => sprintf(__("This is an example of an &ldquo;About&rdquo; blurb. I&rsquo;m sure that the author of this blog is highly intelligent, witty and even <em>possibly</em> socially competent.\n\nIt can be modified from this theme&rsquo;s <strong>options panel</strong> in the admin area (Appearance &rarr; %s Options)", 'erudite'), $themename),
		'type' => 'textarea',
		'options' => array( 'rows' => '6', 'cols' => '70')
	),
	array(
		'name' => __('Disable &lsquo;Keep Reading&rsquo;','erudite'),
		'desc' => __('Turns off the dynamic <em>Keep Reading</em> / <em>Put Away</em> functionality on the homepage if it doesn\'t suit your fancy','erudite'),
		'id' => $shortname.'keepreading_disable',
		'std' => 'false',
		'type' => 'checkbox'
	),
	array(
		'name' => __('Disable Header/Footer Hiding','erudite'),
		'desc' => __('Turns off the dynamic show/hide functionality of the header and footer','erudite'),
		'id' => $shortname.'hide_disable',
		'std' => 'false',
		'type' => 'checkbox'),
	array(
		'name' => __('Enable Page Comments','erudite'),
		'desc' => __('WordPress thinks you should have comments on pages, which The Erudite thinks is a misunderstanding of what pages are. Check this to disagree and allow comments on pages.','erudite'),
		'id' => $shortname.'allow_page_comments',
		'std' => false,
		'type' => 'checkbox'),
	array(
		'name' => __('Disable footer credit','erudite'),
		'desc' => __('Removes the link to the theme\'s author in the footer. Leave it in to say &ldquo;thanks for the hard work.&rdquo;','erudite'),
		'id' => $shortname.'credit_disable',
		'std' => 'false',
		'type' => 'checkbox'),
	array(
		'name' => __('Enable comment threading','erudite'),
		'desc' => __("Allow comment threading. (Global <a href='options-discussion.php' title='Discussion settings'>discussion settings</a> have no effect unless this is checked first)<br /> This is <strong>off</strong> by default because threaded comments are rubbish. Turn on at your peril, and don't go more than 2 levels deep.",'erudite'),
		'id' => $shortname.'comment_threading',
		'std' => 'false',
		'type' => 'checkbox'),
	array(
		'name' => __('Disable editor style','erudite'),
		'desc' => __('Turn off the Visual editor styling','erudite'),
		'id' => $shortname.'editor_style_disable',
		'std' => 'false',
		'type' => 'checkbox'),
	array(
		'name' => __('Google Analytics code','erudite'),
		'desc' => __("Enter your <a href='http://www.google.com/analytics'>Google Analytics</a> (or other service) tracking code here, and it will automatically be placed just before the <code>&lt;body&gt;</code> tag.",'erudite'),
		'id' => $shortname.'analytics',
		'std' => '',
		'type' => 'textarea',
		'options' => array( 'rows' => '6', 'cols' => '70')
	)

);
		
$erdt_options = apply_filters('erdt_options', $erdt_options);

function erdt_add_admin() {

    global $themename, $shortname, $erdt_options, $erdt_nonce;

	if ( isset($_GET['page']) && $_GET['page'] == basename(__FILE__) && isset($_POST[$erdt_nonce]) && wp_verify_nonce($_POST[$erdt_nonce], $erdt_nonce ) && isset($_REQUEST['action']) ) {

		if ( 'save' == $_REQUEST['action'] ) {
			foreach ( $erdt_options as $value ) {
				if ( isset( $_REQUEST[ $value['id'] ] ) ) { 
					update_option( $value['id'], $_REQUEST[ $value['id'] ]  );
				} 
				else {
					delete_option( $value['id'] );
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

    add_theme_page($themename.' '.__('Options', 'erudite'), $themename.' '.__('Options', 'erudite'), 'edit_themes', basename(__FILE__), 'erdt_admin');

}

function erdt_admin() {

    global $themename, $shortname, $erdt_options, $erdt_nonce;

    if ( isset( $_REQUEST['saved'] ) ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' '.__('settings saved.','erudite').'</strong></p></div>';
    if ( isset( $_REQUEST['reset'] ) ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' '.__('settings reset.','erudite').'</strong></p></div>';
    
?>
<div class="wrap">
<?php if ( function_exists('screen_icon') ) screen_icon(); ?>
<h2><?php echo $themename . ' '; _e('Options', 'erudite'); ?></h2>

<form method="post" action="">

<table class="form-table">

<?php foreach ($erdt_options as $value) { 
	
	switch ( $value['type'] ) {
		case 'text': ?>
		<tr valign="top"> 
		    <th scope="row"><?php echo __($value['name'],'erudite'); ?>:</th>
		    <td>
		        <input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_option( $value['id'] ) != "") { echo get_option( $value['id'] ); } else { echo $value['std']; } ?>" />
			    <?php echo __($value['desc'],'erudite'); ?>
		    </td>
		</tr>
		<?php
		break;
		
		case 'select': ?>
		<tr valign="top"> 
	        <th scope="row"><?php echo __($value['name'],'erudite'); ?>:</th>
	        <td>
	            <select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
	                <?php foreach ($value['options'] as $option) { ?>
	                <option<?php if ( get_option( $value['id'] ) == $option) { echo ' selected="selected"'; } elseif ($option == $value['std']) { echo ' selected="selected"'; } ?>><?php echo $option; ?></option>
	                <?php } ?>
	            </select>
	        </td>
	    </tr>
		<?php
		break;
		
		case 'textarea':
		$ta_options = $value['options']; ?>
		<tr valign="top"> 
	        <th scope="row"><?php echo __($value['name'],'erudite'); ?>:</th>
	        <td>
			    <?php echo __($value['desc'],'erudite'); ?>
				<textarea name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" cols="<?php echo $ta_options['cols']; ?>" rows="<?php echo $ta_options['rows']; ?>"><?php 
				if( get_option($value['id']) != "") {
						echo __(stripslashes(get_option($value['id'])),'erudite');
					}else{
						echo __($value['std'],'erudite');
				}?></textarea>
	        </td>
	    </tr>
		<?php
		break;

		case "radio": ?>
		<tr valign="top"> 
	        <th scope="row"><?php echo __($value['name'],'erudite'); ?>:</th>
	        <td>
	            <?php foreach ($value['options'] as $key=>$option) { 
				$radio_setting = get_option($value['id']);
				if($radio_setting != ''){
		    		if ($key == get_option($value['id']) ) {
						$checked = "checked=\"checked\"";
						} else {
							$checked = "";
						}
				}else{
					if($key == $value['std']){
						$checked = "checked=\"checked\"";
					}else{
						$checked = "";
					}
				}?>
	            <input type="radio" name="<?php echo $value['id']; ?>" value="<?php echo $key; ?>" <?php echo $checked; ?> /> <?php echo $option; ?><br />
	            <?php } ?>
	        </td>
	    </tr>
		<?php
		break;
		
		case "checkbox": ?>
			<tr valign="top"> 
		        <th scope="row"><?php echo __($value['name'],'erudite'); ?>:</th>
		        <td>
		           <?php $checked = ( get_option($value['id']) ) ? 'checked="checked"' : ''; ?>
		           <label> <input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="true" <?php echo $checked; ?> />
			    <?php echo __($value['desc'],'erudite'); ?></label>
		        </td>
		    </tr>
			<?php
		break;

		default:

		break;
	}
}
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

add_action('admin_menu' , 'erdt_add_admin'); 


?>
