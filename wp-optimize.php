<?php
/*
Plugin Name: WP-Optimize
Plugin URI: http://www.ruhanirabin.com/wp-optimize/
Description: This plugin helps you to keep your database clean by removing post revisions and spams in a blaze. allows you to rename your admin name also. Additionally it allows you to run optimize command on your wordpress core tables (use with caution).
Version: 0.9.1
Author: Ruhani Rabin
Author URI: http://www.ruhanirabin.com

    Copyright 2008-2011  Ruhani Rabin  (email : get@ruhanirabin.com)

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

register_activation_hook(__FILE__,'optimize_admin_actions');

add_action('init', 'wpoptimize_textdomain');
function wpoptimize_textdomain() {
   if (function_exists('load_plugin_textdomain')) {
	load_plugin_textdomain('wp-optimize', false, 'wp-optimize');
   }		
}

function optimize_menu(){
    include 'wp-optimize-admin.php';
}



function optimize_admin_actions()
{
	if (function_exists('add_meta_box')) { 
    	add_menu_page("WP-Optimize", "WP-Optimize", 2, "WP-Optimize", "optimize_menu");
    } else {
    add_submenu_page("index.php", "WP-Optimize", "WP-Optimize", 2, "WP-Optimize", "optimize_menu");
    }
    //add_submenu_page("index.php", "WP-Optimize", "WP-Optimize", 10, "WP-Optimize", "optimize_menu");
}

add_action('admin_menu', 'optimize_admin_actions');
?>