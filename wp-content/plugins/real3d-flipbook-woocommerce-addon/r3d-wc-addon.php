<?php

/*
Plugin Name: Real3D Flipbook WooCommerce Addon
Plugin URI: http://codecanyon.net/user/creativeinteractivemedia/portfolio?ref=creativeinteractivemedia
Description: Addon for Real3D Flipbook. checks WC product purchase status and displays flipbook
Version: 1.0.0
Author: creativeinteractivemedia
Author URI: http://codecanyon.net/user/creativeinteractivemedia?ref=creativeinteractivemedia
*/

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

define('R3D_WOO_VERSION', '1.0.0');
define('R3D_WOO_FILE', __FILE__);
    
include_once( plugin_dir_path( R3D_WOO_FILE ).'/includes/main.php' );

$r3d_woo = R3D_Woo::get_instance( );

