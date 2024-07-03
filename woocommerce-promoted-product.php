<?php
/*
Plugin Name: Woocommerce Promoted Product
Plugin URI:  https://whitelabelcoders.com/wordpress-development-agency/wordpress-plugin-development/
Description: This plugin allows you to mark a product as promoted and display it in the header.
Version:     0.1.0
Author:      Przemysław Kudła
Author URI:  https://www.linkedin.com/in/przemyslawkudla/
License:     GPL2
*/

use WoocommerceFeaturedProduct\WCPP_Promoted_Product;

if (!defined('ABSPATH')) {
  exit;
}

require_once __DIR__ . '/vendor/autoload.php';

define('WCPP_VERSION', '0.1.0');

/**
 * Initialize the plugin
 * @return void
 */
function wcfp_init(): void
{
  $plugin = new WCPP_Promoted_Product();
  $plugin->run();
}

add_action('plugins_loaded', 'wcfp_init');


