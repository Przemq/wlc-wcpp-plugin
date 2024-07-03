<?php

namespace WoocommerceFeaturedProduct\Admin;

if (!defined('ABSPATH')) {
  exit;
}
class WCPP_Product_Fields {
  public function add_promoted_field(): void {
    woocommerce_wp_checkbox(
      array(
        'id' => '_wcpp_promoted_product',
        'label' => __('Promote this product', 'woocommerce-promoted-product'),
        'description' => __('Check this box to mark as promoted product.', 'woocommerce-promoted-product'),
      )
    );
  }

  public function save_promoted_field(int $post_id): void {
    $is_promoted = isset($_POST['_wcpp_promoted_product']) ? 'yes' : 'no';
    update_post_meta($post_id, '_wcpp_promoted_product', $is_promoted);
  }
}
