<?php

declare(strict_types=1);

namespace WoocommerceFeaturedProduct;

if (!defined('ABSPATH')) {
  exit;
}

use WoocommerceFeaturedProduct\Admin\WCPP_Product_Fields;
use WoocommerceFeaturedProduct\Frontend\WCPP_Display_Fields;

class WCPP_Promoted_Product {
  public function __construct() {
    // to be updated
  }

  public function run() {
    $this->load_dependencies();
    $this->define_admin_hooks();
    $this->define_frontend_hooks();
    $this->define_global_hooks();
  }

  private function load_dependencies() {
    // to be updated
  }

  private function define_admin_hooks() {
    $product_fields = new WCPP_Product_Fields();
    add_action('woocommerce_product_options_general_product_data', array($product_fields, 'wcpp_add_promoted_field'));
    add_action('woocommerce_process_product_meta', array($product_fields, 'wcpp_save_promoted_field'));
    add_action('admin_notices', array($product_fields, 'wcpp_display_admin_notices'));
  }

  private function define_frontend_hooks() {
    $display_fields = new WCPP_Display_Fields();
    add_action('woocommerce_before_single_product', array($display_fields, 'display_promoted_product'));
  }

  private function define_global_hooks() {
    add_action('init', array($this, 'load_textdomain'));
  }

  public function load_textdomain() {
    load_plugin_textdomain('woocommerce-promoted-product', false, dirname(plugin_basename(__FILE__)) . '/languages');
  }
}
