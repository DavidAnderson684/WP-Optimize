<?php
# --------------------------------------- #
# prevent file from being accessed directly
# --------------------------------------- #
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>



<?php
$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'wp_optimize_optimize';  
?>


<!--
<h2 class="nav-tab-wrapper">
		<a href="?page=WP-Optimize&tab=wp_optimize_optimize" class="nav-tab <?php echo $active_tab == 'wp_optimize_optimize' ? 'nav-tab-active' : ''; ?>">
        <?php 
        global $wpdb;
        $sqlversion = $wpdb->get_var("SELECT VERSION() AS version");
        //$php_version_info = substr(PHP_OS, 0, 3);
        _e('Optimizer', 'wp-optimize'); 
        if (defined('WPO_VERSION')){
            echo ' '.WPO_VERSION.' - ';
            _e('MYSQL', 'wp-optimize');
            echo ' '.$sqlversion.' - '; 
            echo PHP_OS;
        } //end if
        ?></a>
		<a href="?page=WP-Optimize&tab=wp_optimize_settings" class="nav-tab <?php echo $active_tab == 'wp_optimize_settings' ? 'nav-tab-active' : ''; ?>"><?php _e('Settings', 'wp-optimize') ?></a>
		<a href="?page=WP-Optimize&tab=wp_optimize_credits" class="nav-tab <?php echo $active_tab == 'wp_optimize_credits' ? 'nav-tab-active' : ''; ?>"><?php _e('Info', 'wp-optimize') ?></a>
</h2> -->

<div id='wpo_cssmenu'>
<ul>
	<li <?php echo $active_tab == 'wp_optimize_optimize' ? 'class="active"' : ''; ?> ><a href="?page=WP-Optimize&tab=wp_optimize_optimize"><span>        <?php 
        global $wpdb;
        $sqlversion = $wpdb->get_var("SELECT VERSION() AS version");
        //$php_version_info = substr(PHP_OS, 0, 3);
        _e('Optimizer', 'wp-optimize'); 
        if (defined('WPO_VERSION')){
            echo ' '.WPO_VERSION.' - ';
            _e('MYSQL', 'wp-optimize');
            echo ' '.$sqlversion.' - '; 
            echo PHP_OS;
        } //end if
        ?></a></span></li>

	<li <?php echo $active_tab == 'wp_optimize_tables' ? 'class="active"' : ''; ?>><a href="?page=WP-Optimize&tab=wp_optimize_tables"><span><?php _e('Tables', 'wp-optimize') ?></span></a></li>

	<li <?php echo $active_tab == 'wp_optimize_settings' ? 'class="active"' : ''; ?>><a href="?page=WP-Optimize&tab=wp_optimize_settings"><span><?php _e('Settings', 'wp-optimize') ?></span></a></li>

	<li <?php echo $active_tab == 'wp_optimize_credits' ? 'class="active"' : ''; ?>><a href='?page=WP-Optimize&tab=wp_optimize_credits'><span><?php _e('Info', 'wp-optimize') ?></span></a></li>

</ul>
</div>
<div class="wrap">

<?php
    //echo '<div id="message" class="updated fade">';
    //echo '<strong>'.$text.'</strong></div>';
    //echo '</div>';
?>


		<?php
        if (! defined('WPO_PLUGIN_MAIN_PATH'))
        	define('WPO_PLUGIN_MAIN_PATH', plugin_dir_path( __FILE__ ));
            			
			if( $active_tab == 'wp_optimize_optimize' ) {
				
                if (file_exists(WPO_PLUGIN_MAIN_PATH. 'wp-optimize-main.php')) {
                  include_once(WPO_PLUGIN_MAIN_PATH. 'wp-optimize-main.php');
                }
                else {
                  echo 'File is missing';  
                }
			} 
			
			if( $active_tab == 'wp_optimize_tables' ) {
				
                if (file_exists(WPO_PLUGIN_MAIN_PATH. 'wp-optimize-tables.php')) {
                  include_once(WPO_PLUGIN_MAIN_PATH. 'wp-optimize-tables.php');
                }
                else {
                  echo 'File is missing';  
                } 
                
			}



			if( $active_tab == 'wp_optimize_settings' ) {
				
                if (file_exists(WPO_PLUGIN_MAIN_PATH. 'wp-optimize-settings.php')) {
                  include_once(WPO_PLUGIN_MAIN_PATH. 'wp-optimize-settings.php');
                }
                else {
                  echo 'File is missing';  
                }
                
			}
			
			if( $active_tab == 'wp_optimize_credits' ) {

                if (file_exists(WPO_PLUGIN_MAIN_PATH. 'wp-optimize-credits.php')) {
                  include_once(WPO_PLUGIN_MAIN_PATH. 'wp-optimize-credits.php');
                }
                else {
                  echo 'File is missing';  
                }

			}

			
			
		?>



</div>