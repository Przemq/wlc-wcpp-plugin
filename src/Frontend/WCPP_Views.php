<?php

declare(strict_types=1);

namespace WoocommerceFeaturedProduct\Frontend;

if (!\defined('ABSPATH')) {
    exit('You are not allowed to be here');
}

class WCPP_Views
{
    // Just an example of how we can add a info about promoted product to the product page.
    // Not related to recruitment task
    public function wcpp_add_info_to_promoted_product(): void
    {

      global $product;
      $is_promoted = get_post_meta($product->get_id(), WCPP_IS_PROMOTED_PRODUCT, true);
      $promoted_product_prefix = get_option(WCPP_PROMOTED_PRODUCT_PREFIX);

      if ('yes' === $is_promoted) {
        echo '<p class="wcpp_is_promoted_product">' . esc_html($promoted_product_prefix) . '</p>';
      }
    }
}
