<?php
# --------------------------------------- #
# prevent file from being accessed directly
# --------------------------------------- #
if ( ! defined( 'WPINC' ) ) {
	die;
}

if (isset($_POST["optimize-db"])) {
    optimizeTables(true);
    }
else optimizeTables(false);

?>
<?php
Function optimizeTables($Optimize=false){
?>
<h3>
<?php
_e('Database Name:', 'wp-optimize'); ?> '<?php _e(DB_NAME, 'wp-optimize');
echo "'";
?></h3>


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
				<th><?php _e('Type', 'wp-optimize'); ?></th>
				<th><?php _e('Overhead', 'wp-optimize');?></th>

			</tr>
		</thead>

<tbody id="the-list">
<?php
	$alternate = ' class="alternate"';
	global $wpdb;
	// Read SQL Version and act accordingly
    // Check for InnoDB tables
    // Check for windows servers
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
		echo '<td>'.$tablestatus->Engine.'</td>'."\n";;
		//echo '<td>'.wpo_format_size($tablestatus->Data_free).'</td>'."\n";

        if ($tablestatus->Engine != 'InnoDB'){

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
           }
           else {
		echo '<td>';

    				echo '<font color="blue">';
    				echo '-';
    				echo '</font>';


		echo '</td>'."\n";

           }

		$row_usage += $tablestatus->Rows;
		$data_usage += $tablestatus->Data_length;
		$index_usage +=  $tablestatus->Index_length;

        if ($tablestatus->Engine != 'InnoDB'){
            $overhead_usage += $tablestatus->Data_free;
            $total_gain += $tablestatus->Data_free;
        } else {
            $overhead_usage += 0;
            $total_gain += 0;
        }
		echo '</tr>'."\n";
	}


		echo '<tr class="thead">'."\n";
		echo '<th>'.__('Total:', 'wp-optimize').'</th>'."\n";
		echo '<th>'.sprintf(_n('%d Table', '%d Tables', $no, 'wp-optimize'), number_format_i18n($no)).'</th>'."\n";
		echo '<th>'.sprintf(_n('%d Record', '%d Records', $row_usage, 'wp-optimize'), number_format_i18n($row_usage)).'</th>'."\n";
		echo '<th>'.wpo_format_size($data_usage).'</th>'."\n";
		echo '<th>'.wpo_format_size($index_usage).'</th>'."\n";
		echo '<th>'.'-'.'</th>'."\n";
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

<h3><?php _e('Total Size of Database:', 'wp-optimize'); ?></h3>
<h2><?php
list ($part1, $part2) = wpo_getCurrentDBSize();
echo $part1;

?></h2>

<?php if (isset($_POST["optimize-db"])) {
    ?>

<?php //$total_gain = round ($total_gain,3);?>

<h3><?php _e('Optimization Results:', 'wp-optimize'); ?></h3>
<p style="color: #0000FF;">
<?php

if ($total_gain > 0){
_e('Total Space Saved:', 'wp-optimize');
    echo wpo_format_size($total_gain);  wpo_updateTotalCleaned(strval($total_gain));
}
?></p>
  <?php } else { ?>
<?php //$total_gain = round ($total_gain,3); ?>
  <?php if(!$total_gain==0){ ?>

<h3><?php

if ($total_gain > 0){
    _e('Optimization Possibility:', 'wp-optimize');
    }

?></h3>
<p style="color: #FF0000;">
<?php if ($total_gain > 0){
    _e('Total space that can be saved:', 'wp-optimize'); ?> <?php echo wpo_format_size($total_gain);
    echo ' ';
    }
    ?></p>
  <?php } ?>
<?php
}
?>

<?php
} //end of optimize function
?>
