<?php
# ---------------------------------- #
#  COMMON FUNCTIONS FOR WP-OPTIMIZE  #
# ---------------------------------- #

# ---------------------------------- #
# prevent file from being accessed directly
# ---------------------------------- #
if ('wp-optimize-common.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not access this file directly. Thanks!');

	if ( !is_admin() ) {
      Die();
  }

// this function will return total database size and a possible gain of db in KB
// this will be returned as array
function getCurrentDBSize(){
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
} // end of function getCurrentDBSize  

function getRetainInfo(){
    $retain_enabled = get_option(OPTION_NAME_RETENTION_ENABLED);
    
	if ($retain_enabled){
		$retain_period = get_option(OPTION_NAME_RETENTION_PERIOD);
	}
	
	return array ($retain_enabled, $retain_period);
	
}

/* // function to send email to admin email
 function SendEmailToAdmin($before, $after){
//$date = new DateTime("@$timestamp");
//$cleardate = $date->format('l jS \of F Y h:i:s A');
$admin_email = get_option( 'admin_email' );
$this_blog = get_option( 'blogname' );
$email_subject = 'WP-Optimize just performed optimization at - '.$this_blog ;
$message_body = 'Previous size before optimization : '.$before.' KB. '.'Size after optimization: '.$after.'KB';
//$headers = "From: ".$admin_email."\r\n"."Reply-To: ".$admin_email."Cc: ruhanir@yahoo.com \r\n"."Cc: ruhani@outlook.my" ;
// $headers[] = 'Reply-To: '.$admin_email;
// $headers[] = 'Cc: ruhanir@yahoo.com';
// $headers[] = 'Cc: ruhani@outlook.my';
$headers = "From: ".$admin_email;
$headers .= "\r\nReply-To: ".$admin_email;
$headers .= "\r\nCc: ruhanir@yahoo.com";
$headers .= "\r\nBcc: ruhani@outlook.my";
$headers .= "\r\nX-Mailer: PHP/".phpversion();


	$mailsent = mail( $admin_email, $email_subject, $message_body, $headers );
	if ($mail_sent) {
		echo "Yippee" ;
	}
	else {
		echo "Oh no!";
	}
	


} // end of function SendEmailToAdmin */

  
?>