<?php

if ('wp-optimize-common.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not access this file directly. Thanks!');

// common functions
if (! defined('WPO_PLUGIN_MAIN_PATH'))
	define('WPO_PLUGIN_MAIN_PATH', plugin_dir_path( __FILE__ ));
            
if (! defined('WPO_PLUGIN_PATH'))
	define('WPO_PLUGIN_PATH', plugin_dir_url( __FILE__ ));
	
if (! defined('WP_CONTENT_DIR'))
    define('WP_CONTENT_DIR', ABSPATH . 'wp-content');

if (! defined('WP_CONTENT_URL'))
    define('WP_CONTENT_URL', get_option('siteurl') . '/wp-content');

if (! defined('WP_ADMIN_URL'))
    define('WP_ADMIN_URL', get_option('siteurl') . '/wp-admin');

if (! defined('WP_PLUGIN_DIR'))
    define('WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins');

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
	
	$text = '<img src="'.WPO_PLUGIN_PATH.'/wp-optimize.png" border="0" alt="WP-Optimize" title="WP-Optimize" width="310px"/><br />';
    //$text .= '<iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.ruhanirabin.com%2Fwp-optimize%2F&amp;layout=standard&amp;show_faces=true&amp;width=450&amp;action=like&amp;font=lucida+grande&amp;colorscheme=light&amp;height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:400px; height:26px;" allowTransparency="true"></iframe>'
	$text .='<iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.ruhanirabin.com%2Fwp-optimize%2F&amp;width=400&amp;height=46&amp;colorscheme=light&amp;layout=standard&amp;action=like&amp;show_faces=false&amp;send=true&amp;" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:400px; height:46px;" allowTransparency="true"></iframe>';
	echo $text;
	$total_cleaned = get_option(OPTION_NAME_TOTAL_CLEANED);
    $total_cleaned_num = floatval($total_cleaned);
    
    if ($total_cleaned_num  > 0){
        echo '<h3>';
        _e('Total clean up overall','wp-optimize');
        echo ': ';
        echo '<font color="green">';
        echo $total_cleaned.' '.__('Kb', 'wp-optimize');
        echo '</font>';
        echo '</h3>';
        echo '<br />';
        
    }    
	
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
    			$clean = "DELETE FROM $wpdb->posts WHERE post_type = 'revision'";
                if ($retention_enabled == 'true') {
                    $clean .= ' and post_modified < NOW() - INTERVAL ' .  $retention_period . ' WEEK';
                }
                $clean .= ';';
    			$revisions = $wpdb->query( $clean );
		    }
            
            // auto drafts
            if ($this_options['drafts'] == 'true'){			
                $clean = "DELETE FROM $wpdb->posts WHERE post_status = 'auto-draft'";
                if ($retention_enabled == 'true') {
                    $clean .= ' and post_modified < NOW() - INTERVAL ' .  $retention_period . ' WEEK';
                }
                $clean .= ';';
                $autodraft = $wpdb->query( $clean );
			
            
                // trash posts
    			$clean = "DELETE FROM $wpdb->posts WHERE post_status = 'trash'";
                if ($retention_enabled == 'true') {
                    $clean .= ' and post_modified < NOW() - INTERVAL ' .  $retention_period . ' WEEK';
                }
                $clean .= ';';
                $posttrash = $wpdb->query( $clean );
            }
            
            // spam comments
            if ($this_options['spams'] == 'true'){	
    			$clean = "DELETE FROM $wpdb->comments WHERE comment_approved = 'spam'";
                if ($retention_enabled == 'true') {
    				$clean .= ' and comment_date < NOW() - INTERVAL ' . $retention_period . ' WEEK';
                }
                $clean .= ';';
                $comments = $wpdb->query( $clean );			
            			
            // trashed comments
    			$clean = "DELETE FROM $wpdb->comments WHERE comment_approved = 'post-trashed'";
                if ($retention_enabled == 'true') {
    				$clean .= ' and comment_date < NOW() - INTERVAL ' . $retention_period . ' WEEK';
                }
                $clean .= ';';			
                $commentstrash = $wpdb->query( $clean );
			}
            
            // transient options
            if ($this_options['transient'] == 'true'){
    			$clean = "DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_%' OR option_name LIKE '_site_transient_%'";
                $clean .= ';';			
                $transient_options = $wpdb->query( $clean );
            }

            // postmeta
            if ($this_options['postmeta'] == 'true'){
    			$clean = "DELETE pm FROM  $wpdb->postmeta  pm LEFT JOIN  $wpdb->posts  wp ON wp.ID = pm.post_id WHERE wp.ID IS NULL";
                $clean .= ';';			
                $postmeta = $wpdb->query( $clean );
            }

            // unused tags
            if ($this_options['tags'] == 'true'){            
    			$clean = "DELETE t,tt FROM  $wpdb->terms t INNER JOIN $wpdb->term_taxonomy tt ON t.term_id=tt.term_id WHERE tt.taxonomy='post_tag' AND tt.count=0";
                $clean .= ';';			
                $tags = $wpdb->query( $clean );
            }
			
		//db optimize part - optimize
        if ($this_options['optimize'] == 'true'){            
    
            $db_tables = $wpdb->get_results('SHOW TABLES',ARRAY_A);
    		foreach ($db_tables as $table){
    			$t = array_values($table);
    			$wpdb->query("OPTIMIZE TABLE ".$t[0]);
                wpo_debugLog('optimizing .... '.$t[0]);
    		}
    		
    		//$dateformat = __('l jS \of F Y h:i:s A');
    		$dateformat = 'l jS \of F Y h:i:s A';
            $thisdate = date($dateformat);
    		list($part1, $part2) = wpo_getCurrentDBSize();
     
            update_option( OPTION_NAME_LAST_OPT, $thisdate );
            wpo_updateTotalCleaned($part2);
            wpo_debugLog('Updating options with value +'.$part2);

        } // endif $this_options['optimize'] 
		
	}	
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
    		'transient' => 'true',
    		'postmeta' => 'false',
    		'tags' => 'false',
    		'optimize' => 'true'
    	);
    
    	update_option( 'wp-optimize-auto', $new_options );
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
	$tot_data = 0; $total_gain = 0; $total_db_space = 0; $total_db_space_a = 0;
	$tot_idx = 0;
	$tot_all = 0;
	$local_query = 'SHOW TABLE STATUS FROM `'. DB_NAME.'`';
	$result = mysql_query($local_query);
	if (mysql_num_rows($result)){
		while ($row = mysql_fetch_array($result))
		{
			$tot_data = $row['Data_length'];
			$tot_idx  = $row['Index_length'];
			$total = $tot_data + $tot_idx;
			$total = $total / 1024 ;
			$total = round ($total,3);

			$total_db_space = $tot_data + $tot_idx;
			$total_db_space = $total_db_space / 1024 ;
			$total_db_space_a += $total_db_space;
			$total_db_space = round ($total_db_space,3);
			
			$gain= $row['Data_free'];
			$gain = $gain / 1024 ;
			$total_gain += $gain;
			$gain = round ($gain,3);
		
			
		}
	return array (round($total_db_space_a,3), round($total_gain,3));	
	}
} // end of function wpo_getCurrentDBSize

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
	
} // end of function wpo_getCurrentDBSize

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
            $clean = "DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_%' OR option_name LIKE '_site_transient_%'";
            $clean .= ';';
			
			$transient_options = $wpdb->query( $clean );
            $message .= $transient_options.' '.__('transient options deleted', 'wp-optimize').'<br>';
            break;

        case "postmeta":
            $clean = "DELETE pm FROM  $wpdb->postmeta  pm LEFT JOIN  $wpdb->posts  wp ON wp.ID = pm.post_id WHERE wp.ID IS NULL";
            $clean .= ';';
			
			$postmeta = $wpdb->query( $clean );
            $message .= $postmeta.' '.__('orphaned postmeta deleted', 'wp-optimize').'<br>';
            break;

        case "tags":
            $clean = "DELETE t,tt FROM  $wpdb->terms t INNER JOIN $wpdb->term_taxonomy tt ON t.term_id=tt.term_id WHERE tt.taxonomy='post_tag' AND tt.count=0";
            $clean .= ';';
			
			$tags = $wpdb->query( $clean );
            $message .= $tags.' '.__('unused tags deleted', 'wp-optimize').'<br>';
            break;

		case "revisions":
            $clean = "DELETE FROM $wpdb->posts WHERE post_type = 'revision'";
            if ($retention_enabled == 'true') {
                $clean .= ' and post_modified < NOW() - INTERVAL ' .  $retention_period . ' WEEK';
            }
            $clean .= ';';
			
			$revisions = $wpdb->query( $clean );
            $message .= $revisions.' '.__('post revisions deleted', 'wp-optimize').'<br>';
            break;

        case "autodraft":
            $clean = "DELETE FROM $wpdb->posts WHERE post_status = 'auto-draft'";
            if ($retention_enabled == 'true') {
                $clean .= ' and post_modified < NOW() - INTERVAL ' .  $retention_period . ' WEEK';
            }
            $clean .= ';';
			
            $autodraft = $wpdb->query( $clean );
            $message .= $autodraft.' '.__('auto drafts deleted', 'wp-optimize').'<br>';

            $clean = "DELETE FROM $wpdb->posts WHERE post_status = 'trash'";
            if ($retention_enabled == 'true') {
                $clean .= ' and post_modified < NOW() - INTERVAL ' .  $retention_period . ' WEEK';
            }
            $clean .= ';';
            $posttrash = $wpdb->query( $clean );
            $message .= $posttrash.' '.__('items removed from Trash', 'wp-optimize').'<br>';

            break;

        case "spam":
            $clean = "DELETE FROM $wpdb->comments WHERE comment_approved = 'spam'";
            if ($retention_enabled == 'true') {
				$clean .= ' and comment_date < NOW() - INTERVAL ' . $retention_period . ' WEEK';
            }
            $clean .= ';';
			
            $comments = $wpdb->query( $clean );
            $message .= $comments.' '.__('spam comments deleted', 'wp-optimize').'<br>';

            $clean = "DELETE FROM $wpdb->comments WHERE comment_approved = 'post-trashed'";
            if ($retention_enabled == 'true') {
				$clean .= ' and comment_date < NOW() - INTERVAL ' . $retention_period . ' WEEK';
            }
            $clean .= ';';			
            $commentstrash = $wpdb->query( $clean );
            $message .= $commentstrash.' '.__('items removed from Trash', 'wp-optimize').'<br>';

            break;

        case "unapproved":
            $clean = "DELETE FROM $wpdb->comments WHERE comment_approved = '0'";
            if ($retention_enabled == 'true') {
				$clean .= ' and comment_date < NOW() - INTERVAL ' . $retention_period . ' WEEK';
            }
            $clean .= ';';	            $comments = $wpdb->query( $clean );
            $message .= $comments.' '.__('unapproved comments deleted', 'wp-optimize').'<br>';
            break;
			
        case "pingbacks":
            $clean = "DELETE FROM $wpdb->comments WHERE comment_type = 'pingback';";
            $comments = $wpdb->query( $clean );
            $message .= $comments.' '.__('pingbacks deleted', 'wp-optimize').'<br>';
            break;

        case "trackbacks":
            $clean = "DELETE FROM $wpdb->comments WHERE comment_type = 'trackback';";
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
            $sql = "SELECT COUNT(*) FROM $wpdb->options WHERE option_name LIKE '_transient_%' OR option_name LIKE '_site_transient_%'";
            $sql .= ';';
            $transient_options = $wpdb->get_var( $sql );

            if(!$transient_options == 0 || !$transient_options == NULL){
              $message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$transient_options.' '.__('transient options in your database', 'wp-optimize');
            }
            else $message .='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.__('No transient options found', 'wp-optimize');
            break;

        case "postmeta":
            $sql = "SELECT COUNT(*) FROM  $wpdb->postmeta  pm LEFT JOIN  $wpdb->posts  wp ON wp.ID = pm.post_id WHERE wp.ID IS NULL";
            $sql .= ';';
            $postmeta = $wpdb->get_var( $sql );

            if(!$postmeta == 0 || !$postmeta == NULL){
              $message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$postmeta.' '.__('orphaned postmeta in your database', 'wp-optimize');
            }
            else $message .='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.__('No orphaned postmeta in your database', 'wp-optimize');
            break;

        case "tags":
            $sql = "SELECT COUNT(*) FROM  $wpdb->terms t INNER JOIN $wpdb->term_taxonomy tt ON t.term_id=tt.term_id WHERE tt.taxonomy='post_tag' AND tt.count=0";
            $sql .= ';';
            $tags = $wpdb->get_var( $sql );

            if(!$tags == 0 || !$tags == NULL){
              $message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$tags.' '.__('unused tags in your database', 'wp-optimize');
            }
            else $message .='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.__('No unused tags found', 'wp-optimize');
            break;

		case "revisions":
            $sql = "SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = 'revision'";
			
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
            $sql = "SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'auto-draft'";

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
            $sql = "SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved = 'spam'";
            if ($retention_enabled == 'true') {
                $sql .= ' and comment_date < NOW() - INTERVAL ' . $retention_period . ' WEEK';
            }
            $sql .= ';';			
            $comments = $wpdb->get_var( $sql );
            if(!$comments == NULL || !$comments == 0){
              $message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$comments.' '.__('spam comments found', 'wp-optimize').' | <a href="edit-comments.php?comment_status=spam">'.' '.__('Review Spams', 'wp-optimize').'</a>';
            } else
              $message .='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.__('No spam comments found', 'wp-optimize');
            break;

        case "unapproved":
            $sql = "SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved = '0'";
            if ($retention_enabled == 'true') {
                $sql .= ' and comment_date < NOW() - INTERVAL ' . $retention_period . ' WEEK';
            }
            $sql .= ';';
			$comments = $wpdb->get_var( $sql );
            if(!$comments == NULL || !$comments == 0){
              $message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$comments.' '.__('unapproved comments found', 'wp-optimize').' | <a href="edit-comments.php?comment_status=moderated">'.' '.__('Review Unapproved Comments', 'wp-optimize').'</a>';;
            } else
              $message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.__('No unapproved comments found', 'wp-optimize');

            break;

        case "pingbacks":
            $sql = "SELECT COUNT(*) FROM $wpdb->comments WHERE comment_type='pingback';";
            $comments = $wpdb->get_var( $sql );
            if(!$comments == NULL || !$comments == 0){
              $message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$comments.' '.__('Pingbacks found', 'wp-optimize');
            } else
              $message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.__('No pingbacks found', 'wp-optimize');

            break;
			
        case "trackbacks":
            $sql = "SELECT COUNT(*) FROM $wpdb->comments WHERE comment_type='trackback';";
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