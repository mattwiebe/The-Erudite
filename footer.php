
			<div id="footer">
				<span id="generator-link"><a href="http://wordpress.org/" title="<?php _e( 'WordPress', 'erudite' ) ?>" rel="generator"><?php _e( 'WordPress', 'erudite' ) ?></a></span>
<?php if ( ! get_option('erdt_credit_disable') ) { //check theme option before showing footer credit ?>
				<span class="meta-sep">|</span>
				<span id="theme-link"><a href="http://somadesign.ca/" title="<?php _e( 'The Erudite theme for WordPress', 'erudite' ) ?>" rel="designer<?php if(!is_home()){echo " nofollow";} ?>"><?php _e( 'The Erudite', 'erudite' ) ?></a></span>
<?php } ?>
			</div><!-- #footer -->
		</div><!-- #footer-wrap-inner -->
	</div><!-- #footer-wrap -->

</div><!-- #wrapper .hfeed -->
<?php wp_footer() ?>
</body>
</html>