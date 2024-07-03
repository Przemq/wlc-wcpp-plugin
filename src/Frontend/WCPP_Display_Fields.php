<?php

namespace WoocommerceFeaturedProduct\Frontend;

if (!defined('ABSPATH')) {
  exit;
}

class WCPP_Display_Fields {

  public function display_promoted_product(): void {
    global $post;
    $promoted_meta = get_post_meta($post->ID, '_wcpp_promoted_product', true);
    if (!empty($promoted_meta)) {
      echo '<div class="woocommerce-promoted-product-pole">' . esc_html($promoted_meta) . '</div>';
    }
  }
}
