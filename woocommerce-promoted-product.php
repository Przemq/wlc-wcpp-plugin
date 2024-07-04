<?php

declare(strict_types=1);
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

if (!\defined('ABSPATH')) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

if (!\defined('WCPP_VERSION')) {
    \define('WCPP_VERSION', '0.1.0');
}

if (!\defined('WCPP_PROMOTED_PRODUCT_ID')) {
    \define('WCPP_PROMOTED_PRODUCT_ID', 'wcpp_promoted_product_id');
}

if (!\defined('WCPP_PLUGIN_FILE')) {
    \define('WCPP_PLUGIN_FILE', __FILE__);
}


\define('WCPP_CUSTOM_TITLE_META_KEY', '_wcpp_custom_title');
\define('WCPP_PROMOTED_PRODUCT_PREFIX', 'wcpp_promoted_product_prefix');
\define('WCPP_PROMOTED_PRODUCT_BG', 'wcpp_background_color');
\define('WCPP_PROMOTED_PRODUCT_TEXT_COLOR', 'wcpp_text_color');


/**
 * Initialize the plugin.
 */
function wcfp_init(): void
{
    $plugin = new WCPP_Promoted_Product();
    $plugin->run();
}

\add_action('plugins_loaded', 'wcfp_init');
