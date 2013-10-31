<?php
/*
Plugin Name: WP-Optimize
Plugin URI: http://www.ruhanirabin.com/wp-optimize/
Description: This plugin helps you to keep your database clean by removing post revisions and spams in a blaze. Additionally it allows you to run optimize command on your WordPress core tables (use with caution).
Version: 1.1.0
Author: Ruhani Rabin
Author URI: http://www.ruhanirabin.com

    Copyright 2013  Ruhani Rabin  (email : get@ruhanirabin.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

# ---------------------------------- #
# prevent file from being accessed directly
# ---------------------------------- #
if ('wp-optimize.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not access this file directly. Thanks!');
	
if (! defined('OPTION_NAME'))
    define('OPTION_NAME', 'wp-optimize-weekly-schedule');	

if (! defined('OPTION_NAME_RETENTION_ENABLED'))
    define('OPTION_NAME_RETENTION_ENABLED', 'wp-optimize-retention-enabled');	

if (! defined('OPTION_NAME_RETENTION_PERIOD'))
    define('OPTION_NAME_RETENTION_PERIOD', 'wp-optimize-retention-period');	

if (! defined('OPTION_NAME_LAST_OPT'))
    define('OPTION_NAME_LAST_OPT', 'wp-optimize-last-optimized');	
	
if (! defined('WPO_PLUGIN_PATH'))
	define('WPO_PLUGIN_PATH', plugin_dir_url( __FILE__ ));

global $current_user;
	
register_activation_hook(__FILE__,'optimize_admin_actions');
register_deactivation_hook(__FILE__,'optimize_admin_actions_remove');


add_action('init', 'wpoptimize_textdomain');
function wpoptimize_textdomain() {
   if (function_exists('load_plugin_textdomain')) {
	load_plugin_textdomain('wp-optimize', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
   }		
}


function optimize_menu(){
    include 'wp-optimize-admin.php';
	//include 'wp-optimize-common.php';
	
/* 	list ($part1, $part2) = getCurrentDBSize();
	SendEmailToAdmin($part1, $part2); */
}

function wpo_admin_bar() {
	global $wp_admin_bar;

	//Add a link called 'My Link'...
	$wp_admin_bar->add_node(array(
		'id'    => 'wp-optimize',
		'title' => 'WP-Optimize',
		'href'  => admin_url( 'admin.php?page=WP-Optimize', 'http' )
	));

}
add_action( 'wp_before_admin_bar_render', 'wpo_admin_bar' ); 


// Add settings link on plugin page
function wpo_plugin_settings_link($links) { 
  $settings_link = '<a href="admin.php?page=WP-Optimize">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
 
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'wpo_plugin_settings_link' );


// plugin activation actions
function optimize_admin_actions()
{
	if ( current_user_can('manage_options') ) {
		if (function_exists('add_meta_box')) {
			add_menu_page("WP-Optimize", "WP-Optimize", "manage_options", "WP-Optimize", "optimize_menu", plugin_dir_url( __FILE__ ).'wpo.png', 81);
		} else {
			add_submenu_page("index.php", "WP-Optimize", "WP-Optimize", "manage_options", "WP-Optimize", "optimize_menu", plugin_dir_url( __FILE__ ).'wpo.png');
		} // end if addmetabox
		wpo_cron_activate();
	}	
}

//executed this function weekly
function wpo_cron_weekly() {
	global $wpdb;
	list ($retention_enabled, $retention_period) = getRetainInfo();
	
    if ( get_option(OPTION_NAME) == 'true') {
            			
			$clean = "DELETE FROM $wpdb->posts WHERE post_type = 'revision'";
            if ($retention_enabled == 'true') {
                $clean .= ' and post_modified < NOW() - INTERVAL ' .  $retention_period . ' WEEK';
            }
            $clean .= ';';
			$revisions = $wpdb->query( $clean );
		
            $clean = "DELETE FROM $wpdb->posts WHERE post_status = 'auto-draft'";
            if ($retention_enabled == 'true') {
                $clean .= ' and post_modified < NOW() - INTERVAL ' .  $retention_period . ' WEEK';
            }
            $clean .= ';';
            $autodraft = $wpdb->query( $clean );
			
            $clean = "DELETE FROM $wpdb->posts WHERE post_status = 'trash'";
            if ($retention_enabled == 'true') {
                $clean .= ' and post_modified < NOW() - INTERVAL ' .  $retention_period . ' WEEK';
            }
            $clean .= ';';
            $posttrash = $wpdb->query( $clean );

            $clean = "DELETE FROM $wpdb->comments WHERE comment_approved = 'spam'";
            if ($retention_enabled == 'true') {
				$clean .= ' and comment_date < NOW() - INTERVAL ' . $retention_period . ' WEEK';
            }
            $clean .= ';';
            $comments = $wpdb->query( $clean );			
			
            $clean = "DELETE FROM $wpdb->comments WHERE comment_approved = 'post-trashed'";
            if ($retention_enabled == 'true') {
				$clean .= ' and comment_date < NOW() - INTERVAL ' . $retention_period . ' WEEK';
            }
            $clean .= ';';			
            $commentstrash = $wpdb->query( $clean );
			
			
		$db_tables = $wpdb->get_results('SHOW TABLES',ARRAY_A);
		foreach ($db_tables as $table){
			$t = array_values($table);
			$wpdb->query("OPTIMIZE TABLE ".$t[0]);
		}
		
		$thisdate = date('l jS \of F Y h:i:s A');
		update_option( OPTION_NAME_LAST_OPT, $thisdate );
		
	}	
}	

function wpo_cron_activate() {
	if ( get_option( OPTION_NAME ) !== false ) {
		if ( get_option(OPTION_NAME) == 'true') {
			if (!wp_next_scheduled('wpo_cron_event2')) {
				wp_schedule_event(time(), 'weekly', 'wpo_cron_event2');
			}
		} 
	} else myPluginOptionsSetDefaults();	
}

function wpo_cron_deactivate() {
	//wp_clear_scheduled_hook('wpo_cron_event');
	wp_clear_scheduled_hook('wpo_cron_event2');
}

add_action('wpo_cron_event2', 'wpo_cron_weekly');
add_filter('cron_schedules', 'wpo_cron_update_sched');

// scheduler functions to update schedulers
// possible problem found at support request 
// http://wordpress.org/support/topic/bug-found-in-scheduler-code
/* function wpo_cron_update_sched( $schedules ) {
	return array(
		'weekly' => array('interval' => 60*60*24*7, 'display' => 'Once Weekly'),
		'otherweekly' => array('interval' => 60*60*24*14, 'display' => 'Once Every Other Week'),
	);
} */
function wpo_cron_update_sched( $schedules ) {
	$schedules['weekly'] = array('interval' => 60*60*24*7, 'display' => 'Once Weekly');
	$schedules['otherweekly'] = array('interval' => 60*60*24*14, 'display' => 'Once Every Other Week');
	return $schedules;
}


// plugin deactivation actions
function optimize_admin_actions_remove()
{
	wpo_cron_deactivate();
	delete_option( OPTION_NAME );
	delete_option( OPTION_NAME_RETENTION_ENABLED );
	delete_option( OPTION_NAME_RETENTION_PERIOD );
	delete_option( OPTION_NAME_LAST_OPT );
}

// setup options if not exists already
function myPluginOptionsSetDefaults() {
		$deprecated = null;
		$autoload = 'no';
		
	if ( get_option( OPTION_NAME ) !== false ) {
		// The option already exists, so we just update it.

	} else {
		// The option hasn't been added yet. We'll add it with $autoload set to 'no'.
		add_option( OPTION_NAME, 'false', $deprecated, $autoload );
		add_option( OPTION_NAME_LAST_OPT, 'Never', $deprecated, $autoload );	
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
} 


add_action('admin_menu', 'optimize_admin_actions');
?>