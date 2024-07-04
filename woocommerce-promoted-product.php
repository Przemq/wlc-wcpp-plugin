<?php

declare(strict_types=1);

/*
Plugin Name: Woocommerce Promoted Product
Plugin URI:  https://whitelabelcoders.com/wordpress-development-agency/wordpress-plugin-development/
Description: This plugin allows you to mark a product as promoted and display it in the header.
Version:     1.3.0
Author:      Przemysław Kudła
Author URI:  https://www.linkedin.com/in/przemyslawkudla/
License:     GPL2
*/

use WoocommerceFeaturedProduct\WCPP_Promoted_Product;

if (!\defined('ABSPATH')) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

\define('WCPP_VERSION', '1.3.0');
\define('WCPP_PROMOTED_PRODUCT_ID', 'wcpp_promoted_product_id');
\define('WCPP_PROMOTED_PRODUCT_EXPIRATION_DATE', '_wcpp_promoted_expiration_date');
\define('WCPP_IS_PROMOTED_PRODUCT', '_wcpp_is_promoted_product');
\define('WCPP_IS_SET_EXPIRTAION', '_wcpp_is_set_expiration');
\define('WCPP_PLUGIN_FILE', __FILE__);
\define('WCPP_CUSTOM_TITLE_META_KEY', '_wcpp_custom_title');
\define('WCPP_PROMOTED_PRODUCT_PREFIX', 'wcpp_promoted_product_prefix');
\define('WCPP_PROMOTED_PRODUCT_BG', 'wcpp_background_color');
\define('WCPP_PROMOTED_PRODUCT_TEXT_COLOR', 'wcpp_text_color');
\define('WCPP_PROMOTED_PRODUCT_DATA', 'wcpp_promoted_product_data');

/**
 * Initialize the plugin.
 */
function wcpp_init(): void
{
  if (!function_exists('is_plugin_active')) {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
  }

  $woocommerce_active = is_plugin_active('woocommerce/woocommerce.php');

  if (is_multisite()) {
    $woocommerce_active = $woocommerce_active || is_plugin_active_for_network('woocommerce/woocommerce.php');
  }

  if (!$woocommerce_active) {
    add_action('admin_notices', 'wcpp_woocommerce_inactive_notice');
    deactivate_plugins(plugin_basename(__FILE__));
    return;
  }


  $plugin = new WCPP_Promoted_Product();
  $plugin->run();
}

\add_action('plugins_loaded', 'wcpp_init');


function wcpp_woocommerce_inactive_notice() {
  echo '<div class="notice notice-warning is-dismissible"><p>';
  _e('WooCommerce Promoted Product requires WooCommerce to be installed and active.', 'woocommerce-promoted-product');
  echo '</p></div>';
}
