<?php

# ---------------------------------- #
# prevent file from being accessed directly
# ---------------------------------- #
if ('wp-optimize-admin.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not access this file directly. Thanks!');

	if ( !is_admin() ) {
      Die();
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

$text = '';
//$textdomain = 'wp-optimize';

if (isset($_POST["clean-revisions"])) {
    $text .= cleanUpSystem('revisions');
    }
	
if (isset($_POST["clean-autodraft"])) {
    $text .= cleanUpSystem('autodraft');
    }	

if (isset($_POST["old_admin"]) && isset($_POST["new_admin"])) {
    $text .= cleanUpSystem('changeadmin');
    }

if (isset($_POST["clean-comments"])) {
    $text .= cleanUpSystem('spam');
    }

if (isset($_POST["unapproved-comments"])) {
    $text .= cleanUpSystem('unapproved');
    }

if (isset($_POST["optimize-db"])) {
    //$text .= cleanUpSystem('optimize-db');
    $text .= DB_NAME.__(" Database Optimized!<br>", 'wp-optimize');
    }


    if ($text !==''){
    echo '<div id="message" class="updated fade">';
    echo '<strong>'.$text.'</strong></div>';
    }

function cleanUpSystem($cleanupType){
    global $wpdb;
    $clean = "";

    switch ($cleanupType) {
        case "revisions":
            $clean = "DELETE FROM $wpdb->posts WHERE post_type = 'revision'";
            $revisions = $wpdb->query( $clean );
			
/* 			$allposts = get_posts('numberposts=-1&orderby=ID&order=ASC&post_type=any&post_status=');
			$allpost_ids = array();
			foreach ($allposts as $onepost)
				$allpost_ids[$onepost->ID] = true;
				$cleaned_ids = array();
				$total_cleaned_metas = 0;
				$postmeta = $wpdb->get_results("SELECT * FROM $wpdb->postmeta", OBJECT);
					foreach ($postmeta as $meta)
						if (!isset($allpost_ids[$meta->post_id]) && !isset($cleaned_ids[$meta->post_id])) {
							$cleaned_metas = $wpdb->query("DELETE FROM $wpdb->postmeta WHERE post_id = '".$meta->post_id."'");
							$total_cleaned_metas += $cleaned_metas;
							$cleaned_ids[$meta->post_id] = true;
						} */
						
            $message .= $revisions.__(' post revisions deleted<br>', 'wp-optimize');
            break;

        case "autodraft":
            $clean = "DELETE FROM $wpdb->posts WHERE post_status = 'auto-draft'";
            $autodraft = $wpdb->query( $clean );
            $message .= $autodraft.__(' auto drafts deleted<br>', 'wp-optimize');
            break;

			
        //case "postmeta":

		//	$message .= $total_cleaned_metas.__(' postmeta items from revisions and nonexistant posts deleted', $textdomain);
        //    break;

        case "spam":
            $clean = "DELETE FROM $wpdb->comments WHERE comment_approved = 'spam';";
            $comments = $wpdb->query( $clean );
            $message .= $comments.__(' spam comments deleted<br>', 'wp-optimize');
            break;

        case "unapproved":
            $clean = "DELETE FROM $wpdb->comments WHERE comment_approved = '0';";
            $comments = $wpdb->query( $clean );
            $message .= $comments.__(' unapproved comments deleted<br>', 'wp-optimize');
            break;


//        case "optimize-db":
//            optimizeTables(true);
//            $message .= "Database ".DB_NAME." Optimized!<br>";
//            break;

        case "changeadmin":
            if (isset($_POST["old_admin"]) && isset($_POST["new_admin"])) {
                $oldAdmin = $_POST["old_admin"];
                $newAdmin = $_POST["new_admin"];
                $clean = "UPDATE $wpdb->users SET user_login = '$newAdmin' WHERE user_login ='$oldAdmin'";
                $setlogin = $wpdb->query( $clean );
                    if ($setlogin !== 0){
                        $message .= __('Admin username updated<br>', 'wp-optimize');
                    }
                    else{
                        $message .= "";
                    }
            }
            else{
                $message .= __('ADMIN USERNAME NOT UPDATED<br>', 'wp-optimize');
            }
            break;

        default:
            $message .= __('NO Actions Taken<br>', 'wp-optimize');
            break;
    } // end of switch
return $message;


} // end of function


function getInfo($cleanupType){
    global $wpdb;
    $sql = "";

    switch ($cleanupType) {
        case "revisions":
            $sql = "SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = 'revision'";
            $revisions = $wpdb->get_var( $sql );

            //var_dump(!$revisions ==);
            if(!$revisions == 0 || !$revisions == NULL){
              $message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$revisions.__(' post revisions in your database', 'wp-optimize');
            }
            else $message .='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.__('No post revisions found', 'wp-optimize');
            break;

        case "autodraft":
            $sql = "SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'auto-draft'";
            $autodraft = $wpdb->get_var( $sql );

            if(!$autodraft == 0 || !$autodraft == NULL){
              $message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$autodraft.__(' auto draft post(s) in your database', 'wp-optimize');
            }
            else $message .='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.__('No auto draft posts found', 'wp-optimize');
            break;
			
			
        case "spam":
            $sql = "SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved = 'spam';";
            $comments = $wpdb->get_var( $sql );
            if(!$comments == NULL || !$comments == 0){
              $message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$comments.__(' spam comments found', 'wp-optimize').' | <a href="edit-comments.php?comment_status=spam">'.__(' Review Spams</a>', 'wp-optimize');
            } else
              $message .='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.__('No spam comments found', 'wp-optimize');
            break;

        case "unapproved":
            $sql = "SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved = '0';";
            $comments = $wpdb->get_var( $sql );
            if(!$comments == NULL || !$comments == 0){
              $message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$comments.__(' unapproved comments found', 'wp-optimize').' | <a href="edit-comments.php?comment_status=moderated">'.__(' Review Unapproved Comments</a>', 'wp-optimize');;
            } else
              $message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.__('No unapproved comments found', 'wp-optimize');

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
<table width="80%" border="0" cellspacing="0" cellpadding="0">
<form action="#" method="post" enctype="multipart/form-data" name="optimize_form" id="optimize_form">
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><img src="<?php _e(WP_PLUGIN_URL, 'wp-optimize') ?>/wp-optimize/wp-optimize.gif" border="0" alt="WP-Optimize Admin" title="WP-Optimize Admin" /></td>
  </tr>
  <tr>
    <td><iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.ruhanirabin.com%2Fwp-optimize%2F&amp;layout=standard&amp;show_faces=true&amp;width=450&amp;action=like&amp;font=lucida+grande&amp;colorscheme=light&amp;height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:80px;" allowTransparency="true"></iframe></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><a href="#report"><?php _e('Tables Report', 'wp-optimize'); ?></a></td>
    <td>&nbsp;</td>
  </tr>


  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><h3><?php _e('Database Optimization Options','wp-optimize'); ?></h3></td>
  </tr>

  <tr>
    <td width="25%">&nbsp;</td>
    <td width="75%"><input name="clean-revisions" id="clean-revisions" type="checkbox" value="" />
	 <?php _e('Remove all Post revisions', 'wp-optimize'); ?><br />
   <small><?php _e(getInfo('revisions'), 'wp-optimize'); ?></small></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input name="clean-autodraft" id="clean-autodraft" type="checkbox" value="" />
	 <?php _e('Remove all auto draft posts', 'wp-optimize'); ?><br />
   <small><?php _e(getInfo('autodraft'), 'wp-optimize'); ?></small></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
    <td>&nbsp;</td>
    <td><input name="clean-comments" type="checkbox" value="" />
	 <?php _e('Clean marked Spam comments', 'wp-optimize'); ?><br />
   <small><?php _e(getInfo('spam'), 'wp-optimize'); ?></small></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input name="unapproved-comments" type="checkbox" value="" />
	 <?php _e('Clean Unapproved comments', 'wp-optimize'); ?><br />
   <small><?php _e(getInfo('unapproved'), 'wp-optimize'); ?></small></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input name="optimize-db" type="checkbox" value="" />
	 <?php _e('Optimize database tables', 'wp-optimize'); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
    <td><h3><?php _e('Security Tools', 'wp-optimize'); ?></h3></td>
  </tr>

  <tr>
    <td><p align="right"><?php _e('Old username:', 'wp-optimize'); ?>&nbsp;</p></td>
    <td><input type="text" name="old_admin" id="old_admin" class="old_admin" size="40" value=""></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><p align="right"><?php _e('New username:', 'wp-optimize'); ?>&nbsp;</p></td>
    <td><input type="text" name="new_admin" id="new_admin" class="new_admin" size="40" value=""></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input class="button-primary" type="submit" name="wp-optimize" value="<?php _e('PROCESS', 'wp-optimize'); ?>" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><p align="right"><?php _e('Plugin Homepage', 'wp-optimize'); ?> :&nbsp;</p></td>
    <td><a href="http://www.ruhanirabin.com/wp-optimize/" target="_blank">WP-Optimize</a></td>
  </tr>
  <tr>
    <td><p align="right"><?php _e('RSS Feed', 'wp-optimize'); ?> :&nbsp;</p></td>
    <td><a href="http://feeds2.feedburner.com/RuhaniRabin" target="_blank"><?php _e('Stay updated with RSS feed', 'wp-optimize'); ?></a>
    </td>
  </tr>
  <tr>
    <td><p align="right"><?php _e('Did this helped you out', 'wp-optimize'); ?>? :&nbsp;</p></td>
    <td><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=2732133" target="_blank"><?php _e('Do you like to donate an amount?', 'wp-optimize'); ?></a></td>
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

<h3><?php _e('Database Tables Report', 'wp-optimize'); ?></h3>
<h3><?php _e('Database Name:', 'wp-optimize'); ?> '<?php _e(DB_NAME, 'wp-optimize');?>'</h3>
<?php if($Optimize){
    ?>

<p><?php _e('Optimized all the tables found in the database.', 'wp-optimize')?></p>
<?php } ?>

<a name="report">&nbsp;</a>

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
	$tot_data = 0;
	$tot_idx = 0;
	$tot_all = 0;
	//$local_query = 'SHOW TABLE STATUS FROM '. DB_NAME;
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