<table width="95%" border="0" cellspacing="0" cellpadding="0">
<form action="#" method="post" enctype="multipart/form-data" name="optimize_form" id="optimize_form">

  <tr>
    <td colspan="1" valign="top"><h3><?php _e('Status : ', 'wp-optimize'); ?>
   <?php
	$lastopt = get_option(OPTION_NAME_LAST_OPT, 'Never');
	if ($lastopt !== 'Never'){
		echo '<i>';		
		_e('Last automatic optimization was at ', 'wp-optimize');
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
			//echo $date->format('l jS \of F Y h:i:s A') . "\n";
			//echo $date->format(__('l jS \of F Y')) . "\n";
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
		echo $part1.'</font> '.__('Kb.', 'wp-optimize');
        echo ' ';
		_e('You have saved', 'wp-optimize');
		echo ' : ';
		echo '<font color="red">';
		echo $part2.'</font> '.__('Kb', 'wp-optimize');
		
    }
	else {
		list ($part1, $part2) = wpo_getCurrentDBSize();
         
		_e('Current database size', 'wp-optimize');
		echo ' : ';
		echo '<font color="blue">';
		echo $part1.'</font> '.__('Kb.', 'wp-optimize');
        $this_value = floatval($part2);
        if ($this_value > 0){
            echo ' ';
    		_e('You can save almost', 'wp-optimize');
    		echo ' : ';
    		echo '<font color="red">';
    		echo $part2.'</font> '.__('Kb', 'wp-optimize');
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
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><input name="clean-postmeta" id="clean-postmeta" type="checkbox" value="" />
	 <span style="color: red;">
     <?php _e('Remove orphaned postmeta', 'wp-optimize'); ?>
     </span>
     <br />
   <small><?php _e(wpo_getInfo('postmeta'), 'wp-optimize'); ?></small></td>
  </tr>
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
<h3><?php _e('Total Size of Database', 'wp-optimize'); ?>:</h3>
<h2><?php echo round ($total_db_space_a,3);?> Kb</h2>

<?php if (isset($_POST["optimize-db"])) {
    ?>

<?php $total_gain = round ($total_gain,3);?>

<h3><?php _e('Optimization Results', 'wp-optimize'); ?>:</h3>
<p style="color: #0000FF;"><?php _e('Total Space Saved', 'wp-optimize'); ?>: <?php echo $total_gain;  wpo_updateTotalCleaned(strval($total_gain));?> Kb</p>
  <?php } else { ?>
<?php $total_gain = round ($total_gain,3); ?>
  <?php if(!$total_gain==0){ ?>

<h3><?php _e('Optimization Possibility', 'wp-optimize'); ?>:</h3>
<p style="color: #FF0000;"><?php _e('Total space can be saved', 'wp-optimize'); ?>: <?php echo $total_gain;?> Kb</p>
  <?php } ?>
<?php
}
?>

<?php
}
?>