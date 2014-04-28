<?php
/*
Plugin Name: WP-Optimize
Plugin URI: http://www.ruhanirabin.com/wp-optimize/
Description: This plugin helps you to keep your database clean by removing post revisions and spams in a blaze. Additionally it allows you to run optimize command on your WordPress core tables (use with caution).
Version: 1.8.3
Author: Ruhani Rabin
Author URI: https://github.com/ruhanirabin/WP-Optimize
Text Domain: wp-optimize
Domain Path: /languages
Git URI: https://github.com/ruhanirabin/wp-optimize
GitHub Plugin URI: https://github.com/ruhanirabin/wp-optimize
GitHub Branch: master
    Copyright 2009-2014  Ruhani Rabin  (email : get@ruhanirabin.com)

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


# ---------------------------------------- #
# Find and replace version info in all files
# ---------------------------------------- #

# ---------------------------------- #
# prevent file from being accessed directly
# ---------------------------------- #
if ( ! defined( 'WPINC' ) ) {
	die;
}	
	
global $current_user;

if (! defined('WPO_VERSION'))
    define('WPO_VERSION', '1.8.3');

if (! defined('WPO_PLUGIN_MAIN_PATH'))
	define('WPO_PLUGIN_MAIN_PATH', plugin_dir_path( __FILE__ ));

if (! defined('WPO_PAYPAL'))
	define('WPO_PAYPAL', 'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=LTCMF6JDX94QS');

	if ( file_exists(WPO_PLUGIN_MAIN_PATH . 'wp-optimize-common.php')) {
    require_once (WPO_PLUGIN_MAIN_PATH . 'wp-optimize-common.php');
    
    } else {
	die ('Functions File is missing!');
	}

   
register_activation_hook(__FILE__,'wpo_admin_actions');
register_deactivation_hook(__FILE__,'wpo_admin_actions_remove');

// init text domain
add_action('init', 'wp_optimize_textdomain');
function wp_optimize_textdomain() {
   if (function_exists('load_plugin_textdomain')) {
	load_plugin_textdomain('wp-optimize', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
   }
}

function wp_optimize_menu(){
	include_once( 'wp-optimize-admin.php' );
}

function wpo_admin_bar() {
	global $wp_admin_bar;

	//Add a link called at the top admin bar
	$wp_admin_bar->add_node(array(
		'id'    => 'wp-optimize',
		'title' => 'WP-Optimize',
		'href'  => admin_url( 'admin.php?page=WP-Optimize', 'http' )
	));

}


// Add settings link on plugin page
function wpo_plugin_settings_link($links) {
  //$optimize_link = '<a href="admin.php?page=WP-Optimize">Optimizer</a>';
  $settings_link = '<a href="' . esc_url( admin_url( 'admin.php?page=WP-Optimize&tab=wp_optimize_settings' ) ) . '">' . __( 'Settings', 'wp-optimize' ) . '</a>';
  $optimize_link = '<a href="' . esc_url( admin_url( 'admin.php?page=WP-Optimize' ) ) . '">' . __( 'Optimizer', 'wp-optimize' ) . '</a>';
  array_unshift($links, $settings_link);
  array_unshift($links, $optimize_link);
  return $links;
}

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'wpo_plugin_settings_link' );


// plugin activation actions
function wpo_admin_actions()
{
	if ( current_user_can('manage_options') ) {
		if (function_exists('add_meta_box')) {
			add_menu_page("WP-Optimize", "WP-Optimize", "manage_options", "WP-Optimize", "wp_optimize_menu", plugin_dir_url( __FILE__ ).'wpo.png');
		} else {
			add_submenu_page("index.php", "WP-Optimize", "WP-Optimize", "manage_options", "WP-Optimize", "wp_optimize_menu", plugin_dir_url( __FILE__ ).'wpo.png');
		} // end if addmeta box
        if (get_option( OPTION_NAME_ENABLE_ADMIN_MENU, 'false' ) == 'true' ){
        		add_action( 'wp_before_admin_bar_render', 'wpo_admin_bar' );
        }        
        
		wpo_PluginOptionsSetDefaults();
		wpo_cron_activate();
	}
}

// TODO: Need to find out why the schedule time is not refreshing
function wpo_cron_activate() {
	//wpo_debugLog('running wpo_cron_activate()');
    $gmtoffset = (int) (3600 * ((double) get_option('gmt_offset')));
   
    if ( get_option( OPTION_NAME_SCHEDULE ) !== false ) {
		if ( get_option(OPTION_NAME_SCHEDULE) == 'true') {
			if (!wp_next_scheduled('wpo_cron_event2')) {

				$schedule_type = get_option(OPTION_NAME_SCHEDULE_TYPE, 'wpo_weekly');
                
                switch ($schedule_type) {
                        case "wpo_weekly":
                         //
                         $this_time = 60*60*24*7;
                        break;                

                        case "wpo_otherweekly":
                         //
                         $this_time = 60*60*24*14;
                        break;

                        case "wpo_monthly":
                         //
                         $this_time = 60*60*24*31;
                        break;
                        
                        default:
                         $this_time = 60*60*24*7;
                        break;                        
                                              
                }               
				//$this_time = time() + $gmtoffset; 
                add_action('wpo_cron_event2', 'wpo_cron_action');
                wp_schedule_event(time() + $this_time, $schedule_type, 'wpo_cron_event2');
                wpo_debugLog('running wp_schedule_event()');

                
				//add_filter('cron_schedules', 'wpo_cron_update_sched');
			}
		}
	} else wpo_PluginOptionsSetDefaults();
}

function wpo_cron_deactivate() {
	//wp_clear_scheduled_hook('wpo_cron_event');
	wpo_debugLog('running wpo_cron_deactivate()');
    wp_clear_scheduled_hook('wpo_cron_event2');
}

add_action('wpo_cron_event2', 'wpo_cron_action');
add_filter('cron_schedules', 'wpo_cron_update_sched');

// scheduler functions to update schedulers
// possible problem found at support request
// http://wordpress.org/support/topic/bug-found-in-scheduler-code

function wpo_cron_update_sched( $schedules ) {
	$schedules['wpo_weekly'] = array('interval' => 60*60*24*7, 'display' => 'Once Weekly');
	$schedules['wpo_otherweekly'] = array('interval' => 60*60*24*14, 'display' => 'Once Every Other Week');
	$schedules['wpo_monthly'] = array('interval' => 60*60*24*31, 'display' => 'Once Every Month');
	return $schedules;
}


// plugin deactivation actions
function wpo_admin_actions_remove()
{
	wpo_cron_deactivate();
	wpo_removeOptions();
}
add_action('admin_menu', 'wpo_admin_actions');

/* function wpo_admin_styles() {
    wp_register_style( 'wpo_admin_stylesheet', plugins_url( '/css/wpo_admin.css', __FILE__ ) );
    wp_enqueue_style( 'wpo_admin_stylesheet' );
}
add_action( 'admin_enqueue_scripts', 'wpo_admin_styles' ); */


function wpo_admin_register_head() {
    $style_url = plugins_url( '/css/wpo_admin.css', __FILE__ ) ;
    echo "<link rel='stylesheet' type='text/css' href='".$style_url."' />\n";
}
add_action('admin_head', 'wpo_admin_register_head');

?>