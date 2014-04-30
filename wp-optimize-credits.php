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
    <p><?php _e('WP-Optimize started as a utility for my own projects. I have realized soon, that this plugin might help a lot of people out there. I am personally thanking all of the users who use this plugin as a daily basis. Also thank you all of the translators and the generous people who have donated for this project.','wp-optimize'); ?><br />
    <br />
    - Ruhani Rabin </p>
        <a href="#" 
      onclick="
      window.open(
     'https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent('http://www.ruhanirabin.com/wp-optimize/'), 
     'facebook-share-dialog', 
     'width=626,height=436'); 
      return false;">
      <?php _e('Share this plugin on Facebook','wp-optimize');?>
    </a>
	
	<br /><br />
	  <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="hosted_button_id" value="LTCMF6JDX94QS">
		<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG_global.gif" border="0" name="submit" alt="PayPal � The safer, easier way to pay online.">
		<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
	  </form>
    
	<br /><br />
 	
	<h3><?php _e('Contributing Developers','wp-optimize'); ?></h3><br />
	<a href="mailto:plugins@ruhanirabin.com" target="_blank" alt="" title="E-mail"><?php _e('Help me make this plugin better','wp-optimize'); ?></a>, <?php _e('I am looking for contributing developers.','wp-optimize'); ?><br />
	<?php _e('Your name and website will be credited here in the plugin','wp-optimize'); ?>.<br />
	&nbsp;
			</div>
		</div>	
</div>

<div class="wpo_col wpo_span_1_of_3">
		<div class="postbox">
			<div class="inside">
	<h3><?php _e('Translators','wp-optimize'); ?></h3><br />
	<h4><a href="<?php _e('http://www.ruhanirabin.com/','wp-optimize'); ?>" target="_blank" alt="" title=""><?php _e('Default Language by Ruhani Rabin (Change this text and the link inside translation file)','wp-optimize') ?></a></h4>
	<p>	
	<b><a href="<?php echo WPO_PLUGIN_URL.'languages/wp-optimize.pot'; ?>" target="_blank" title=""><?php _e('Download .POT File to translate','wp-optimize'); ?></a> | <?php _e('Email your translations to','wp-optimize'); ?> <a href="mailto:plugins@ruhanirabin.com">plugins@ruhanirabin.com</a></b>
	</p>
	<br />
		<br />

	
	<h3><?php _e('Plugin Resources','wp-optimize'); ?></h3>
	<h4>
	<p><a href="http://wordpress.org/plugins/wp-optimize/" target="_blank"><?php _e('Plugin Homepage', 'wp-optimize'); ?></a></p>
    <p><a href="https://github.com/ruhanirabin/WP-Optimize/issues" target="_blank"><?php _e('Support (GitHub)', 'wp-optimize'); ?></a></p>
    <p><a href="http://wordpress.org/plugins/wp-optimize/changelog/" target="_blank"><?php _e('Change Log', 'wp-optimize'); ?></a></p>
    <p><a href="http://wordpress.org/plugins/wp-optimize/faq/" target="_blank"><?php _e('FAQ', 'wp-optimize'); ?></a></p>
    </h4>
			</div>
		</div>	
</div>

<div class="wpo_col wpo_span_1_of_3">
		<div class="postbox">
			<div class="inside">
		<h3><?php _e('Development Log','wp-optimize'); ?></h3>
		<?php // Get RSS Feed(s)
		include_once( ABSPATH . WPINC . '/feed.php' );

		// Get a SimplePie feed object from the specified feed source.
		$rss = fetch_feed( 'http://plugins.trac.wordpress.org/log/wp-optimize?limit=20&mode=stop_on_copy&format=rss' );

		if ( ! is_wp_error( $rss ) ) : // Checks that the object is created correctly

			// Figure out how many total items there are, but limit it to 5. 
			$maxitems = $rss->get_item_quantity( 5 ); 

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
						<b><?php printf( __( 'Update %s', 'wp-optimize' ), $item->get_date('j F Y | g:i a') ); ?></b>
						<p><small>
						<?php //echo esc_html( $item->get_description() ); ?>
						<?php echo $item->get_description(); ?>
						</small></p>
					</li>
				<?php endforeach; ?>
			<?php endif; ?>
		</ul>
			</div>
		</div>	
</div>