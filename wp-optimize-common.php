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

if (! defined('OPTION_NAME_CURRENT_CLEANED'))
    define('OPTION_NAME_CURRENT_CLEANED', 'wp-optimize-current-cleaned');

if (! defined('OPTION_NAME_ENABLE_EMAIL_ADDRESS'))
    define('OPTION_NAME_ENABLE_EMAIL_ADDRESS', 'wp-optimize-email-address');

if (! defined('OPTION_NAME_ENABLE_EMAIL'))
    define('OPTION_NAME_ENABLE_EMAIL', 'wp-optimize-email');
/**
 * wpo_sendemail($sendto, $msg)
 * @return success
 * @param $sentdo - eg. who to send it to, abc@def.com
 * @param $msg - the msg in text
 */
function wpo_sendEmail($date, $cleanedup){
//
    ob_start();
// #TODO this need to work on - currently not using the parameter values
$myTime = current_time( "timestamp", 0 );
$myDate = gmdate(get_option('date_format') . ' ' . get_option('time_format'), $myTime );

//$formattedCleanedup = wpo_format_size($cleanedup);


    if ( get_option( OPTION_NAME_ENABLE_EMAIL_ADDRESS ) !== "" ) {
    //
        $sendto = get_option( OPTION_NAME_ENABLE_EMAIL_ADDRESS );
    }
    else{
        $sendto = get_bloginfo ( 'admin_email' );
    }
        
//$thiscleanup = wpo_format_size($cleanedup);
    
$subject = get_bloginfo ( 'name' ).": ".__("Automatic Operation Completed","wp-optimize")." ".$myDate;

$msg  = __("Scheduled optimization was executed at","wp-optimize")." ".$myDate."\r\n"."\r\n";
//$msg .= __("Recovered space","wp-optimize").": ".$thiscleanup."\r\n";
$msg .= __("You can safely delete this email.","wp-optimize")."\r\n";
$msg .= "\r\n";
$msg .= __("Regards,","wp-optimize")."\r\n";
$msg .= __("WP-Optimize Plugin","wp-optimize");

//wp_mail( $sendto, $subject, $msg );

ob_end_flush();
}


/**
 * wpo_readFeed($rss_url, $number_of_itmes)
 * @return RSS items
 * @param $rss_url - url of RSS feed
 * @param number of items - number of items to return
 */
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
 * function wpo_disable_linkbacks()
 *
 * parameters: message to debug
 *
 *
 *
 * @return none
 */
function wpo_disableLinkbacks($type) {
global $wpdb;
	switch ($type) {
        case "trackbacks":

		$thissql = "UPDATE `$wpdb->posts` SET ping_status='closed' WHERE post_status = 'publish' AND post_type = 'post'";
		$thissql .= ';';
		$trackbacks = $wpdb->query( $thissql );
		break;

        case "comments":
		$thissql = "UPDATE `$wpdb->posts` SET comment_status='closed' WHERE post_status = 'publish' AND post_type = 'post'";
		$thissql .= ';';
		$comments = $wpdb->query( $thissql );
		break;


	default:
	//;
	break;
	}

}

/*
 * function wpo_disable_linkbacks()
 *
 * parameters: message to debug
 *
 *
 *
 * @return none
 */
function wpo_enableLinkbacks($type) {
global $wpdb;
	switch ($type) {
        case "trackbacks":

		$thissql = "UPDATE `$wpdb->posts` SET ping_status='open' WHERE post_status = 'publish' AND post_type = 'post'";
		$thissql .= ';';
		$trackbacks = $wpdb->query( $thissql );
		break;

        case "comments":
		$thissql = "UPDATE `$wpdb->posts` SET comment_status='open' WHERE post_status = 'publish' AND post_type = 'post'";
		$thissql .= ';';
		$comments = $wpdb->query( $thissql );
		break;


	default:
	//;
	break;
	}

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

	//$text .='<iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.ruhanirabin.com%2Fwp-optimize%2F&amp;width=310&amp;height=46&amp;colorscheme=light&amp;layout=standard&amp;action=like&amp;show_faces=false&amp;send=true&amp;" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:310px; height:46px;" allowTransparency="true"></iframe>';
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
    delete_option( OPTION_NAME_CURRENT_CLEANED );
	delete_option( OPTION_NAME_ENABLE_EMAIL_ADDRESS );
	delete_option( OPTION_NAME_ENABLE_EMAIL );

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
                //$commentstrash1 = $wpdb->query( $clean );

			}

            // transient options
            if ($this_options['transient'] == 'true'){
    			$clean = "DELETE FROM `$wpdb->options` WHERE option_name LIKE '_site_transient_browser_%' OR option_name LIKE '_site_transient_timeout_browser_%' OR option_name LIKE '_transient_feed_%' OR option_name LIKE '_transient_timeout_feed_%'";
                $clean .= ';';
                $transient = $wpdb->query( $clean );
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



            ob_start();
            list($part1, $part2) = wpo_getCurrentDBSize();            
            $thistime = current_time( "timestamp", 0 );
            $thedate = gmdate(get_option('date_format') . ' ' . get_option('time_format'), $thistime );
            update_option( OPTION_NAME_LAST_OPT, $thedate );
            wpo_updateTotalCleaned(strval($part2));

            // Sending notification email
            if ( get_option( OPTION_NAME_ENABLE_EMAIL ) !== false ) {
                //#TODO need to fix the problem with variable value not passing through
                if ( get_option( OPTION_NAME_ENABLE_EMAIL_ADDRESS ) !== '' ) {
                //wpo_sendEmail($thedate, $part2);                     
                }

            }
            else{
                //
            }
            ob_end_flush();
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
        // ---------
	if ( get_option( OPTION_NAME_ENABLE_EMAIL ) !== false ) {
	//
	}
	else{
	    //add_option( OPTION_NAME_ENABLE_EMAIL, 'true', $deprecated, $autoload );
	}    
        // ---------
	if ( get_option( OPTION_NAME_ENABLE_EMAIL_ADDRESS ) !== '' ) {
	//
	}
	else{
	    //add_option( OPTION_NAME_ENABLE_EMAIL_ADDRESS, get_bloginfo ( 'admin_email' ), $deprecated, $autoload );
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
			return number_format_i18n($rawSize/1073741824, 2) . ' '.__('GB', 'wp-optimize');
		else if ($rawSize / 1048576 > 1)
			return number_format_i18n($rawSize/1048576, 1) . ' '.__('MB', 'wp-optimize');
		else if ($rawSize / 1024 > 1)
			return number_format_i18n($rawSize/1024, 1) . ' '.__('KB', 'wp-optimize');
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

                if ($tablestatus->Engine != 'InnoDB'){
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
    update_option(OPTION_NAME_CURRENT_CLEANED, $current);

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
        case "transient":
           // backticks
            $clean = "DELETE FROM `$wpdb->options` WHERE option_name LIKE '_site_transient_browser_%' OR option_name LIKE '_site_transient_timeout_browser_%' OR option_name LIKE '_transient_feed_%' OR option_name LIKE '_transient_timeout_feed_%'";
            //$clean .= ';';

			$transient = $wpdb->query( $clean );
            $message .= sprintf(_n('%d transient option deleted', '%d transient options deleted', $transient, 'wp-optimize'), number_format_i18n($transient)).'<br>';
            break;
		// TODO:  need to use proper query
        case "postmeta":
            $clean = "DELETE pm FROM `$wpdb->postmeta` pm LEFT JOIN `$wpdb->posts` wp ON wp.ID = pm.post_id WHERE wp.ID IS NULL";
            $clean .= ';';

			$postmeta = $wpdb->query( $clean );
            $message .= sprintf(_n('%d orphaned postmeta deleted', '%d orphaned postmeta deleted', $postmeta, 'wp-optimize'), number_format_i18n($postmeta)).'<br>';
            break;

        case "commentmeta":
           $clean = "DELETE FROM `$wpdb->commentmeta` WHERE comment_id NOT IN (SELECT comment_id FROM `$wpdb->comments`)";
            $clean .= ';';
            $commentstrash_meta = $wpdb->query( $clean );
            $message .= sprintf(_n('%d unused comment metadata item removed', '%d unused comment metadata items removed', $commentstrash_meta, 'wp-optimize'), number_format_i18n($commentstrash_meta)).'<br>';

            // TODO:  still need to test now cleaning up comments meta tables - removing akismet related settings
            $clean = "DELETE FROM `$wpdb->commentmeta` WHERE meta_key LIKE '%akismet%'";
            $clean .= ';';
            $commentstrash_meta2 = $wpdb->query( $clean );
            $message .= sprintf(_n('%d unused akismet comment metadata item removed', '%d unused akismet comment metadata items removed', $commentstrash_meta2, 'wp-optimize'), number_format_i18n($commentstrash_meta2)).'<br>';
            break;


        case "orphandata":
            $clean = "DELETE FROM `$wpdb->term_relationships` WHERE term_taxonomy_id=1 AND object_id NOT IN (SELECT id FROM `$wpdb->posts`)";
            $clean .= ';';

            $orphandata = $wpdb->query( $clean );
            $message .= sprintf(_n('%d orphaned meta data deleted', '%d orphaned meta data deleted', $orphandata, 'wp-optimize'), number_format_i18n($orphandata)).'<br>';
            break;

        case "tags":
//            $clean = "DELETE t,tt FROM  `$wpdb->terms` t INNER JOIN `$wpdb->term_taxonomy` tt ON t.term_id=tt.term_id WHERE tt.taxonomy='post_tag' AND tt.count=0";
//            $clean .= ';';
//
//			$tags = $wpdb->query( $clean );
//            $message .= sprintf(_n('%d unused tag deleted', '%d unused tags deleted', $tags, 'wp-optimize'), number_format_i18n($tags)).'<br>';
            break;

		case "revisions":
            $clean = "DELETE FROM `$wpdb->posts` WHERE post_type = 'revision'";
            if ($retention_enabled == 'true') {
                $clean .= ' and post_modified < NOW() - INTERVAL ' .  $retention_period . ' WEEK';
            }
            $clean .= ';';

			$revisions = $wpdb->query( $clean );
            $message .= sprintf(_n('%d post revision deleted', '%d post revisions deleted', $revisions, 'wp-optimize'), number_format_i18n($revisions)).'<br>';
            break;

        case "autodraft":
            $clean = "DELETE FROM `$wpdb->posts` WHERE post_status = 'auto-draft'";
            if ($retention_enabled == 'true') {
                $clean .= ' and post_modified < NOW() - INTERVAL ' .  $retention_period . ' WEEK';
            }
            $clean .= ';';

            $autodraft = $wpdb->query( $clean );
            $message .= sprintf(_n('%d auto draft deleted', '%d auto drafts deleted', $autodraft, 'wp-optimize'), number_format_i18n($autodraft)).'<br>';


			// TODO:  query trashed posts and cleanup metadata
			$clean = "DELETE FROM `$wpdb->posts` WHERE post_status = 'trash'";
            if ($retention_enabled == 'true') {
                $clean .= ' and post_modified < NOW() - INTERVAL ' .  $retention_period . ' WEEK';
            }
            $clean .= ';';
            $posttrash = $wpdb->query( $clean );
            $message .= sprintf(_n('%d item removed from Trash', '%d items removed from Trash', $posttrash, 'wp-optimize'), number_format_i18n($posttrash)).'<br>';

            break;

        case "spam":
            $clean = "DELETE FROM `$wpdb->comments` WHERE comment_approved = 'spam'";
            if ($retention_enabled == 'true') {
				$clean .= ' and comment_date < NOW() - INTERVAL ' . $retention_period . ' WEEK';
            }
            $clean .= ';';

            $comments = $wpdb->query( $clean );
            $message .= sprintf(_n('%d spam comment deleted', '%d spam comments deleted', $comments, 'wp-optimize'), number_format_i18n($comments)).'<br>';

            // TODO:  query trashed comments and cleanup metadata
            $clean = "DELETE FROM `$wpdb->comments` WHERE comment_approved = 'trash'";
            if ($retention_enabled == 'true') {
				$clean .= ' and comment_date < NOW() - INTERVAL ' . $retention_period . ' WEEK';
            }
            $clean .= ';';
            $commentstrash = $wpdb->query( $clean );
            $message .= sprintf(_n('%d comment removed from Trash', '%d comments removed from Trash', $commentstrash, 'wp-optimize'), number_format_i18n($commentstrash)).'<br>';
 
            break;

        case "unapproved":
            $clean = "DELETE FROM `$wpdb->comments` WHERE comment_approved = '0'";
            if ($retention_enabled == 'true') {
				$clean .= ' and comment_date < NOW() - INTERVAL ' . $retention_period . ' WEEK';
            }
            $clean .= ';';
            $comments = $wpdb->query( $clean );
            $message .= sprintf(_n('%d unapproved comment deleted', '%d unapproved comments deleted', $comments, 'wp-optimize'), number_format_i18n($comments)).'<br>';
            break;

        case "pingbacks":
            $clean = "DELETE FROM `$wpdb->comments` WHERE comment_type = 'pingback';";
            $comments = $wpdb->query( $clean );
            $message .= sprintf(_n('%d pingback deleted', '%d pingbacks deleted', $comments, 'wp-optimize'), number_format_i18n($comments)).'<br>';
            break;

        case "trackbacks":
            $clean = "DELETE FROM `$wpdb->comments` WHERE comment_type = 'trackback';";
            $comments = $wpdb->query( $clean );
            $message .= sprintf(_n('%d trackback deleted', '%d trackbacks deleted', $comments, 'wp-optimize'), number_format_i18n($comments)).'<br>';
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
        case "transient":
            $sql = "SELECT COUNT(*) FROM `$wpdb->options` WHERE option_name LIKE '_site_transient_browser_%' OR option_name LIKE '_site_transient_timeout_browser_%' OR option_name LIKE '_transient_feed_%' OR option_name LIKE '_transient_timeout_feed_%'";
            //$sql .= ';';
            $transient = $wpdb->get_var( $sql );

            if(!$transient == 0 || !$transient == NULL){
              $message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.sprintf(_n('%d transient option in your database', '%d transient options in your database', $transient, 'wp-optimize'), number_format_i18n($transient));
            }
            else $message .='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.__('No transient options found', 'wp-optimize');
            break;

        case "postmeta":
            $sql = "SELECT COUNT(*) FROM `$wpdb->postmeta` pm LEFT JOIN `$wpdb->posts` wp ON wp.ID = pm.post_id WHERE wp.ID IS NULL";
            $sql .= ';';
            $postmeta = $wpdb->get_var( $sql );

             if(!$postmeta == 0 || !$postmeta == NULL){
              $message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.sprintf(_n('%d orphaned post meta data in your database', '%d orphaned postmeta in your database', $postmeta, 'wp-optimize'), number_format_i18n($postmeta));
            }
            else $message .='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.__('No orphaned post meta data in your database', 'wp-optimize');            
            break;

        case "commentmeta":
            $sql = "SELECT COUNT(*) FROM `$wpdb->commentmeta` WHERE comment_id NOT IN (SELECT comment_id FROM `$wpdb->comments`)";
            $sql .= ';';
            $commentmeta = $wpdb->get_var( $sql );

             if(!$commentmeta == 0 || !$commentmeta == NULL){
              $message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.sprintf(_n('%d orphaned comment meta data in your database', '%d orphaned comment meta data in your database', $commentmeta, 'wp-optimize'), number_format_i18n($commentmeta));
            }
            else $message .='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.__('No orphaned comment meta data in your database', 'wp-optimize');            
            break;

        case "orphandata":
            $sql = "SELECT COUNT(*) FROM `$wpdb->term_relationships` WHERE term_taxonomy_id=1 AND object_id NOT IN (SELECT id FROM `$wpdb->posts`)";
            $sql .= ';';
            $orphandata = $wpdb->get_var( $sql );

             if(!$orphandata == 0 || !$orphandata == NULL){
              $message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.sprintf(_n('%d orphaned relationship data in your database', '%d orphaned relationship data in your database', $orphandata, 'wp-optimize'), number_format_i18n($orphandata));
            }
            else $message .='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.__('No orphaned relationship data in your database', 'wp-optimize');            
            break;

        // not used
        /*case "transient":
            $sql = "SELECT COUNT(*) FROM `$wpdb->options` WHERE option_name LIKE '_site_transient_browser_%' OR option_name LIKE '_site_transient_timeout_browser_%' OR option_name LIKE '_transient_feed_%' OR option_name LIKE '_transient_timeout_feed_%'";
            $sql .= ';';
            $transient = $wpdb->get_var( $sql );

            if(!$transient == 0 || !$transient == NULL){
              $message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.sprintf(_n('%d transient data in your database', '%d transient data in your database', $transient, 'wp-optimize'), number_format_i18n($transient));
            }
            else $message .='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.__('No transient data found', 'wp-optimize');
            break;*/

		case "revisions":
            $sql = "SELECT COUNT(*) FROM `$wpdb->posts` WHERE post_type = 'revision'";

            if ($retention_enabled == 'true') {
                $sql .= ' and post_modified < NOW() - INTERVAL ' .  $retention_period . ' WEEK';
            }
            $sql .= ';';
            $revisions = $wpdb->get_var( $sql );

            if(!$revisions == 0 || !$revisions == NULL){
              $message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.sprintf(_n('%d post revision in your database', '%d post revisions in your database', $revisions, 'wp-optimize'), number_format_i18n($revisions));
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
              $message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.sprintf(_n('%d auto draft post in your database', '%d auto draft posts in your database', $autodraft, 'wp-optimize'), number_format_i18n($autodraft));
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
              $message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.sprintf(_n('%d spam comment found', '%d spam comments found', $comments, 'wp-optimize'), number_format_i18n($comments)).' | <a href="edit-comments.php?comment_status=spam">'.' '.__('Review', 'wp-optimize').'</a>';
            } else
              $message .='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.__('No spam comments found', 'wp-optimize');

            break;

        case "unapproved":
            $sql = "SELECT COUNT(*) FROM `$wpdb->comments` WHERE comment_approved = '0'";
            if ($retention_enabled == 'true') {
                $sql .= ' and comment_date < NOW() - INTERVAL ' . $retention_period . ' WEEK';
            }
            $sql .= ';';
			$comments = $wpdb->get_var( $sql );
            if(!$comments == NULL || !$comments == 0){
              $message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.sprintf(_n('%d unapproved comment found', '%d unapproved comments found', $comments, 'wp-optimize'), number_format_i18n($comments)).' | <a href="edit-comments.php?comment_status=moderated">'.' '.__('Review', 'wp-optimize').'</a>';;
            } else
              $message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.__('No unapproved comments found', 'wp-optimize');

            break;

        case "pingbacks":
            $sql = "SELECT COUNT(*) FROM `$wpdb->comments` WHERE comment_type='pingback';";
            $comments = $wpdb->get_var( $sql );
            if(!$comments == NULL || !$comments == 0){
              $message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.sprintf(_n('%d Pingback found', '%d Pingbacks found', $comments, 'wp-optimize'), number_format_i18n($comments));
            } else
              $message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.__('No pingbacks found', 'wp-optimize');

            break;

        case "trackbacks":
            $sql = "SELECT COUNT(*) FROM `$wpdb->comments` WHERE comment_type='trackback';";
            $comments = $wpdb->get_var( $sql );
            if(!$comments == NULL || !$comments == 0){
              $message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.sprintf(_n('%d Trackback found', '%d Trackbacks found', $comments, 'wp-optimize'), number_format_i18n($comments));
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
