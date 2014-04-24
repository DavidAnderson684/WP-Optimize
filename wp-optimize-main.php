<?php
$text = '';

if (isset($_POST["clean-revisions"])) {
    $text .= wpo_cleanUpSystem('revisions');
    }
	
if (isset($_POST["clean-autodraft"])) {
    $text .= wpo_cleanUpSystem('autodraft');
    }	

if (isset($_POST["clean-comments"])) {
    $text .= wpo_cleanUpSystem('spam');
    }

if (isset($_POST["unapproved-comments"])) {
    $text .= wpo_cleanUpSystem('unapproved');
    }
if (isset($_POST["clean-pingbacks"])) {
    $text .= wpo_cleanUpSystem('pingbacks');
    }
if (isset($_POST["clean-trackbacks"])) {
    $text .= wpo_cleanUpSystem('trackbacks');
    }	

if (isset($_POST["clean-transient"])) {
    $text .= wpo_cleanUpSystem('transient_options');
    }

if (isset($_POST["clean-postmeta"])) {
    $text .= wpo_cleanUpSystem('postmeta');
    }	

if (isset($_POST["clean-tags"])) {
    $text .= wpo_cleanUpSystem('tags');
    }	
	
if (isset($_POST["optimize-db"])) {
    $text .= DB_NAME.' '.__('Database Optimized!', 'wp-optimize').'<br>';
    }

    if ($text !==''){
     echo '<div id="message" class="updated">';
     echo '<strong>'.$text.'</strong></div>';
    }
    
    ?>
<table width="95%" border="0" cellspacing="0" cellpadding="0">
<form action="#" method="post" enctype="multipart/form-data" name="optimize_form" id="optimize_form">

  <tr>
    <td colspan="1" valign="top"><h3><?php _e('Status : ', 'wp-optimize'); ?>
   <?php
	$lastopt = get_option(OPTION_NAME_LAST_OPT, 'Never');
	if ($lastopt !== 'Never'){
		echo '<i>';		
		_e('Last automatic optimization was at', 'wp-optimize');
		echo ' ';
		echo '</i>';
		echo '<b>';
		echo '<font color="green">';
		echo $lastopt;
		echo '</font>';
		echo '</b>';
		echo '</i>';
		
	} else {  
		echo '<i>';		
		_e('There was no automatic optimization', 'wp-optimize');
		echo ' - ';
		echo '<a href="?page=WP-Optimize&tab=wp_optimize_settings">';
		_e('Check settings', 'wp-optimize');
		echo '</a>';
		echo '</i>';
	}
   ?>
    <br />
    <h3>
	<?php
	if ( get_option( OPTION_NAME_SCHEDULE, 'false' ) == 'true' ) {
		echo '<b>';		
		echo '<i>';		
		echo '<font color="green">';		
		_e('Scheduled cleaning enabled', 'wp-optimize');
		echo ', ';
		echo '</font>';
		echo '</i>';
		echo '</b>';
		if (wp_next_scheduled('wpo_cron_event2')) {
			 //$timestamp = wp_next_scheduled( 'wpo_cron_event2' ); 
			wpo_cron_activate();
			 
			 $timestamp = wp_next_scheduled( 'wpo_cron_event2' ); 
			 $date = new DateTime("@$timestamp");
			echo '<i>';
			_e('Next schedule', 'wp-optimize');
			echo ' : ';
			echo '<font color="green">';		
			echo $date->format('l jS \of F Y') . "\n";
			echo '</i>';
			echo '</font>';	
			echo '<i>';		
			echo ' - ';
			echo '<a href="?page=WP-Optimize">';
			_e('Refresh', 'wp-optimize');
			echo '</a>';
			echo '</i>';
			
		 }
	} else {
		echo '<b>';		
		echo '<i>';		
		_e('Scheduled cleaning DISABLED', 'wp-optimize');
		echo ' - ';
		echo '<a href="?page=WP-Optimize&tab=wp_optimize_settings">';
		_e('Check settings', 'wp-optimize');
		echo '</a>';
		echo '</i>';
		echo '</b>';
	}
		echo '<br />';

	if ( get_option( OPTION_NAME_RETENTION_ENABLED, 'false' ) == 'true' ) {
		echo '<i>';		
		echo '<b>';		
		echo '<font color="blue">';		
		_e('Retention enabled and keeping last ', 'wp-optimize');
		echo get_option( OPTION_NAME_RETENTION_PERIOD, '2' );
        echo ' ';
		_e('weeks data', 'wp-optimize');
		echo '</font>';
		echo '</i>';
		echo '</b>';
	
	} else {
		echo '<i>';		
		echo '<b>';		
		_e('Retention is disabled', 'wp-optimize');
		echo ' - ';
		echo '<a href="?page=WP-Optimize&tab=wp_optimize_settings">';
		_e('Check settings', 'wp-optimize');
		echo '</a>';
		echo '</i>';
		echo '</b>';
	}
	
	?>
	<br />
	</h3>
	<h3><?php 
	
	if (isset($_POST["optimize-db"])) {
		list ($part1, $part2) = wpo_getCurrentDBSize(); 
		
		_e('Current database size : ', 'wp-optimize');
		echo '<font color="blue">';
		echo $part1.'</font> ';
        echo ' ';
		_e('You have saved', 'wp-optimize');
		echo ' : ';
		echo '<font color="red">';
		echo $part2.'</font> ';
		
    }
	else {
		list ($part1, $part2) = wpo_getCurrentDBSize();
         
		_e('Current database size', 'wp-optimize');
		echo ' : ';
		echo '<font color="blue">';
		echo $part1.'</font> ';
        $this_value = $part2;
        if ($this_value > 0){
            echo ' ';
    		_e('You can save almost', 'wp-optimize');
    		echo ' : ';
    		echo '<font color="red">';
    		echo $part2.'</font> ';
        }
	}
	
	?>
	<br /><br />
	<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=KYFUKEK7NXAZ8" class="button" target="_blank"><?php _e('Buy me a Coffee :)', 'wp-optimize'); ?></a> &nbsp; 
	<a href="#report" class="button"><?php _e('See Tables > ', 'wp-optimize'); ?></a> &nbsp; <a href="#total" class="button"><?php _e('Database Size > ', 'wp-optimize'); ?></a>
	<br />
	</h3>
   </td>
    <td colspan="1" valign="top">
	<?php wpo_headerImage(); ?>
	</td>
	</tr>

  <tr>
    <td><h3>
<!-- TODO: Need to make this checkbox selection thing working -->

<script type="text/javascript">
function SetDefaults() {
    document.getElementById("clean-revisions").checked = true;
    document.getElementById("clean-comments").checked = true;
    document.getElementById("clean-autodraft").checked = true;

    document.getElementById("optimize-db").checked = true;
    return false;
}
</script>

    <?php 
    _e('Database Optimization Options','wp-optimize'); 

//    echo ' - ';
//    echo '<a href="#" onClick="SetDefaults();">';
//    _e('Select recommended','wp-optimize');
//    echo '</a>';
//    ?>
    
    </h3><br />
	</td>
		<td rowspan="28" valign="top">
	<small><?php _e('Sponsor','wp-optimize')?></small><br><a href="http://j.mp/1ePlbvc" target="_blank"><img style="border:0px" src="<?php echo WPO_PLUGIN_PATH ;?>elegantthemes_sm.png" width="310" height="350" alt=""></a></td>

  </tr>	
  <tr>
  <td ><input name="clean-revisions" id="clean-revisions" type="checkbox" value="" />
	 <?php 
	    if ( get_option( OPTION_NAME_RETENTION_ENABLED, 'false' ) == 'true' ) {
		_e('Remove post revisions which are older than ', 'wp-optimize');
		echo get_option( OPTION_NAME_RETENTION_PERIOD, '2' );
		echo ' ';
        _e('weeks', 'wp-optimize');
		} else {
		_e('Remove all post revisions', 'wp-optimize');
		}
	 
	  ?>
	 <br />
   <small><?php _e(wpo_getInfo('revisions'), 'wp-optimize'); ?></small></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>  
  <tr>
    <td><input name="clean-autodraft" id="clean-autodraft" type="checkbox" value="" />
	 <?php 
	    if ( get_option( OPTION_NAME_RETENTION_ENABLED, 'false' ) == 'true' ) {
		_e('Remove auto draft posts which are older than ', 'wp-optimize');
		echo get_option( OPTION_NAME_RETENTION_PERIOD, '2' );
		echo ' ';
		_e('weeks', 'wp-optimize');
		} else {
		_e('Remove auto draft posts (This will also clear out posts in Trash)', 'wp-optimize');
		}
	 
	  ?><br />
     <small><?php _e(wpo_getInfo('autodraft'), 'wp-optimize'); ?></small></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
    <td><input name="clean-comments" id="clean-comments" type="checkbox" value="" />
	 <?php 
	    if ( get_option( OPTION_NAME_RETENTION_ENABLED, 'false' ) == 'true' ) {
		_e('Remove spam comments which are older than ', 'wp-optimize');
		echo get_option( OPTION_NAME_RETENTION_PERIOD, '2' );
		echo ' ';
		_e('weeks', 'wp-optimize');
		} else {
		_e('Remove spam comments (This will also clear out comments in Trash)', 'wp-optimize');
		}
	 
	  ?><br />
	  <small><?php _e(wpo_getInfo('spam'), 'wp-optimize'); ?></small></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><input name="unapproved-comments" id="unapproved-comments" type="checkbox" value="" />
	 <?php 
	    if ( get_option( OPTION_NAME_RETENTION_ENABLED, 'false' ) == 'true' ) {
		_e('Remove unapproved comments which are older than ', 'wp-optimize');
		echo get_option( OPTION_NAME_RETENTION_PERIOD, '2' );
		echo ' ';
		_e('weeks', 'wp-optimize');
		} else {
		_e('Remove unapproved comments', 'wp-optimize');
		}
	 
	  ?><br />	 
   <small><?php _e(wpo_getInfo('unapproved'), 'wp-optimize'); ?></small></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><input name="clean-transient" id="clean-transient" type="checkbox" value="" />
	 <span style="color: red;">
     <?php _e('Remove transient options', 'wp-optimize'); ?>
     </span>
     <br />
   <small><?php _e(wpo_getInfo('transient_options'), 'wp-optimize'); ?></small></td>
  </tr>
  <tr>
<!--    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><input name="clean-postmeta" id="clean-postmeta" type="checkbox" value="" />
	 <span style="color: red;">
     <?php _e('Remove orphaned postmeta', 'wp-optimize'); ?>
     </span>
     <br />
   <small><?php _e(wpo_getInfo('postmeta'), 'wp-optimize'); ?></small></td>
  </tr> -->
<!--  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

  <tr>
    <td><input name="clean-tags" id="clean-tags" type="checkbox" value="" />
    <span style="color: red;">
	 <?php _e('Remove unused tags', 'wp-optimize'); ?>
     </span>
     <br />
   <small><?php _e(wpo_getInfo('tags'), 'wp-optimize'); ?></small></td>
  </tr>
-->
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
   <tr>
    <td><input name="clean-pingbacks" id="clean-pingbacks" type="checkbox" value="" />
	 <span style="color: red;">
     <?php _e('Remove pingbacks', 'wp-optimize'); ?>
     </span>
     <br />
   <small><?php _e(wpo_getInfo('pingbacks'), 'wp-optimize'); ?></small></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><input name="clean-trackbacks" id="clean-trackbacks" type="checkbox" value="" />
	 <span style="color: red;">
     <?php _e('Remove trackbacks', 'wp-optimize'); ?>
     </span>
     <br />
   <small><?php _e(wpo_getInfo('trackbacks'), 'wp-optimize'); ?></small></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>   
  <tr>
    <td><input name="optimize-db" id="optimize-db" type="checkbox" value="" />
	 <?php 
	 echo '<b>';
	 _e('Optimize database tables', 'wp-optimize'); 
	 echo '</b>';
	 ?></td>
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

<script>
SetDefaults();
</script>


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


	<br style="clear" />
	<table class="widefat">
		<thead>
			<tr>
				<th><?php _e('No.', 'wp-optimize'); ?></th>
				<th><?php _e('Tables', 'wp-optimize'); ?></th>
				<th><?php _e('Records', 'wp-optimize'); ?></th>
				<th><?php _e('Data Size', 'wp-optimize'); ?></th>
				<th><?php _e('Index Size', 'wp-optimize'); ?></th>
				<th><?php _e('Overhead', 'wp-optimize'); ?></th>
			</tr>
		</thead>
		
<tbody id="the-list">
<?php
	$alternate = ' class="alternate"';
	global $wpdb;
	// TODO: Read SQL Version and act accordingly
    // TODO: Check for innoDB tables
    // TODO: Check for windows servers
    $sqlversion = $wpdb->get_var("SELECT VERSION() AS version");
    $total_gain = 0;
	$no = 0;
	$row_usage = 0;
	$data_usage = 0;
	$index_usage = 0;
	$overhead_usage = 0;
	$tablesstatus = $wpdb->get_results("SHOW TABLE STATUS");
	foreach($tablesstatus as  $tablestatus) {
		if($no%2 == 0) {
			$style = '';
		} else {
			$style = ' class="alternate"';
		}
		$no++;
		echo "<tr$style>\n";
		echo '<td>'.number_format_i18n($no).'</td>'."\n";
		echo "<td>$tablestatus->Name</td>\n";
		echo '<td>'.number_format_i18n($tablestatus->Rows).'</td>'."\n";
		echo '<td>'.wpo_format_size($tablestatus->Data_length).'</td>'."\n";
		echo '<td>'.wpo_format_size($tablestatus->Index_length).'</td>'."\n";;		
		//echo '<td>'.wpo_format_size($tablestatus->Data_free).'</td>'."\n";
		
		echo '<td>';
		
		if (isset($_POST["optimize-db"])) {
		
			if($tablestatus->Data_free>0){
				echo '<font color="blue">';
				echo wpo_format_size($tablestatus->Data_free);
				echo '</font>';
				}
			else {
				echo '<font color="green">';
				echo wpo_format_size($tablestatus->Data_free);
				echo '</font>';			
			}
		}
		else {
			if($tablestatus->Data_free>0){
				echo '<font color="red">';
				echo wpo_format_size($tablestatus->Data_free);
				echo '</font>';
				}
			else {
				echo '<font color="green">';
				echo wpo_format_size($tablestatus->Data_free);
				echo '</font>';			
			}		
		}
		
		echo '</td>'."\n";
		
		$row_usage += $tablestatus->Rows;
		$data_usage += $tablestatus->Data_length;
		$index_usage +=  $tablestatus->Index_length;
		$overhead_usage += $tablestatus->Data_free;
		$total_gain += $tablestatus->Data_free;
		echo '</tr>'."\n";
	}	

		if (isset($_POST["optimize-db"])) {
		### Show Tables
		$tables = $wpdb->get_col("SHOW TABLES");  	    
		foreach($tables as $table_name) {
		$local_query = 'OPTIMIZE TABLE '.$table_name;
		$resultat  = $wpdb->query($local_query);
		}

		
			  
        //echo "optimization";
            }


		echo '<tr class="thead">'."\n";
		echo '<th>'.__('Total:', 'wp-optimize').'</th>'."\n";
		echo '<th>'.sprintf(_n('%s Table', '%s Tables', $no, 'wp-optimize'), number_format_i18n($no)).'</th>'."\n";
		echo '<th>'.sprintf(_n('%s Record', '%s Records', $row_usage, 'wp-optimize'), number_format_i18n($row_usage)).'</th>'."\n";
		echo '<th>'.wpo_format_size($data_usage).'</th>'."\n";
		echo '<th>'.wpo_format_size($index_usage).'</th>'."\n";
		echo '<th>';
		
		
		if (isset($_POST["optimize-db"])) {
			if($overhead_usage>0){
				echo '<font color="blue">';
				echo wpo_format_size($overhead_usage);
				echo '</font>';
				}
			else {
				echo '<font color="green">';
				echo wpo_format_size($overhead_usage);
				echo '</font>';		
			}
		}
		else {
			if($overhead_usage>0){
				echo '<font color="red">';
				echo wpo_format_size($overhead_usage);
				echo '</font>';
				}
			else {
				echo '<font color="green">';
				echo wpo_format_size($overhead_usage);
				echo '</font>';		
			}
		}		
		echo '</th>'."\n";
		echo '</tr>';
	
?>
</tbody>
</table>
<a name="total">&nbsp;</a>
<h3><?php _e('Total Size of Database', 'wp-optimize'); ?>:</h3>
<h2><?php 
list ($part1, $part2) = wpo_getCurrentDBSize(); 
echo $part1;

?></h2>

<?php if (isset($_POST["optimize-db"])) {
    ?>

<?php //$total_gain = round ($total_gain,3);?>

<h3><?php _e('Optimization Results', 'wp-optimize'); ?>:</h3>
<p style="color: #0000FF;"><?php _e('Total Space Saved', 'wp-optimize'); ?>: <?php echo wpo_format_size($total_gain);  wpo_updateTotalCleaned(strval($total_gain));?></p>
  <?php } else { ?>
<?php //$total_gain = round ($total_gain,3); ?>
  <?php if(!$total_gain==0){ ?>

<h3><?php _e('Optimization Possibility', 'wp-optimize'); ?>:</h3>
<p style="color: #FF0000;"><?php _e('Total space can be saved', 'wp-optimize'); ?>: <?php echo wpo_format_size($total_gain);?></p>
  <?php } ?>
<?php
}
?>

<?php
}
?>