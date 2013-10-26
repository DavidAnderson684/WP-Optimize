<?php
/*
Plugin Name: WP-Optimize
Plugin URI: http://www.ruhanirabin.com/wp-optimize/
Description: This plugin helps you to keep your database clean by removing post revisions and spams in a blaze. Additionally it allows you to run optimize command on your WordPress core tables (use with caution).
Version: 1.0.1
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

if (! defined('WPO_PLUGIN_PATH'))
	define('WPO_PLUGIN_PATH', plugin_dir_url( __FILE__ ));

global $current_user;
/* if ( !current_user_can('manage_options') )
	die(__('Erm.. Not really admin? uh?'));	 */
	
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
}


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
			add_menu_page("WP-Optimize", "WP-Optimize", "manage_options", "WP-Optimize", "optimize_menu");
		} else {
			add_submenu_page("index.php", "WP-Optimize", "WP-Optimize", "manage_options", "WP-Optimize", "optimize_menu");
		} // end if addmetabox
	
		wpo_cron_activate();
	}	
}

//executed this function weekly
function wpo_cron_weekly() {
	global $wpdb;
    if ( get_option(OPTION_NAME) == 'true') {
			$clean = "DELETE FROM $wpdb->posts WHERE post_type = 'revision';";
			$revisions = $wpdb->query( $clean );
		
            $clean = "DELETE FROM $wpdb->posts WHERE post_status = 'auto-draft';";
            $autodraft = $wpdb->query( $clean );

            $clean = "DELETE FROM $wpdb->posts WHERE post_status = 'trash';";
            $trashpost = $wpdb->query( $clean );

            $clean = "DELETE FROM $wpdb->comments WHERE comment_approved = 'post-trashed';";
            $trashcomments = $wpdb->query( $clean );
			
            $clean = "DELETE FROM $wpdb->comments WHERE comment_approved = 'spam';";
            $comments = $wpdb->query( $clean );

            // this is disabled for now
			//$clean = "DELETE FROM $wpdb->comments WHERE comment_approved = '0';";
            //$comments = $wpdb->query( $clean );

            //$clean = "DELETE FROM $wpdb->comments WHERE comment_type = 'pingback';";
            //$comments = $wpdb->query( $clean );

            //$clean = "DELETE FROM $wpdb->comments WHERE comment_type = 'trackback';";
            //$comments = $wpdb->query( $clean );
			
		$db_tables = $wpdb->get_results('SHOW TABLES',ARRAY_A);
		foreach ($db_tables as $table){
			$t = array_values($table);
			$wpdb->query("OPTIMIZE TABLE ".$t[0]);
		}
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
function wpo_cron_update_sched( $schedules ) {
	return array(
		'weekly' => array('interval' => 60*60*24*7, 'display' => 'Once Weekly'),
		'otherweekly' => array('interval' => 60*60*24*14, 'display' => 'Once Every Other Week'),
	);
}


// plugin deactivation actions
function optimize_admin_actions_remove()
{
	wpo_cron_deactivate();
	delete_option( OPTION_NAME );
}

// setup options if not exists already
function myPluginOptionsSetDefaults() {
	if ( get_option( OPTION_NAME ) !== false ) {
		// The option already exists, so we just update it.
		//update_option( OPTION_NAME, $value );

	} else {
		// The option hasn't been added yet. We'll add it with $autoload set to 'no'.
		$deprecated = null;
		$autoload = 'no';
		add_option( OPTION_NAME, 'false', $deprecated, $autoload );
		// deactivate cron
		wpo_cron_deactivate();
	}
} 


add_action('admin_menu', 'optimize_admin_actions');
?>