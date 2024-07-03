<?php

namespace WoocommerceFeaturedProduct\Admin;

if (!defined('ABSPATH')) {
  exit;
}
class WCPP_Product_Fields {
  public function wcpp_add_promoted_field(): void {
    woocommerce_wp_checkbox(
      array(
        'id' => '_wcpp_promoted_product',
        'label' => __('Promote this product', 'woocommerce-promoted-product'),
        'description' => __('Check this box to mark as promoted product.', 'woocommerce-promoted-product'),
      )
    );
  }

  public function wcpp_save_promoted_field(int $post_id): void {
    $is_promoted = isset($_POST['_wcpp_promoted_product'])
      ? sanitize_text_field($_POST['_wcpp_promoted_product'])
      : 'no';
    $current_promoted_id = get_option(WCPP_PROMOTED_PRODUCT_ID);

    if ($is_promoted === 'yes') {
      if (!empty($current_promoted_id) && $current_promoted_id != $post_id) {
        set_transient('wcpp_already_promoted_notice', true, 5);
      } else {
        update_option('wcpp_promoted_product_id', $post_id);
        update_post_meta($post_id, '_wcpp_promoted_product', $is_promoted);
      }
    } else {
      if ($current_promoted_id == $post_id) {
        delete_option(WCPP_PROMOTED_PRODUCT_ID);
        delete_post_meta($post_id, '_wcpp_promoted_product');
      }
    }
  }


  public function wcpp_display_admin_notices(): void {
    if (get_transient('wcpp_already_promoted_notice')) {
      echo '<div class="notice notice-error is-dismissible"><p>' . __('You cannot mark this product as promoted because another product is already marked as promoted.', 'woocommerce-promoted-product') . '</p></div>';
      delete_transient('wcpp_already_promoted_notice');
    }
  }
}
