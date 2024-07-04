<?php

declare(strict_types=1);

namespace WoocommerceFeaturedProduct\Frontend;

if (!\defined('ABSPATH')) {
    exit('You are not allowed to be here');
}

class WCPP_Views
{
    public function wcpp_add_info_to_promoted_product(): void
    {
      global $product;
      $is_promoted = get_post_meta($product->get_id(), WCPP_IS_PROMOTED_PRODUCT, true);

      if ('yes' === $is_promoted) {
        echo '<p style="font-weight: bold; color: green;">THIS IS PROMOTED PRODUCT</p>';
      }
    }
}
