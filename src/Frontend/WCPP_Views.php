<?php

declare(strict_types=1);

namespace WoocommerceFeaturedProduct\Frontend;

if (!\defined('ABSPATH')) {
    exit;
}

class WCPP_Views
{
    public function wcpp_display_promoted_product(): void
    {
//        global $post;
//        $promoted_meta = \get_post_meta($post->ID, '_wcpp_is_promoted_product', true);
//        if (!empty($promoted_meta)) {
//            echo '<div class="woocommerce-promoted-product-pole">' . \esc_html($promoted_meta) . '</div>';
//        }

      echo "<h1>WCPP_Views::wcpp_display_promoted_product</h1>";
    }
}
