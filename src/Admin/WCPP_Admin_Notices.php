<?php

declare(strict_types=1);

namespace WoocommerceFeaturedProduct\Admin;

if (!\defined('ABSPATH')) {
    exit('You are not allowed to be here');
}

class WCPP_Admin_Notices
{
    public function __construct()
    {
        \add_action('admin_notices', [$this, 'wcpp_display_promoted_product_changed_message']);
    }

    public function wcpp_display_promoted_product_changed_message(): void
    {
        if (\get_transient('wcpp_promoted_product_changed_notice')) {
            echo '<div class="notice notice-success is-dismissible"><p>'
              . \__('The promoted product has been updated.', 'woocommerce-promoted-product')
              . '</p></div>';
            \delete_transient('wcpp_promoted_product_changed_notice');
        }
    }
}
