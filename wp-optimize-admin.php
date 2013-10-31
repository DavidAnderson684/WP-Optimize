<?php
# ---------------------------------- #
# prevent file from being accessed directly
# ---------------------------------- #
# Removed Security tools             #
# Added total db size                #
# Added weekly schedule              #
# ---------------------------------- #
# ---------------------------------- #
if ('wp-optimize-admin.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not access this file directly. Thanks!');

	if ( !is_admin() ) {
      Die();
  }
  
if ( file_exists(__DIR__ . '/wp-optimize-common.php')) {
    require(__DIR__ . '/wp-optimize-common.php');
}   

if (! defined('WP_CONTENT_DIR'))
    define('WP_CONTENT_DIR', ABSPATH . 'wp-content');
if (! defined('WP_CONTENT_URL'))
    define('WP_CONTENT_URL', get_option('siteurl') . '/wp-content');

if (! defined('WP_ADMIN_URL'))
    define('WP_ADMIN_URL', get_option('siteurl') . '/wp-admin');

if (! defined('WP_PLUGIN_DIR'))
    define('WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins');
if (! defined('WP_PLUGIN_URL'))
    define('WP_PLUGIN_URL', WP_CONTENT_URL . '/plugins');

if (! defined('OPTION_NAME'))
    define('OPTION_NAME', 'wp-optimize-weekly-schedule');
	
$text = '';

if (isset($_POST["clean-revisions"])) {
    $text .= cleanUpSystem('revisions');
    }
	
if (isset($_POST["clean-autodraft"])) {
    $text .= cleanUpSystem('autodraft');
    }	

if (isset($_POST["clean-comments"])) {
    $text .= cleanUpSystem('spam');
    }

if (isset($_POST["unapproved-comments"])) {
    $text .= cleanUpSystem('unapproved');
    }
if (isset($_POST["clean-pingbacks"])) {
    $text .= cleanUpSystem('pingbacks');
    }
if (isset($_POST["clean-trackbacks"])) {
    $text .= cleanUpSystem('trackbacks');
    }	

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // …
	if (isset($_POST["enable-weekly"])) {
		update_option( OPTION_NAME, 'true' );
		
			if (!wp_next_scheduled('wpo_cron_event2')) {
				wp_schedule_event(time(), 'weekly', 'wpo_cron_event2');
				add_filter('cron_schedules', 'wpo_cron_update_sched');
			}		
		} else { 
		update_option( OPTION_NAME, 'false' );
		wpo_cron_deactivate();
		}
		
	if (isset($_POST["enable-retention"])) {
		$retention_period = $_POST['retention-period'];
		update_option( OPTION_NAME_RETENTION_ENABLED, 'true' );
		update_option( OPTION_NAME_RETENTION_PERIOD, $retention_period );	
	} else { 
		update_option( OPTION_NAME_RETENTION_ENABLED, 'false' );
	}
		
	
}	
	
	
if (isset($_POST["optimize-db"])) {
    $text .= DB_NAME.__(' Database Optimized!', 'wp-optimize').'<br>';
    }


    if ($text !==''){
    echo '<div id="message" class="updated fade">';
    echo '<strong>'.$text.'</strong></div>';
    }
	
// cleanup functions	
function cleanUpSystem($cleanupType){
    global $wpdb;
    $clean = ""; $message = "";
    list ($retention_enabled, $retention_period) = getRetainInfo();
	
    switch ($cleanupType) {
        case "revisions":
            $clean = "DELETE FROM $wpdb->posts WHERE post_type = 'revision'";
            if ($retention_enabled == 'true') {
                $clean .= ' and post_modified < NOW() - INTERVAL ' .  $retention_period . ' WEEK';
            }
            $clean .= ';';
			
			$revisions = $wpdb->query( $clean );
            $message .= $revisions.__(' post revisions deleted', 'wp-optimize').'<br>';
            break;

        case "autodraft":
            $clean = "DELETE FROM $wpdb->posts WHERE post_status = 'auto-draft'";
            if ($retention_enabled == 'true') {
                $clean .= ' and post_modified < NOW() - INTERVAL ' .  $retention_period . ' WEEK';
            }
            $clean .= ';';
			
            $autodraft = $wpdb->query( $clean );
            $message .= $autodraft.__(' auto drafts deleted', 'wp-optimize').'<br>';

            $clean = "DELETE FROM $wpdb->posts WHERE post_status = 'trash'";
            if ($retention_enabled == 'true') {
                $clean .= ' and post_modified < NOW() - INTERVAL ' .  $retention_period . ' WEEK';
            }
            $clean .= ';';
            $posttrash = $wpdb->query( $clean );
            $message .= $posttrash.__(' items removed from Trash', 'wp-optimize').'<br>';

            break;

        case "spam":
            $clean = "DELETE FROM $wpdb->comments WHERE comment_approved = 'spam'";
            if ($retention_enabled == 'true') {
				$clean .= ' and comment_date < NOW() - INTERVAL ' . $retention_period . ' WEEK';
            }
            $clean .= ';';
			
            $comments = $wpdb->query( $clean );
            $message .= $comments.__(' spam comments deleted', 'wp-optimize').'<br>';

            $clean = "DELETE FROM $wpdb->comments WHERE comment_approved = 'post-trashed'";
            if ($retention_enabled == 'true') {
				$clean .= ' and comment_date < NOW() - INTERVAL ' . $retention_period . ' WEEK';
            }
            $clean .= ';';			
            $commentstrash = $wpdb->query( $clean );
            $message .= $commentstrash.__(' items removed from Trash', 'wp-optimize').'<br>';

            break;

        case "unapproved":
            $clean = "DELETE FROM $wpdb->comments WHERE comment_approved = '0'";
            if ($retention_enabled == 'true') {
				$clean .= ' and comment_date < NOW() - INTERVAL ' . $retention_period . ' WEEK';
            }
            $clean .= ';';	            $comments = $wpdb->query( $clean );
            $message .= $comments.__(' unapproved comments deleted', 'wp-optimize').'<br>';
            break;
			
        case "pingbacks":
            $clean = "DELETE FROM $wpdb->comments WHERE comment_type = 'pingback';";
            $comments = $wpdb->query( $clean );
            $message .= $comments.__(' pingbacks deleted', 'wp-optimize').'<br>';
            break;

        case "trackbacks":
            $clean = "DELETE FROM $wpdb->comments WHERE comment_type = 'trackback';";
            $comments = $wpdb->query( $clean );
            $message .= $comments.__(' trackbacks deleted', 'wp-optimize').'<br>';
            break;			

        case "enable-weekly":
			update_option( OPTION_NAME, 'true' );
            $comments = '';
			$message .= $comments.__(' Enabled weekly processing', 'wp-optimize').'<br>';
            break;			

        case "disable-weekly":
            update_option( OPTION_NAME, 'false' );
            $comments = '';
			$message .= $comments.__(' Disabled weekly processing', 'wp-optimize').'<br>';
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


function getInfo($cleanupType){
    global $wpdb;
    $sql = ""; $message = "";
    list ($retention_enabled, $retention_period) = getRetainInfo();
	
    switch ($cleanupType) {
        case "revisions":
            $sql = "SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = 'revision'";
			
            if ($retention_enabled == 'true') {
                $sql .= ' and post_modified < NOW() - INTERVAL ' .  $retention_period . ' WEEK';
            }
            $sql .= ';';
            $revisions = $wpdb->get_var( $sql );

            if(!$revisions == 0 || !$revisions == NULL){
              $message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$revisions.__(' post revisions in your database', 'wp-optimize');
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
              $message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$autodraft.__(' auto draft post(s) in your database', 'wp-optimize');
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
              $message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$comments.__(' spam comments found', 'wp-optimize').' | <a href="edit-comments.php?comment_status=spam">'.__(' Review Spams', 'wp-optimize').'</a>';
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
              $message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$comments.__(' unapproved comments found', 'wp-optimize').' | <a href="edit-comments.php?comment_status=moderated">'.__(' Review Unapproved Comments', 'wp-optimize').'</a>';;
            } else
              $message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.__('No unapproved comments found', 'wp-optimize');

            break;

        case "pingbacks":
            $sql = "SELECT COUNT(*) FROM $wpdb->comments WHERE comment_type='pingback';";
            $comments = $wpdb->get_var( $sql );
            if(!$comments == NULL || !$comments == 0){
              $message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$comments.__(' Pingbacks found', 'wp-optimize');
            } else
              $message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.__('No pingbacks found', 'wp-optimize');

            break;
			
        case "trackbacks":
            $sql = "SELECT COUNT(*) FROM $wpdb->comments WHERE comment_type='trackback';";
            $comments = $wpdb->get_var( $sql );
            if(!$comments == NULL || !$comments == 0){
              $message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$comments.__(' Trackbacks found', 'wp-optimize');
            } else
              $message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.__('No trackbacks found', 'wp-optimize');

            break;
			
			
        default:
            $message .= __('nothing', 'wp-optimize');
            break;
    } // end of switch
return $message;


} // end of function

?>


<div class="wrap">
<?php
    //echo '<div id="message" class="updated fade">';
    //echo '<strong>'.$msg.'</strong></div>';
    //echo '</div>';
?>
<table width="95%" border="0" cellspacing="0" cellpadding="0">
<form action="#" method="post" enctype="multipart/form-data" name="optimize_form" id="optimize_form">
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="1" valign="top"><img src="<?php _e(WP_PLUGIN_URL, 'wp-optimize') ?>/wp-optimize/wp-optimize.png" border="0" alt="WP-Optimize" title="WP-Optimize" />
	<br />
	<iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.ruhanirabin.com%2Fwp-optimize%2F&amp;layout=standard&amp;show_faces=true&amp;width=450&amp;action=like&amp;font=lucida+grande&amp;colorscheme=light&amp;height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:400px; height:26px;" allowTransparency="true"></iframe>
	</td>
	<td colspan="1" valign="top">
    <input name="enable-weekly" id="enable-weekly" type="checkbox" value ="true" <?php echo get_option(OPTION_NAME) == 'true' ? 'checked="checked"':''; ?> />
	 <?php _e('Enable Weekly Cleanup (EXPERIMENTAL!)', 'wp-optimize'); ?>
	 <?php 
		 if (wp_next_scheduled('wpo_cron_event2')) {
		 $timestamp = wp_next_scheduled( 'wpo_cron_event2' ); 
		 $timestamp = wp_next_scheduled( 'wpo_cron_event2' ); 
		 $date = new DateTime("@$timestamp");
		 echo '<b>';
		 _e(' | Next Schedule - ', 'wp-optimize');
		 echo $date->format('l jS \of F Y h:i:s A') . "\n";
		 echo '</b>';
	 }
	 ?><br />
   <small><?php 
            _e('This will enable weekly scheduled of all the optimization listed including - ', 'wp-optimize'); 
            echo '<br/>';
			_e('Optimize db, Remove AutoDrafts/Revisions, Posts and Comments in Trash.', 'wp-optimize'); 
            echo '<br/>';
			_e('NOTE: Unapproved comments will not be removed automatically; just in case there are legitimate comments', 'wp-optimize');
 			?>
			</small>
   <br />
   <br />
   <input name="enable-retention" id="enable-retention" type="checkbox" value ="true" <?php echo get_option(OPTION_NAME_RETENTION_ENABLED) == 'true' ? 'checked="checked"':''; ?> />
   <?php _e('Keep last ', 'wp-optimize'); ?>
	<select id="retention-period" name="retention-period">                      
		<option value="<?php echo get_option(OPTION_NAME_RETENTION_PERIOD, '2'); ?>"><?php echo get_option(OPTION_NAME_RETENTION_PERIOD,'2'); ?></option>
		<option value="2">2</option>
		<option value="4">4</option>
		<option value="6">6</option>
		<option value="8">8</option>
		<option value="10">10</option>
	</select>
   <?php _e(' weeks of data', 'wp-optimize'); ?>
   <br />
   <small><?php 
            _e('This option will retain the last X weeks of data and remove any junk data before that period', 'wp-optimize'); 
 			?>
			</small>
   <br />
   <br />
   <?php  ?>
   <?php
	$lastopt = get_option(OPTION_NAME_LAST_OPT, 'Never');
	if ($lastopt !== 'Never'){
		echo '<i>';		
		_e('Last automatic optimization was at ', 'wp-optimize');
		echo '</i>';
		echo '<b>';
		echo $lastopt;
		echo '</b>';
		
	} else {  
		echo '<i>';		
		_e('There was no automatic optimization', 'wp-optimize'); 
		echo '</i>';
	}
   ?>
   </td>
	</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><h3><?php _e('Credits','wp-optimize'); ?></h3>
	</td>
	<td><h3><?php _e('Database Optimization Options','wp-optimize'); ?></h3><br />
    <a href="#report"><?php _e('See Tables Report > ', 'wp-optimize'); ?></a> | <a href="#total"><?php _e('Database Size > ', 'wp-optimize'); ?></a><br />
	&nbsp;
	</td>
	<td rowspan="20" valign="top"><p align="center"><small>Sponsor</small><br><a href="http://j.mp/1ePlbvc" target="_blank"><img style="border:0px" src="http://www.elegantthemes.com/affiliates/banners/120x600gif.gif" width="120" height="600" alt=""></a></p></td>
  </tr>

  <tr>
    <td rowspan="20" valign="top">
	<h3><?php _e('Contibuting Developers','wp-optimize'); ?></h3><br />
	<a href="http://www.ruhanirabin.com/contact/" target="_blank" alt="" title=""><?php _e('Help me make this plugin better','wp-optimize'); ?></a>, <?php _e('I am looking for contributing developers.','wp-optimize'); ?><br />
	<?php _e('Your name and website will be credited here in the plugin','wp-optimize'); ?>.<br />
	&nbsp;
	<h3><?php _e('Translators','wp-optimize'); ?></h3><br />
	<h4><a href="<?php _e('http://www.ruhanirabin.com/','wp-optimize'); ?>" target="_blank" alt="" title=""><?php _e('Default Language by Ruhani Rabin (Change this text and the link inside translation file)','wp-optimize') ?></a></h4><br />
	&nbsp;<br />
	<a href="<?php echo WPO_PLUGIN_PATH.'languages/wp-optimize.pot'; ?>" target="_blank" title=""><?php _e('Download .POT File to translate','wp-optimize'); ?></a><br />
	<br />
	<h3><?php _e('Plugin Resources','wp-optimize'); ?></h3><br />
	<a href="http://www.ruhanirabin.com/wp-optimize/" target="_blank"><?php _e('Plugin Homepage', 'wp-optimize'); ?></a> | <a href="http://wordpress.org/support/plugin/wp-optimize" target="_blank"><?php _e('Support Forum', 'wp-optimize'); ?></a><br />
	<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=KYFUKEK7NXAZ8" target="_blank"><?php _e('Buy me a Coffee ?', 'wp-optimize'); ?></a><br />

	</td>
    <td ><input name="clean-revisions" id="clean-revisions" type="checkbox" value="" />
	 <?php _e('Remove all Post revisions', 'wp-optimize'); ?><br />
   <small><?php _e(getInfo('revisions'), 'wp-optimize'); ?></small></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><input name="clean-autodraft" id="clean-autodraft" type="checkbox" value="" />
	 <?php _e('Remove all auto draft posts (This will also clear out posts in Trash)', 'wp-optimize'); ?><br />
   <small><?php _e(getInfo('autodraft'), 'wp-optimize'); ?></small></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
    <td><input name="clean-comments" type="checkbox" value="" />
	 <?php _e('Clean Spam comments (This will also clear out comments in Trash)', 'wp-optimize'); ?><br />
   <small><?php _e(getInfo('spam'), 'wp-optimize'); ?></small></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><input name="unapproved-comments" type="checkbox" value="" />
	 <?php _e('Clean Unapproved comments', 'wp-optimize'); ?><br />
   <small><?php _e(getInfo('unapproved'), 'wp-optimize'); ?></small></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><input name="clean-pingbacks" type="checkbox" value="" />
	 <?php _e('Clean Pingbacks', 'wp-optimize'); ?><br />
   <small><?php _e(getInfo('pingbacks'), 'wp-optimize'); ?></small></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><input name="clean-trackbacks" type="checkbox" value="" />
	 <?php _e('Clean Trackbacks', 'wp-optimize'); ?><br />
   <small><?php _e(getInfo('trackbacks'), 'wp-optimize'); ?></small></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><input name="optimize-db" type="checkbox" value="" />
	 <?php _e('Optimize database tables', 'wp-optimize'); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><input class="button-primary" type="submit" name="wp-optimize" value="<?php _e('PROCESS', 'wp-optimize'); ?>" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  </form>
</table>


<?php

if (isset($_POST["optimize-db"])) {
    optimizeTables(true);
    }
else optimizeTables(false);

?>
<?php
Function optimizeTables($Optimize=false){
?>
<a name="report">&nbsp;</a>
<h3><?php _e('Database Tables Report', 'wp-optimize'); ?></h3>
<h3><?php _e('Database Name:', 'wp-optimize'); ?> '<?php _e(DB_NAME, 'wp-optimize');?>'</h3>
<?php if($Optimize){
    ?>

<p><?php _e('Optimized all the tables found in the database.', 'wp-optimize')?></p>
<?php } ?>


<table class="widefat fixed" cellspacing="0">
<thead>
	<tr>
	<th scope="col"><?php _e('Table', 'wp-optimize'); ?></th>
	<th scope="col"><?php _e('Size', 'wp-optimize')?></th>
	<th scope="col"><?php _e('Status', 'wp-optimize'); ?></th>
	<th scope="col"><?php _e('Space Save', 'wp-optimize'); ?></th>
	</tr>
</thead>
<tfoot>
	<tr>
	<th scope="col"><?php _e('Table', 'wp-optimize'); ?></th>
	<th scope="col"><?php _e('Size', 'wp-optimize')?></th>
	<th scope="col"><?php _e('Status', 'wp-optimize'); ?></th>
	<th scope="col"><?php _e('Space Save', 'wp-optimize'); ?></th>
	</tr>
</tfoot>
<tbody id="the-list">
<?php
$alternate = ' class="alternate"';
	$db_clean = DB_NAME;
	$tot_data = 0; $total_gain = 0; $total_db_space = 0; $total_db_space_a = 0;
	$tot_idx = 0;
	$tot_all = 0;
	//$local_query = 'SHOW TABLE STATUS FROM '. DB_NAME;
	$local_query = 'SHOW TABLE STATUS FROM `'. DB_NAME.'`';
	$result = mysql_query($local_query);
	//if (mysql_num_rows($result)){
	//fix by mikel king
	if (mysql_num_rows($result) && is_resource($result)){
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
			if (isset($_POST["optimize-db"])) {
        $local_query = 'OPTIMIZE TABLE '.$row[0];
			  $resultat  = mysql_query($local_query);
			  
			  
        //echo "optimization";
            }

      if ($gain == 0){
				echo "<tr". $alternate .">
					<td class='column-name'>". $row[0] ."</td>
					<td class='column-name'>". $total ." Kb"."</td>
					<td class='column-name'>" .  __('Already Optimized', 'wp-optimize') . "</td>
					<td class='column-name'>0 Kb</td>
					</tr>\n";
			} else
			{
      if (isset($_POST["optimize-db"])) {
        echo "<tr". $alternate .">
					<td class='column-name'>". $row[0] ."</td>
					<td class='column-name'>". $total ." Kb"."</td>
          <td class='column-name' style=\"color: #0000FF;\">" .  __('Optimized', 'wp-optimize') . "</td>
					<td class='column-name'>". $gain ." Kb</td>
					</tr>\n";
        }
        else {
        echo "<tr". $alternate .">
					<td class='column-name'>". $row[0] ."</td>
					<td class='column-name'>". $total ." Kb"."</td>
          <td class='column-name' style=\"color: #FF0000;\">" .  __('Need to Optimize', 'wp-optimize') . "</td>
					<td class='column-name'>". $gain ." Kb</td>
					</tr>\n";
        }
			}
			$alternate = ( empty( $alternate ) ) ? ' class="alternate"' : '';
		}
	}
?>
</tbody>
</table>
<a name="total">&nbsp;</a>
<h3><?php _e('Total Size of Database:', 'wp-optimize'); ?></h3>
<h2><?php echo round ($total_db_space_a,3);?> Kb</h2>

<?php if (isset($_POST["optimize-db"])) {
    ?>

<?php $total_gain = round ($total_gain,3);?>

<h3><?php _e('Optimization Results:', 'wp-optimize'); ?></h3>
<p style="color: #0000FF;"><?php _e('Total Space Saved:', 'wp-optimize'); ?> <?php echo $total_gain?> Kb</p>
  <?php } else { ?>
<?php $total_gain = round ($total_gain,3); ?>
  <?php if(!$total_gain==0){ ?>

<h3><?php _e('Optimization Possibility:', 'wp-optimize'); ?></h3>
<p style="color: #FF0000;"><?php _e('Total space can be saved:', 'wp-optimize'); ?> <?php echo $total_gain?> Kb</p>
  <?php } ?>
<?php
}
?>

<?php
}
?>
</div>