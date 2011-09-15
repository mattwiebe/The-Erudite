
	<div id="footer-wrap">
		<div id="footer-wrap-inner">
			<div id="primary" class="footer">
				<ul class="xoxo">
		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar(1) ) : // begin primary sidebar widgets ?>

					<li id="about-sidebar">
						<h3><?php _e( 'About', 'erudite' ) ?></h3>
						<div>
						<?php erdt_the_option('footer_about', true) ?>
						</div>
					</li>
					
					<li id="pages">
						<h3><?php _e( 'Pages', 'erudite' ) ?></h3>
						<ul>
		<?php wp_list_pages('title_li=&sort_column=menu_order' ) ?>
						</ul>
					</li>
								
		<?php endif; // end primary sidebar widgets  ?>
				</ul>
			</div><!-- #primary .sidebar -->

		<div id="secondary" class="footer">
			<ul class="xoxo">
	<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar(2) ) : // begin primary sidebar widgets ?>

				<li id="categories">
					<h3><?php _e( 'Categories', 'erudite' ) ?></h3>
					<ul>
	<?php wp_list_categories('title_li=&show_count=0&hierarchical=1') ?> 

					</ul>
				</li>

				<li id="archives">
					<h3><?php _e( 'Archives', 'erudite' ) ?></h3>
					<ul>
	<?php wp_get_archives('type=monthly') ?>

					</ul>
				</li>
	<?php endif; // end primary sidebar widgets  ?>
			</ul>
		</div><!-- #secondary .sidebar -->

		<div id="ternary" class="footer">
			<ul class="xoxo">
	<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar(3) ) : // begin secondary sidebar widgets ?>
				<li id="search">
					<h3><label for="s"><?php _e( 'Search', 'erudite' ) ?></label></h3>
					<form id="searchform" class="blog-search" method="get" action="<?php echo home_url() ?>">
						<div>
							<input id="s" name="s" type="text" class="text" value="<?php the_search_query() ?>" size="10" tabindex="1" />
							<input type="submit" class="button" value="<?php _e( 'Find', 'erudite' ) ?>" tabindex="2" />
						</div>
					</form>
				</li>

	<?php wp_list_bookmarks('title_before=<h3>&title_after=</h3>&show_images=1') ?>

				<li id="rss-links">
					<h3><?php _e( 'RSS Feeds', 'erudite' ) ?></h3>
					<ul>
						<li><a href="<?php bloginfo('rss2_url') ?>" title="<?php printf( __( '%s latest posts', 'erudite' ), esc_attr( get_bloginfo('name') ) ) ?>" rel="alternate" type="application/rss+xml"><?php _e( 'All posts', 'erudite' ) ?></a></li>
						<li><a href="<?php bloginfo('comments_rss2_url') ?>" title="<?php printf( __( '%s latest comments', 'erudite' ), esc_attr( get_bloginfo('name') ) ) ?>" rel="alternate" type="application/rss+xml"><?php _e( 'All comments', 'erudite' ) ?></a></li>
					</ul>
				</li>

				<li id="meta">
					<h3><?php _e( 'Meta', 'erudite' ) ?></h3>
					<ul>
						<?php wp_register() ?>

						<li><?php wp_loginout() ?></li>
						<?php wp_meta() ?>

					</ul>
				</li>
	<?php endif; // end secondary sidebar widgets  ?>
			</ul>
		</div><!-- #ternary .sidebar -->