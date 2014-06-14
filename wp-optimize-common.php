<?php

# --------------------------------------- #
# prevent file from being accessed directly
# --------------------------------------- #
if ( ! defined( 'WPINC' ) ) {
	die;
}

// common functions
if (! defined('WPO_PLUGIN_MAIN_PATH'))
	define('WPO_PLUGIN_MAIN_PATH', plugin_dir_path( __FILE__ ));
            
if (! defined('WPO_PLUGIN_URL'))
	define('WPO_PLUGIN_URL', plugin_dir_url( __FILE__ ));
	
if (! defined('OPTION_NAME_SCHEDULE'))
    define('OPTION_NAME_SCHEDULE', 'wp-optimize-schedule');	

if (! defined('OPTION_NAME_SCHEDULE_TYPE'))
    define('OPTION_NAME_SCHEDULE_TYPE', 'wp-optimize-schedule-type');

if (! defined('OPTION_NAME_RETENTION_ENABLED'))
    define('OPTION_NAME_RETENTION_ENABLED', 'wp-optimize-retention-enabled');	

if (! defined('OPTION_NAME_RETENTION_PERIOD'))
    define('OPTION_NAME_RETENTION_PERIOD', 'wp-optimize-retention-period');	

if (! defined('OPTION_NAME_LAST_OPT'))
    define('OPTION_NAME_LAST_OPT', 'wp-optimize-last-optimized');	

if (! defined('OPTION_NAME_ENABLE_ADMIN_MENU'))
    define('OPTION_NAME_ENABLE_ADMIN_MENU', 'wp-optimize-enable-admin-menu');	

if (! defined('OPTION_NAME_TOTAL_CLEANED'))
    define('OPTION_NAME_TOTAL_CLEANED', 'wp-optimize-total-cleaned');
	

function wpo_readFeed($rss_url, $number_of_itmes){

    include_once( ABSPATH . WPINC . '/feed.php' );
    $rss = fetch_feed( $rss_url );

    if ( ! is_wp_error( $rss ) ) { // Checks that the object is created correctly

            // Figure out how many total items there are, but limit it to 5. 
            $maxitems = $rss->get_item_quantity( $number_of_itmes ); 

            // Build an array of all the items, starting with element 0 (first element).
            $rss_items = $rss->get_items( 0, $maxitems );
            if ($maxitems > 0) {
                return $rss_items;
            }
    }
    else {
        $rss_items = NULL;
        return $rss_items;
    }
    
}


/**
 * wpo_detectDBType()
 * this function is redundant 
 * @return void
 */
function wpo_detectDBType() {

	global $wpdb;
    //global $table_prefix;
	$tablestype = $wpdb->get_results("SHOW TABLE STATUS WHERE Name = `$wpdb->options`");
	foreach($tablestype as  $tabletype) {
		$table_engine = $tabletype->Engine;
	}	
	
	$wpo_table_type = strtolower(strval($table_engine));
	
//if (! defined('WPO_TABLE_TYPE'))      
//        define( WPO_TABLE_TYPE,$wpo_table_type);

return $wpo_table_type;
       
}

/*
 * function wpo_getRetainInfo()
 * 
 * parameters: none
 * 
 * it returns 2 options values
 *
 * @return (array of enabled state, period)
 */
function wpo_getRetainInfo(){
    $retain_enabled = get_option(OPTION_NAME_RETENTION_ENABLED, 'false' );
    
	if ($retain_enabled){
		$retain_period = get_option(OPTION_NAME_RETENTION_PERIOD, '2');
	}
	
	return array ($retain_enabled, $retain_period);
	
}


/*
 * function wpo_debugLog()
 * 
 * parameters: message to debug
 * 
 * 
 *
 * @return none
 */
function wpo_debugLog($message) {
    if (WP_DEBUG === true) {
        if (is_array($message) || is_object($message)) {
            error_log(print_r($message, true));
        } else {
            error_log($message);
        }
    }
}

/*
 * function wpo_headerImage()
 * 
 * parameters: none
 * 
 * it returns header image and fb code
 *
 * @return $text
 */
function wpo_headerImage(){
	
	$text = '<img src="'.WPO_PLUGIN_URL.'/wp-optimize.png" border="0" alt="WP-Optimize" title="WP-Optimize" width="310px" height="auto"/><br />';

	$text .='<iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.ruhanirabin.com%2Fwp-optimize%2F&amp;width=310&amp;height=46&amp;colorscheme=light&amp;layout=standard&amp;action=like&amp;show_faces=false&amp;send=true&amp;" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:310px; height:46px;" allowTransparency="true"></iframe>';
	echo $text;
	
}

/*
 * function wpo_removeOptions()
 * 
 * parameters: none
 * 
 * deletes all option values from wp_options table
 *
 * @return none
 */
function wpo_removeOptions(){

	delete_option( 'wp-optimize-weekly-schedule' );
	delete_option( OPTION_NAME_SCHEDULE );
	delete_option( OPTION_NAME_RETENTION_ENABLED );
	delete_option( OPTION_NAME_RETENTION_PERIOD );
	delete_option( OPTION_NAME_LAST_OPT );
	delete_option( OPTION_NAME_ENABLE_ADMIN_MENU );	
	delete_option( OPTION_NAME_SCHEDULE_TYPE );	
	delete_option( OPTION_NAME_TOTAL_CLEANED );	
	
    delete_option( 'wp-optimize-auto' );	
    delete_option( 'wp-optimize-settings' );

}

/*
 * function wpo_cron_action()
 * 
 * parameters: none
 * 
 * executed this function on cron event
 *
 * @return none
 */
function wpo_cron_action() {
	global $wpdb;
	list ($retention_enabled, $retention_period) = wpo_getRetainInfo();
	
    wpo_debugLog('Starting wpo_cron_action()');
    if ( get_option(OPTION_NAME_SCHEDULE) == 'true') {
            			
			$this_options = get_option('wp-optimize-auto');
            // revisions
            if ($this_options['revisions'] == 'true'){
    			$clean = "DELETE FROM `$wpdb->posts` WHERE post_type = 'revision'";
                if ($retention_enabled == 'true') {
                    $clean .= ' and post_modified < NOW() - INTERVAL ' .  $retention_period . ' WEEK';
                }
                $clean .= ';';
    			$revisions = $wpdb->query( $clean );
		    }
            
            // auto drafts
            if ($this_options['drafts'] == 'true'){			
                $clean = "DELETE FROM `$wpdb->posts` WHERE post_status = 'auto-draft'";
                if ($retention_enabled == 'true') {
                    $clean .= ' and post_modified < NOW() - INTERVAL ' .  $retention_period . ' WEEK';
                }
                $clean .= ';';
                $autodraft = $wpdb->query( $clean );
			
            
                // trash posts
				// TODO:  query trashed posts and cleanup metadata 
    			$clean = "DELETE FROM `$wpdb->posts` WHERE post_status = 'trash'";
                if ($retention_enabled == 'true') {
                    $clean .= ' and post_modified < NOW() - INTERVAL ' .  $retention_period . ' WEEK';
                }
                $clean .= ';';
                $posttrash = $wpdb->query( $clean );
            }
            
            // spam comments
            if ($this_options['spams'] == 'true'){	
    			$clean = "DELETE FROM `$wpdb->comments` WHERE comment_approved = 'spam'";
                if ($retention_enabled == 'true') {
    				$clean .= ' and comment_date < NOW() - INTERVAL ' . $retention_period . ' WEEK';
                }
                $clean .= ';';
                $comments = $wpdb->query( $clean );			
            			
            // trashed comments
			// TODO:  query trashed comments and cleanup metadata 
                $clean = "DELETE FROM `$wpdb->comments` WHERE comment_approved = 'trash'";
                if ($retention_enabled == 'true') {
    				$clean .= ' and comment_date < NOW() - INTERVAL ' . $retention_period . ' WEEK';
                }
                $clean .= ';';			
                $commentstrash = $wpdb->query( $clean );
                
			// TODO:  still need to test now cleaning up comments meta tables 
                $clean = "DELETE FROM `$wpdb->commentmeta` WHERE comment_id NOT IN ( SELECT comment_id FROM `$wpdb->comments` )";
                $clean .= ';';			
                $commentstrash1 = $wpdb->query( $clean );                

			// TODO:  still need to test now cleaning up comments meta tables - removing akismet related settings 
                $clean = "DELETE FROM `$wpdb->commentmeta` WHERE meta_key LIKE '%akismet%'";
                $clean .= ';';			
                $commentstrash2 = $wpdb->query( $clean );                


			}
            
            // transient options
            if ($this_options['transient'] == 'true'){
    			$clean = "DELETE FROM `$wpdb->options` WHERE option_name LIKE '_transient_%' OR option_name LIKE '_site_transient_%'";
                $clean .= ';';			
                $transient_options = $wpdb->query( $clean );
            }

            // postmeta
			// TODO:  refactor this with proper query
            if ($this_options['postmeta'] == 'true'){
    			$clean = "DELETE pm FROM  `$wpdb->postmeta`  pm LEFT JOIN  `$wpdb->posts`  wp ON wp.ID = pm.post_id WHERE wp.ID IS NULL";
                $clean .= ';';			
                 
				//$postmeta = $wpdb->query( $clean );
            }

            // unused tags
            if ($this_options['tags'] == 'true'){            
    			//$clean = "DELETE t,tt FROM  `$wpdb->terms` t INNER JOIN `$wpdb->term_taxonomy` tt ON t.term_id=tt.term_id WHERE tt.taxonomy='post_tag' AND tt.count=0";
                //$clean .= ';';			
                //$tags = $wpdb->query( $clean );
            }
			
		//db optimize part - optimize
        if ($this_options['optimize'] == 'true'){            
    
            $db_tables = $wpdb->get_results('SHOW TABLES',ARRAY_A);
    		foreach ($db_tables as $table){
    			$t = array_values($table);
    			$wpdb->query("OPTIMIZE TABLE `".$t[0]."`");
                wpo_debugLog('optimizing .... '.$t[0]);
    		}
    		
    		//$dateformat = __('l jS \of F Y h:i:s A');
    		//$dateformat = 'l jS \of F Y h:i:s A';
            //$thisdate = date($dateformat);
            //$thisdate = gmdate(get_option('date_format') . ' ' . get_option('time_format'), $time() + (get_option('gmt_offset')));
    		list($part1, $part2) = wpo_getCurrentDBSize();
     
            $thistime = current_time( "timestamp", 0 );
            $thedate = gmdate(get_option('date_format') . ' ' . get_option('time_format'), $thistime );
            update_option( OPTION_NAME_LAST_OPT, $thedate );
            wpo_updateTotalCleaned(strval($part2));
            wpo_debugLog('Updating options with value +'.$part2);

        } // endif $this_options['optimize'] 		
	}	// end if ( get_option(OPTION_NAME_SCHEDULE) == 'true')
}	

/*
 * function wpo_PluginOptionsSetDefaults()
 * 
 * parameters: none
 * 
 * setup options if not exists already
 *
 * @return none
 */
function wpo_PluginOptionsSetDefaults() {
		$deprecated = null;
		$autoload = 'no';
		
	if ( get_option( OPTION_NAME_SCHEDULE ) !== false ) {
		// The option already exists, so we just update it.

	} else {
		// The option hasn't been added yet. We'll add it with $autoload set to 'no'.
		add_option( OPTION_NAME_SCHEDULE, 'false', $deprecated, $autoload );
		add_option( OPTION_NAME_LAST_OPT, 'Never', $deprecated, $autoload );	
		add_option( OPTION_NAME_SCHEDULE_TYPE, 'wpo_weekly', $deprecated, $autoload );	
		// deactivate cron
		wpo_cron_deactivate();
	}
	if ( get_option( OPTION_NAME_RETENTION_ENABLED ) !== false ) {
	//
	}
	else{
	    add_option( OPTION_NAME_RETENTION_ENABLED, 'false', $deprecated, $autoload );
		add_option( OPTION_NAME_RETENTION_PERIOD, '2', $deprecated, $autoload ); 
	}
	
	if ( get_option( OPTION_NAME_ENABLE_ADMIN_MENU ) !== false ) {
	//
	}
	else{
	    add_option( OPTION_NAME_ENABLE_ADMIN_MENU, 'false', $deprecated, $autoload );
	}
    
	if ( get_option( OPTION_NAME_TOTAL_CLEANED ) !== false ) {
	//
	}
	else{
	    add_option( OPTION_NAME_TOTAL_CLEANED, '0', $deprecated, $autoload );
	}
    
    if ( get_option( 'wp-optimize-auto' ) !== false ) {
		// The option already exists, so we just update it.

	} else {
        // 'revisions', 'drafts', 'spams', 'unapproved', 'transient', 'postmeta', 'tags' 
    	$new_options = array(
    		'revisions' => 'true',
    		'drafts' => 'true',
    		'spams' => 'true',
    		'unapproved' => 'false',
    		'transient' => 'false',
    		'postmeta' => 'false',
    		'tags' => 'false',
    		'optimize' => 'true'
    	);
    
    	update_option( 'wp-optimize-auto', $new_options );
        }

        // settings for main screen
        if ( get_option( 'wp-optimize-settings' ) !== false ) {
		// The option already exists, so we just update it.

	} else {
        // 'revisions', 'drafts', 'spams', 'unapproved', 'transient', 'postmeta', 'tags' 
    	$new_options_main = array(
    		'user-revisions' => 'true',
    		'user-drafts' => 'true',
    		'user-spams' => 'true',
    		'user-unapproved' => 'true',
    		'user-transient' => 'false',
    		'user-optimize' => 'true'
    	);
    
    	update_option( 'wp-optimize-settings', $new_options_main );
        }
    	
} 


/**
 * wpo_format_size()
 * Function: Format Bytes Into KB/MB
 * @param mixed $rawSize
 * @return
 */
  if(!function_exists('wpo_format_size')) {

	function wpo_format_size($rawSize) {
		if($rawSize / 1073741824 > 1)
			return number_format_i18n($rawSize/1048576, 1) . ' '.__('Gb', 'wp-optimize');
		else if ($rawSize / 1048576 > 1)
			return number_format_i18n($rawSize/1048576, 1) . ' '.__('Mb', 'wp-optimize');
		else if ($rawSize / 1024 > 1)
			return number_format_i18n($rawSize/1024, 1) . ' '.__('Kb', 'wp-optimize');
		else
			return number_format_i18n($rawSize, 0) . ' '.__('bytes', 'wp-optimize');
	}
}

/*
 * function wpo_getCurrentDBSize()
 * 
 * parameters: none
 * 
 * this function will return total database size and a possible gain of db in KB
 *
 * @return array $total size, $gain
 */
function wpo_getCurrentDBSize(){
	global $wpdb;
	$total_gain = 0;
	$total_size = 0;
	$no = 0;
	$row_usage = 0;
	$data_usage = 0;
	$index_usage = 0;
	$overhead_usage = 0;
	$tablesstatus = $wpdb->get_results("SHOW TABLE STATUS");
	
        wpo_debugLog('Checking DB size .... ');
        foreach($tablesstatus as  $tablestatus) {
		$row_usage += $tablestatus->Rows;
		$data_usage += $tablestatus->Data_length;
		$index_usage +=  $tablestatus->Index_length;
                
                if ($tablestatus->Engine != 'innodb'){
                    $overhead_usage += $tablestatus->Data_free;
                    $total_gain += $tablestatus->Data_free;
                }
	}	
	
	$total_size = $data_usage + $index_usage;
        wpo_debugLog('Total Size .... '.$total_size);
        wpo_debugLog('Total Gain .... '.$total_gain);
	return array (wpo_format_size($total_size), wpo_format_size($total_gain));
    //$wpdb->flush();
	}
 // end of function wpo_getCurrentDBSize

/*
 * function wpo_updateTotalCleaned($current)
 * 
 * parameters: a string value
 * 
 * this function will return total saved data in KB
 *
 * @return total size
 */
function wpo_updateTotalCleaned($current){
    $previously_saved = get_option(OPTION_NAME_TOTAL_CLEANED,'0');
    $previously_saved = floatval($previously_saved);
	
    $converted_current = floatval($current);
    
    $total_now = $previously_saved + $converted_current;
    $total_now = strval($total_now);
    
    update_option(OPTION_NAME_TOTAL_CLEANED, $total_now);
    
    return $total_now; 	
	
} // end of function wpo_updateTotalCleaned

/*
 * function wpo_cleanUpSystem($cleanupType)
 * 
 * parameters: cleanup type
 * 
 * this function will do the cleanup
 *
 * @return $message
 */
function wpo_cleanUpSystem($cleanupType){
    global $wpdb;
    $clean = ""; $message = "";
    list ($retention_enabled, $retention_period) = wpo_getRetainInfo();
	
    switch ($cleanupType) {
        case "transient_options":
           // backticks
            $clean = "DELETE FROM `$wpdb->options` WHERE option_name LIKE '_transient_%' OR option_name LIKE '_site_transient_%'";
            $clean .= ';';
			
			$transient_options = $wpdb->query( $clean );
            $message .= $transient_options.' '.__('transient options deleted', 'wp-optimize').'<br>';
            break;
		// TODO:  need to use proper query
        case "postmeta":
            $clean = "DELETE pm FROM  `$wpdb->postmeta`  pm LEFT JOIN  `$wpdb->posts`  wp ON wp.ID = pm.post_id WHERE wp.ID IS NULL";
            $clean .= ';';
			
			//$postmeta = $wpdb->query( $clean );
            //$message .= $postmeta.' '.__('orphaned postmeta deleted', 'wp-optimize').'<br>';
            break;

        case "tags":
//            $clean = "DELETE t,tt FROM  `$wpdb->terms` t INNER JOIN `$wpdb->term_taxonomy` tt ON t.term_id=tt.term_id WHERE tt.taxonomy='post_tag' AND tt.count=0";
//            $clean .= ';';
//			
//			$tags = $wpdb->query( $clean );
//            $message .= $tags.' '.__('unused tags deleted', 'wp-optimize').'<br>';
            break;

		case "revisions":
            $clean = "DELETE FROM `$wpdb->posts` WHERE post_type = 'revision'";
            if ($retention_enabled == 'true') {
                $clean .= ' and post_modified < NOW() - INTERVAL ' .  $retention_period . ' WEEK';
            }
            $clean .= ';';
			
			$revisions = $wpdb->query( $clean );
            $message .= $revisions.' '.__('post revisions deleted', 'wp-optimize').'<br>';
            break;

        case "autodraft":
            $clean = "DELETE FROM `$wpdb->posts` WHERE post_status = 'auto-draft'";
            if ($retention_enabled == 'true') {
                $clean .= ' and post_modified < NOW() - INTERVAL ' .  $retention_period . ' WEEK';
            }
            $clean .= ';';
			
            $autodraft = $wpdb->query( $clean );
            $message .= $autodraft.' '.__('auto drafts deleted', 'wp-optimize').'<br>';

            
			// TODO:  query trashed posts and cleanup metadata
			$clean = "DELETE FROM `$wpdb->posts` WHERE post_status = 'trash'";
            if ($retention_enabled == 'true') {
                $clean .= ' and post_modified < NOW() - INTERVAL ' .  $retention_period . ' WEEK';
            }
            $clean .= ';';
            $posttrash = $wpdb->query( $clean );
            $message .= $posttrash.' '.__('items removed from Trash', 'wp-optimize').'<br>';

            break;

        case "spam":
            $clean = "DELETE FROM `$wpdb->comments` WHERE comment_approved = 'spam'";
            if ($retention_enabled == 'true') {
				$clean .= ' and comment_date < NOW() - INTERVAL ' . $retention_period . ' WEEK';
            }
            $clean .= ';';
			
            $comments = $wpdb->query( $clean );
            $message .= $comments.' '.__('spam comments deleted', 'wp-optimize').'<br>';
            
            // TODO:  query trashed comments and cleanup metadata 
            $clean = "DELETE FROM `$wpdb->comments` WHERE comment_approved = 'trash'";
            if ($retention_enabled == 'true') {
				$clean .= ' and comment_date < NOW() - INTERVAL ' . $retention_period . ' WEEK';
            }
            $clean .= ';';			
            $commentstrash = $wpdb->query( $clean );
            $message .= $commentstrash.' '.__('items removed from Trash', 'wp-optimize').'<br>';
            
    		// TODO:  still need to test now cleaning up comments meta tables
            $clean = "DELETE FROM `$wpdb->commentmeta` WHERE comment_id NOT IN ( SELECT comment_id FROM `$wpdb->comments` )";
            $clean .= ';';			
            $commentstrash_meta = $wpdb->query( $clean );
            $message .= $commentstrash_meta.' '.__('unused comment metadata items removed', 'wp-optimize').'<br>';                

	   	    // TODO:  still need to test now cleaning up comments meta tables - removing akismet related settings 
            $clean = "DELETE FROM `$wpdb->commentmeta` WHERE meta_key LIKE '%akismet%'";
            $clean .= ';';			
            $commentstrash_meta2 = $wpdb->query( $clean );               
            $message .= $commentstrash_meta2.' '.__('unused akismet comment metadata items removed', 'wp-optimize').'<br>';
            break;

        case "unapproved":
            $clean = "DELETE FROM `$wpdb->comments` WHERE comment_approved = '0'";
            if ($retention_enabled == 'true') {
				$clean .= ' and comment_date < NOW() - INTERVAL ' . $retention_period . ' WEEK';
            }
            $clean .= ';';
            $comments = $wpdb->query( $clean );
            $message .= $comments.' '.__('unapproved comments deleted', 'wp-optimize').'<br>';
            break;
			
        case "pingbacks":
            $clean = "DELETE FROM `$wpdb->comments` WHERE comment_type = 'pingback';";
            $comments = $wpdb->query( $clean );
            $message .= $comments.' '.__('pingbacks deleted', 'wp-optimize').'<br>';
            break;

        case "trackbacks":
            $clean = "DELETE FROM `$wpdb->comments` WHERE comment_type = 'trackback';";
            $comments = $wpdb->query( $clean );
            $message .= $comments.' '.__('trackbacks deleted', 'wp-optimize').'<br>';
            break;			

			
        case "enable-weekly":
			update_option( OPTION_NAME_SCHEDULE, 'true' );
            $comments = '';
			$message .= $comments.' '.__('Enabled weekly processing', 'wp-optimize').'<br>';
            break;			

        case "disable-weekly":
            update_option( OPTION_NAME_SCHEDULE, 'false' );
            $comments = '';
			$message .= $comments.' '.__('Disabled weekly processing', 'wp-optimize').'<br>';
            break;

		//case "optimize-db":
           //optimizeTables(true);
           //$message .= "Database ".DB_NAME." Optimized!<br>";
           //break;

        default:
            $message .= __('NO Actions Taken', 'wp-optimize').'<br>';
            break;
    } // end of switch
return $message;
} // end of function

/*
 * function wpo_getInfo($cleanupType)
 * 
 * parameters: cleanup type
 * 
 * this function will do the cleanup
 *
 * @return $message
 */
function wpo_getInfo($cleanupType){
    global $wpdb;
    $sql = ""; $message = "";
    list ($retention_enabled, $retention_period) = wpo_getRetainInfo();
	
    switch ($cleanupType) {
        case "transient_options":
            $sql = "SELECT COUNT(*) FROM `$wpdb->options` WHERE option_name LIKE '_transient_%' OR option_name LIKE '_site_transient_%'";
            $sql .= ';';
            $transient_options = $wpdb->get_var( $sql );

            if(!$transient_options == 0 || !$transient_options == NULL){
              $message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$transient_options.' '.__('transient options in your database', 'wp-optimize');
            }
            else $message .='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.__('No transient options found', 'wp-optimize');
            break;

        case "postmeta":
            $sql = "SELECT COUNT(*) FROM  `$wpdb->postmeta`  pm LEFT JOIN  `$wpdb->posts`  wp ON wp.ID = pm.post_id WHERE wp.ID IS NULL";
            $sql .= ';';
            $postmeta = $wpdb->get_var( $sql );

            if(!$postmeta == 0 || !$postmeta == NULL){
              $message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$postmeta.' '.__('orphaned postmeta in your database', 'wp-optimize');
            }
            else $message .='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.__('No orphaned postmeta in your database', 'wp-optimize');
            break;

        case "tags":
            $sql = "SELECT COUNT(*) FROM  `$wpdb->terms` t INNER JOIN `$wpdb->term_taxonomy` tt ON t.term_id=tt.term_id WHERE tt.taxonomy='post_tag' AND tt.count=0";
            $sql .= ';';
            $tags = $wpdb->get_var( $sql );

            if(!$tags == 0 || !$tags == NULL){
              $message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$tags.' '.__('unused tags in your database', 'wp-optimize');
            }
            else $message .='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.__('No unused tags found', 'wp-optimize');
            break;

		case "revisions":
            $sql = "SELECT COUNT(*) FROM `$wpdb->posts` WHERE post_type = 'revision'";
			
            if ($retention_enabled == 'true') {
                $sql .= ' and post_modified < NOW() - INTERVAL ' .  $retention_period . ' WEEK';
            }
            $sql .= ';';
            $revisions = $wpdb->get_var( $sql );

            if(!$revisions == 0 || !$revisions == NULL){
              $message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$revisions.' '.__('post revisions in your database', 'wp-optimize');
            }
            else $message .='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.__('No post revisions found', 'wp-optimize');
            break;

        case "autodraft":
            $sql = "SELECT COUNT(*) FROM `$wpdb->posts` WHERE post_status = 'auto-draft'";

            if ($retention_enabled == 'true') {
                $sql .= ' and post_modified < NOW() - INTERVAL ' .  $retention_period . ' WEEK';
            }
            $sql .= ';';
            $autodraft = $wpdb->get_var( $sql );

            if(!$autodraft == 0 || !$autodraft == NULL){
              $message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$autodraft.' '.__('auto draft post(s) in your database', 'wp-optimize');
            }
            else $message .='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.__('No auto draft posts found', 'wp-optimize');
            break;
			
			
        case "spam":
            $sql = "SELECT COUNT(*) FROM `$wpdb->comments` WHERE comment_approved = 'spam'";
            if ($retention_enabled == 'true') {
                $sql .= ' and comment_date < NOW() - INTERVAL ' . $retention_period . ' WEEK';
            }
            $sql .= ';';			
            $comments = $wpdb->get_var( $sql );
            if(!$comments == NULL || !$comments == 0){
              $message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$comments.' '.__('spam comments found', 'wp-optimize').' | <a href="edit-comments.php?comment_status=spam">'.' '.__('Review', 'wp-optimize').'</a>';
            } else
              $message .='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.__('No spam comments found', 'wp-optimize');
              
            // TODO: still need to test 2 more sections for info - still need to test
//            $sql = "SELECT * FROM $wpdb->commentmeta WHERE comment_id NOT IN ( SELECT comment_id FROM $wpdb->comments )";
//            $sql .= ';';			
//            $comments_meta = $wpdb->query( $sql );
//            if(!$comments_meta == NULL || !$comments_meta == 0){
//              $message .= '&nbsp;|&nbsp;'.$comments_meta.' '.__('Unused comment meta found', 'wp-optimize');
//            } 
//
//
//            $sql = "SELECT * FROM $wpdb->commentmeta WHERE meta_key LIKE '%akismet%'";
//            $sql .= ';';			
//            $comments_meta2 = $wpdb->query( $sql );
//            if(!$comments_meta2 == NULL || !$comments_meta2 == 0){
//              $message .= '&nbsp;|&nbsp;'.$comments_meta2.' '.__('additional Akismet junk data found', 'wp-optimize');
//            } 


            break;

        case "unapproved":
            $sql = "SELECT COUNT(*) FROM `$wpdb->comments` WHERE comment_approved = '0'";
            if ($retention_enabled == 'true') {
                $sql .= ' and comment_date < NOW() - INTERVAL ' . $retention_period . ' WEEK';
            }
            $sql .= ';';
			$comments = $wpdb->get_var( $sql );
            if(!$comments == NULL || !$comments == 0){
              $message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$comments.' '.__('unapproved comments found', 'wp-optimize').' | <a href="edit-comments.php?comment_status=moderated">'.' '.__('Review', 'wp-optimize').'</a>';;
            } else
              $message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.__('No unapproved comments found', 'wp-optimize');

            break;

        case "pingbacks":
            $sql = "SELECT COUNT(*) FROM `$wpdb->comments` WHERE comment_type='pingback';";
            $comments = $wpdb->get_var( $sql );
            if(!$comments == NULL || !$comments == 0){
              $message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$comments.' '.__('Pingbacks found', 'wp-optimize');
            } else
              $message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.__('No pingbacks found', 'wp-optimize');

            break;
			
        case "trackbacks":
            $sql = "SELECT COUNT(*) FROM `$wpdb->comments` WHERE comment_type='trackback';";
            $comments = $wpdb->get_var( $sql );
            if(!$comments == NULL || !$comments == 0){
              $message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$comments.' '.__('Trackbacks found', 'wp-optimize');
            } else
              $message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.__('No trackbacks found', 'wp-optimize');

            break;
			
			
        default:
            $message .= __('nothing', 'wp-optimize');
            break;
    } // end of switch
return $message;
} // end of function

/*
 * function showStatus($text)
 * 
 * parameters: $text
 * 
 * this function will show a yellow status msg
 *
 * @return none
 */
function showStatus($text){
	echo '<div id="message" class="updated fade">';
    echo '<strong>'.$text.'</strong></div>';
}

// end of file
?>