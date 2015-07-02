<?php


/* ------------------------------------------------------------------------------------
Plugin Name: Livemenu Lite
Plugin URI: http://livemenuwp.com
Description: Live Build & Style Your WordPress Mega Menus on Your Front-End. Take full control of you wordpress menus.
Version: 1.0
Author: Sabri Taieb ( codezag )
Author URI: http://codecanyon.net/user/vamospace
License: You should have purchased a license from http://codecanyon.net/
Copyright 2015 : Sabri Taieb -> http://vamospace.com
Support Forums : http://vamospace.com/support
------------------------------------------------------------------------------------ */


/**
 * Defined
**/
define('LM_VERSION', '1.0' );
define('LM_BASE_URL', plugin_dir_url( __FILE__ ) );
define('LM_BASE_DIR', plugin_dir_path(__FILE__) );
define('LM_DEFAULT_SKIN', 'dark' ); // Default skin

/**
 * Load Plugin Files
**/
require_once('inc/files-loader.php');

/**
 * Plugin Activation Hook
**/
register_activation_hook( __FILE__, array( 'livemenu_manager', 'plugin_activation_hook' ) );

/**
 * Start The Show!
**/
$lm_manager = new livemenu_manager();