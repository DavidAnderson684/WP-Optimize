<?php
# --------------------------------------- #
# prevent file from being accessed directly
# --------------------------------------- #
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<!-- a feed reader to read the development log -->
<div class="wpo_section wpo_group">
	<div class="wpo_col wpo_span_1_of_3">
		<div class="postbox">
			<div class="inside">
			<h3><?php _e('Credits','wp-optimize'); ?></h3>
    			<p>
    				<?php _e('WP-Optimize started as a utility for my own projects. I soon realized, that this plugin might help a lot of people. I am personally thanking all of the users who use this plugin on a daily basis. Also, thank you to all of the translators and the generous people who have donated to this project.','wp-optimize'); ?>
    			</p>
    			<p>
    				- Ruhani Rabin 
    			</p>
    			
			<br /><br />
			
	  		<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="hosted_button_id" value="LTCMF6JDX94QS">
			<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG_global.gif" name="submit" alt="PayPal The safer, easier way to pay online.">
			<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
	  		</form>

			<br /><br />

			<h3><?php _e('Contributing Developers','wp-optimize'); ?></h3>
			<br />
			<a href="mailto:plugins@ruhanirabin.com" target="_blank" title="E-mail"><?php _e('Help me make this plugin better','wp-optimize'); ?></a>, <?php _e('I am looking for contributing developers.','wp-optimize'); ?>
			<br />
			<?php _e('Your name and website will be credited here in the plugin.','wp-optimize'); ?>
			<br />
			</div>
		</div>
	</div>

	<div class="wpo_col wpo_span_1_of_3">
		<div class="postbox">
			<div class="inside">
			<h3><?php _e('Translators','wp-optimize'); ?></h3>
			<br />
			<h3><a href="<?php _e('http://(Translator Website)','wp-optimize'); ?>" target="_blank" title=""><?php _e('(Translator name)','wp-optimize') ?></a></h3>

			<br /><br />

			<h3><?php _e('Plugin Resources','wp-optimize'); ?></h3>
        		<p>
        			<b><a href="https://translate.wordpress.org/projects/wp-plugins/wp-optimize" target="_blank"><?php _e('Plugin Translation Portal (needs WordPress account)', 'wp-optimize'); ?></a></b>
        		</p>
        		<p>
        			<b><a href="http://ruhanirabin.github.io/WP-Optimize/" target="_blank"><?php _e('Plugin Homepage', 'wp-optimize'); ?></a></b>
        		</p>
        		<p>
        			<b><a href="https://github.com/ruhanirabin/WP-Optimize/issues" target="_blank"><?php _e('Support (GitHub)', 'wp-optimize'); ?></a></b>
        		</p>
        		<p>
        			<b><a href="mailto:plugins@ruhanirabin.com" target="_blank"><?php _e('Support E-mail', 'wp-optimize'); ?></a></b>
        		</p>
        		<p>
        			<b><a href="https://wordpress.org/plugins/wp-optimize/changelog/" target="_blank"><?php _e('Change Log', 'wp-optimize'); ?></a></b>
        		</p>
        		<p>
        			<b><a href="https://wordpress.org/plugins/wp-optimize/faq/" target="_blank"><?php _e('FAQ', 'wp-optimize'); ?></a></b>
        		</p>
			</div>
		</div>
	</div>

	<div class="wpo_col wpo_span_1_of_3">
		<div class="postbox">
			<div class="inside">
			<h3><?php _e('GitHub Development Log','wp-optimize'); ?></h3>
			<?php // Get RSS Feed(s)
			include_once( ABSPATH . WPINC . '/feed.php' );

			// Get a SimplePie feed object from the specified feed source.
			$rss = fetch_feed( 'https://github.com/ruhanirabin/wp-optimize/commits/master.atom' );

			if ( ! is_wp_error( $rss ) ) : // Checks that the object is created correctly

			// Figure out how many total items there are, but limit it to 5.
			$maxitems = $rss->get_item_quantity( 8 );

			// Build an array of all the items, starting with element 0 (first element).
			$rss_items = $rss->get_items( 0, $maxitems );

			endif;
			?>

			<ul>
				<?php if ( $maxitems == 0 ) : ?>
					<li><?php _e( 'No items', 'wp-optimize' ); ?></li>
				<?php else : ?>
				<?php // Loop through each feed item and display each item as a hyperlink. ?>
				<?php foreach ( $rss_items as $item ) : ?>
					<li>
						<p>
						<?php //echo esc_html( $item->get_description() ); ?>
						<a href="<?php echo $item->get_link(); ?>" title="<?php echo $item->get_title(); ?>" target="_blank"><?php echo $item->get_title(); ?></a>
                                                &nbsp;
                                                <small><?php echo $item->get_date('j F Y | g:i a') ; ?></small>
						</p>
					</li>
				<?php endforeach; ?>
				<?php endif; ?>
			</ul>
			</div>
		</div>
	</div>
</div>
